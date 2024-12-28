<?php

namespace App\Http\Controllers;

use App\Mail\OTPServiceMail;
use App\Models\OTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Common;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Validator;

class AuthController extends Controller {
    public function index(){
        if(Auth::check()){
            return redirect("/branch");
        }
        return view("auth.login");
    }
    public function login(Request $request){

        $v = Validator($request->all(), [
            "login_email"=> "required",
            "login_password"=> "required",
        ],
            [
                "login_email.required"=> "Email required",
                "login_password.required"=> "Password required",
            ]
        );

        if(!$v) {
            return redirect()->back()->with("error",$v->errors()->first());
        }

        $credentials  = [
            "email" => $request->input('login_email'),
            "password" => $request->input('login_password')
        ];

        //$hashedPassword = Hash::make($credentials['password']);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            return redirect('/branch');
        } 
        else 
            return redirect()->back()->with("error", "Failed to login. incorrect email and/or password");

    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect(to: "/");
    }

    public function displayOneTimePasswordView() {
        $email = session("email");
        if($email == "") return redirect('/auth')->with("error","Set your email address first to send OTP code");
        return view("auth.consume-otp", ["email" => $email]);
    }
    public function generateOneTimePassword(Request $request){ 
        $v = Validator($request->all(), [
            "email"=> "required|email",
        ],
            [
                "email.required"=> "Email required",
                "email.email"=> "Invalid email address",
            ]
        );

        if(!$v) {
            return Common::responseError($request, null, $v->errors()->first());
        }

        $email  =$request->input("email");
        

        if(!User::where("email","=", $email)->exists()) {
            return Common::responseError($request, null, "Unknown account. Please insure the email address is correct and is registered as a user for the organization.");
        }
        if(Common::isGuest()) {
            if(RateLimiter::tooManyAttempts($email, 5)) {
                return Common::responseError($request, "/auth","Too many attemps. try again after one hour");
            }
            $result = OTP::tryToCreate(random_int(100000, 999999), $email);
            if($result == null) {
                return Common::responseError($request, null, "Failed to generate unique OTP code at the moment. try again later");
            }
            else {
                $code = $result['code'];
                $due = $result['due'];
                try{Mail::to($email)->send(new OTPServiceMail($code, $due));}
                catch(\Exception $e){
                    return Common::responseError($request, null, "Failed to send OTP code to specified email address");
                }

                RateLimiter::increment($email);
                
                return Common::respons(
                    $request,
                    redirect("/auth/login-otp")->with("email", $email),
                    "check your email address. use the route /consume-otp with 'otp' = {YOUR_CODE} as a payload"
                );
            }
        } 
        else 
            return Common::responseError(
                $request, 
                null, 
                "You are already logged in!"
            );
    }

    public function consumeOneTimePassword(Request $request){
        $v = Validator($request->all(), [
            "otp"=> "required",
            "login_email" => "required"
        ],
            [
                "otp.required"=> "OTP code requried",
                "login_email.required"=> "Email address requried",
            ]
        );

        if(!$v) {
            return Common::responseError($request, '/auth', $v->errors()->first());
        }

        
        $email_input = $request->input("login_email"); /* get the input value */
        $otp_input = $request->input("otp"); /* get the input value */
        $otp = OTP::where('code', $otp_input)->first(); /* trying to fetch record from otp table  */
        if(!$otp || $otp->isExpired()) { /* check if invalid or used OTP code */
            
            return Common::responseError($request, redirect("/auth/login-otp")->with('email', $email_input), "Invalid OTP Code");
        }

        $otp->markAsUsed();

        $user = User::where("email", $email_input)->first();
        if(!$user) {
            //Log::info("Been here: ".$otp_input." Email address:".$email_input);
            return Common::responseError($request, '/auth', "Internal Error. please try again later or contact Cstomer Support");
        }

        if($this->internal_login($user))
            return Common::respons($request, redirect('/branch'), "Successfully logged in");
        else
            return Common::responseError($request,  redirect("/auth/login-otp")->with('email', $email_input),"Failed to log in");
    }

    private function internal_login(User $user) {
        Auth::login($user, true);
        if(Auth::guest()) {
            if(!Auth::attempt(["email" => $user->email, "password" => $user->password]))
                return false;
        }
        return true;
    }


    public function fetchEmployees(Request $request) {
        return Common::respons($request, null, User::where('state', 'Active')->where('lastname', '!=', "")->orderBy('firstname')->orderBy('lastname')->get());
    }
}
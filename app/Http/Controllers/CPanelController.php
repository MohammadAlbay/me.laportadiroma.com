<?php

namespace App\Http\Controllers;

use App\Models\BusinessBrand;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\CPanelService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CPanelController extends Controller
{
    private $acceptDomains = ['@laportadiroma.com'];
    protected $cpanelService;
    public function __construct(CPanelService $cpanelService)
    {
        $this->cpanelService = $cpanelService;
    }
    public function getEmailPasswordList()
    {
        $emailPasswordList = $this->cpanelService->getEmailPasswordList(true, $this->acceptDomains);
        return response()->json($emailPasswordList);
    }
    public function getEmailList()
    {
        $emailPasswordList = $this->cpanelService->getEmailPasswordList(false, $this->acceptDomains);
        return response()->json($emailPasswordList);
    }
    public function getEmailListPlainText() {
        $text = "";
        $emailPasswordList = $this->cpanelService->getEmailAsText($this->acceptDomains);
        return $emailPasswordList;
    }


    public function generateThenSaveEmails(Request $request) {
        set_time_limit(0);
        $emailPasswordList = $this->cpanelService->getEmailPasswordDictionary(true, $this->acceptDomains);
        $found = []; $new = [];

        $laportadiroma_id = BusinessBrand::where('name', "La Porta Di Roma")->first() ?? null;
        if($laportadiroma_id == null) {
            throw new \Exception("Base Business name 'La Porta Di Roma' is missing. Please fix the issue and try again");
            return;
        }
        $laportadiroma_id = $laportadiroma_id->id;
        foreach ($emailPasswordList as $emailPassword) {
            $email =$emailPassword['email'];
            $password = $emailPassword['password'];

            $names = explode(".", explode("@", $email)[0]);
            $firstname = $names[0];
            $lastname = count($names) > 1? $names[1] : "";
            
            if(User::where('email', $email)->count() == 0) {
                $user = User::create([
                    'email' => $email,
                    'password' => Hash::make($password),
                    'gender' => "Male",
                    'birthdate' => now(),
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'business_brand_id' => $laportadiroma_id,
                    'role_id' => 2
                ]);

                if($email == "mohamed.albay@laportadiroma.com") {
                    Mail::to($email)
                        ->send(new \App\Mail\SendUserPassword("$firstname $lastname", $password));
                }

                $new[] = $email;
            } else {
                $found[] = $email;
            }
        }
        return response()->json(["Found Count" => count($found), "Found" => $found, "New Count" => count($new), "New" => $new]);
    }
}

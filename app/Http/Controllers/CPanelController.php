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

    public function updateLocalJSON(Request $request) {
        $emailPasswordList = $this->cpanelService->getEmailPasswordList(true, $this->acceptDomains, true);
        $emailEntityCollection = [];
        foreach ($emailPasswordList as $emailPassword) { 
            $exploded = explode("@", $emailPassword["email"]);
            $businessBrand = $exploded[1];
            $fullname = explode(".", $exploded[0]);
            $firstname = $fullname[0];
            $lastname = count($fullname) > 1 ? $fullname[1] : "";
            $emailEntityCollection[] = [
                "id" => $emailPassword["id"],
                "email" => $emailPassword["email"],
                "password" => $emailPassword["password"],
                "firstname" => ucfirst($firstname),
                "lastname" => $lastname == "" ? "" : ucfirst($lastname),
                "business-brand" => $businessBrand
            ];
        }
        $path = storage_path()."/emails.json";
        file_put_contents($path, json_encode($emailEntityCollection));
        return "Updated";
    }
    public function generateThenSaveEmails(Request $request) {
        set_time_limit(0);
        //$emailPasswordList = $this->cpanelService->getEmailPasswordDictionary(true, $this->acceptDomains);
        $file = storage_path()."/emails.json";
        if(!file_exists($file)) {
            $this->updateLocalJSON($request);
            $this->generateThenSaveEmails($request);
            return;
        }
        $emailPasswordList = json_decode(file_get_contents($file) , true);
        $found = []; $new = [];

        // $laportadiroma = BusinessBrand::where('name', "La Porta Di Roma")->first() ?? null;
        // $holasaci = BusinessBrand::where('name', "Holasaci")->first() ?? null;
        // if($laportadiroma == null) {
        //     throw new \Exception("Base Business name 'La Porta Di Roma' is missing. Please fix the issue and try again");
        // }
        $laportadiroma = BusinessBrand::getLaPortaDiRoma();
        $holasaci = BusinessBrand::getHolasaci();
        
        foreach ($emailPasswordList as $emailEntity) {
            $email =$emailEntity['email'];
            $password = $emailEntity['password'];

            $firstname = $emailEntity["firstname"];
            $lastname = $emailEntity["lastname"];
            
            $businessBrand = $emailEntity["business-brand"];
            //Log::info("Business brand : '$businessBrand' Holasaci: '$holasaci->domain' LPDR: $laportadiroma->domain");
            if(User::where('email', $email)->count() == 0) {
                $user = User::create([
                    'email' => $email,
                    'password' => Hash::make($password),
                    'gender' => "Male",
                    'birthdate' => now(),
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'business_brand_id' => $businessBrand == $laportadiroma->domain ? $laportadiroma->id 
                                        : ($businessBrand == $holasaci->domain ? $holasaci->id : null),
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

<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CPanelService
{
    protected $client;
    protected $cpanelUrl;
    protected $cpanelUser;
    protected $cpanelPassword;
    public function __construct()
    {
        $this->client = new Client();
        $this->cpanelUrl = config('cpanel.url');
        $this->cpanelUser = config('cpanel.user');
        $this->cpanelPassword = config('cpanel.password');
        //Log::info("The host is ".$this->cpanelUrl);
    }
    
    public function getEmailPasswordList($generate_passwords = false, $acceptedDomains)
    {
        $response = $this->client->request('GET', $this->cpanelUrl . '/execute/Email/list_pops', ['auth' => [$this->cpanelUser, $this->cpanelPassword],]);
        $data = json_decode($response->getBody(), true);
        $emailPasswordList = [];
        $passwordGenerator = new PasswordGeneratorService();
        $id = 0;
        if($generate_passwords) {
            foreach ($data['data'] as $email) {
                foreach($acceptedDomains as $domain) {
                    if(str_ends_with($email['email'], $domain)) {
                        $emailPasswordList[] = ['id' => $id, 'email' => $email['email'], 'password' => $passwordGenerator->generateStrongPassword(10)];
                        $id += 1;   
                    }
                }
                 
            }
        } else {
            foreach ($data['data'] as $email) {
                foreach($acceptedDomains as $domain) {
                    if(str_ends_with($email['email'], $domain)) {
                        $emailPasswordList[] = ['id' => $id, 'email' => $email['email']];
                        $id += 1;    
                    }
                }
                  
            }
        }
        return json_encode($emailPasswordList);
    }

    public function getEmailAsText($acceptedDomains) {
        $response = $this->client->request('GET', $this->cpanelUrl . '/execute/Email/list_pops', ['auth' => [$this->cpanelUser, $this->cpanelPassword],]);
        $data = json_decode($response->getBody(), true);
        $emailPasswordList = "";
        foreach ($data['data'] as $email) {
            foreach($acceptedDomains as $domain) {
                if(str_ends_with($email['email'], $domain)) {
                    $emailPasswordList .=  " ".$email['email'].'\n';
                }
            }
              
        }
        return $emailPasswordList;
    }

    public function getEmailPasswordDictionary($generate_passwords = false, $acceptedDomains)
    {
        $response = $this->client->request('GET', $this->cpanelUrl . '/execute/Email/list_pops', ['auth' => [$this->cpanelUser, $this->cpanelPassword],]);
        $data = json_decode($response->getBody(), true);
        $emailPasswordList = [];
        $passwordGenerator = new PasswordGeneratorService();
        $id = 0;
        if($generate_passwords) {
            foreach ($data['data'] as $email) {
                foreach($acceptedDomains as $domain) {
                    if(str_ends_with($email['email'], $domain)) {
                        $emailPasswordList[] = ['id' => $id, 'email' => $email['email'], 'password' => $passwordGenerator->generateStrongPassword(10)];
                        $id += 1;   
                    }
                }
                 
            }
        } else {
            foreach ($data['data'] as $email) {
                foreach($acceptedDomains as $domain) {
                    if(str_ends_with($email['email'], $domain)) {
                        $emailPasswordList[] = ['id' => $id, 'email' => $email['email']];
                        $id += 1;    
                    }
                }
                  
            }
        }
        return $emailPasswordList;
    }

    private function getEmailPassword($email)
    { // Implement logic to retrieve the email password here. 
        // Note: Storing passwords in plain text is highly discouraged! 
        return 'example_password';
    }
}

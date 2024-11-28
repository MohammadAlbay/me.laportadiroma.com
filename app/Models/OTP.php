<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class OTP extends Model
{
    //
    protected $guarded = [];

    public static function tryToCreate($code, $email) {
        $max_tries = 50;
        $value = null;
        while(true) {

            if($max_tries <= 0) break;

            try {
                $due = Carbon::now('Africa/Tripoli')->addMinutes(30);
                $otp = OTP::create(["code"=> $code,"email"=> $email, "due" => $due]);
                $value = ['code' => $otp->code, 'due' => $due];
                break;
            }
            catch(\Exception $e) {
                Log::error($e->getMessage());
                $max_tries--;
            }
        }

        return $value;
    }




    public function isExpired() {
        return $this->used == true;
    }

    public function markAsUsed() {
        $this->used = true;
        $this->save();
    }
}

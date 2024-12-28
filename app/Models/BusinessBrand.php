<?php

namespace App\Models;

use Directory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BusinessBrand extends Model
{
    //
    protected $guarded = [];

    protected static function booted()
    {
        parent::booted();

        static::created(function ($businessBrand) {
            // Define the folder path
            $folderPath = 'cloud/' . $businessBrand->name;

            // Create the folder if it doesn't already exist
            Storage::disk('local')->makeDirectory($folderPath);
        });

        static::deleted(function ($businessBrand) {
            // Define the folder path
            $folderPath = 'cloud/' . $businessBrand->name;

            // Check if the folder exists and delete it
            if (Storage::disk('local')->exists($folderPath)) {
                Storage::disk('local')->deleteDirectory($folderPath);
            }
        });
    }

    /**
     *
     * @return string
     */
    public function space() {
        $folderPath = 'cloud/' . $this->name;
        //if(!Storage::disk('local')->exists($folderPath))
        //    Storage::disk('local')->makeDirectory($folderPath);
        return $this->name;
    }

    public static function getLaPortaDiRoma()
    {
        return self::find(2);
    }

    public static function getHolasaci()
    {
        return self::find(1);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessBrand extends Model
{
    //
    protected $guarded = [];

    public static function getLaPortaDiRoma()
    {
        return self::find(2);
    }

    public static function getHolasaci()
    {
        return self::find(1);
    }
}

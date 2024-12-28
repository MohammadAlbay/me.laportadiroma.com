<?php

namespace App\Models;

use App\Common;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    //
    protected $guarded = [];

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }
    public function owners()
    {
        return AssetOwner::where("asset_id", $this->id)
            ->where("state", "Active")
            ->get("user_id")
            ->pluck("first_name");
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    protected static function booted()
    {
        parent::booted();

        static::created(function ($asset) {
            $folderPath = public_path().'/asset/' . $asset->id;

            // Create the folder if it doesn't already exist
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
                // Set permissions: read-only for www, read/write for server scripts
                chmod($folderPath, 0755);
            }
        });

        static::deleted(function ($asset) {
            // Define the folder path
            $folderPath = public_path().'/asset/' . $asset->id;

            Common::deleteDirectory($folderPath);

        });
    }
}

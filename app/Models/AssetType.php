<?php

namespace App\Models;

use App\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssetType extends Model
{
    //
    protected $guarded = [];

    public function createAsset($name, $quantity = 1, $fields = [])
    {
        Log::info("Creating asset with name $name and quantity $quantity. my ttpe is unique: ".$this->unique);
        if($this->unique == 0 && $quantity > 1) {
            for($i = 1; $i <= $quantity; $i++) {
                $asset = new Asset();
                $asset->name = $name;
                $asset->asset_type_id = $this->id;
                $asset->created_by = Auth::user()->id;
                $asset->data = "{}";
                $asset->save();
                $asset->data = json_encode($this->saveAssetFiles($asset, $fields));
                $asset->save();
            }
        } else {
            $asset = new Asset();
            $asset->name = $name;
            $asset->asset_type_id = $this->id;
            $asset->created_by = Auth::user()->id;
            $asset->data = "{}";
            $asset->save();
            $asset->data = json_encode($this->saveAssetFiles($asset, $fields));
            $asset->save();
        }
        return $asset;
    }


    private function saveAssetFiles($asset, $fields)
    {
        //Log::info($fields);
        $resultFields = [];
        if(count($fields) > 0) {
            foreach($fields as $field) {
                $newField = $field;
                if($field["type"] == "file" || $field["type"] == "image" || $field["type"] == "video") {
                    $files = $field["input"];
                    $paths = [];
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            if ($file) 
                                array_push($paths, Common::moveFileTo("/asset/{$asset->id}", $file));
                            /* just ignore if some none sence login error araised here */
                        }
                    } else {
                        array_push($paths, Common::moveFileTo("/asset/{$asset->id}", $files)); 
                        // just one file;
                    }

                    $newField["input"] = $paths;
                    
                }

                $resultFields[] = $newField;
            }
        }

        Log::info("File fields is ".json_encode($resultFields, JSON_PRETTY_PRINT));
        return $resultFields;
    }
}

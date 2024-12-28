<?php

namespace App\Http\Controllers;

use App\Common;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\BusinessBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    //
    public function dispayAssetsView() {
        return view(
            "dashboard.laportadiroma.asset.view",
            [
                "sections" => BusinessBrand::all(), 
                "asset_types" => AssetType::where("state", "Active")->orderBy("name")->get(),
                "assets" => Asset::all()
            ]
        );
    }

    public function dispayAssetNewTypeView()
    {
        return view(
            "dashboard.laportadiroma.asset.add-type",
            ["sections" => BusinessBrand::all(), "asset_types" => AssetType::where("state", "Active")->get()]
        );
    }
    public function dispayAssetAddNewView()
    {
        return view(
            "dashboard.laportadiroma.asset.add-new",
            ["sections" => BusinessBrand::all(), "asset_types" => AssetType::where("state", "Active")->get()]
        );
    }
    public function addNewAsset(Request $request)
    {
        $asset_name = $request->input("asset_name_input");                     /* the name of the asset */
        $asset_type_id = $request->input("asset_type_input");                  /* the id of asset type */
        $asset_quantity = $request->input("asset_quantity_input") ?? 1;        /* store quantity. discarded if asset type is unique */
        $asset_fields = $this->generateFieldsFromRequest($request);      /* generate fields */

        // check if the fields are not generated correctly
        if ($asset_fields == null)
            return Common::responseError($request, null, "Invalid fields format");
        $asset_type = AssetType::find($asset_type_id);
        // exit here if for any reason the asset type is not found/invalid id
        if ($asset_type == null)
            return Common::responseError($request, null, "Invalid asset type");

        // try to create the asset
        $asset = $asset_type->createAsset($asset_name, $asset_quantity, $asset_fields);
        // if the asset is not created, return an error
        if ($asset == null)
            return Common::responseError($request, null, "Failed to create asset");
        // create the asset successfully done
        return Common::respons($request, null, $asset);
    }
    private function generateFieldsFromRequest(Request $request)
    {
        $fields = json_decode($request->input("fields"), true);
        if (!is_array($fields))
            return null;

        $generatedFields = [];
        foreach ($fields as $field) {
            $fileInputName = "";
            if (($field['type'] == "file" || $field['type'] == "image" || $field['type'] == "video")) {
                $fileInputName = str_replace("[]", "", $field["input"]);
                $files = $request->file($fileInputName);
                // if (is_array($files)) {
                //     foreach ($files as $file) {
                //         if ($file) {
                //             $fileName = $file->getClientOriginalName();
                //             Log::info("The field name is " . $field['input'] . " File is $fileName Correct");
                //             $generatedFields[] = [
                //                 'name' => $field['name'],
                //                 'type' => $field['type'],
                //                 'input' => $file,
                //             ];
                //         } else {
                //             Log::info("The field name is " . $field['input'] . " File is Not correct");
                //         }
                //     }
                // } else {
                //     Log::info("The field name is " . $field['input'] . " File is Not correct");
                // }
            }

            $generatedFields[] = [
                'name' => $field['name'],
                'type' => $field['type'],
                'input' => ($field['type'] == "file" || $field['type'] == "image" || $field['type'] == "video") ? $request->file($fileInputName) : $request->input($field["input"]),
            ];
        }

        return $generatedFields;
    }
    public function createNewAssetType(Request $request)
    {

        $details = $request->input("details");

        if (!is_array($details)) {
            return response()->json(['error' => 'Invalid details format'], 400);
        }

        $asset_name = $details['asset_name'] ?? null;  // Use null coalescing to avoid undefined index
        $asset_section = $details['asset_section'] ?? null;
        $asset_is_unique = $details['asset_is_unique'] ?? null;
        $asset_multiowner = $details['asset_multiowner'] ?? null;
        $fields = $details['fields'] ?? null;

        $validator = Validator::make([
            "name" => $asset_name,
            "section" => $asset_section,
            "unique" => $asset_is_unique,
            "multiowner" => $asset_multiowner,
        ], [
            "name" => "required|string",
            "section" => "required",
            "unique" => "required|boolean",
            "multiowner" => "required|boolean",
        ]);

        if ($validator->failed())
            return Common::responseError($request, null, "Some of the input fields are empty. fill all required fields please");

        $optimized_fields = $this->optimizedFields($fields);

        $new_type = AssetType::create([
            "name" => $asset_name,
            "section_id" => "shared" == $asset_section ? null : $asset_section,
            "unique" => $asset_is_unique,
            "multipleownership" => $asset_is_unique,
            "state" => "Active",
            "fields" => json_encode($optimized_fields)
        ]);

        return Common::respons($request, null, $new_type);
    }

    private function optimizedFields($fields)
    {
        $result = [];
        foreach ($fields as $field) {
            /* we need it to check for various required information for each field type */
            $field_type = $field["field_type"];
            /* storing basic information */
            $resultedField = [];
            $resultedField["type"] = $field_type;
            $resultedField["name"] = $field["field_name"];;

            if ($field_type == "list-users") {
                /* 
                1.required by default
                2.no default value
                3.support multi select */
                $resultedField["required"] = $field["field_required"] == "1";
                $resultedField["multiselect"] = $field["field_multiselect"] == "1";
            } else if ($field_type == "list-values") {
                /*
                1.support multi select
                2.support default value */
                $resultedField["required"] = $field["field_required"] == "1";
                $resultedField["multiselect"] = $field["field_multiselect"] == "1";
                $resultedField["default"] = $field["field_default"];
                $resultedField["values"] = explode("|", $field["field_targetvalues"]);
            } else if ($field_type == "string") {
                /*
                1.support required choice
                2.support default value
                3.support both max & min length */
                $resultedField["required"] = $field["field_required"] == "1";
                $resultedField["default"] = $field["field_default"] ?? "";
                $resultedField["maxlength"] = $field["field_maxlength"] ?? 10000000;
                $resultedField["minlength"] = $field["field_minlength"] ?? 0;
            } else if ($field_type == "datetime") {
                /*
                1.support required choice
                2.support default value
                3.support both max & min length */
                $resultedField["required"] = $field["field_required"] == "1";
                $resultedField["default"] = $field["field_default"] ?? "";
            } else if ($field_type == "number" || $field_type == "double") {
                /*
                1.support required choice
                2.support default value
                3.support both max & min value */
                $resultedField["required"] = $field["field_required"] == "1";
                $resultedField["default"] = $field["field_default"] ?? 0;
                $resultedField["maxvalue"] = $field["field_maxvalue"] ?? 9999999999;
                $resultedField["minvalue"] = $field["field_minvalue"] ?? -9999999999;
            } else if ($field_type == "boolean") {
                /*
                1.support required choice
                2.support default value */
                $resultedField["required"] = $field["field_required"] == "1";
                $resultedField["default"] = $field["field_booleandefault"] ?? false;
            } else if ($field_type == "file" || $field_type == "image" || $field_type == "video") {
                /*
                1.support required choice
                2.support multiselect */
                $resultedField["required"] = $field["field_required"] == "1";
                $resultedField["multiselect"] = $field["field_multiselect"] == "1";
            }

            array_push($result, $resultedField);
        }
        //Log::info(json_encode($result));
        return $result;
    }

    public function getAssetTypes(Request $request)
    {
        return Common::respons(
            $request, 
            null, 
            AssetType::where("state", "Active")->orderBy("name")->get()
        ); // return all active asset types
    }
}

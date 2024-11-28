<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MigrationOnTheFlyService {
    public function __construct() {}
    public function generateMigration(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'model_name' => 'required|string',
            'table_name' => 'required|string',
            'fields' => 'required|array',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|string',
        ]);

        $modelName = $validated['model_name'];
        $tableName = $validated['table_name'];
        $fields = $validated['fields'];

        // Generate the model
        Artisan::call('make:model', ['name' => $modelName]);

        // Add the $ignored property to the model
        $modelPath = app_path("Models/{$modelName}.php");
        if (File::exists($modelPath)) {
            $modelContent = File::get($modelPath);

            // Add the protected $ignored property
            $ignoredProperty = "protected \$ignored = [];\n\n";
            $modelContent = str_replace('class ' . $modelName, 'class ' . $modelName . "\n" . $ignoredProperty . 'class ' . $modelName, $modelContent);

            // Write the updated content to the model file
            File::put($modelPath, $modelContent);
        }

        // Prepare migration name
        $migrationName = 'create_' . strtolower($tableName) . '_table';

        // Generate the migration
        Artisan::call('make:migration', ['name' => $migrationName]);

        // Prepare migration file content dynamically
        $migrationPath = database_path('migrations/' . now()->format('Y_m_d_His') . '_create_' . strtolower($tableName) . '_table.php');
        
        if (File::exists($migrationPath)) {
            $migrationContent = File::get($migrationPath);

            // Add the fields to the migration file content
            $fieldsCode = '';
            foreach ($fields as $field) {
                $fieldsCode .= '$table->' . $field['type'] . '(\'' . $field['name'] . '\');' . PHP_EOL . '            ';
            }

            // Add fields to the migration file dynamically
            $migrationContent = str_replace(
                '});',
                "        {$fieldsCode}        });",
                $migrationContent
            );

            // Write the updated content to the migration file
            File::put($migrationPath, $migrationContent);
        }

        // Run the migration after creating the files
        Artisan::call('migrate');

        // Return success message
        return redirect()->back()->with('success', 'Model and Migration created and migrated successfully!');
    }
}

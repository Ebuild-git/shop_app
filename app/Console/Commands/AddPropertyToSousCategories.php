<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddPropertyToSousCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:souscategories-property';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add property ID 26 to all sous_categories and mark it as required';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sousCategories = DB::table('sous_categories')->get();

        foreach ($sousCategories as $category) {
            // Decode existing JSON fields
            $proprietes = json_decode($category->proprietes, true);
            $required = json_decode($category->required, true);

            // Add property if it doesn't exist
            if (!in_array(26, $proprietes)) {
                $proprietes[] = 26;
            }

            // Check if property is already in required and update
            $requiredExists = array_filter($required, function ($item) {
                return $item['id'] == 26;
            });

            if (empty($requiredExists)) {
                $required[] = ["id" => 26, "required" => "Oui"];  // Add with 'Oui' for required
            }

            // Update the database
            DB::table('sous_categories')
                ->where('id', $category->id)
                ->update([
                    'proprietes' => json_encode($proprietes),
                    'required' => json_encode($required)
                ]);
        }

        $this->info('Property 26 has been added to all sous_categories.');
    }
}

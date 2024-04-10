<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrationOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migration-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute the migrations in the order specified in the file app/Console/Comands/MigrateInOrder.php \n Drop all the table in db before execute the command.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migrations = [
            '2024_03_21_081210_create_regions_table.php',
            '0_create_users_table.php',
            '1_create_categories_table.php',
            '2_create_sous_categories_table.php',
            '3_create_historique_recherches_table.php',
            '4_create_posts_table.php',
            '5_create_signalements_table.php',
            '6_create_commandes_table.php',
            '7_create_notifications_table.php',
            '20_create_propositions_table.php',
            '25_create_permission_tables.php',
            '26_create_configurations_table.php',
            '27_create_personal_access_tokens_table.php',
            '28_create_failed_jobs_table.php',
            '29_create_password_reset_tokens_table.php',
            '30_create_contacts_table.php',
            "2024_03_11_083340_create_proprietes_table.php",
            '2024_03_15_144434_create_likes_table.php',
            '2024_03_21_083016_create_regions_categories_table.php',
            "2024_03_25_124212_create_ratings_tables.php",
            "2024_04_04_152640_create_favoris_table.php",
            "2024_04_10_095736_create_pings_table.php"

        ];

        foreach ($migrations as $migration) {
            $basePath = 'database/migrations/';
            $migrationName = trim($migration);
            $path = $basePath . $migrationName;
            $this->call('migrate', [
                '--path' => $path,
            ]);
        }
    }
}

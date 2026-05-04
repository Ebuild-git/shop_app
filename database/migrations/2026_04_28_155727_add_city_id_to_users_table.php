<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\City;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->nullable()->after('region');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });

        // Migrate existing address data to cities table and link users
        $users = User::whereNotNull('address')->get();
        foreach ($users as $user) {
            $cityName = trim($user->address);
            if ($cityName !== '') {
                $city = City::firstOrCreate(['name' => $cityName]);
                $user->city_id = $city->id;
                // Note: keeping the original address field for backward compatibility
                $user->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_item_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->text('content');
            $table->timestamps();

            $table->foreign('order_item_id')
                  ->references('id')->on('orders_items') // adjust table name if different
                  ->onDelete('cascade');
        });

        // Migrate existing single `note` values into the new table so nothing is lost
        DB::table('orders_items')
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->orderBy('id')
            ->chunkById(500, function ($items) {
                foreach ($items as $item) {
                    DB::table('order_item_notes')->insert([
                        'order_item_id' => $item->id,
                        'content'       => $item->note,
                        'created_at'    => $item->updated_at ?? now(),
                        'updated_at'    => $item->updated_at ?? now(),
                    ]);
                }
            });
    }

    public function down()
    {
        Schema::dropIfExists('order_item_notes');
    }
};

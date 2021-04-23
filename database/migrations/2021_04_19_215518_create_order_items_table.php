<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedInteger('quantity');
            $table->decimal('price', 20, 6);
            $table->decimal('base_total', 16, 2)->default(0);
				$table->decimal('tax_amount', 16, 2)->default(0);
				$table->decimal('tax_percent', 16, 2)->default(0);
				$table->decimal('discount_amount', 16, 2)->default(0);
				$table->decimal('discount_percent', 16, 2)->default(0);
				$table->decimal('sub_total', 16, 2)->default(0);
				$table->string('sku');
				$table->string('type');
				$table->string('name');
				$table->string('weight');
				$table->json('attributes');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index('sku');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}

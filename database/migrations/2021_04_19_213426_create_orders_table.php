<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->enum('status', ['pending', 'processing', 'completed', 'decline'])->default('pending');
            $table->datetime('order_date');
				$table->datetime('payment_due');
				
				$table->decimal('base_total_price', 16, 2)->default(0);
				$table->decimal('tax_amount', 16, 2)->default(0);
				$table->decimal('tax_percent', 16, 2)->default(0);
				$table->decimal('discount_amount', 16, 2)->default(0);
				$table->decimal('discount_percent', 16, 2)->default(0);
				$table->decimal('shipping_cost', 16, 2)->default(0);
            $table->decimal('grand_total', 20, 6);
            $table->unsignedInteger('item_count');

            $table->boolean('payment_status')->default(1);
            $table->enum('payment_method', ['handcash', 'visa', 'master', 'debit'])->default('handcash');

            $table->string('first_name');
            $table->string('last_name');
            $table->text('address');
            $table->string('address2')->nullable();
            $table->string('city');
            $table->string('country');
            $table->string('province_id')->nullable();
            $table->string('post_code');
            $table->string('phone_number');
            $table->string('email');
            $table->text('notes')->nullable();
            
				
				$table->string('shipping_courier')->nullable();
				$table->string('shipping_service_name')->nullable();
				$table->unsignedBigInteger('approved_by')->nullable();
				$table->datetime('approved_at')->nullable();
				$table->unsignedBigInteger('cancelled_by')->nullable();
				$table->datetime('cancelled_at')->nullable();
				$table->text('cancellation_note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users');
				$table->foreign('cancelled_by')->references('id')->on('users');
				$table->index('code');
				$table->index(['code', 'order_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}


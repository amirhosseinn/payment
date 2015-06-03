<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreatePaymentTransactionTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create(Config::get('payment::table'), function ($table) {

            $table->increments('id');
            $table->integer('order_id')->unsigned()->index()->nullable();
            $table->string('ref_id', 255)->index();
            $table->string('authority', 255)->index();
            $table->decimal('amount', 15, 0);
            $table->enum('provider', array(
                'zarinpal',
                'payline',
                'jahanpay',
            ));
            $table->integer('status');
            $table->text('description')->nullable();
            $table->text('additional_data')->nullable();
            $table->integer('updated_at')->unsigned();
            $table->integer('created_at')->unsigned();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::drop(Config::get('payment::table'));
    }

}

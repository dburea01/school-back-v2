<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table): void {
            $table->uuid('id');
            $table->string('name', 100);
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('zip_code', 20);
            $table->string('city', 100);
            $table->string('country_id', 2);
            $table->string('comment')->nullable();
            $table->string('status');
            //$table->string('school_type_id', 10);
            //$table->string('school_status', 10);
            $table->integer('max_users');
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->primary('id');
        });

        /*
        Schema::table('schools', function (Blueprint $table): void {
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('school_type_id')->references('id')->on('school_types')->onDelete('set null');
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
}

<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->tinyInteger('position');
            $table->boolean('displayable')->default(false);
            $table->timestamps();
        });

        $roles = [

            'TEACHER' => [
                'id' => 'TEACHER',
                'position' => 10,
                'displayable' => true,
                'name' => 'Enseignant'
            ],

            'STUDENT' => [
                'id' => 'STUDENT',
                'position' => 20,
                'displayable' => true,
                'name' => 'Etudiant'
            ],

            'PARENT' => [
                'id' => 'PARENT',
                'position' => 30,
                'displayable' => true,
                'name' => 'Parent'
            ],

            'DIRECTOR' => [
                'id' => 'DIRECTOR',
                'position' => 40,
                'displayable' => true,
                'name' => 'Directeur'
            ],

            'SUPERADMIN' => [
                'id' => 'SUPERADMIN',
                'position' => 50,
                'displayable' => false,
                'name' => 'Super Admin'
            ],

        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}

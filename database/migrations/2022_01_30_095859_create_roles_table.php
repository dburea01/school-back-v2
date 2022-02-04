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

        $role = new Role();
        $role->id = 'TEACHER';
        $role->position = 10;
        $role->displayable = true;
        $role->name = 'Enseignant';
        $role->save();

        $role = new Role();
        $role->id = 'STUDENT';
        $role->position = 20;
        $role->displayable = true;
        $role->name = 'Etudiant';
        $role->save();

        $role = new Role();
        $role->id = 'PARENT';
        $role->position = 30;
        $role->displayable = true;
        $role->name = 'Parent';
        $role->save();

        $role = new Role();
        $role->id = 'DIRECTOR';
        $role->position = 40;
        $role->displayable = true;
        $role->name = 'Directeur';
        $role->save();

        $role = new Role();
        $role->id = 'SUPERADMIN';
        $role->position = 50;
        $role->displayable = false;
        $role->name = 'Super Administrateur';
        $role->save();
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

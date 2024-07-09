<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Processton\ProcesstonObject\Traits\SchemaTasks\ProcessObject;

return new class extends Migration
{
    use ProcessObject;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unsignedInteger('permission_id')->nullable();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->tinyInteger('is_granted')->default(0);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

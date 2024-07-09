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
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('is_default')->default(0);
            $table->timestamps();
        });
        $this->processObjects([
            [
                "name" => "Role",
                "plural_name" => "Roles",
                "slug" => "roles",
                "model" => null,
                "fields" => [
                    [
                        "name" => "Name",
                        "slug" => "name",
                        "is_required" => true,
                        "nullable" => false,
                        "type" => "text",
                        "min" => "3",
                        "max" => "128"
                    ],
                    [
                        "name" => "Is Default",
                        "slug" => "is_default",
                        "is_required" => false,
                        "nullable" => false,
                        "type" => "boolean",
                        "default" => 0
                    ]
                ],
            ]
        ]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

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
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category')->default('un_categorise');
            $table->string('sub_category')->nullable();
            $table->string('name');
            $table->string('key');
            $table->timestamps();
        });
        $this->processObjects([
            [
                "name" => "Permission",
                "plural_name" => "Permissions",
                "slug" => "permissions",
                "model" => null,
                "fields" => [
                    [
                        "name" => "Category",
                        "slug" => "category",
                        "is_required" => false,
                        "nullable" => false,
                        "type" => "text",
                        "min" => "3",
                        "max" => "128"
                    ],
                    [
                        "name" => "Sub Category",
                        "slug" => "sub_category",
                        "is_required" => false,
                        "nullable" => false,
                        "type" => "text",
                        "min" => "3",
                        "max" => "128"
                    ],
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
                        "name" => "Key",
                        "slug" => "key",
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

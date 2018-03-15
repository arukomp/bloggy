<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTypesTable extends Migration
{
    protected $dbPrefix;

    public function __construct()
    {
        $this->dbPrefix = config('bloggy.database_prefix');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->dbPrefix.'post_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('plural');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists($this->dbPrefix.'post_types');
    }
}

<?php

require_once '../Base/init.php';

use Base\DbConnection;
use Illuminate\Database\Capsule\Manager as Capsule;

$dbConnection = new DbConnection();
$dbConnection->openConnection();

Capsule::schema()->dropIfExists('users');
Capsule::schema()->create('users', function ($table) {
    /** @var Illuminate\Database\Schema\Blueprint $table */
    $table->increments('id');
    $table->string('name');
    $table->string('email')->unique();
    $table->integer('age')->unsigned();
    $table->text('description');
    $table->timestamps();
});

Capsule::schema()->dropIfExists('files');
Capsule::schema()->create('files', function ($table) {
    /** @var Illuminate\Database\Schema\Blueprint $table */
    $table->increments('id');
    $table->string('name');
    $table->integer('user_id')->unsigned();
});

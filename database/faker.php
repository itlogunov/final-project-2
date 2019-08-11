<?php

require_once '../Base/init.php';

use App\Models\User;
use App\Models\File;
use Base\DbConnection;

$dbConnection = new DbConnection();
$dbConnection->openConnection();


$faker = Faker\Factory::create('ru_Ru');

for ($i = 0; $i < 10; $i++) {
    $user = new User();

    $user->name = $faker->name;
    $user->age = mt_rand(18, 35);
    $user->email = $faker->email;
    $user->description = $faker->realText(200);
    $user->save();

    $fileUrl = $faker->imageUrl(200, 200, 'people', true);
    if (isset($fileUrl)) {
        $filePath = '/images/' . md5(microtime()) . '.jpeg';
        file_put_contents('../public' . $filePath, file_get_contents($fileUrl));

        $file = new File();
        $file->name = $filePath;
        $file->user_id = $user->getKey('id');
        $file->save();
    }

}
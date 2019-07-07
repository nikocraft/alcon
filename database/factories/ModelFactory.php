<?php

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Core\Content\Content::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(6, $variableNbWords = true),
        'slug' => $faker->slug,
        'content_type_id' => 2,
        'user_id' => 1,
        'published_at' => Carbon::now(),
    ];
});


$factory->define(App\Models\Core\Content\ContentBlock::class, function (Faker\Generator $faker) {
    return [
        'content_id' => $faker->randomNumber(1)+1,
        'type' => 'Text',
        'content' => '<p>' . $faker->paragraph($faker->numberBetween(15, 40), $faker->numberBetween(2, 8)) . '</p>',
        'unique_id' => $faker->randomNumber(9),
    ];
});


$factory->define(App\Models\Core\Comments\Comment::class, function (Faker\Generator $faker) {
    $username = str_replace(".","",$faker->unique()->username);
    return [
        'content_id' => $faker->randomNumber(1)+1,
        'title' => $faker->sentence,
        'body' => $faker->paragraph($faker->numberBetween(5, 15)),
        'name' => $username,
        'email' => $username.'@gmail.com',
        'status' => 1
    ];
});

<?php

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
$a = $factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    $faker = \Faker\Factory::create('zh_CN');
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'hid' => '',
        'avatar' => ''
    ];
});

$factory->define(\App\Models\Nodes::class,function(){
    $faker = \Faker\Factory::create('zh_CN');
    return [
        'hid' => '',
        'parent_hid' => 0,
        'weight' => 0,
        'level' => 0,
        'name' => 'default-node',
        'display_name' => "默认节点",
        'description' => "这是默认节点,创建时自动创建",
    ];
});




<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\User;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $super = Role::find(1);
        $admin = Role::find(2);
        $member = Role::find(3);

        $environment = env('APP_ENV', 'production');

        if($environment == 'local') {
            $username = str_replace(".","",$faker->unique()->username);
            $user = User::create([
                'firstname' => $faker->firstName(),
                'lastname' => $faker->lastName(),
                'username' => $username,
                'email' => 'first@gmail.com',
                'password' => bcrypt('123456'),
                'timezone' => 'Europe/Amsterdam',
                'is_activated' => true,
                'bio' => $faker->text(rand(200, 250)),
            ]);
            $user->detachRole($super);
            $user->attachRole($super);

            $username = str_replace(".","",$faker->unique()->username);
            $user = User::create([
                'firstname' => $faker->firstName(),
                'lastname' => $faker->lastName(),
                'username' => $username,
                'email' => 'second@gmail.com',
                'password' => bcrypt('123456'),
                'bio' => $faker->text(rand(200, 250)),
            ]);
            $user->detachRole($admin);
            $user->attachRole($admin);
        }
    }
}

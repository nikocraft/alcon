<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

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

        $super = Role::where('name', 'super')->first();
        $admin = Role::where('name', 'admin')->first();
        $member = Role::where('name', 'member')->first();

        $environment = env('APP_ENV', 'production');

        if($environment == 'local') {
            $username = str_replace(".", "", $faker->unique()->username);
            $user = User::create([
                'firstname' => $faker->firstName(),
                'lastname' => $faker->lastName(),
                'username' => $username,
                'email' => 'super@gmail.com',
                'password' => bcrypt('12345678'),
                'timezone' => 'Europe/Amsterdam',
                'activated' => true,
                'activated_at' => Carbon::now(),
                'approved' => true,
                'bio' => $faker->text(rand(200, 250)),
            ]);
            $user->detachRole($super);
            $user->attachRole($super);

            $username = str_replace(".", "", $faker->unique()->username);
            $user = User::create([
                'firstname' => $faker->firstName(),
                'lastname' => $faker->lastName(),
                'username' => $username,
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345678'),
                'timezone' => 'Europe/Amsterdam',
                'activated' => true,
                'activated_at' => Carbon::now(),
                'approved' => true,
                'bio' => $faker->text(rand(200, 250)),
            ]);
            $user->detachRole($admin);
            $user->attachRole($admin);

            $username = str_replace(".", "", $faker->unique()->username);
            $user = User::create([
                'firstname' => $faker->firstName(),
                'lastname' => $faker->lastName(),
                'username' => $username,
                'email' => 'member@gmail.com',
                'password' => bcrypt('12345678'),
                'timezone' => 'Europe/Amsterdam',
                'activated' => true,
                'activated_at' => Carbon::now(),
                'approved' => true,
                'bio' => $faker->text(rand(200, 250)),
            ]);
            $user->detachRole($member);
            $user->attachRole($member);
        }
    }
}

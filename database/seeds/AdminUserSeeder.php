<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super = Role::find(1);

        $username = env('ADMIN_USERNAME', null);
        $email = env('ADMIN_EMAIL', null);
        $password = env('ADMIN_PASSWORD', null);
        $firstname = env('ADMIN_FIRSTNAME', null);
        $lastname = env('ADMIN_LASTNAME', null);

        if($username && $email && $password) {
            $user = User::create([
                'username' => $username,
                'firstname' => $firstname ? $firstname : 'Admin',
                'lastname' => $lastname ? $lastname : 'User',
                'email' => $email,
                'password' => bcrypt($password),
                'activated' => true,
                'activated_at' => Carbon::now(),
                'approved' => true
            ]);
            $user->detachRole($super);
            $user->attachRole($super);
        } else {
            dd('Install aborted, you are in production and since you are running from CLI please provide admin user details in .env file, check .env.example for details');
        }

    }
}

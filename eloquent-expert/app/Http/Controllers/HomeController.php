<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\UserRole;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    public function index()
    {

        $email = 'user@gmail.com';
        // First method
        $user = User::firstOrCreate(
            ['email' => $email],
            [
            'name' => fake()->name(),
            'role' => UserRole::User,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);
        dd($user->wasRecentlyCreated ? 'User was created' : 'User already exists');
        // $user = User::firstOrNew(
        //     ['email' => $email],
        //     ['name' => fake()->name(),
        //     'role' => UserRole::User,
        //     'password' => 'password',
        //     'email_verified_at' => now(),
        //     ]
        // );


        // Second method
        // $user = new User();
        // $user->name = fake()->name();
        // $user->email = fake()->email();
        // $user->password = 'password';
        // $user->role = UserRole::User; // Assuming you want to set the role to User
        // $user->email_verified_at = now();
        // $user->save();


        // if ($user->role->value == UserRole::User->value) {
        //     dd('i got here');
        // }
    }
}

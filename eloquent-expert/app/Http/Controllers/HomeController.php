<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    public function index()
    {
        // First method
        User::create([
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        // Second method
        $user = new User();
        $user->name = fake()->name();
        $user->email = fake()->email();
        $user->password = 'password';
        $user->email_verified_at = now();
        $user->save();
    }

}

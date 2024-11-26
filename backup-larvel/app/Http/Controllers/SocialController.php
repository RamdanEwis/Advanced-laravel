<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{


    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    
  public function callbackGoogle()
    {
        try {
            $user = Socialite::driver('google')->user();
        //    return response()->json(['token' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' =>  'google' . ' authentication failed'], 401);
        }

        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            $token = JWTAuth::fromUser($existingUser);
            return $this->createNewToken($token,$existingUser);
        } else {
            $newUser = User::create([
                'first_name' => $user->user['given_name'],
                'last_name' => $user->user['family_name'],
                'email' => $user->user['email'],
                'provider' => 'google',
                'provider_id' => $user->user['id'],
                'provider_token' => $user->token,
                'password' => Hash::make(Str::random(8))
                // Set any other required user attributes
            ]);
            $token = JWTAuth::fromUser($newUser);
        }
    
    }
    public function callbackLinkedin()
    {
        try {
            $user = Socialite::driver('linkedin')->user();
          //  return response()->json(['token' => $user->attributes['first_name']]);
        } catch (\Exception $e) {
            return response()->json(['error' =>  'linkedin' . ' authentication failed'], 401);
        }

        $existingUser = User::where('email', $user->attributes['email'])->first();

        if ($existingUser) {
            $token = JWTAuth::fromUser($existingUser);
            return $this->createNewToken($token,$existingUser);
        } else {
            $newUser = User::create([
                'first_name' => $user->attributes['first_name'],
                'last_name' => $user->attributes['last_name'],
                'email' => $user->attributes['email'],
                'provider' => 'linkedin',
                'provider_id' => $user->attributes['id'],
                'provider_token' => $user->token,
                'password' => Hash::make(Str::random(8))
                // Set any other required user attributes
            ]);
            $token = JWTAuth::fromUser($newUser);
        }
    }
    protected function createNewToken($token,$user)
	{
        $expires_in = Carbon::now()->addDays(5)->format('Y-m-d H:i:s');
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expires_in,
            'user' => new UserResource($user)
        ]);
    }
    
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // 🚪 Handle user login
    public function login(LoginRequest $request)
    {
        // 🔍 Validate user credentials
        $credentials = $request->validated();

        try {
            // 🛑 Attempt authentication
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }

        // 🔐 Generate CSRF token for extra security
        $csrfToken = bin2hex(random_bytes(32));

        // 🍪 Store JWT in HTTP-only, secure cookie with SameSite=None
        $accessTokenCookie = cookie(
            'access_token',
            $token,
            60, // Minutes
            '/',
            false,
            false,   // Secure: true for HTTPS
            true,   // HttpOnly
            false,  // Raw
            'None'  // SameSite
        );

        // 🍪 Store CSRF token in a separate cookie (not HTTP-only)
        $csrfTokenCookie = cookie(
            'csrf_token',
            $csrfToken,
            60,
            '/',
            false,
            false,   // Secure: true for HTTPS in production
            true,  // HttpOnly
            false,  // Raw
            'None'  // SameSite
        );
        // 🎉 Return success response with cookies
        return response()->json([
            'message' => 'Logged in successfully',
            'csrf_token'=>$csrfToken,
            'user'=> new UserResource(auth()->user())])
            ->cookie($accessTokenCookie)
            ->cookie($csrfTokenCookie);
    }


    // 🚪 Handle user logout
    public function logout(Request $request)
    {
        try {
            // 🏷️ Get token from request
            $token = JWTAuth::getToken();

            if ($token) {
                // 🗑️ Invalidate the token
                JWTAuth::invalidate($token);
            }

            // 🍪 Create expired cookies to remove them from the client
            $accessTokenCookie = cookie('access_token', '', -1);
            $csrfTokenCookie = cookie('csrf_token', '', -1);

            // 🎉 Return success response with expired cookies
            return response()->json(['message' => 'Logged out successfully'])
                ->cookie($accessTokenCookie)
                ->cookie($csrfTokenCookie);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not logout'], 500);
        }
    }
}

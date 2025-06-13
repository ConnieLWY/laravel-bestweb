<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication related endpoints"
 * )
 */
class AuthController extends Controller
{
    /**
     * Dummy method for Swagger to parse annotations
     *
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Test"),
     *             @OA\Property(property="email", type="string", example="test@example.com"),
     *             @OA\Property(property="password", type="string", example="test123"),
     *             @OA\Property(property="password_confirmation", type="string", example="test123")
     *         )
     *     ),
     *     @OA\Response(
     *         @OA\MediaType(mediaType="application/json"),
     *         response=201,
     *         description="User registered successfully"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /**
     * @OA\Post(
        *     path="/api/login",
        *     summary="Login a user",
        *     tags={"Auth"},
        *     @OA\RequestBody(
        *         required=true,
        *         @OA\JsonContent(
        *             required={"email", "password"},
        *             @OA\Property(property="email", type="string", example="test@example.com"),
        *             @OA\Property(property="password", type="string", example="test123")
        *         )
        *     ),
        *     @OA\Response(
        *         @OA\MediaType(mediaType="application/json"),
        *         response=200,
        *         description="User logged in successfully"
        *     )
        * )
        */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /**
     * @OA\Post(
        *     path="/api/logout",
        *     summary="Logout the authenticated user",
        *     tags={"Auth"},
        *     @OA\Response(
        *         @OA\MediaType(mediaType="application/json"),
        *         response=200,
        *         description="User logged out"
        *     ),
        *     security={{"sanctum": {}}}
        * )
        */
   public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}

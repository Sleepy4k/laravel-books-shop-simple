<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = validator($request->all(), [
            'username' => ['required','string','max:255','unique:users,username'],
            'email' => ['required','string','max:255','unique:users,email'],
            'password' => ['required','string','min:8','max:255','confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'data not match with our validation',
                'data' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'data created successfully',
            'data' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'username' => ['required','string','max:255'],
            'password' => ['required','string','min:8','max:255']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'data not match with our validation',
                'data' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $user = User::where('username', $validated['username'])->first();

        if (!$user) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'user data not found'
            ], 401);
        }

        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'invalid password'
            ], 401);
        }

        $token = $user->createToken('sanctum_token')->plainTextToken;

        return response()->json([
            'code' => 201,
            'status' => 'success',
            'message' => $user->username . ' successfully login',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'bearer'
            ]
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => $user->username . ' successfully logout',
            'data' => [
                'user' => $user,
                'token' => 'null',
                'token_type' => 'null'
            ]
        ], 202);
    }
}
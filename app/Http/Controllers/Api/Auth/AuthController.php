<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {

        try {

            DB::beginTransaction();            
            
            // Create user
            $user = User::create($request->validated());

            // Create social media record
            $user->social()->create();

            // Dispatch event
            event(new Registered($user));
            info('Registered', $user->toArray());

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();

            // Return response
            return response()->json([
                'success' => 'true',
                'message' => 'account created successfully',
                'data' => $user,
                'token' => $token,
                'type' => 'Bearer',
            ], 201);

        } catch (\Throwable $th) {
            //throw $th;
            $message = $th->getMessage();

            DB::rollBack();
            Log::error('Error rolling back transaction: ', [$message]);

            return $this->sendError($th, $message);
        }

    }

    public function login(LoginRequest $request)
    {

        // Authenticate request
        $request->authenticate();

        // Get user
        $user = $request->user();
        info('Login', $user->toArray());

        // Delete all user tokens
        $user->tokens()->delete();

        // Generate new token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'User login successful',
            'data' => $user,
            'token' => $token,
            'type' => 'Bearer',
        ], 201);

    }

    public function logout(Request $request)
    {
        // Get user
        $user = $request->user();
        // return $user;

        // Delete all user tokens
        $user->tokens()->delete();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
            'user' => $user
        ], 200);
    }

}

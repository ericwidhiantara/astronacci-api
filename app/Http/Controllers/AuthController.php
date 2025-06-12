<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'name.required' => 'Masukkan nama lengkap',
                'email.required' => 'Masukkan email',
                'email.email' => 'Format email salah',
                'email.unique' => 'Email sudah digunakan',
                'password.required' => 'Masukkan password',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sesuai',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Validation failed, please check your input',
                    'error' => $validator->errors(),
                ], 'Register failed', 400);
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Token
            $token = $user->createToken('auth-token')->plainTextToken;

            DB::commit();

            return ResponseFormatter::success([
                'success' => true,
                'message' => 'Register successful',
                'token' => $token
            ], 'Register successful');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Register failed', 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;
            return ResponseFormatter::success([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
            ], 'Login successful');
        }

        return ResponseFormatter::error([
            'success' => false,
            'message' => 'Wrong email or password',
        ], 'Authentication Failed, Wrong email or password', 401);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return ResponseFormatter::success([
                'success' => true,
                'message' => 'Reset password link sent',
                'status' => __($status)
            ], 'Reset password link sent');
        }

        return ResponseFormatter::error([
            'success' => false,
            'message' => 'Reset password link could not be sent',
            'status' => __($status)
        ], 'Reset password link could not be sent');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success(null,'Logout successful');

    }

    public function userProfile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'User not found',
            ], 'User not found', 404);
        }

        return ResponseFormatter::success($user, 'User profile retrieved successfully');
    }
}

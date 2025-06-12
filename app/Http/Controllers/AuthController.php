<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function submitForgetPasswordForm(Request $request)
    {
        //validasi
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|exists:users',
            ],
            [
                'email.required' => 'Masukkan email',
                'email.email' => 'Format email salah',
                'email.exists' => 'Email tidak terdaftar di sistem kami',
            ]
        );
        //jika validasi gagal maka return error ke user
        if ($validator->fails()) {
            return ResponseFormatter::error([
                'success' => false,
                'message' => Arr::first(Arr::flatten($validator->errors()->get('*'))), //to get the first message validation
                'error' => $validator->errors(),
            ], 'Validasi gagal', 400);
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::to($request->email)->send(new ForgotPasswordMail($token, $request->email));

        return ResponseFormatter::success(null, 'Email terkirim, silakan periksa email anda');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showResetPasswordForm($token, $email)
    {
        return view('auth.passwords.reset', ['token' => $token, 'email' => $email]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();
        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();
        if ($user) {

            return redirect()->back()->with(['success' => 'Kata Sandi Berhasil Diganti']);
        }
        return redirect()->back()->with(['error' => 'Kata Sandi Gagal Diganti']);
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

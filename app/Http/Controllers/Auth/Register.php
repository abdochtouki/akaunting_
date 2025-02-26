<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\Register as Request;
use App\Models\Auth\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Register extends Controller
{
    use RegistersUsers;


    protected $redirectTo = '/';


    public function __construct()
    {
        $this->middleware('guest');
    }


    public function create(): \Illuminate\Contracts\View\View
    {
        return view('auth.register.create');
    }


    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            Log::info('Creating user with data:', $validatedData);
            $user = $this->createUser($validatedData);
            Log::info('User created successfully:', ['user' => $user]);

            Auth::login($user);
            Log::info('User logged in successfully:', ['user' => Auth::user()]);

            if (Auth::check()) {
                Log::info('User is authenticated');
            } else {
                Log::error('User is not authenticated');
            }

            return response()->json([
                'status' => 'success',
                'message' => trans('auth.register_success'),
                'redirect' => route('dashboard'),
            ]);
        } catch (\Exception $e) {
            Log::error('Registration error:', ['error' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);

            return response()->json([
                'status' => 'error',
                'message' => trans('auth.register_error'),
                'error' => true,
            ], 500);
        }
    }


    protected function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'enabled' => true,
            'locale' => config('app.locale'),
            'created_from' => 'registration',
            'created_by' => null,
        ]);
    }
}

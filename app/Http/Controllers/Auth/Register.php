<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\Register as Request;
use App\Models\Auth\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

            event(new Registered($user));

            Auth::login($user);
            Log::info('User logged in successfully:', ['user' => Auth::user()]);

            $company = $this->createCompanyForUser($user);

            if (!$company) {
                $this->logout();
                return response()->json([
                    'status' => null,
                    'success' => false,
                    'error' => true,
                    'message' => trans('auth.error.company_creation_failed'),
                    'data' => null,
                    'redirect' => null,
                ]);
            }

            $user->companies()->attach($company->id);
            $company->makeCurrent();

            $url = route('dashboard', ['company_id' => $company->id]);

            return response()->json([
                'status' => null,
                'success' => true,
                'error' => false,
                'message' => trans('auth.login_redirect'),
                'data' => null,
                'redirect' => redirect()->intended($url)->getTargetUrl(),
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

    protected function createCompanyForUser(User $user)
    {
        try {
            $job = new \App\Jobs\Common\CreateCompany((object) [
                'name' => $user->name . "'s Company",
                'email' => $user->email,
                'locale' => config('app.locale'),
                'currency' => 'USD',
                'owner_id' => $user->id,
            ]);

            return dispatch($job);
        } catch (\Exception $e) {
            Log::error('Company creation failed:', ['error' => $e->getMessage()]);
            return null;
        }
    }


    protected function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'enabled' => true,
            'company_id' => 1,
            'locale' => config('app.locale'),
            'created_from' => 'registration',
            'created_by' => null,
        ]);
    }

    protected function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}

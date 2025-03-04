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

            // Fire the Registered event
            event(new Registered($user));

            // Log in the user
            Auth::login($user);
            Log::info('User logged in successfully:', ['user' => Auth::user()]);

            // Get the authenticated user
            $user = user();

            // Check if the user is enabled
            if (!$user->enabled) {
                $this->logout();

                return response()->json([
                    'status' => null,
                    'success' => false,
                    'error' => true,
                    'message' => trans('auth.disabled'),
                    'data' => null,
                    'redirect' => null,
                ]);
            }

            // Get the user's company
            $company = $user->withoutEvents(function () use ($user) {
                return $user->companies()->enabled()->first();
            });

            // Logout if no company is assigned
            if (!$company) {
                $this->logout();

                return response()->json([
                    'status' => null,
                    'success' => false,
                    'error' => true,
                    'message' => trans('auth.error.no_company'),
                    'data' => null,
                    'redirect' => null,
                ]);
            }

            if ($user->isCustomer()) {
                $path = session('url.intended', '');

                if (!Str::startsWith($path, $company->id . '/portal')) {
                    $path = route('portal.dashboard', ['company_id' => $company->id]);
                }

                return response()->json([
                    'status' => null,
                    'success' => true,
                    'error' => false,
                    'message' => trans('auth.login_redirect'),
                    'data' => null,
                    'redirect' => url($path),
                ]);
            }

            $url = route($user->landing_page, ['company_id' => $company->id]);

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

    protected function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'enabled' => true, // Ensure the user is enabled by default
            'company_id' => 1, // Assign the user to a default company
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

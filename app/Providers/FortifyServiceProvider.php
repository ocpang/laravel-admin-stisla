<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Fortify\UpdateUserProfileInformation;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        /**
        * VIEW
        */

        // Register
        // Fortify::registerView(function () {
        //     return view('auth.register');
        // });

        // Login
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // Forgot Password
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        // Reset Password
        Fortify::resetPasswordview(function($request){
            return view('auth.reset-password',['request'=>$request]);
        });

        // Update last login
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', Str::lower($request->input(Fortify::username())))->first();

            if ($user && Hash::check($request->password, $user->password)) {

                $user->update([
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => $request->ip()
                ]);

                return $user;
            }
        });

    }
}

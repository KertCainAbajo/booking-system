<?php

namespace App\Providers;

use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
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
        // Every new or changed password: 8+ characters with letters and numbers.
        // In production also refuse passwords found in known data breaches.
        Password::defaults(function () {
            $rule = Password::min(8)->letters()->numbers();

            return $this->app->isProduction() ? $rule->uncompromised() : $rule;
        });

        // Live site: every link, redirect and email button uses https://
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // MAIL_MAILER=brevo sends through Brevo's HTTPS API
        Mail::extend('brevo', fn () => (new BrevoTransportFactory)->create(
            new Dsn('brevo+api', 'default', config('services.brevo.key'))
        ));

        // Logging out stops booking alerts on that device (whichever logout button was used)
        Event::listen(Logout::class, function (Logout $event) {
            $endpoint = session('push_endpoint');
            if ($endpoint && $event->user && method_exists($event->user, 'deletePushSubscription')) {
                $event->user->deletePushSubscription($endpoint);
                session()->forget('push_endpoint');
            }
        });

        // API: 60 requests a minute per signed-in user (or per IP for guests)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

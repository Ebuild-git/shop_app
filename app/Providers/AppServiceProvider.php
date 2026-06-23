<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use App\Services\FcmNotificationService;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;

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
        date_default_timezone_set(config('app.timezone'));

        Paginator::useBootstrap();

        $allowedLocales = ['en', 'fr', 'ar'];

        $locale = Cookie::get('locale')
            ?? Session::get('locale')
            ?? config('app.locale');

        if (! in_array($locale, $allowedLocales)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        $this->app['events']->listen(NotificationSent::class, function (NotificationSent $event) {
            Log::info('NotificationSent event fired', [
                'notifiable_type' => get_class($event->notifiable),
                'notification_type' => get_class($event->notification),
            ]);

            $notifiable = $event->notifiable;

            if ($notifiable instanceof \App\Models\User) {
                app(FcmNotificationService::class)->sendCountUpdate($notifiable, $event->notification);
            }
        });
    }
}

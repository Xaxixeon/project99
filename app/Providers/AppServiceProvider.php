<?php

namespace App\Providers;

use App\Events\OrderStatusUpdated;
use App\Listeners\AdjustInventoryOnOrderStatus;
use App\Listeners\NotifyCustomerOnOrderReady;
use App\Listeners\UpdateOrderLifecycle;
use App\Events\OrderActivityLogged;
use App\Listeners\ApplyAutomationRules;
use App\Models\Customer;
use App\Observers\CustomerObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $helper = app_path('Support/activity.php');
        if (file_exists($helper)) {
            require_once $helper;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(OrderStatusUpdated::class, [UpdateOrderLifecycle::class, 'handle']);
        Event::listen(OrderStatusUpdated::class, [AdjustInventoryOnOrderStatus::class, 'handle']);
        Event::listen(OrderStatusUpdated::class, [NotifyCustomerOnOrderReady::class, 'handle']);
        Event::listen(OrderStatusUpdated::class, [ApplyAutomationRules::class, 'handle']);
        Event::listen(OrderActivityLogged::class, function () {
            // placeholder, broadcasting handled by event itself
        });
        Customer::observe(CustomerObserver::class);
    }
}

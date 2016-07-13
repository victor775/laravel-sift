<?php

namespace Suth\LaravelSift;

use SiftClient;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class SiftServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for Sift Science.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'Suth\LaravelSift\Listeners\RecordLoginSuccess',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'Suth\LaravelSift\Listeners\RecordLogout',
        ],
        'Illuminate\Auth\Events\Failed' => [
            'Suth\LaravelSift\Listeners\RecordLoginFailure',
        ],
    ];

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->mergeConfigFrom(__DIR__.'/config/sift.php', 'sift');

        $this->publishes([
            __DIR__.'/config/sift.php' => config_path('sift.php'),
        ]);

        $this->app->singleton('siftscience', function ($app) {
            return new SiftClient(config('sift.api_key'));
        });
	}

    /**
     * Perform post-registration booting of services.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        $this->loadViewsFrom(__DIR__.'/Views', 'sift');
    }
}
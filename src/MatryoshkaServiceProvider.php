<?php

namespace Laracasts\Matryoshka;

use Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;

class MatryoshkaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param Kernel $kernel
     */
    public function boot(Kernel $kernel)
    {
        if ($this->app->isLocal()) {
           $kernel->pushMiddleware('Laracasts\Matryoshka\FlushViews');
        }

        $this->publishes([
            __DIR__ . '/../config/matryoshka.php' => config_path('matryoshka.php'),
        ], 'config');

        Blade::directive('cache', function ($expression) {
            return "<?php if (!app('Laracasts\Matryoshka\BladeDirective')->setUp({$expression})): ?>";
        });

        Blade::directive('endcache', function ($expression) {
            return "<?php endif; echo app('Laracasts\Matryoshka\BladeDirective')->tearDown({$expression}); ?>";
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/matryoshka.php', 'matryoshka'
        );
        $this->app->singleton(BladeDirective::class);
    }
}

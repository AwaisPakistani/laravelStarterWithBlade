<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
class BladeDirective extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
         Blade::directive('statusBadge', function ($status) {
            return "<?php if ($status == 1): ?>
                <span class=\"badge bg-success\">Active</span>
            <?php else: ?>
                <span class=\"badge bg-danger\">Inactive</span>
            <?php endif; ?>";
        });
    }
}

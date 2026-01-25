<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $modulesPath = base_path('modules');

        if (!is_dir($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $moduleDir) {
            $this->registerModuleProviders($moduleDir);
        }
    }

    public function boot(): void
    {
        $modulesPath = base_path('modules');

        if (!is_dir($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $moduleDir) {
            $this->registerModuleRoutes($moduleDir);
            $this->registerModuleMigrations($moduleDir);
            $this->registerModuleViews($moduleDir);
        }
    }

    private function registerModuleProviders(string $moduleDir): void
    {
        $providersPath = $moduleDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Providers';

        if (!is_dir($providersPath)) {
            return;
        }

        $moduleName = basename($moduleDir);

        foreach (File::files($providersPath) as $providerFile) {
            $className = pathinfo($providerFile->getFilename(), PATHINFO_FILENAME);
            $providerClass = "Modules\\{$moduleName}\\Providers\\{$className}";

            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }

    private function registerModuleRoutes(string $moduleDir): void
    {
        $routesPath = $moduleDir . DIRECTORY_SEPARATOR . 'routes';
        $apiRoutes = $routesPath . DIRECTORY_SEPARATOR . 'api.php';
        $webRoutes = $routesPath . DIRECTORY_SEPARATOR . 'web.php';

        if (is_file($apiRoutes)) {
            Route::middleware('api')
                ->prefix('api')
                ->group($apiRoutes);
        }

        if (is_file($webRoutes)) {
            Route::middleware('web')
                ->group($webRoutes);
        }
    }

    private function registerModuleMigrations(string $moduleDir): void
    {
        $migrationsPath = $moduleDir . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';

        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    private function registerModuleViews(string $moduleDir): void
    {
        $viewsPath = $moduleDir . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';

        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, basename($moduleDir));
        }
    }
}

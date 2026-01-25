<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\BranchObserver;
use App\Observers\ProductObserver;
use Modules\Article\Contracts\AiServiceInterface;
use Modules\Article\Services\NullAiService;
use Modules\Contact\Contracts\MailServiceInterface;
use Modules\Contact\Services\NullMailService;
use Modules\Order\Contracts\PaymentGatewayInterface;
use Modules\Order\Contracts\OrderRepositoryInterface;
use Modules\Order\Services\NullPaymentGateway;
use Modules\Order\Services\EloquentOrderRepository;
use Modules\Product\Contracts\AIGenerationContract;
use Modules\Product\Services\GeminiService;
use Modules\Branch\Models\Branch;
use Modules\Product\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AiServiceInterface::class, NullAiService::class);
        $this->app->bind(MailServiceInterface::class, NullMailService::class);
        $this->app->bind(PaymentGatewayInterface::class, NullPaymentGateway::class);
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
        $this->app->bind(AIGenerationContract::class, GeminiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Branch::observe(BranchObserver::class);
    }
}

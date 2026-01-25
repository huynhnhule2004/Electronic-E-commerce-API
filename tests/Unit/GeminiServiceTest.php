<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Modules\Product\Services\GeminiService;

it('generates product description via gemini api with retry', function () {
    config()->set('services.gemini.api_key', 'test-key');
    config()->set('services.gemini.model', 'gemini-1.5-flash');
    config()->set('services.gemini.base_url', 'https://generativelanguage.googleapis.com');

    Http::fakeSequence()
        ->pushStatus(500)
        ->push([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => 'SEO content'],
                        ],
                    ],
                ],
            ],
        ], 200);

    $service = new GeminiService();

    $result = $service->generateProductDescription('Phone', ['battery', 'camera']);

    expect($result)->toBe('SEO content');
});

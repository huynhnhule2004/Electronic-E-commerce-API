<?php

namespace Modules\Product\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Product\Contracts\AIGenerationContract;
use RuntimeException;
use Throwable;

class GeminiService implements AIGenerationContract
{
    public function generateProductDescription(string $productName, array $keywords): string
    {
        $apiKey = (string) config('services.gemini.api_key');
        $model = (string) config('services.gemini.model', 'gemini-1.5-flash');
        $baseUrl = (string) config('services.gemini.base_url', 'https://generativelanguage.googleapis.com');

        if ($apiKey === '') {
            throw new RuntimeException('Gemini API key is not configured.');
        }

        $prompt = $this->buildPrompt($productName, $keywords);

        try {
            /** @var Response $response */
            $response = Http::retry(3, 500)
                ->withQueryParameters(['key' => $apiKey])
                ->post("{$baseUrl}/v1beta/models/{$model}:generateContent", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ]);

            if (! $response->successful()) {
                throw new RuntimeException('Gemini API returned non-200 response.');
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (! is_string($text) || trim($text) === '') {
                throw new RuntimeException('Gemini API returned empty content.');
            }

            return trim($text);
        } catch (Throwable $exception) {
            Log::error('Gemini generation failed', [
                'message' => $exception->getMessage(),
            ]);

            throw new RuntimeException('Gemini generation failed after retries.', 0, $exception);
        }
    }

    /**
     * @param array<string> $keywords
     */
    private function buildPrompt(string $productName, array $keywords): string
    {
        $keywordsText = implode(', ', $keywords);

        return <<<PROMPT
Bạn là chuyên gia copywriter SEO cho thiết bị điện tử. Hãy viết mô tả sản phẩm bằng tiếng Việt với yêu cầu:
- 1 tiêu đề SEO (<= 70 ký tự)
- 1 meta description (<= 160 ký tự)
- 2 đoạn mô tả chính, ngắn gọn, dễ đọc
- 5 gạch đầu dòng nêu lợi ích/điểm nổi bật
- Chèn các từ khóa sau một cách tự nhiên: {$keywordsText}
- Không bịa thông số kỹ thuật. Nếu thiếu thông tin, hãy mô tả theo hướng an toàn.

Tên sản phẩm: {$productName}
PROMPT;
    }
}

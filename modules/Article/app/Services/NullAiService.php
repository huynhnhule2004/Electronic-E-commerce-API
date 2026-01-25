<?php

namespace Modules\Article\Services;

use Modules\Article\Contracts\AiServiceInterface;

class NullAiService implements AiServiceInterface
{
    public function summarize(string $content): string
    {
        return '';
    }
}

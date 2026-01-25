<?php

namespace Modules\Article\Contracts;

interface AiServiceInterface
{
    public function summarize(string $content): string;
}

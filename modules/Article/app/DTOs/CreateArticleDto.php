<?php

namespace Modules\Article\DTOs;

readonly class CreateArticleDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $content
    ) {
    }
}

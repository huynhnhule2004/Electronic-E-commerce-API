<?php

namespace Modules\Article\Actions;

use Modules\Article\DTOs\CreateArticleDto;

class CreateArticleAction
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(CreateArticleDto $dto): array
    {
        return [
            'title' => $dto->title,
            'content' => $dto->content,
        ];
    }
}

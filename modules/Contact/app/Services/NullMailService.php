<?php

namespace Modules\Contact\Services;

use Modules\Contact\Contracts\MailServiceInterface;

class NullMailService implements MailServiceInterface
{
    public function send(string $to, string $subject, string $body): void
    {
    }
}

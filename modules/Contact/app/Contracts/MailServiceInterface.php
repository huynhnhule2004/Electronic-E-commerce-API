<?php

namespace Modules\Contact\Contracts;

interface MailServiceInterface
{
    public function send(string $to, string $subject, string $body): void;
}

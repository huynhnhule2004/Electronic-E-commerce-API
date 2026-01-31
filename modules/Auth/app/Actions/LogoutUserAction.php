<?php

namespace Modules\Auth\Actions;

use Illuminate\Support\Facades\Auth;

class LogoutUserAction
{
    public function execute(): void
    {
        Auth::guard('api')->logout();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Traits\RespondAsApi;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use RespondAsApi;
}

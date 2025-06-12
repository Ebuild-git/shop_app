<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\ImageUpload;

/**
 * @OA\Info(
 *     title="SHOPIN",
 *     version="1.0.0",
 *     description="SHOPIN API's integration"
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    use ImageUpload;


}

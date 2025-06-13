<?php

namespace App\Http\Controllers\Api\Doc;

use App\Http\Controllers\Controller;


/**
 * @OA\Info(
 *     title="My API",
 *     version="1.0",
 *     description="This is the API documentation for your Laravel app"
 * )
 *
 *
 * @OA\Tag(
 *     name="Products",
 *     description="Operations about products"
 * )
 */
class Doc extends Controller
{
    public function dummy() {}
}

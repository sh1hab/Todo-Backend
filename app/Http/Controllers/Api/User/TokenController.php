<?php

namespace App\Http\Controllers\Api\User;

use App\Models\UserToken;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\ApiController;

class TokenController extends ApiController
{
    /**
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateUserToken()
    {
        $userToken = UserToken::create([
            'uuid' => Str::orderedUuid()
        ]);

        return response()->json([
            'uuid' => $userToken->uuid
        ])->cookie('uuid', $userToken->uuid, 60);
    }
}

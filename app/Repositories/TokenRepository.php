<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\UserToken;

class TokenRepository
{
    public function createToken()
    {
        $user = \Auth::user();

        if(!$user->is_verified) return false;

        $token = $user->tokens()->create([
            'access_token' => hash('sha256', Str::random(40)),
            'expires_at' => Carbon::now()->addDays(30)
        ]);
     
        return $token;
    }

    public function deleteToken($token)
    {
        $userID = \Auth::user()->id;

        $token = UserToken::where('access_token', $token)
            ->first();

        if ($token && $token->user_id == $userID) {
            $token->delete();
            return true;
        }

        return false;
    }
}
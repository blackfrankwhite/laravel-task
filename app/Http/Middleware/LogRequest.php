<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserToken;
use App\Models\UserRequestLog;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        $token = $request->query('access_token');
        $user = \Auth::user();

		if (empty($token)) {
			$token = $request->bearerToken();
		}

        $tokenID = UserToken::where('access_token', $token)
            ->first()
            ->id;

        $params = json_encode($request->all());        

        UserRequestLog::create([
            'user_id' => $user->id,
            'token_id' => $tokenID,
            'request_method' => $request->method(),
            'request_params' => $params
        ]);

        $user->update([
            'requests_count' => $user->requests_count++
        ]);

        return $next($request);
    }
}

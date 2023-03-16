<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Admin
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token =  JWTAuth::getToken();
        try {
            $user = JWTAuth::authenticate($token);

            if ($user->user_type !== 'admin') {
                return response(['code' => 401, 'description' => 'Unauthorized.'], 200);
            }

            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response(['code' => 402, 'description' => 'Token expired.'], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['code' => 403, 'description' => 'Token Invalid.']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response(['code' => 406, 'description' => 'Not Acceptable.'], 200);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class BasicAuthMiddleware
{
    private string $username;
    private string $password;

    public function __construct(string $username = '', string $password = '')
    {
        $this->username = $username ?: config('constants.BASIC_AUTH_USER');
        $this->password = $password ?: config('constants.BASIC_AUTH_PASS');
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array(App::environment(), $this->environments())) {
            header('Cache-Control: no-cache, must-revalidate, max-age=0');
            $credentialsSupplied = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
            $isUnauthorised = (
                !$credentialsSupplied ||
                $_SERVER['PHP_AUTH_USER'] != $this->username ||
                $_SERVER['PHP_AUTH_PW'] != $this->password
            );
            if ($isUnauthorised) {
                header('HTTP/1.1 401 Authorization Required');
                header('WWW-Authenticate: Basic realm="Access denied"');
                return response(view('unauthorized'), 401);
            }
        }
        return $next($request);
    }

    protected function environments(): array
    {
        return [
            config('constants.ENVIRONMENTS.DEV'),
            config('constants.ENVIRONMENTS.PROD'),
        ];
    }
}

<?php

namespace App\Exceptions;

use App\Constants\ErrorConstants;
use App\Helpers\ResponseHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\MissingScopeException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class HandlerApi extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        InvalidCredentialsException::class,
        ValidationException::class,
        BadRequestHttpException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $e
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     * Overridden the parent handler function instance to customize and fulfil our need to send notificationData in
     * the response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $debug = env('APP_DEBUG') ?? false;
        if ($exception instanceof ValidationException) {
            $errorDetails = [];
            $error = $exception->getMessage();
            if (Str::isJson($exception->getMessage())) {
                $err = json_decode($exception->getMessage());
                $error = $err->error;
                $errorDetails = $err->errorDetails;
            }
            $errorResponse = [
                'error' => $error,
                'status' => 'fail',
                'message' => array_merge([$error], $errorDetails),
                'data' => null,
                'type' => ErrorConstants::TYPE_VALIDATION_ERROR
            ];
            $statusCode = 400;
        } elseif ($exception instanceof NotFoundHttpException) {
            $errorResponse = [
                'error' => !empty($exception->getMessage()) ? $exception->getMessage() : "Resource not found. Please try again by clearing your browser cache (or) hard refreshing the tab (CTRL+F5 / Ctrl+Shift+R). If problem still persists, feel free to reach out to us.",
                'type' => ErrorConstants::TYPE_RESOURCE_NOT_FOUND_ERROR,
                'errorDetails' => "Resource not found"
            ];
            $statusCode = 404;
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $errorResponse = [
                'error' => !empty($exception->getMessage()) ? $exception->getMessage() : "Method not allowed",
                'type' => ErrorConstants::TYPE_METHOD_NOT_ALLOWED_ERROR,
                'errorDetails' => "Method not allowed"
            ];
            $statusCode = 405;
        } elseif ($exception instanceof InvalidCredentialsException) {
            $errorResponse = [
                'error' => !empty($exception->getMessage()) ? $exception->getMessage() : "Invalid Credentials",
                'type' => ErrorConstants::TYPE_INVALID_CREDENTIALS_ERROR,
                'errorDetails' => $exception->getMessage()
            ];
            $statusCode = 401;
        } elseif ($exception instanceof MissingScopeException) {
            $errorResponse = [
                'error' => !empty($exception->getMessage()) ? $exception->getMessage() : "Invalid Scope",
                'type' => ErrorConstants::TYPE_INVALID_CREDENTIALS_ERROR,
                'errorDetails' => $exception->getMessage()
            ];
            $statusCode = 401;
        } elseif ($exception instanceof AuthorizationException) {
            $errorResponse = [
                'error' => !empty($exception->getMessage()) ? $exception->getMessage() : "Authorization Failed",
                'type' => ErrorConstants::TYPE_AUTHORIZATION_ERROR,
                'errorDetails' => $exception->getMessage()
            ];
            $statusCode = 403;
        } elseif ($exception instanceof InvalidSocialLoginException) {
            $errorResponse = [
                'error' => !empty($exception->getMessage()) ? $exception->getMessage() : "Social authentication Failed",
                'type' => ErrorConstants::TYPE_AUTHORIZATION_ERROR,
                'errorDetails' => $exception->getMessage()
            ];
            $statusCode = 403;
        } elseif ($exception instanceof BadRequestHttpException) {
            $errorResponse = [
                'error' => !empty($exception->getMessage()) ? $exception->getMessage() : "Bad Request",
                'type' => ErrorConstants::TYPE_BAD_REQUEST_ERROR,
                'errorDetails' => "Bad Request"
            ];
            $statusCode = 400;
        } elseif ($exception instanceof ThrottleRequestsException) {
            $errorResponse = [
                'error' => !empty($exception->getMessage()) ? $exception->getMessage() : "Bad Request",
                'type' => ErrorConstants::TYPE_TOO_MANY_REQUEST_ERROR,
                'errorDetails' => "Too many requests"
            ];
            $statusCode = 429;
        } elseif ($exception instanceof HttpException && $exception->getStatusCode() == 502) {
            $errorResponse = [
                'error' => $exception->getMessage(),
                'errorDetails' => $exception->getMessage(),
                'type' => ErrorConstants::TYPE_BAD_GATEWAY_ERROR
            ];
            $statusCode = $exception->getStatusCode();
        } elseif ($exception instanceof Google2faAlreadyEnabledException) {
            $errorResponse = [
                'error' => $exception->getMessage(),
                'errorDetails' => $exception->getMessage(),
                'type' => ErrorConstants::TYPE_GOOGLE2FA_ALREADY_ENABLED_ERROR
            ];
            $statusCode = $exception->getStatusCode();
        } elseif ($exception instanceof Google2faInvalidCodeException) {
            $errorResponse = [
                'error' => $exception->getMessage(),
                'errorDetails' => $exception->getMessage(),
                'type' => ErrorConstants::TYPE_GOOGLE2FA_INVALID_CODE_ERROR,
            ];
            $statusCode = $exception->getStatusCode();
        } elseif ($exception instanceof Google2faRequiredException) {
            $errorResponse = [
                'error' => $exception->getMessage(),
                'errorDetails' => $exception->getMessage(),
                'data' => $exception->getData(),
                'type' => ErrorConstants::TYPE_GOOGLE2FA_REQUIRED_ERROR
            ];
            $statusCode = $exception->getStatusCode();
        } elseif ($exception instanceof AuthorizationException && str_contains($request->getRequestUri(), '/admin')) {
            Auth::logout(); // logout the user just in case..
            return response(view('unauthorized'), 403);
        } elseif ($exception instanceof AuthenticationException && str_contains($request->getRequestUri(), '/oauth/authorize')) {
            // set the current URI for redirect
            redirect()->setIntendedUrl($request->getRequestUri());
            return redirect()->route('oauth.login.show')->withErrors('error', $exception->getMessage());
        } else {
            $errorResponse = [
                'error' => $debug ? $exception->getMessage() : 'Something went wrong. Please try again later.',
                'type' => ErrorConstants::TYPE_INTERNAL_SERVER_ERROR,
                'errorDetails' => $exception->getTrace()
            ];
            $statusCode = 500;
        }

        if (!$debug) {
            unset($errorResponse['errorDetails']);
        }

        $notificationData = ResponseHelper::getNotification();

        if (empty($notificationData) && is_string($errorResponse['error'])) {
            $notificationData = ResponseHelper::setCustomErrorNotification($errorResponse['error']);
        }

        $errorResponse['notificationData'] = $notificationData;
        if (!empty($errorResponseData = ResponseHelper::getErrorResponseData())) {
            $errorResponse['data'] = (json_decode(json_encode($errorResponseData), true));
        }
        return $request->expectsJson()
            ? response()->json($errorResponse, $statusCode)
            : $this->prepareResponse($request, $exception);
    }
}

<?php

namespace App\Exceptions;

use Throwable;
use App\Models\ErrorLog;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            error_log("12345" . $e);
            //Str::uuid(),
            $data = [

                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];

            $dataArr = [

                'file'           => $data['file'],
                'error_summary'  => 'Line ' . $data['line'] . ' ' . $data['message'],
                'log_trace'      => $data['trace']
            ];

            if (auth()->check()) {
                $dataArr['user_id'] = auth()->user()->id;
            } else {
                $dataArr['user_id'] = 0;
            }
            ErrorLog::create($dataArr);
        });
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'status'    => "error",
            // 'message' => $exception->getMessage(),
            'errors'  => $this->transformErrors($exception),

        ], $exception->status);
    }

    // transform the error messages,
    private function transformErrors(ValidationException $exception)
    {
        $errors = [];

        foreach ($exception->errors() as $field => $messages) {
            $errors[$field] = $messages[0];
        }

        return $errors;
    }
}

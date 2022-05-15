<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        BusinessLogicValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof BusinessLogicValidationException) { // this should be unit tested
            return $this->renderBusinessLogicValidationException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  BusinessLogicValidationException  $e
     * @return \Illuminate\Http\JsonResponse|object
     */
    private function renderBusinessLogicValidationException($request, BusinessLogicValidationException $e)
    {
        if (! $request->isJson()) {
            return parent::render($request, $e);
        }

        session()->flash('errors', \array_merge(
            [$e->getField() => $e->getMessage()],
            session()->get('errors', [])
        ));

        return response()->json([
            'message' => 'The given data was invalid.',
            'errors' => [
                $e->getField() => $e->getMessage(),
            ],
        ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

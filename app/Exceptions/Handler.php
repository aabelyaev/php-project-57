<?php

namespace App\Exceptions;

use Exception;

class Handler
{
    public function register(): void
    {
        $this->renderable(function (ValidationException $exception, $request) {
            if (!$request->wantsJson()) {
                return null; // Laravel handles as usual
            }

            throw CustomValidationException::withMessages(
                $exception->validator->getMessageBag()->getMessages()
            );
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}

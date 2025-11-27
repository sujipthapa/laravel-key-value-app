<?php
namespace App\Exceptions\API;

use Exception;

class ValidationException extends Exception
{
    protected $errors = [];

    public function __construct($errors = [])
    {
        parent::__construct("Validation failed", 422);

        $this->errors = $errors;
    }

    public function render()
    {
        return response()->json([
            'success' => false,
            'error'   => [
                'code'    => "VALIDATION_ERROR",
                'message' => "Invalid input data",
                'details' => $this->errors,
            ],
        ], 422);
    }
}

<?php
namespace App\Exceptions\API;

use Exception;

class NotFoundException extends Exception
{
    protected string $errorCode = 'NOT_FOUND';
    protected array $details    = [];

    public function __construct(
        string $message = "Resource not found",
        string $code = "NOT_FOUND",
        array $details = []
    ) {
        parent::__construct($message, 404);
        $this->errorCode = $code;
        $this->details   = $details;
    }

    public function render()
    {
        return response()->json([
            'success' => false,
            'error'   => [
                'code'    => $this->errorCode,
                'message' => $this->getMessage(),
                'details' => $this->details,
            ],
        ], 404);
    }
}

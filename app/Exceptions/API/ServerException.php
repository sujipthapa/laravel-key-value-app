<?php
namespace App\Exceptions\API;

use Exception;

class ServerException extends Exception
{
    protected $details;

    public function __construct($message = "Internal server error", $details = null)
    {
        parent::__construct($message, 500);

        $this->details = $details;
    }

    public function render()
    {
        return response()->json([
            'success' => false,
            'error'   => [
                'code'    => "SERVER_ERROR",
                'message' => $this->getMessage(),
                'details' => $this->details,
            ],
        ], 500);
    }
}

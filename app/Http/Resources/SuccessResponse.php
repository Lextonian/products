<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResponse extends JsonResource
{
    private string $message;

    public function __construct(string $message = 'Успешно выполнено')
    {
        parent::__construct(null);
        $this->message = $message;
    }

    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'message' => $this->message
        ];
    }
}

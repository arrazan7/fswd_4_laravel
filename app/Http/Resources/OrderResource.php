<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    private $status;
    private $message;

    public function __construct($resource, $status, $message){
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }

    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'travel_id' => $this->travel_id,
                'ticket' => $this->ticket,
                'date' => $this->date,
                'price' => $this->price
            ]
        ];
    }
}

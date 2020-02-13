<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FailedJobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'queue' => strtoupper($this->queue),
            'display_name' => getValueFromJson($this->payload, 'displayName'),
            'exception' => substr($this->exception, 0, 70),
            'failed_at' => $this->failed_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'attempts' => $this->attempts,
            'badge' => ($this->attempts > 0) ? 'warning' : 'primary',
            'reserved_at' => ($this->reserved_at) ? date('d-m-Y H:i:s', $this->reserved_at) : null,
            'available_at' => date('d-m-Y H:i:s', $this->available_at),
            'created_at' => date('d-m-Y H:i:s', $this->created_at),
            'duration' => ($this->reserved_at) ? time() - $this->reserved_at : null,
            'running' => ($this->reserved_at) ? 1 : 0,
        ];
    }
}

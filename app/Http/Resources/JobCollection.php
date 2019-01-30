<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class JobCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'count' => $this->collection->count(),
            'timestamp' => \Carbon\Carbon::now()->timezone(auth()->user()->time_zone)->format(auth()->user()->date_format . ' H:i:s'),
            'data' => $this->collection
        ];
    }

}

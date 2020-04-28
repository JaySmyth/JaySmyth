<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait Logable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs()
    {
        return $this->morphMany(\App\Models\Log::class, 'logable')->orderBy('id', 'DESC');
    }

    /**
     * Add a log entry.\App\Models\
     *
     * @return Model
     */
    public function log($information = 'Record created', $comments = null, $data = null)
    {
        if (is_array($data)) {
            $data = Arr::except($data, ['_token']);
        }

        $attributes = [
            'information' => ucwords(strtolower($information)),
            'comments' => ucfirst(strtolower($comments)),
            'data' => ($data) ? json_encode($data) : false,
            'user_id' => (auth()->id()) ? auth()->id() : 2,
        ];

        return $this->logs()->create($attributes);
    }

    /**
     * Update record and log the values that have changed.
     *
     * @param type $attributes
     * @return type
     */
    public function updateWithLog($attributes, $comments = null)
    {
        $this->fill($attributes);

        $changes = $this->getDirty();

        $this->log('Record updated', $comments, $changes);

        return $this->update($attributes);
    }
}

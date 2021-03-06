<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Log extends Model
{
    /**
     * Mass assignment protection.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get all of the owning logable models.
     */
    public function logable()
    {
        return $this->morphTo();
    }

    /**
     * A log belongs to a user.
     *
     * @return
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Build link to parent - could setup inverse relationships in future....
     */
    public function getParentUrlAttribute()
    {
        $classPath = explode('\\', $this->logable_type);
        $model = last($classPath);

        return url(str_replace('_', '-', Str::plural(strtolower(Str::snake($model)))), $this->logable_id);
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('logable_type', 'LIKE', '%'.$filter.'%');
        }
    }

    /**
     * Scope date.
     *
     * @return
     */
    public function scopeDateBetween($query, $dateFrom, $dateTo)
    {
        if (! $dateFrom && $dateTo) {
            return $query->where('logs.created_at', '<', Carbon::parse($dateTo)->endOfDay());
        }

        if ($dateFrom && ! $dateTo) {
            return $query->where('logs.created_at', '>', Carbon::parse($dateFrom)->startOfDay());
        }

        if ($dateFrom && $dateTo) {
            return $query->whereBetween('logs.created_at', [Carbon::parse($dateFrom)->startOfDay(), Carbon::parse($dateTo)->endOfDay()]);
        }
    }

    /**
     * Scope information.
     *
     * @return
     */
    public function scopeHasInformation($query, $information)
    {
        if ($information) {
            return $query->where('information', 'LIKE', '%'.$information.'%');
        }
    }

    /**
     * Scope comments.
     *
     * @return
     */
    public function scopeHasComments($query, $comments)
    {
        if ($comments) {
            return $query->where('comments', 'LIKE', '%'.$comments.'%');
        }
    }
}

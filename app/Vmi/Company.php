<?php

namespace App\Vmi;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'vmi';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /**
     * A company has dispatch settings.
     *
     * @return
     */
    public function courierDispatch()
    {
        return $this->hasOne(CourierDispatch::class);
    }
}

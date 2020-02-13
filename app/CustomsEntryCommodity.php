<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomsEntryCommodity extends Model
{
    /*
     * Mass assignable.
     */

    protected $fillable = ['vendor', 'commodity_code', 'value', 'duty', 'duty_percent', 'vat', 'weight', 'country_of_origin', 'customs_entry_id', 'customs_procedure_code_id'];

    /**
     * CPC Relationship.
     *
     * @return type
     */
    public function customsProcedureCode()
    {
        return $this->belongsTo(CustomsProcedureCode::class);
    }
}

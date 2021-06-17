<?php

namespace App\Models;

use App\Traits\Logable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyService extends Model
{
    use Logable;

    protected $table = 'company_service';

    protected $fillable = ['name', 'preference', 'account', 'scs_account', 'country_filter', 'monthly_limit', 'max_weight_limit', 'company_id', 'service_id'];
}

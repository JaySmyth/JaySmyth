<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportConfig extends Model
{
    /**
     * Number of columns that can be configured.
     *
     * @var type
     */
    public $numberOfColumns = 52;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * A Configuration is owned by a company.
     *
     * @return
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * A Configuration is owned by a user.
     *
     * @return
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A Configuration is owned by a company.
     *
     * @return
     */
    public function mode()
    {
        return $this->belongsTo(Mode::class);
    }

    /**
     * @param type $value
     * @return type
     */
    public function getDefaultRecipientEmailAttribute($value)
    {
        if (stristr($value, ';')) {
            return explode(';', $value);
        }

        return $value;
    }

    /**
     * @param type $value
     * @return type
     */
    public function getCcImportResultsEmail($value)
    {
        if (stristr($value, ';')) {
            return explode(';', $value);
        }

        return $value;
    }

    /**
     * Get the field names of the import columns with excel columns names
     * as the array key.
     *
     * @return array
     */
    public function getColumns()
    {
        $columns = [];
        $prefix = null;

        for ($i = 0; $i <= $this->numberOfColumns; $i++) {
            if ($i == 26) {
                $prefix = 'A';
            }

            $key = strtoupper($prefix.chr($i % 26 + 0x41));

            $field = "column$i";
            if ($this->$field) {
                $columns[$key] = $this->$field;
            }
        }

        return $columns;
    }
}

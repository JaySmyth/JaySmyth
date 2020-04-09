<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait HasPreferences
{
    /*
     * Fields that we do not want the user to set preferences for
     */

    private $exclude = ['collection_date'];

    /**
     * Relationship.
     * @return type
     */
    public function preferences()
    {
        return $this->hasMany(\App\Models\Preference::class);
    }

    /**
     * @param type $companyId
     * @param type $modeId
     * @param type $values
     */
    public function setPreferences($companyId, $modeId, $values)
    {
        if (! is_array($values)) {
            parse_str($values, $values);
        }

        // Flatten the multi-dimensional array into 1D array using dot notation
        $values = Arr::dot($values);

        // Remove any existing default values first
        $this->resetPreferences($companyId, $modeId);

        // Construct an array for bulk insert
        $preferences = [];

        foreach ($values as $field => $value) {
            if (strlen($value) > 0 && ! in_array($field, $this->exclude)) {
                $preferences[] = [
                    'field' => $field,
                    'value' => $value,
                    'company_id' => $companyId,
                    'mode_id' => $modeId,
                    'user_id' => $this->id,
                ];
            }
        }

        $this->preferences()->insert($preferences);
    }

    /**
     * @param type $companyId
     * @param type $modeId
     * @return type
     */
    public function getPreferences($companyId, $modeId, $asArray = false)
    {
        $values = [];

        $preferences = $this->preferences()
                ->where('company_id', $companyId)
                ->where('mode_id', $modeId)
                ->get();

        // Build an array of the user's preferences
        foreach ($preferences as $preference) {
            $values[$preference->field] = $preference->value;
        }

        // User has no preferences defined, so prepopulate with system defaults
        if ($preferences->count() <= 0) {
            $company = Company::findOrFail($companyId);

            $values = [
                'sender_name' => $this->name,
                'sender_company_name' => $company->company_name,
                'sender_type' => 'c',
                'sender_address1' => $company->address1,
                'sender_address2' => $company->address2,
                'sender_address3' => $company->address3,
                'sender_city' => $company->city,
                'sender_state' => $company->state,
                'sender_postcode' => $company->postcode,
                'sender_country_code' => $company->country_code,
                'sender_telephone' => $this->telephone,
                'sender_email' => $this->email,
                'terms_of_sale' => 'DAP',
                'bill_tax_duty' => 'recipient',
                'eori' => $company->eori,
            ];
        }

        if ($asArray) {
            return array_undot($values);
        }

        return json_encode($values);
    }

    /**
     * @param type $companyId
     * @param type $modeId
     */
    public function resetPreferences($companyId, $modeId)
    {
        $this->preferences()
                ->where('company_id', $companyId)
                ->where('mode_id', $modeId)
                ->delete();
    }
}

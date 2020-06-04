<?php

/*
 * ****************************************
 * Class
 *  To get and set ISLEDI attributes
 *  To output IFS objects as XML
 *  Read SCS table dump and decode
 * ****************************************
 */

namespace App\ScsXml;

/**
 * Description of DocAdds.
 *
 * @author gmcbroom
 */
class SCSTable
{
    public $attributes;
    public $table = [];
    public $tableName = '';
    public $fieldCount = 0;
    public $cr;
    public $lf;

    public function __construct()
    {
        $this->cr = chr(13);
        $this->lf = chr(10);
    }

    public function cleanAttribute($value)
    {
        $value = str_replace('<', '&lt;', $value);                              // Optional
        $value = str_replace('>', '&gt;', $value);                              // Optional
        $value = str_replace('&', '&amp;', $value);                             // Mandatory
        $value = str_replace("'", '&apos;', $value);                            // Mandatory
        $value = str_replace('"', '&quot;', $value);                            // Mandatory
        return $value;
    }

    public function setAttribute($attribute, $value = '')
    {
        if (array_key_exists($attribute, $this->attributes)) {
            if ($value > '') {

                // Remove any characters not allowed
                $value = $this->cleanAttribute($value);

                // Only output attribute if it has a value
                if (empty($this->attributes[$attribute])) {

                    // No Validation necessary
                    $this->table[$attribute] = $value;
                } else {

                    // Validate field then set (add validation later)
                    $this->table[$attribute] = $value;
                }
            }
        } else {
            dd('Table : '.$this->tableName." Field $attribute not found");
        }
    }

    public function getAttribute($attribute)
    {
        if (in_array($attribute, $this->attributes)) {
            if (isset($this->table[$attribute])) {
                return $this->table[$attribute];
            } else {
                return;
            }
        }
    }

    public function toXML()
    {
        $xml = null;

        if ($this->table) {
            $term = "\n";
            $xml .= '<'.$this->tableName.">$term";
            $blank = '<'.$this->tableName.'>'.'</'.$this->tableName.">$term";

            foreach ($this->attributes as $attribute => $value) {
                if (isset($this->table[$attribute]) && !empty($this->table[$attribute])) {

                    // Only set if it contains a value
                    $xml .= "<$attribute>".$this->table[$attribute]."</$attribute>$term";
                }
            }
            $xml .= '</'.$this->tableName.">$term";
        }

        return $xml;
    }

    /*
     * *****************************************************
     * Functions below related to processing SCS table dumps
     * *****************************************************
     */

    public function decodeTable($table)
    {
        $table = $this->cleanString($table);

        // Change to array of csv strings
        $records = explode(chr(10), $table);

        foreach ($records as $record) {
            $data = array_map('trimMe', str_getcsv($record, ' ', '"'));
        }
    }

    public function trimMe($data)
    {
        return trim($data, '|');
    }

    public function cleanString($table)
    {
        $table = removeCrFromFields($table);

        // Replace comma with .
        $table = str_replace(',', '.', $table);

        // Replace empty fields with |^^|
        $table = str_replace('""', '|^^|', $table);
        $table = str_replace('""', '|^^|', $table);

        // Remove any remaining "
        $table = str_replace('""', "'", $table);

        // Put empty fields back
        $table = str_replace('|^^|', '""', $table);

        return $table;
    }

    public function removeCrFromFields($table)
    {
        $cnt = strlen($table) - 1;  // string index from 0 not 1
        $finished = false;
        $startFrom = 0;
        $currentPos = 0;
        $newString = '';

        // Loop until we run out of quotes
        while (! $finished) {

            // Find opening quote
            $pos1 = strpos($table, '"', $startFrom);

            if ($pos1 === false) {

                /*
                 * ******************************************
                 *  No more quotes so add remainder of string
                 *  And return complete $newString
                 * ******************************************
                 */
                $newString .= substr($table, $startFrom, $cnt);

                return $newString;
            } else {

                /*
                 * *************************************
                 * Opening quote found,
                 * so now find closing Quote
                 * *************************************
                 */
                $pos2 = strpos($table, '"', $pos1 + 1);

                if ($pos2 === false) {

                    /*
                     * **************************************
                     * No Closing Quote found
                     * Invalid format - raise an error
                     * **************************************
                     */
                    mail('it@antrim.ifsgroup.com', 'import_job_line.php - Pos1 '.$pos1.' CNT ', $cnt);
                    dd("Invalid File Format Pos : $pos1");
                } else {

                    /*
                     * **************************************
                     * Process process everthing in between
                     * Quotes and add to $newString
                     * **************************************
                     */

                    // Add Unquoted data
                    if ($pos1 > $currentPos) {
                        $newString .= substr($table, $currentPos, $pos1 - $currentPos);
                    }

                    // Get Field/ String
                    $origString = substr($table, $pos1, $pos2 - $pos1 + 1);

                    // Remove CR
                    $origString = str_replace($this->cr, '.', $origString);
                    $newString .= str_replace($this->lf, '.', $origString);

                    $startFrom = $pos2 + 1;
                    $currentPos = $pos2 + 1;
                }
            }
        }
    }
}

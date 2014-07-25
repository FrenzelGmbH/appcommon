<?php
/**
 * @author Johannes "Haensel" Bauer <johannes@frenzel.net>
 * @author Yii2 Philipp Frenzel <philipp@frenzel.net>
 */

/**
 * Validator class used to check csv files
 * @todo needs to be rewritten - complete
 */
class CsvValidator extends CValidator
{
    /**
     * @var bool Wheter this attribute is allowed to be empty. Defaults to false
     */
    public $allowEmpty = false;
	/**
	 * @var string The separator used to separate the files columns. Defaults to ";"
	 */
	public $separator = ';';
	/**
	 * @var integer The number of columns the validated file has to use
	 */
	public $columnCount = 0;
	/**
	 * @var array The name of the columns the validated file has to use
	 */
	public $columnNames = array();
    /**
     * @var int The column number the country information is held in
     */
    public $countryColumnNumber = 1;
    /**
     * @var CUploadedFile Holds the CUploadedFile that has to be validated
     */
    private $_file;

	/**
	 * Main entry point for validation. This method is called via the model's rules method when using
	 * the "csv" validator
	 */
    public function validateAttribute($object, $attribute)
    {
        //decrement the columnCount by 1 as we are starting with 0
        $this->columnCount--;

        $file = $object->$attribute;
        if(!$file instanceof CUploadedFile) {
            $file = CUploadedFile::getInstance($object, $attribute);
            if(null===$file)
                return $this->emptyAttribute($object, $attribute);
        }

        $this->validateCSVHeaders($object, $attribute, $file);
    	$this->validateCSVRows($object, $attribute, $file);
    }

    /**
     * Validates the headers of a csv file
     */
    protected function validateCSVHeaders($object, $attribute, $file)
    {
        $file = fopen($file->getTempName(), "r");
        $headerColumns = $this->explodeToColumns(fgets($file));
        if($headerColumns == array()) {
            $this->addError($object,$attribute,'Could not read header line. File empty? Make sure it uses: '.implode($this->separator,$this->columnNames));
        }
        if($headerColumns != $this->columnNames) {
            $this->addError($object,$attribute,'Wrong headers. Make sure the file uses: '.implode($this->separator,$this->columnNames));
        }
        fclose($file);
    }

    /**
     * Validates every row in a csv
     */
    protected function validateCSVRows($object, $attribute, $file)
    {
        //decrement the countryColumnNumber by 1 as we are starting with 0
        $this->countryColumnNumber--;

        //put the country synonyms in an array (for each synonym one entry)
        $countrySynonyms = Yii::app()->params['countrySynonyms'];
        $allowedCountrySynonyms = array();
        foreach($countrySynonyms as $countrySynonymsRow) {
            foreach(explode(',',$countrySynonymsRow) as $synonym) {
                $allowedCountrySynonyms[] = $synonym;
            }
        }

        //ACTUAL VALIDATION STARTS HERE

        $file = fopen($file->getTempName(), "r");
        $rowNumber = 1;

        while (!feof($file)) {
            $line = fgets($file);
            if($rowNumber == 1) {
                 //jump through the first line (the header)
                $rowNumber++;
                continue;
            } else {
                //right number of columns defined?
                $separatorCount = substr_count($line,$this->separator);
                if($separatorCount != $this->columnCount) {
                    $this->addError($object, $attribute, 'Wrong separator count in row '.$rowNumber.'. '.$separatorCount.' "'.$this->separator.'" found. Expected: '.$this->columnCount.'!');
                    return false; //jump out of here as the rest would not work anyway
                }
                //check wheter the country column is a valid value (one of the synonyms used by the system)
                $line = $this->explodeToColumns($line);
                $countryValue = trim(strtolower($line[$this->countryColumnNumber]));
                if($countryValue != '' && !in_array($countryValue,$allowedCountrySynonyms)) {
                    $this->addError($object, $attribute, 'Unknown country value "'.$countryValue.'" in row '.$rowNumber.'!');
                }
            }
            $rowNumber++;
        }
        fclose($file);
    }

    /**
     * Explodes a row to columns and returns the resulting array
     *
     * @param string $row The row that has to be exploded
     * @return array The columns and the values of a single row as associative array
     */
    protected function explodeToColumns($row)
    {
    	$columns = explode($this->separator,$row);
        foreach($columns as $column => $value) {
            $columns[$column] = trim($value);
        }
        return $columns;
    }

    /**
     * Raises an error to inform end user about blank attribute.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function emptyAttribute($object, $attribute)
    {
        if(!$this->allowEmpty) {
            $message=$this->message!==null ? $this->message : Yii::t('yii','{attribute} cannot be blank.');
            $this->addError($object,$attribute,$message);
        }
    }
}
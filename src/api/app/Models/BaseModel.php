<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{


    //default model values
    public $defaults = [];

    //load all relations
    public $relations_data = [];
    public $return_all_relations_data = false;

    //validation
    protected $validation_rules = [];
    protected $errors;

    // Disables the log record in this model.
    //protected $auditEnabled  = true;

    // Disables the log record after 500 records.
    //protected $historyLimit = 500;

    // Fields you do NOT want to register.
    //protected $dontKeepLogOf = ['created_at', 'updated_at'];

    // Tell what actions you want to audit.
    //protected $auditableTypes = ['created', 'saved', 'deleted'];

    public function getItemLabel() {
        return 'Item Label';
    }

    public function __construct(array $attributes = array())
    {
        $this->setRawAttributes($this->defaults, true);
        parent::__construct($attributes);
    }

    public function isLoadData($relation)
    {
        return $this->return_all_relations_data || in_array($relation, $this->relations_data);
    }

    public function getItemData($return_all_relations_data = false)
    {
        if ($return_all_relations_data) {
            $this->return_all_relations_data = true;
        }
        return [];
    }

    public function validate($data)
    {
        // make a new validator object
        $validator = Validator::make($data, $this->validation_rules);
        // check for failure
        if ($validator->fails())
        {
            // set errors and return false
            $this->errors = $validator->errors()->all(['field' => ':key', 'message' => ':message']);
            return false;
        }
        // validation pass
        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getValidationRules()
    {
        return $this->validation_rules;
    }
}

<?php

namespace Iris;

class FieldRequiredException extends \Exception {
    public function __construct($key) {
        $this->key = $key;
        parent::__construct("Field {$this->key} is required.");
    }
}

class FieldValidateInArrayException extends \Exception {
    public function __construct($key, $arr) {
        $this->key = $key;
        $this->arr = $arr;
        parent::__construct("Field {$this->key} should have one of the following values: ".implode(", ", $this->arr));
    }
}

class FieldNotExistsException extends \Exception {
    public function __construct($key) {
        $this->key = $key;
        parent::__construct("Field {$this->key} not exists");
    }
}

trait BaseModel {

    // private $dataset = array();
    //
    // public function __get($key) {
    //     if(!array_key_exists($key, $this->fields))
    //         return $this->{$key};
    //     if(isset($this->dataset[$key]))
    //         return $this->dataset[$key];
    //     else
    //         return null;
    // }
    //
    // public function __set($key, $value) {
    //     if(!array_key_exists($key, $this->fields)) {
    //         $this->{$key} = $value;
    //     } else if($this->fields[$key]["type"] == "string") {
    //         $this->dataset[$key] = $value;
    //     } else if(!isset($this->dataset[$key])) {
    //         $this->dataset[$key] = $value;
    //     } else {
    //         $this->dataset[$key]->set_data($value);
    //     }
    // }

    public function set_data($data) {
        if(!is_array($data)) {
            return;
        }

        foreach($data as $key => $value) {
            if(!array_key_exists($key, $this->fields))
                continue;

            $classname = $this->fields[$key]["type"];

            if($classname === "string") {
                $this->{$key} = $value;
            } else {
                if(is_array($value) && $this->is_assoc($value)) { // if assoc array => to object
                    if(!property_exists($this, $key)) // if not exists create new class
                        $this->{$key} = new $classname($value);
                    else if(is_callable(array($this->{$key}, 'set_data'))) // or update existing class
                        $this->{$key}->set_data($value);
                } else if(is_array($value)) { // if array[] => array of objects
                    if(!property_exists($this, $key)) // create an array if not exists
                        $this->{$key} = [];

                    $field = & $this->{$key};
                    $count = count($field);

                    for($i = 0; $i < count($value); $i++) {
                        $item = $value[$i];

                        if($i < $count) {
                            $field[$i]->set_data($item); // update existing objects
                        } else {
                            $field[] = new $classname($item); // create new objects if not exists
                        }
                    }

                    if($count > count($value)) {
                        for($i = count($value); $i < $count; $i++) {
                            unset($field[$i]);
                        }
                    }
                }
            }
        }
    }
    protected function is_assoc($array) {
        $array = array_keys($array); return ($array !== array_keys($array));
    }

    public function validate($key, $value, $validate) {
        switch($validate['type']) {
            case "in_array":
                if(!in_array($value, $validate['value']))
                    throw new FieldValidateInArrayException($key, $validate['value']);
                break;
        }
    }

    public function to_array() {
        $out = array();

        foreach($this->fields as $key => $rules) {
            if(isset($this->{$key})) {
                $value = $this->{$key};

                if($rules['type'] === "string")
                    $out[$key] = $value;
                else {
                    if(is_array($value)) {
                        $out[$key] = [];
                        foreach($value as $item) {
                            $out[$key][] = $item->to_array();
                        }
                    } else {
                        $out[$key] = $value->to_array();
                    }
                }
            }
            else if(isset($rules['required']) && $rules['required'] === true) {
                throw new FieldRequiredException($key);
            }

            if(isset($rules['validate'])) {
                $this->validate($key, $value, $rules['validate']);
            }
        }

        return $out;
    }
}

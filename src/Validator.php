<?php 

namespace MGForm;

use MGFormValidation\OriginalValidator;

class Validator extends OriginalValidator {

    public function __construct(string|array $data = self::METHOD_POST)
    {
        parent::__construct($data);
    }

    public function validate(array $data) {        
        $this->setRules($data)->apply();

        $result = [
            "type"=>$this->getType(),
        ];

        
        switch ($this->getType()) {
            case 'error':
                $result["data"] = $this->getErrors();
                break;
                
            case 'success':
                $result["data"] = $this->getData();
                break;
            
            default:
                break;
        }

        return $result;
    }

}
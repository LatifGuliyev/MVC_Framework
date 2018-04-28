<?php

class FormValidation{
    private $rules;
    private $errorEachRule = false;
    
    public function setRule($fieldName, $rule, $errorMessage=''){
        $this->rules[$fieldName]['rules'] = explode("|", $rule);
        $this->rules[$fieldName]['errors'] = explode("|", $errorMessage);
        $error_message_size = count($this->rules[$fieldName]['errors']);
        $rule_size = count($this->rules[$fieldName]['rules']);
        if($error_message_size == 1){
            $this->errorEachRule=false;
        }
        elseif($error_message_size == $rule_size){
            $this->errorEachRule=true;
        }
        else{
            throw new Exception("Error message count must be 1 or equal to number of rules");
        }
    }
    
    public function validateForm(){
        foreach($this->rules as $key=>$value){
            //echo $key."<br>";
            $rule_count = count($value['rules']);
            for($i = 0; $i < $rule_count; $i++){
                switch(trim($value['rules'][$i])){
                    case "required":
                        if(isset($_POST[$key]) && !empty($_POST[$key]) && $_POST[$key] != null){
                            continue;
                        }
                        else{
                            return self::sendError($key, $i);
                        }
                    default:
                        echo $value['rules'][$i];
                        return "Undefined error!";
                }
            }
        }
        return true;
    }
       
    private function sendError($key, $i){
        if($this->errorEachRule){
            return $this->rules[$key]['errors'][$i];
        }
        else{
            return $this->rules[$key]['errors'][0];
        }
    }
}

?>
<?php

class SharedDataModel extends DB{
    function __construct(){
        parent::__construct();
    }
    
    
    function demo_data(){
        $data = array('item1', 'item2', 'item3', 'item4');
        return $data;
    }
}

?>
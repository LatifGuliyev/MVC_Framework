<?php

class IndexController extends View{
    private $dbDemo;
    
    function __construct(){
         $this->dbDemo= new SharedDataModel();
    }
    
	function index(){
        $data['items'] = $this->dbDemo->demo_data();
		parent::viewDoc(array('visitor/home'), $data);
	}
}

?>
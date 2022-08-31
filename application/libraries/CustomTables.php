<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class CustomTables {
    function __construct() {
        $CI =& get_instance();
        $this->CI =$CI;
    }
    public function select_fields($db_fields){
        foreach($db_fields as $k => $field){
            if($k == 0){
                $db_fields_string = $field;
            } else{
                $db_fields_string .= ', '.$field; 
            }
            
        }
        return $db_fields_string;
    }
    
}
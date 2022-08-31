<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registry_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
        $this->load->database();
        //$this->load->library('table');
        //$this->load->helper('form');

    }
    /*
    METODO:
    generazioni campi per il form
    VARIABILI METODO:
    
    */
    public function registry_form_fields(){

        //l'array è così strutturato:
        /*
        $formfields = array(
            'Nome_campo_in_db => array contenente tutti i parametri del campo del form così strutturato:       
                array(
                    'parametro' => 'valore/nome parametro'
                )        
        )
        Le modifiche si possono fare da qui se sono abbastanza permanenti oppure si possono fare falla view richiamando 
        $formfields['Nome_campo_in_db']['parametro'] = valore che si desidera
        */
        $formfields = array(
            'CL_name' => array(
                'id' => 'nome',
                'name' => 'nome',
                'label' => 'Nome',
                'f_type' => 'input',
                'class' => 'form-control',
            ),
            'CL_surname' => array(
                'id' => 'surname',
                'name' => 'surname',
                'label' => 'Cognome',
                'f_type' => 'input',
                'class' => 'form-control',
            ),
            'CL_mobile' => array(
                'id' => 'mobile',
                'name' => 'mobile',
                'label' => 'Cellulare',
                'f_type' => 'input',
                'class' => 'form-control',
            ),
            'CL_phone' => array(
                'id' => 'phone',
                'name' => 'phone',
                'label' => 'Telefono',
                'f_type' => 'input',
                'class' => 'form-control',
            ),
            'CL_email' => array(
                'id' => 'email',
                'name' => 'email',
                'label' => 'Email',
                'f_type' => 'input',
                'class' => 'form-control',
            ),
            'CL_address' => array(
                'id' => 'address',
                'name' => 'address',
                'label' => 'Indirizzo',
                'f_type' => 'input',
                'class' => 'form-control',
            ),
            'CL_city' => array(
                'id' => 'city',
                'name' => 'city',
                'label' => 'Città',
                'f_type' => 'input',
                'class' => 'form-control',
            ),
            'CL_province' => array(
                'id' => 'province',
                'name' => 'province',
                'label' => 'Provincia',
                'f_type' => 'input',
                'class' => 'form-control',
            ),
            'CL_country' => array(
                'id' => 'country',
                'name' => 'country',
                'label' => 'Paese',
                'f_type' => 'input',
                'class' => 'form-control',
            )
        );
    
        // ritorna l'array
        return ($formfields);
    }
     /*
    METODO:
    salvataggio nuovo record in db
    VARIABILI METODO:
     $data = dati in $_POST
    */
    public function registry_insert($data){

        //pesco l'array dei campi del form dal prec metodo x avere corrispondenza tra nomi e campi
        $form_fields = $this->registry_form_fields();

        // CICLO X METTERE IN ARRAY CAMPI E VALORi
        $data_to_insert = array();
        foreach($form_fields as $db_field => $par_list){
            $name =$par_list['name'];
            if(array_key_exists($name, $data)){          //se c'è corrispondenza tra nome in formfields e nomi dei campi in POST memorizzo in array
                $data_to_insert[$db_field] = $data[$name];
            }
        }

        // inserimento dati in database
        $this->db->insert('clients', $data_to_insert);
    }

     /*
    METODO:
    modifica dati, update in db
    VARIABILI METODO:
        $cl_id = ID cliente preso in GET e passato dal controller al model
    */
    public function registry_form_fields_edit($cl_id){
        $formfields = $this->registry_form_fields();

        //query al db per pescare dati
        $result = $this->db->select('*')->from('clients')->where('CL_ID', $cl_id)->get()->result();
        $result = json_decode(json_encode($result), true); // conversione da object ad array
        
        // verifico l'esistenza dei campi di formfields in db e pesco i loro valori
        foreach($formfields as $db_field => $par_list){
            if(array_key_exists($db_field, $result[0])){    
                $formfields[$db_field]['value'] = $result[0][$db_field];
            }
        }
        // ritorno l'array aggiornato con valori del db da passare alla view
        return($formfields);
    }
    public function registry_update($data){
        //pesco l'array dei campi del form dal prec metodo x avere corrispondenza tra nomi e campi
        $form_fields = $this->registry_form_fields();

        // CICLO X METTERE IN ARRAY CAMPI E VALORI CORRISPONDENTI
        $data_to_update = array();
        foreach($form_fields as $db_field => $par_list){
            $name =$par_list['name'];
            if(array_key_exists($name, $data)){          //se c'è corrispondenza tra nome in formfields e nomi dei campi in POST memorizzo in array
                $data_to_update[$db_field] = $data[$name];
            }
        }
        //pesco l'id del cliente da $data xk in $data_to_update non l'ho salvato visto che non lo devo aggiornare quello
        $cl_id = $data['CL_ID'];

        // inserimento dati in database
        $this->db->where('CL_ID', $cl_id)->update('clients', $data_to_update);
    }
    public function registry_delete($cl_id){
        // array per corrispondenza campo - valore
        $where_array = array(
            'CL_ID' => $cl_id
        );
        // valori da modificare, cl_active, x disattivare
        $data_to_update = array(
            'CL_active' => 0
        );
        //query eliminazione riga
        $this->db->where($where_array);
        $this->db->update('clients', $data_to_update);
    }
}



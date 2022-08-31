<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dates_model extends CI_Model {
    
  public function __construct(){
    parent::__construct();
    $this->load->database();
   
  }
  /*
  VARIABILI METODO:
  $dbfield = array('campo1', 'campo2', ...) lista campi del db da pescare, serve x la select della query
  $labels = array(
    'Nome_campo_nel_db' => 'Titolo in tabella',             se presente in db
    'Nome_campo_a_propria_scelta' => 'Titolo in tabella'    se assente in db (colonna extra)
  ) 
  $extracol = array(
    'Nome_col_extra_uguale_a_labels' => 'contenuto fisso'
  )
  $cl_id = id del cliente ricavato in GET
  */
  public function table_view($dbfield, $labels, $extracol, $cl_id){    

    $this->load->library('table');
   
    $this->load->library('CustomTables');     // funzioni varie x elaborazione tabelle



    // FIELDS DAL DB
    //ricavo i campi x la select dall'array $dbfields
    $db_fields = $this->customtables->select_fields($dbfield);

    //aggiungo l'ID x avere un riferimento
    $db_fields .= ', CL_ID, DAT_ID';
    
    // query alla tabella
    $result = $this->db->select($db_fields)->from('clients_alldates')->where('CL_ID', $cl_id)->get()->result();   // risultato
    $result = json_decode(json_encode($result), true); // conversione da object ad array
    if(empty($result)){
      $tot['no_dates'] = 'No dates'; 
      return($tot);
    }

    // HEADER TABELLA
    // ricavo l'array header x i titoli della tabella dal primo record cioè key == 0
    foreach($result as $numb => $value){
      if($numb == 0){
        foreach($value as $k => $v){
          if(array_key_exists($k, $labels)){  // verifico l'esistenza dei campi in tabella e li memorizzo nell'array header
            $header[$k] = $labels[$k];
          }
        }
      }
    }
    //COLONNE EXTRA
    if(isset($extracol) && $extracol !=='' && !empty($extracol)){
      foreach($result as $numb => $value){
        foreach($extracol as $nome => $titolo){
          $header[$nome] = $labels[$nome];
          $result[$numb][$nome] = (isset($extracol[$nome]))? $extracol[$nome] : '';
        }
      }
    }
    

    //MEMORIZZO ID   X OR disabilito
    foreach($result as $numb => $value){
      $actions = $value['actions'];
      $new_actions = str_replace("dates_single_view", "dates_single_view?cl=".$value['CL_ID']."&dat=".$value['DAT_ID'], $actions);
      $new_actions = str_replace("dates_edit_form", "dates_edit_form?cl=".$value['CL_ID']."&dat=".$value['DAT_ID'], $new_actions);
      $new_actions = str_replace("dates_delete_confirm", "dates_delete_confirm?cl=".$value['CL_ID']."&dat=".$value['DAT_ID'], $new_actions);
      $result[$numb]['actions'] = $new_actions;
      unset($result[$numb]['CL_ID']);
      unset($result[$numb]['DAT_ID']);
    }

    
    //RIORDINO HEADER E RESULT SECONDO L'ARRAY LABELS
    
    arrays_sort($labels, $header); 
    
    foreach($result as $numb => $value){
      arrays_sort($labels, $value);
      $result[$numb] = $value;
    }


    
    // FILTRO STATUS IN BASE ALLA DATA
    foreach($result as $numb => $value){
      $status = $value['status'];                     // campo status
      $data = $value['DAT_date'];                     // campo data appuntamento
      $datetime1 = date_create(date('Y/m/d'));        // data odierna
      $datetime2 = date_create($data);                // data dell'appuntamento
      $interval = date_diff($datetime1, $datetime2);  // differenza
      $diff = $interval->format('%R%a');              // formato -n o +n
      // setto le caratteristiche dello status rosso
      $next_date = str_replace('<span', '<span style = "color: Tomato"', $status);  //style span
      $next_date = str_replace('status', 'next status', $next_date);                //class
      $next_date = str_replace('<i', '<i alt = "a"', $next_date);                   //attributo alt sull'icona x poterle ordinare in tabella
      // setto le caratteristiche dello status verde
      $other_date = str_replace('<span', '<span style = "color: green"', $status);
      $other_date = str_replace('status', 'other status', $other_date);
      $other_date = str_replace('<i', '<i alt = "b"', $other_date);
      
      $result[$numb]['status'] = ($diff < 0 || $diff > 5)? $other_date: $next_date;   // se prossimi 5 gg allora rosso, sennò verde
      
    }

    // ciclo per mettere span
    foreach($result as $numb => $value){
      foreach($value as $field => $v){
        if(array_key_exists($field, $labels)){  // verifico l'esistenza dei campi in tabella e li memorizzo nell'array header
          $v = '<span class = "mobile-column-name">'.$labels[$field].': </span>'.$v;
          $result[$numb][$field] = $v;
        }
        
      }
    }

    //RACCOLTA DATI
    $tot = array(
      'result' => $result,  //corpo tabella
      'header' => $header,  //titoli tabella
    );
    return $tot;
    
  }
  /*
  METODO:
  generazione array formfields per avere campi del form
  VARIABILI:
  $cl_id = ID cliente, viene passato dal controller se sono in add new o dal metodo di modifica di questo model, non è una variabile
  obbligatoria perchè questo metodo torna utile anche in fase di salvataggio e mi servono solo i nomi dei campi, non gli passo l'id
  */
  public function dates_form_fields($cl_id = ''){
    
    // faccio query per anagrafica cliente e poi metto i valori nell'array formfields x i campi che mi interessa mostrare
    if(isset($cl_id) & $cl_id !==''):
      $result = $this->db->select('*')->from('clients')->where('CL_ID', $cl_id)->get()->result();
      $result = json_decode(json_encode($result), true); // conversione da object ad array
      $cliente = $result[0];
    endif;

   
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
            'disabled' => true,
            
        ),
        'CL_surname' => array(
            'id' => 'surname',
            'name' => 'surname',
            'label' => 'Cognome',
            'f_type' => 'input',
            'class' => 'form-control',
            'disabled' => true,
            
        ),
        'CL_mobile' => array(
            'id' => 'mobile',
            'name' => 'mobile',
            'label' => 'Cellulare',
            'f_type' => 'input',
            'class' => 'form-control',
            'disabled' => true,
            
        ),
        'CL_city' => array(
            'id' => 'city',
            'name' => 'city',
            'label' => 'Città',
            'f_type' => 'input',
            'class' => 'form-control',
            'disabled' => true,
            
        ),
        'DAT_title' => array(
            'id' => 'dat_title',
            'name' => 'dat_title',
            'label' => 'Titolo',
            'f_type' => 'input',
            'class' => 'form-control',
        ),
        'DAT_description' => array(
            'id' => 'dat_description',
            'name' => 'dat_description',
            'label' => 'Descrizione',
            'f_type' => 'textarea',
            'class' => 'form-control',
        ),
        'DAT_date' => array(
          'id' => 'dat_date',
          'name' => 'dat_date',
          'label' => 'Data',
          'placeholder' => 'dd/mm/yyyy',
          'f_type' => 'input',
          'type' => 'date',
          'class' => 'form-control',
        ),
        'DAT_time' => array(
          'id' => 'dat_time',
          'name' => 'dat_time',
          'label' => 'Ora',
          'placeholder' => 'hh:mm',
          'f_type' => 'input',
          'type' => 'time',
          'class' => 'form-control',
        )
    );

    // CICLO PER ATTRIBUIRE VALORI ALL'ARRAY
    if(isset($cl_id) & $cl_id !==''):
      foreach($formfields as $field => $par_list){
        if(array_key_exists($field, $cliente)){
          $formfields[$field]['value'] = $cliente[$field];
        }
      }
    endif;
    
    // ritorna l'array
    return ($formfields);
  }

    /*
    METODO:
    generazione campi form relativi a scadenza colllegata all'appuntamento
    VARIABILI:
    Per la struttura rifarsi al formfields qui sopra
    */
  public function deadline_rel_date_formfields(){
    $dl_formfields = array(
      'DL_title' => array(
        'id' => 'dl_title',
        'name' => 'dl_title',
        'label' => 'Titolo',
        'f_type' => 'input',
        'class' => 'form-control',
        'disabled' => true
      ),
    'DL_description' => array(
        'id' => 'dl_description',
        'name' => 'dl_description',
        'label' => 'Descrizione',
        'f_type' => 'textarea',
        'class' => 'form-control',
        'disabled' => true
      ),
    'DL_date' => array(
        'id' => 'dl_date',
        'name' => 'dl_date',
        'label' => 'Data',
        'placeholder' => 'dd/mm/yyyy',
        'f_type' => 'input',
        'type' => 'date',
        'class' => 'form-control',
        'disabled' => true
      ),
    );
    return $dl_formfields;
  }
  /*
  METODO:
  campi con scadenza correlata
  VARIABILI:
  $dl_id = ID della scadenza
  */
  public function dates_dl_formfields($cl_id, $dl_id){
    $formfields = $this->dates_form_fields($cl_id);         // pesco i campi che riguardano anagrafica e appuntamenti
    $dl_formfields = $this->deadline_rel_date_formfields(); // pesco i campi della scadenza correlata
    $result = $this->db->select('*')->from('deadlines')->where('DL_ID', $dl_id)->get()->result();  // query per dati scadenza
    $result = json_decode(json_encode($result), true); // conversione da object ad array

    // attribuzione valori ai campi
    foreach($dl_formfields as $db_field => $par_list){
      if(array_key_exists($db_field, $result[0])){    
          $dl_formfields[$db_field]['value'] = $result[0][$db_field];
      }
    }
    $formfields = array_merge($dl_formfields, $formfields);

    // ritorno l'array dei campi
    return($formfields);
  }
    /*
    METODO:
    salvataggio nuovo record in db
    VARIABILI METODO:
     $data = dati in $_POST
    */
  public function dates_insert($data){

      //pesco l'array dei campi del form dal prec metodo x avere corrispondenza tra nomi e campi
      $form_fields = $this->dates_form_fields();

      // CICLO X METTERE IN ARRAY CAMPI E VALORI
      $data_to_insert = array();
      foreach($form_fields as $db_field => $par_list){
        $name =$par_list['name'];
        if(array_key_exists($name, $data)){          //se c'è corrispondenza tra nome in formfields e nomi dei campi in POST memorizzo in array
          $data_to_insert[$db_field] = $data[$name];
        }
      }

      // AGGIUNGO A MANO L'ID CLIENTE ED EVENTUALE SCADENZA CORRELATA (non sono presente nell'array formfields perchè presi da GET e messi nella view)
      $data_to_insert['DAT_CL_ID'] = $data['CL_ID'];
      $data_to_insert['DAT_DL_ID'] = $data['DAT_DL_ID'];

      // inserimento dati in database
      $this->db->insert('dates', $data_to_insert);

      if(isset($data['DAT_DL_ID']) && $data['DAT_DL_ID']!== '' && !empty($data['DAT_DL_ID']))
      // ricavo id dell'appuntamento appena inserito
      $lastid = $this->db->insert_id();
      
      $dl_id = $data['DAT_DL_ID'];
      $data_to_update['DL_DAT_ID'] = $lastid;
      $this->db->where('DL_ID', $dl_id)->update('deadlines', $data_to_update);

  }

 /*
    METODO:
    modifica dati, update in db
    VARIABILI METODO:
        $cl_id = ID cliente preso in GET e passato dal controller al model
    */
  public function dates_form_fields_edit($cl_id, $dat_id){
    $formfields = $this->dates_form_fields($cl_id);

    //query al db per pescare dati dell'appuntamento
    $result = $this->db->select('*')->from('dates')->where('DAT_ID', $dat_id)->get()->result();
    $result = json_decode(json_encode($result), true); // conversione da object ad array
    
    // verifico l'esistenza dei campi di formfields in db e pesco i lo valori
    foreach($formfields as $db_field => $par_list){
        if(array_key_exists($db_field, $result[0])){    
            $formfields[$db_field]['value'] = $result[0][$db_field];
        }
    }
   
    
    // AGGIUNTA CAMPI CARATTERISTICI PER SCADENZA, SE C'È
    if($result[0]['DAT_DL_ID']!=='' && !empty($result[0]['DAT_DL_ID'])):
      $dl_formfields = $this->deadline_rel_date_formfields();         // metodo per pescare campi scadenza
      $dl_id = $result[0]['DAT_DL_ID'];
      //query al db per pescare dati dell'appuntamento
      $result = $this->db->select('*')->from('deadlines')->where('DL_ID', $dl_id)->get()->result();
      $result = json_decode(json_encode($result), true); // conversione da object ad array

       // verifico l'esistenza dei campi di formfields in db e pesco i lo valori
      foreach($dl_formfields as $db_field => $par_list){
        if(array_key_exists($db_field, $result[0])){    
            $dl_formfields[$db_field]['value'] = $result[0][$db_field];
        }
      }

      // unione 2 array
      $formfields = array_merge($dl_formfields, $formfields);
    endif;
    // ritorno l'array aggiornato con valori del db
    return($formfields);
  }

  /*
  METODO:
  modifica dati in db relativi agli appuntamenti (tabella dates)
  VARIABILI:
  $data = $_POST passato da controller
  */
  public function dates_update($data){
    //pesco l'array dei campi del form dal prec metodo x avere corrispondenza tra nomi e campi
    $form_fields = $this->dates_form_fields();

    // CICLO X METTERE IN ARRAY CAMPI E VALORI CORRISPONDENTI
    $data_to_update = array();
    foreach($form_fields as $db_field => $par_list){
        $name =$par_list['name'];
        if(array_key_exists($name, $data)){          //se c'è corrispondenza tra nome in formfields e nomi dei campi in POST memorizzo in array
            $data_to_update[$db_field] = $data[$name];
        }
    }
    //pesco l'id dell'appuntamento da $data xk in $data_to_update non l'ho salvato visto che non lo devo aggiornare quello
    $dat_id = $data['DAT_ID'];

    // inserimento dati in database
    $this->db->where('DAT_ID', $dat_id)->update('dates', $data_to_update);
  }

  /*
  METODO:
  cancellazione record da db
  VARIABILI:
  $dat_id = Id dell'aapuntamento corrispondente in db al campo 'DAT_ID'
  */
  
  public function dates_delete($dat_id){
    // seleziono eventuale appuntamento correlato
    $result = $this->db->select('*')->where('DAT_ID', $dat_id)->from('dates')->get()->result();
    $result = json_decode(json_encode($result), true); // conversione da object ad array

    $dl_id = $result[0]['DAT_DL_ID'];
    if($dl_id !== ''){
      $data_to_update = array(
        'DL_DAT_ID' => NULL,
      );
      $this->db->where('DL_ID', $dl_id)->update('deadlines', $data_to_update);
    }

    $this->db->where('DAT_ID', $dat_id)->delete('dates');
  }


  
}



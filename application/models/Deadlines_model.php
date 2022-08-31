<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deadlines_model extends CI_Model {
    
  public function __construct(){
    parent::__construct();
    $this->load->database();
   
  }

  /*
  METODO:
  tabella scadenze con filtro per cliente
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
    $db_fields .= ', CL_ID, DL_ID';
    
    // query alla tabella
    $result = $this->db->select($db_fields)->from('clients_alldeadlines')->where('CL_ID', $cl_id)->get()->result();   // risultato
    $result = json_decode(json_encode($result), true); // conversione da object ad array
    if(empty($result)){
      $tot['no_deadlines'] = 'No deadlines'; 
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

    
    

    //MEMORIZZO ID   
    foreach($result as $numb => $value){
      $actions = $value['actions'];
      $new_actions = str_replace("deadlines_single_view", "deadlines_single_view?cl=".$value['CL_ID']."&dl=".$value['DL_ID'], $actions);
      $new_actions = str_replace("deadlines_edit_form", "deadlines_edit_form?cl=".$value['CL_ID']."&dl=".$value['DL_ID'], $new_actions);
      $new_actions = str_replace("deadlines_delete_confirm", "deadlines_delete_confirm?cl=".$value['CL_ID']."&dl=".$value['DL_ID'], $new_actions);
      $result[$numb]['actions'] = $new_actions;
      unset($result[$numb]['CL_ID']);
      unset($result[$numb]['DL_ID']);
    }

     foreach($result as $numb => $value){
      if($numb == 0){
        foreach($value as $k => $v){
          if(array_key_exists($k, $labels)){  // verifico l'esistenza dei campi in tabella e li memorizzo nell'array header
            $header[$k] = $labels[$k];
          }
        }
      }
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
      $data = $value['DL_date'];                     // campo data appuntamento
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

    // ciclo per resettare formato data
    foreach($result as $numb => $value){
      foreach($value as $field => $v){
        if($field == 'DL_date'){  // verifico l'esistenza dei campi in tabella e li memorizzo nell'array header         
          $date = new DateTime($v);
          $newDateString = $date->format('d-m-Y');        
          $result[$numb][$field] = $newDateString;
        }
        
      }
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
  genera i campi da inserire in form di add ed edit deadlines
  VARIABILI:
  $cl_id = ID cliente da passare obbligatoriamente nei metodi relativi al form, non serve per il salvataggio perchè mi interessano solo i nomi dei campi
  */
  public function deadlines_form_fields($cl_id = ''){
    
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
        'CL_address' => array(
          'id' => 'address',
          'name' => 'address',
          'label' => 'Indirizzo',
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
        'DL_title' => array(
            'id' => 'dl_title',
            'name' => 'dl_title',
            'label' => 'Titolo',
            'f_type' => 'input',
            'class' => 'form-control',
        ),
        'DL_description' => array(
            'id' => 'dl_description',
            'name' => 'dl_description',
            'label' => 'Descrizione',
            'f_type' => 'textarea',
            'class' => 'form-control',
        ),
        'DL_date' => array(
          'id' => 'dl_date',
          'name' => 'dl_date',
          'label' => 'Data',
          'placeholder' => 'dd/mm/yyyy',
          'f_type' => 'input',
          'type' => 'date',
          'class' => 'form-control',
        ),
        'DL_time' => array(
          'id' => 'dl_time',
          'name' => 'dl_time',
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
  pescare campi dell'appuntamento correlato
  */
  public function deadline_rel_date_formfields(){
    $dat_formfields = array(
      'DAT_title' => array(
        'id' => 'dat_title',
        'name' => 'dat_title',
        'label' => 'Titolo',
        'f_type' => 'input',
        'class' => 'form-control',
        'disabled' => true
      ),
    'DAT_description' => array(
        'id' => 'dat_description',
        'name' => 'dat_description',
        'label' => 'Descrizione',
        'f_type' => 'textarea',
        'class' => 'form-control',
        'disabled' => true
      ),
    'DAT_date' => array(
        'id' => 'dat_date',
        'name' => 'dat_date',
        'label' => 'Data',
        'placeholder' => 'dd/mm/yyyy',
        'f_type' => 'input',
        'type' => 'date',
        'class' => 'form-control',
        'disabled' => true
      ),
    'DAT_time' => array(
        'id' => 'dat_time',
        'name' => 'dat_time',
        'label' => 'Ora',
        'placeholder' => 'hh:mm',
        'f_type' => 'input',
        'type' => 'time',
        'class' => 'form-control',
        'disabled' => true
      ),
    );
    return $dat_formfields;
  }

    /*
    METODO:
    salvataggio nuovo record in db
    VARIABILI METODO:
     $data = dati in $_POST
    */
    public function deadlines_insert($data){

      //pesco l'array dei campi del form dal prec metodo x avere corrispondenza tra nomi e campi
      $form_fields = $this->deadlines_form_fields();

      // CICLO X METTERE IN ARRAY CAMPI E VALORi
      $data_to_insert = array();
      foreach($form_fields as $db_field => $par_list){
          $name =$par_list['name'];
          if(array_key_exists($name, $data)){          //se c'è corrispondenza tra nome in formfields e nomi dei campi in POST memorizzo in array
            
            // conversione formato data
            if($db_field == 'DL_date'){
              $date = new DateTime($data[$name]);
              $data[$name] = $date->format('d-m-Y');      
            }
            $data_to_insert[$db_field] = $data[$name];
          }
      }

      // AGGIUNGO A MANO L'ID CLIENTE (non è presente nell'array formfields perchè preso da GET e messo nella view)
      $data_to_insert['DL_CL_ID'] = $data['CL_ID'];

      // inserimento dati in database
      $this->db->insert('deadlines', $data_to_insert);
      $last_id = $this->db->insert_id();
      return($last_id);
  }

 /*
  METODO:
  modifica dati, update in db
  VARIABILI METODO:
  $cl_id = ID cliente preso in GET e passato dal controller al model
  */
  public function deadlines_form_fields_edit($cl_id, $dl_id){
    $formfields = $this->deadlines_form_fields($cl_id);

    //query al db per pescare dati
    $result = $this->db->select('*')->from('deadlines')->where('DL_ID', $dl_id)->get()->result();
    $result = json_decode(json_encode($result), true); // conversione da object ad array
    
    // verifico l'esistenza dei campi di formfields in db e pesco i lo valori
    foreach($formfields as $db_field => $par_list){
        if(array_key_exists($db_field, $result[0])){ 

          // conversione formato data
          if($db_field == 'DL_date'){
            $date= new DateTime($result[0][$db_field]);
            $result[0][$db_field] = $date->format('d-m-Y');      
          }  
          $formfields[$db_field]['value'] = $result[0][$db_field];
        }
    }

    // AGGIUNTA CAMPI CARATTERISTICI PER SCADENZA, SE C'È
    if($result[0]['DL_DAT_ID']!=='' && !empty($result[0]['DL_DAT_ID'])):
      $dat_formfields = $this->deadline_rel_date_formfields();         // metodo per pescare campi scadenza
      $dat_id = $result[0]['DL_DAT_ID'];
      //query al db per pescare dati dell'appuntamento
      $result = $this->db->select('*')->from('dates')->where('DAT_ID', $dat_id)->get()->result();
      $result = json_decode(json_encode($result), true); // conversione da object ad array

       // verifico l'esistenza dei campi di formfields in db e pesco i lo valori
      foreach($dat_formfields as $db_field => $par_list){
        if(array_key_exists($db_field, $result[0])){    
            $dat_formfields[$db_field]['value'] = $result[0][$db_field];
        }
      }

      // unione 2 array
      $formfields = array_merge($dat_formfields, $formfields);
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
  public function deadlines_update($data){
    //pesco l'array dei campi del form dal prec metodo x avere corrispondenza tra nomi e campi
    $form_fields = $this->deadlines_form_fields();

    // CICLO X METTERE IN ARRAY CAMPI E VALORI CORRISPONDENTI
    $data_to_update = array();
    foreach($form_fields as $db_field => $par_list){
        $name =$par_list['name'];
        if(array_key_exists($name, $data)){          //se c'è corrispondenza tra nome in formfields e nomi dei campi in POST memorizzo in array
          // conversione formato data
          if($db_field == 'DL_date'){
            $date= new DateTime($data[$name]);
            $data[$name] = $date->format('d-m-Y');      
          }    
          $data_to_update[$db_field] = $data[$name];
        }
    }
    //pesco l'id della scadenza da $data xk in $data_to_update non l'ho salvato visto che non lo devo aggiornare quello
    $dl_id = $data['DL_ID'];

    // inserimento dati in database
    $this->db->where('DL_ID', $dl_id)->update('deadlines', $data_to_update);
  }

  /*
  METODO:
  cancellazione record da db
  VARIABILI:
  $dl_id = Id della scadenza corrispondente in db al campo 'DAT_ID'
  */
  public function deadlines_delete($dl_id){
    // seleziono eventuale appuntamento correlato
    $result = $this->db->select('*')->where('DL_ID', $dl_id)->from('deadlines')->get()->result();
    $result = json_decode(json_encode($result), true); // conversione da object ad array

    // sistemo appuntamento correlato eliminando la correlazione anche dalla tabella dates (tolgo id della scadenza)
    $dat_id = $result[0]['DL_DAT_ID'];
    if($dat_id !== ''){
      $data_to_update = array(
        'DAT_DL_ID' => NULL,
      );
      $this->db->where('DAT_ID', $dat_id)->update('dates', $data_to_update);
    }

    $this->db->where('DL_ID', $dl_id)->delete('deadlines');
  }


  // METODI RAPPORTINI
  /*
  METODO: Add field in form
  */
  public function deadlines_form_add_report($formfields = ''){
    $report = array(
      'REP_upload' => array(
        'id' => 'report',
        'name' => 'report',
        'label' => 'Rapportino',
        'f_type' => 'upload',
        'class' => 'form-control',
      ) 
    );
    if($formfields !== ''){
      $formfields = array_merge($formfields, $report);
    } else{
      $formfields = $report;
    }
    
    return($formfields);
  }
  /*
  METODO: SALVA RAPPORTINO
  */
  public function deadlines_save_report($data, $dl_id, $files){
    $data_to_insert = array(
      'REP_DL_ID' => $dl_id
    );
    

    //pesco array campi report
    $report_fields = $this->deadlines_form_add_report();
    $fields_names = array();
    //array con nome dei campi
    foreach($report_fields as $field => $par_list){
      $fields_names[$field] = $par_list['name']; 
    }
    
    
    // UPLOAD DEL FILE
    $rel_path = './uploads/';
    foreach($files as $name => $value){
      if(in_array($name, $fields_names)){
        // UPLOAD DEL FILE
        // SET CONFIG //
        $config['allowed_types'] = 'pdf|png|jpg|jpeg|doc|docx';
        $config['overwrite'] = FALSE; //If the file exists it will be saved with a progressive number appended
        $config['upload_path'] = $rel_path; //Use relative or absolute path
        
        // carico libreria apposita insieme ad array config
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if(!$this->upload->do_upload($name)){
          // se fallisce mi mostra gli errori
          echo $this->upload->display_errors();
        } else{
          // salvataggio della path relativa in db
          $file_name = $this->upload->data('file_name');       // Returns the path
          $file_path = $rel_path.$file_name;
          //echo '<a href = "'.base_url().$rel_path.$file_name.'">Vedi</a>';
        }
      }
    }

    //SALVATAGGIO INFO FILE IN DB
    if(isset($file_path)){
      $data_to_insert = array(
        'REP_DL_ID' => $dl_id,
        'REP_upload' => $file_path,
        'REP_filename' => $file_name
      );
      $this->db->insert('reports', $data_to_insert);
    }
  }

  /*
  METODO: campi rapportino per form di modifica scadenza
  */
  public function deadlines_form_edit_report($formfields, $dl_id){
    // ricavo i report correlati alla scadenza (dovrebbe essere solo 1)
    $result = $this->db->select('*')->from('reports')->where('REP_DL_ID', $dl_id)->get()->result();
    $result = json_decode(json_encode($result), true); // conversione da object ad array
    
    // campi del report
    $report_fields = $this->deadlines_form_add_report();
    
    
    if(!empty($result)):
      // array del report correlato
      $report = $result[0];

      //assegno ai campi del report i valori del report, non li assegno come valori perchè in questo caso lo devo passare a un input upload
      foreach($report_fields as $field => $par_list){
        if(array_key_exists($field, $report)){
          // assegno come voce 'uploaded' anzichè value
          $report_fields[$field]['uploaded'] = $report[$field];
          // gli associo anche il nome del file
          $report_fields[$field]['file_name'] = $report['REP_filename'];
        }
      }    
    endif;
    // merge dell'array principale con l'array dei campi del report
    $formfields = array_merge($formfields, $report_fields);
    return($formfields);
    
  }

  /*
  METODO: modifica report caricato (eliminazione vecchio e caricamento di quello nuovo)
  */
  public function deadlines_edit_report($data){
    foreach($_FILES as $name => $par_list){
      if($par_list['name'] !== ''){
        $files[$name] = $par_list;
      }
    }

    if(isset($files)){
      $dl_id = $data['DL_ID'];
      // delete file
      $this->deadlines_report_unlink($data, $dl_id);
      // save report
      $this->deadlines_save_report($data, $dl_id, $files);
    }
    
  }

  /*
  METODO: delete file
  */
  public function deadlines_report_unlink($data, $dl_id){  
    //pesco array campi report
    $report_fields = $this->deadlines_form_add_report();
    $fields_names = array();
    //array con nome dei campi
    foreach($report_fields as $field => $par_list){
      $fields_names[$field] = $par_list['name']; 
    }

    // faccio ciclare per verificare il singolo file
    foreach($_FILES as $name => $value){
      if(in_array($name, $fields_names)){
        // query per trovare la path del vecchio file
        $result = $this->db->select('*')->from('reports')->where('REP_DL_ID',$dl_id)->get()->result();
        $result = json_decode(json_encode($result), true); // conversione da object ad array
        // RIMUOVO PRIMI 2 CARATTERI
        $result[0]['REP_upload'] = substr($result[0]['REP_upload'], 2);
        // path server
        $path = FCPATH.$result[0]['REP_upload'];
        //delete file
        unlink($path);
        // delete db row
        $this->db->where('REP_DL_ID', $dl_id)->delete('reports');
      }
    }
  }
  
}



<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {
    
  public function __construct(){
    parent::__construct();

    // visto che questo model serve prinipalmente per costruire tabelle carico in costruttore le librerie inerenti
    $this->load->database();
    $this->load->library('table');            // libreria tabelle
    $this->load->library('TableTemplates');   // template delle tabelle
    $this->load->library('CustomTables');     // funzioni varie x elaborazione tabelle

  }


  /*
  METODO:
  metodo di visualizzazione tabella appuntamenti
  VARIABILI METODO:
  $dbfield = array('campo1', 'campo2', ...) lista campi del db da pescare, serve x la select della query
  $labels = array(
    'Nome_campo_nel_db' => 'Titolo in tabella',             se presente in db
    'Nome_campo_a_propria_scelta' => 'Titolo in tabella'    se assente in db (colonna extra)
  ) 
  $extracol = array(
    'Nome_col_extra_uguale_a_labels' => 'contenuto fisso'
  )
  */
  public function table_dates_view($dbfield, $labels, $extracol){    
    // FIELDS DAL DB
    //ricavo i campi x la select dall'array $dbfields
    $db_fields = $this->customtables->select_fields($dbfield);

    //aggiungo l'ID x avere un riferimento, una volta usato viene disabilitato
    $db_fields .= ', CL_ID';

    //filtro per clienti attivi
    $where_array = array(
      'CL_active' => 1
    );
    
    // query alla tabella
    $result = $this->db->select($db_fields)->from('cl_dat_filter')->where($where_array)->get()->result();   // risultato
    $result = json_decode(json_encode($result), true); // conversione da object ad array
    

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
    // setto le colonne extra in header e result assegnando il loro contenuto in base all'array $extracol
    foreach($result as $numb => $value){
      foreach($extracol as $nome => $titolo){
        $header[$nome] = $labels[$nome];
        $result[$numb][$nome] = (isset($extracol[$nome]))? $extracol[$nome] : '';
      }
    }

    //MEMORIZZO ID
    // memorizzo l'id del cliente nei link che ci sono nella colonna 'actions' (anagrafica e appuntamenti) così da poter filtrare x cliente
    foreach($result as $numb => $value){
      $actions = $value['actions'];
      $new_actions = str_replace("registry_single_view", "registry_single_view?cl=".$value['CL_ID'], $actions); // memorizzo in anagrafica
      $new_actions = str_replace("dates_table", "dates_table?cl=".$value['CL_ID'], $new_actions);           // memorizzo in appuntamenti
      
      //gestione scadenze
      
      $result[$numb]['actions'] = $new_actions;
      // disabilito l'ID del cliente perchè ora non mi serve +
      unset($result[$numb]['CL_ID']);
    }

    
    //RIORDINO HEADER E RESULT SECONDO L'ARRAY LABELS
    // funzione presente nell'helper utility, vedere lì x riferimenti
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

      // SETTO I 2 STATUS
      // setto le caratteristiche dello status rosso
      $next_date = str_replace('<span', '<span style = "color: Tomato"', $status);  //  setto lo style dello span
      $next_date = str_replace('status', 'next status', $next_date);                // setto class x distinguere i diversi status
      $next_date = str_replace('<i', '<i alt = "a"', $next_date);                   //attributo alt sull'icona x poterle ordinare in tabella (plugin caricato in footer + inizializzazione in footer)
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
  metodo per la visualizzazione della tabella 'scadenze, selezionabile dalla home page
  VARIABILI METODO:
  $dbfield = array('campo1', 'campo2', ...) lista campi del db da pescare, serve x la select della query
  $labels = array(
    'Nome_campo_nel_db' => 'Titolo in tabella',             se presente in db
    'Nome_campo_a_propria_scelta' => 'Titolo in tabella'    se assente in db (colonna extra)
  ) 
  $extracol = array(
    'Nome_col_extra_uguale_a_labels' => 'contenuto fisso'
  )
  */
  public function table_deadlines_view($dbfield, $labels, $extracol){    
    // FIELDS DAL DB
    //ricavo i campi x la select dall'array $dbfields
    $db_fields = $this->customtables->select_fields($dbfield);

    //aggiungo l'ID x avere un riferimento
    $db_fields .= ', CL_ID, DL_DAT_ID, DL_ID';

    // filtro per clienti attivi
    $where_array = array(
      'CL_active' => 1
    );
    
    // query alla tabella
    $result = $this->db->select($db_fields)->from('cl_dl_filter')->where($where_array)->get()->result();   // risultato
    $result = json_decode(json_encode($result), true);                                // conversione da object ad array

   
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
    // setto le colonne extra in header e result assegnando il loro contenuto in base all'array $extracol
    foreach($result as $numb => $value){
      foreach($extracol as $nome => $titolo){
        $header[$nome] = $labels[$nome];
        $result[$numb][$nome] = (isset($extracol[$nome]))? $extracol[$nome] : '';
      }
    }

    //MEMORIZZO ID NEI VARI BOTTONI DELLE ACTIONS
    // memorizzo l'id del cliente nei link che ci sono nella colonna 'actions' (anagrafica e scadenze) così da poter filtrare x cliente
    foreach($result as $numb => $value){
      $actions = $value['actions'];
      $new_actions = str_replace("registry_single_view", "registry_single_view?cl=".$value['CL_ID'], $actions);
      $new_actions = str_replace("deadlines_table", "deadlines_table?cl=".$value['CL_ID'], $new_actions);

      // APPUNTAMENTO CORRELATO
      if(isset($value['DL_DAT_ID']) && $value['DL_DAT_ID']!==''):
        $new_actions = str_replace("dates_single_view", "dates_single_view?cl=".$value['CL_ID']."&dat=".$value['DL_DAT_ID']."&dl=".$value['DL_ID'], $new_actions);
        $new_actions = str_replace('add_date"', 'add_date" style = "display: none"', $new_actions);
      else:
        $new_actions = str_replace('view_date"', 'view_date" style = "display: none"', $new_actions);
        // verifico che ci siano scadenze eprchè se non ne ho non metto il pulsante per la correlazione con gli appuntamenti
        if(isset($value['DL_ID']) && $value['DL_ID']!==''):
          $new_actions = str_replace("dates_rel_dl_add_form", "dates_rel_dl_add_form?cl=".$value['CL_ID']."&dl=".$value['DL_ID'], $new_actions);
        else:
          $new_actions = str_replace('add_date"', 'add_date" style = "display: none"', $new_actions);
        endif;
          
      endif;

      $result[$numb]['actions'] = $new_actions;
      unset($result[$numb]['CL_ID']);
      unset($result[$numb]['DL_DAT_ID']);
      unset($result[$numb]['DL_ID']);
    }

    
    //RIORDINO HEADER E RESULT SECONDO L'ARRAY LABELS
    // funzione presente nell'helper utility, vedere lì x riferimenti
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

      // SETTO I 2 STATUS
      // setto le caratteristiche dello status rosso
      $next_date = str_replace('<span', '<span style = "color: Tomato"', $status);  // setto lo style dello span
      $next_date = str_replace('status', 'next status', $next_date);                // setto class
      $next_date = str_replace('<i', '<i alt = "a"', $next_date);                   // attributo alt sull'icona x poterle ordinare in tabella (plugin caricato in footer + inizializzazione in footer)
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
  public function table_registry_view($dbfield, $labels, $extracol){    
    // FIELDS DAL DB
    //ricavo i campi x la select dall'array $dbfields
    $db_fields = $this->customtables->select_fields($dbfield);

    //aggiungo l'ID x avere un riferimento
    $db_fields .= ', CL_ID, CL_active';
    
    // query alla tabella
    $result = $this->db->select($db_fields)->from('clients')->get()->result();   // risultato
    $result = json_decode(json_encode($result), true);                                // conversione da object ad array

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
    // setto le colonne extra in header e result assegnando il loro contenuto in base all'array $extracol
    foreach($result as $numb => $value){
      foreach($extracol as $nome => $titolo){
        $header[$nome] = $labels[$nome];
        $result[$numb][$nome] = (isset($extracol[$nome]))? $extracol[$nome] : '';
      }
    }

    //MEMORIZZO ID
    // memorizzo l'id del cliente nei link che ci sono nella colonna 'actions' (anagrafica e scadenze) così da poter filtrare x cliente
    foreach($result as $numb => $value){
      $actions = $value['actions'];
      $new_actions = str_replace("registry_single_view", "registry_single_view?cl=".$value['CL_ID'], $actions);
      $new_actions = str_replace("registry_edit_form", "registry_edit_form?cl=".$value['CL_ID'], $new_actions);
      $new_actions = str_replace("dates_table", "dates_table?cl=".$value['CL_ID'], $new_actions);
      $new_actions = str_replace("deadlines_table", "deadlines_table?cl=".$value['CL_ID'], $new_actions);
      //$new_actions = str_replace("registry_edit_form", "registry_edit_form?cl=".$value['CL_ID'], $action)
      $result[$numb]['actions'] = $new_actions;
      unset($result[$numb]['CL_ID']);
    }

    
    //RIORDINO HEADER E RESULT SECONDO L'ARRAY LABELS
    // funzione presente nell'helper utility, vedere lì x riferimenti
    arrays_sort($labels, $header); 
    foreach($result as $numb => $value){
      arrays_sort($labels, $value);
      $result[$numb] = $value;
    }

    // FILTRO STATUS IN BASE AD ATTIVO/NON ATTIVO
    
    foreach($result as $numb => $value){
      $status = $value['status'];         // campo status
      $cl_active =  $value['CL_active'];  //campo cl_active
     
      // SETTO I 2 STATUS
      // setto le caratteristiche dello status rosso
      $not_active = str_replace('<span', '<span style = "color: Tomato"', $status);  // setto lo style dello span
      $not_active = str_replace('status', 'not_active status', $not_active);       // setto class
      $not_active = str_replace('<i', '<i alt = "b"', $not_active);                // attributo alt sull'icona x poterle ordinare in tabella (plugin caricato in footer + inizializzazione in footer)
      // setto le caratteristiche dello status verde
      $active = str_replace('<span', '<span style = "color: green"', $status);
      $active = str_replace('status', 'active status', $active);
      $active = str_replace('<i', '<i alt = "a"', $active);
      
      $result[$numb]['status'] = ($cl_active == 0)? $not_active: $active;   // se non attivo rosso, sennò verde
      unset($result[$numb]['CL_active']);
      
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
}



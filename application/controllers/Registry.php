<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registry extends CI_Controller {
    
    public function __construct(){
		parent::__construct();

		$this->load->database();
    }
    
    // METODO: form per 'Aggiungi nuovo cliente'
	public function registry_add_form(){
        // carico model e library necessari
        $this->load->helper('form');
        $this->load->model('Registry_model');
        
        //DATI DA PASSARE ALLA VIEW
        // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
        $formfields = $this->Registry_model->registry_form_fields();
        //indirizzo
        $submit_form = 'Registry/submit_form_add';
        //titolo della pagina
        $card_title = 'Aggiungi nuovo cliente';
        // array x dati da passare alla view
        $data_to_view = array(
            'formfields' => $formfields,        // campi form
            'submit_form' => $submit_form,      // metodo salvataggio dati
            'card_title' => $card_title,        //titolo pagina
        );

        // titolo per header
        $data_to_header = array(
            'title' => 'Nuovo cliente'
        );

		try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header', $data_to_header);
			$this->load->view('registry_form', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    // METODO: aggiungi cliente
    public function submit_form_add(){
        // carico model e library necessari
        $this->load->model('Registry_model');

        //pesco dati in POST
        $data = $_POST;
        //li passo al model
        $this->Registry_model->registry_insert($data);
        try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('registry_submit_success');
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    // METODO. modifica dei dati di anagrafica
    public function registry_edit_form(){
        // carico model e library necessari
        $this->load->helper('form');
        $this->load->model('Registry_model');
        $cl_id = $_GET['cl'];
        
        //DATI DA PASSARE ALLA VIEW
        // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
        $formfields = $this->Registry_model->registry_form_fields_edit($cl_id);
        //indirizzo
        $submit_form = 'Registry/submit_form_edit';
        // titolo della pagina
        $card_title = 'Modifica/Visualizza anagrafica cliente';
        // raccolgo dati in array
        $data_to_view = array(
            'formfields' => $formfields,        // campi form
            'submit_form' => $submit_form,      //metodo savataggio
            'card_title' => $card_title,        //titolo pagina
        );
        
         // titolo per header
         $data_to_header = array(
            'title' => 'Modifica cliente'
        );

		try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header', $data_to_header);
			$this->load->view('registry_form', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    // METODO: update dei dati
    public function submit_form_edit(){
         // carico model e library necessari
         $this->load->model('Registry_model');

        //pesco dati in POST
        $data = $_POST;
        //li passo al model
        $this->Registry_model->registry_update($data);
         
         try{
             // VIEWS
             // all'header passo l'array per poter caricare i file css			
             $this->load->view('header');
             $this->load->view('registry_submit_success');
             $this->load->view('footer');
         }catch(Exception $e){			
             show_error($e->getMessage().' --- '.$e->getTraceAsString());
         }
    }

    // METODO: disattiva cliente
    public function registry_delete(){
        // carico model, helper e library
        $this->load->model('Registry_model');

        // GET client id
        $cl_id = $_GET['cl'];
        $this->Registry_model->registry_delete($cl_id);
    }

    // METODO: visualizza anagrafica
    public function registry_single_view(){
        // carico model e library necessari
        $this->load->helper('form');
        $this->load->model('Registry_model');
        $cl_id = $_GET['cl'];
        
        //DATI DA PASSARE ALLA VIEW
        // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
        $formfields = $this->Registry_model->registry_form_fields_edit($cl_id);
        // titolo della pagina
        $card_title = 'Visualizza cliente';
        // raccolgo dati in array
        $data_to_view = array(
            'formfields' => $formfields,        // campi form
            'card_title' => $card_title,        //titolo pagina
        );
        
         // titolo per header
         $data_to_header = array(
            'title' => 'Visualizza cliente'
        );

		try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header', $data_to_header);
			$this->load->view('registry_single_view', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
}

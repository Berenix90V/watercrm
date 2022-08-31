<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dates extends CI_Controller {
    
    public function __construct(){
		parent::__construct();

		$this->load->database();
    }

    // METODO:  tabella degli appuntamenti con filtro per cliente
    public function dates_table(){
        // carico helper e library che mi servono
        $this->load->library('table');
        $this->load->library('TableTemplates');   // template delle tabelle
        $this->load->model('Dates_model');

         // titolo per header
        $data_to_header = array(
            'title' => 'Nuovo cliente'
        );

        try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header', $data_to_header);
			$this->load->view('dates_table');
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}

    }

    // METODO: form di aggiunta nuovo appuntamento
	public function dates_add_form(){
        // carico model e library necessari
        $this->load->helper('form');
        $this->load->model('Dates_model');

        //PESCO ID CLIENTE
        $cl_id = $_GET['cl'];

        //DATI DA PASSARE ALLA VIEW
        // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
        // passo l'id perchè dovro pescare delle info del cliente dal db x metterle in disabled
        $formfields = $this->Dates_model->dates_form_fields($cl_id);
        //indirizzo
        $submit_form = 'Dates/submit_form_add';
        //titolo della pagina
        $card_title = 'Aggiungi nuovo appuntamento';
        // array x dati da passare alla view
        $data_to_view = array(
            'formfields' => $formfields,
            'submit_form' => $submit_form,
            'card_title' => $card_title,
        );

		try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('dates_form', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    
    //METODO: salvataggio dati nuovo appuntamento
    public function submit_form_add(){
        // carico model e library necessari
        $this->load->model('Dates_model');

        //pesco dati in POST
        $data = $_POST;
        $cl_id = $data['CL_ID'];

        // dati da passare alla view
        $data_to_view = array(
            'cl_id' => $cl_id,
        );
        
        //li passo al model
        $this->Dates_model->dates_insert($data);
        try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('dates_submit_success', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    
    // METODO: form di modifica appuntamento
    public function dates_edit_form(){
       // carico model e library necessari
       $this->load->helper('form');
       $this->load->model('Dates_model');

       //PESCO ID CLIENTE
       $cl_id = $_GET['cl'];
       $dat_id = $_GET['dat'];

       //DATI DA PASSARE ALLA VIEW
       // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
       // passo l'id perchè dovro pescare delle info del cliente dal db x metterle in disabled
       $formfields = $this->Dates_model->dates_form_fields_edit($cl_id, $dat_id);

       //indirizzo
       $submit_form = 'Dates/submit_form_edit';
       //titolo della pagina
       $card_title = 'Modifica appuntamento';
       // array x dati da passare alla view
       $data_to_view = array(
           'formfields' => $formfields,
           'submit_form' => $submit_form,
           'card_title' => $card_title,
       );

       try{
           // VIEWS
           // all'header passo l'array per poter caricare i file css			
           $this->load->view('header');
           $this->load->view('dates_form', $data_to_view);
           $this->load->view('footer');
       }catch(Exception $e){			
           show_error($e->getMessage().' --- '.$e->getTraceAsString());
       }
    }

    // METOTO:  Update dei dati di un appuntamento preesistente
    public function submit_form_edit(){
        // carico model e library necessari
        $this->load->model('Dates_model');

        //pesco dati in POST
        $data = $_POST;
        //li passo al model
        $this->Dates_model->dates_update($data);

        // passo alla view l'id del cliente per poter avere il link 'Indietro' corretto e tornare agli appuntamenti del cliente
        $data_to_view = array(
            'cl_id' => $_POST['CL_ID'],
        );

        try{
            // VIEWS
            // all'header passo l'array per poter caricare i file css			
            $this->load->view('header');
            $this->load->view('dates_submit_success', $data_to_view);
            $this->load->view('footer');
        }catch(Exception $e){			
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
    // METODO: conferma eliminazione dell'appuntamento
    public function dates_delete_confirm(){
        // carico model e library necessari
        $this->load->model('Dates_model');

        //pesco id dell'appuntamento e del cliente
        $cl_id = $_GET['cl'];
        $dat_id = $_GET['dat'];

        //DATI DA PASSARE ALLA VIEW
        $data_to_view = array(
            'cl_id' => $cl_id,
            'dat_id' => $dat_id
        );
        
        try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('dates_delete_confirm', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    // METODO: eliminazione appuntamento
    public function dates_delete(){
        // carico model e library necessari
        $this->load->model('Dates_model');

        //pesco id dell'appuntamento e del cliente
        $cl_id = $_GET['cl'];
        $dat_id = $_GET['dat'];

        // DATI DA PASSARE ALLA VIEW
        $data_to_view = array(
            'cl_id' => $cl_id,
            'dat_id' => $dat_id
        );

        $this->Dates_model->dates_delete($dat_id);

        
        try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('dates_delete', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    //METODO: aggiunta appuntamento correlato a scadenza
    public function dates_rel_dl_add_form(){
        // carico model e library necessari
        $this->load->helper('form');
        $this->load->model('Dates_model');

        //PESCO ID CLIENTE e SCADENZA
        $cl_id = $_GET['cl'];
        $dl_id = $_GET['dl'];

        //DATI DA PASSARE ALLA VIEW
        // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
        // passo l'id perchè dovro pescare delle info del cliente dal db x metterle in disabled
        $formfields = $this->Dates_model->dates_dl_formfields($cl_id, $dl_id);
        //indirizzo
        $submit_form = 'Dates/submit_form_add';
        //titolo della pagina
        $card_title = 'Aggiungi nuovo appuntamento';
        // array x dati da passare alla view
        $data_to_view = array(
            'formfields' => $formfields,
            'submit_form' => $submit_form,
            'card_title' => $card_title,
        );

		try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('dates_form', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    // METODO: metodo per la view dell'appuntamento
    public function dates_single_view(){
        // carico model e library necessari
        $this->load->helper('form');
        $this->load->model('Dates_model');
 
        //PESCO ID CLIENTE
        $cl_id = $_GET['cl'];
        $dat_id = $_GET['dat'];
 
        //DATI DA PASSARE ALLA VIEW
        // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
        // passo l'id perchè dovro pescare delle info del cliente dal db x metterle in disabled
        $formfields = $this->Dates_model->dates_form_fields_edit($cl_id, $dat_id);

 
        //titolo della pagina
        $card_title = 'Vedi appuntamento';
        // array x dati da passare alla view
        $data_to_view = array(
            'formfields' => $formfields,
            'card_title' => $card_title,
        );
 
        try{
            // VIEWS
            // all'header passo l'array per poter caricare i file css			
            $this->load->view('header');
            $this->load->view('dates_single_view', $data_to_view);
            $this->load->view('footer');
        }catch(Exception $e){			
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
     }
}

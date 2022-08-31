<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deadlines extends CI_Controller {
    
    public function __construct(){
		parent::__construct();

		$this->load->database();
    }
    // METODO: Tabella scadenze con filtro per cliente
    public function deadlines_table(){
        // carico quello helper  elibrary che mi servono
        $this->load->library('table');
        $this->load->library('TableTemplates');   // template delle tabelle
        $this->load->model('Deadlines_model');
        try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('deadlines_table');
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}

    }

    //METODO: Form di aggiunta nuova scadenza
	public function deadlines_add_form(){
        // carico model e library necessari
        $this->load->helper('form');
        $this->load->model('Deadlines_model');

        //PESCO ID CLIENTE
        $cl_id = $_GET['cl'];

        //DATI DA PASSARE ALLA VIEW
        // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
        // passo l'id perchè dovro pescare delle info del cliente dal db x metterle in disabled
        $formfields = $this->Deadlines_model->deadlines_form_fields($cl_id);

        //RAPPORTINO
        // campi rapportino
        $formfields = $this->Deadlines_model->deadlines_form_add_report($formfields);

        //indirizzo
        $submit_form = 'Deadlines/submit_form_add';
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
			$this->load->view('deadlines_form', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    
    // METODO: salvataggio dati nuova scadenza
    public function submit_form_add(){
        // carico model e library necessari
        $this->load->model('Deadlines_model');

        //pesco dati in POST
        $data = $_POST;
        $cl_id = $data['CL_ID'];

        // dati da passare alla view
        $data_to_view = array(
            'cl_id' => $cl_id,
        );
        
        //li passo al model
        $dl_id = $this->Deadlines_model->deadlines_insert($data);

        // RAPPORTINO
        //salvataggio rapportino
        $files = $_FILES;
        $this->Deadlines_model->deadlines_save_report($data, $dl_id, $files);
        
        try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('deadlines_submit_success', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    
    //METODO: form di modifica scadenza
    public function deadlines_edit_form(){
       // carico model e library necessari
       $this->load->helper('form');
       $this->load->model('Deadlines_model');

       //PESCO ID CLIENTE
       $cl_id = $_GET['cl'];
       $dl_id = $_GET['dl'];

       //DATI DA PASSARE ALLA VIEW
       // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
       // passo l'id perchè dovro pescare delle info del cliente dal db x metterle in disabled
       $formfields = $this->Deadlines_model->deadlines_form_fields_edit($cl_id, $dl_id);

       //RAPPORTINO
       // modifica rapportino
       $formfields = $this->Deadlines_model->deadlines_form_edit_report($formfields, $dl_id);

       //indirizzo
       $submit_form = 'Deadlines/submit_form_edit';
       //titolo della pagina
       $card_title = 'Modifica scadenza';
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
           $this->load->view('deadlines_form', $data_to_view);
           $this->load->view('footer');
       }catch(Exception $e){			
           show_error($e->getMessage().' --- '.$e->getTraceAsString());
       }
    }

    // METODO:  Update dei dati di una scadenza preesistente
    public function submit_form_edit(){
        // carico model e library necessari
        $this->load->model('Deadlines_model');

        //pesco dati in POST
        $data = $_POST;
        //li passo al model
        $this->Deadlines_model->deadlines_update($data);

         // RAPPORTINO
        //salvataggio nuovo rapportino
        $this->Deadlines_model->deadlines_edit_report($data);
        

        // passo alla view l'id del cliente per poter avere il link 'Indietro' corretto e tornare agli appuntamenti del cliente
        $data_to_view = array(
            'cl_id' => $_POST['CL_ID'],
        );

        try{
            // VIEWS
            // all'header passo l'array per poter caricare i file css			
            $this->load->view('header');
            $this->load->view('deadlines_submit_success', $data_to_view);
            $this->load->view('footer');
        }catch(Exception $e){			
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
    
    //METODO: conferma eliminazione record
    public function deadlines_delete_confirm(){
        // carico model e library necessari
        $this->load->model('Deadlines_model');

        //pesco id dell'appuntamento e del cliente
        $cl_id = $_GET['cl'];
        $dl_id = $_GET['dl'];

        //DATI DA PASSARE ALLA VIEW
        $data_to_view = array(
            'cl_id' => $cl_id,
            'dl_id' => $dl_id
        );
        
        try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('deadlines_delete_confirm', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    // METODO: eliminazione di un songolo record da db
    public function deadlines_delete(){
        // carico model e library necessari
        $this->load->model('Deadlines_model');

        //pesco id dell'appuntamento e del cliente
        $cl_id = $_GET['cl'];
        $dl_id = $_GET['dl'];

        // DATI DA PASSARE ALLA VIEW
        $data_to_view = array(
            'cl_id' => $cl_id,
            'dl_id' => $dl_id
        );

        $this->Deadlines_model->deadlines_delete($dl_id);

        
        try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('deadlines_delete', $data_to_view);
			$this->load->view('footer');
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    // METODO: visualizza scadenza
    public function deadlines_single_view(){
        // carico model e library necessari
        $this->load->helper('form');
        $this->load->model('Deadlines_model');
 
        //PESCO ID CLIENTE
        $cl_id = $_GET['cl'];
        $dl_id = $_GET['dl'];
 
        //DATI DA PASSARE ALLA VIEW
        // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
        // passo l'id perchè dovro pescare delle info del cliente dal db x metterle in disabled
        $formfields = $this->Deadlines_model->deadlines_form_fields_edit($cl_id, $dl_id);

        //RAPPORTINO
        // modifica rapportino
        $formfields = $this->Deadlines_model->deadlines_form_edit_report($formfields, $dl_id);
 
        //titolo della pagina
        $card_title = 'Visualizza scadenza';
        // array x dati da passare alla view
        $data_to_view = array(
            'formfields' => $formfields,
            'card_title' => $card_title,
        );
 
        try{
            // VIEWS
            // all'header passo l'array per poter caricare i file css			
            $this->load->view('header');
            $this->load->view('deadlines_single_view', $data_to_view);
            $this->load->view('footer');
        }catch(Exception $e){			
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
    
    //vista per generare un pdf
    public function deadline_single_view_pdf() {
        //carico i model ed i library necessari
        $this->load->model('Deadlines_model');
        $this->load->library('pdfgenerator');
        
        //pesco l'id del cliente
        $cl_id = $_GET['cl'];
        $dat_id = $_GET['dl'];

        //DATI DA PASSARE ALLA VIEW
        // richiamo metodo x generare array campi, se servono modifiche basta entrare e modificare l'array
        // passo l'id perchè dovro pescare delle info del cliente dal db x metterle in disabled
        $formfields = $this->Deadlines_model->deadlines_form_fields_edit($cl_id, $dat_id);
        
        //titolo della pagina
        $card_title = 'Rapportino';

        //array di dat passato alla view
        $data_to_view = array(
            'formfields' => $formfields,
            'card_title' => $card_title,
        );		
        
        $html1 = $this->load->view( 'header' , '' , true );
        $html2 = $this->load->view( 'deadlines_single_view_pdf' , $data_to_view , true );
        $html3 = $this->load->view( 'footer' , '' , true );
        $html = $html1.$html2.$html3;
        $filename = "rapportino";
        $this->pdfgenerator->generate($html, $filename, true, 'A4', 'portrait');
    }

     
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function __construct(){
		parent::__construct();

		$this->load->database();
		$this->load->helper('form');
	}
	
	/*
	METODO:
	è la home page e visualizza di default gli appuntamenti
	questo metodo viene richiamato in home page quando si apre il sito e da chiamata ajax on change della select
	VARIABILI:
	*/
	public function index(){
		// carico model e library necessari
		$this->load->model('Home_model');

		// dati alla view
		$data_to_view = array(
		);

		
		try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('home_deadlines_table');
			$this->load->view('footer');
		
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function deadlines_table(){
		// carico model e library necessari
		$this->load->model('Home_model');
		
		try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('home_deadlines_table');
			$this->load->view('footer');
			
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function registry_table(){
		// carico model e library necessari
		$this->load->model('Home_model');

		// dati alla view
		$data_to_view = array(
			'mode' => 'registry'
		);
		
		try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('home_registry_table', $data_to_view);
			$this->load->view('footer');
			
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}


	//DISABLED
	/*
	METODO:
	è il metodo che serve per generare tabella appuntamenti richiamato in index quando esso viene richiamato da ajax
	VARIABILI:
	*/
	/*public function dates_table(){
		// carico model e library necessari
		$this->load->model('Home_model');
		
		try{
			// VIEWS
			// all'header passo l'array per poter caricare i file css			
			$this->load->view('header');
			$this->load->view('home_dates_table');
			$this->load->view('footer');
			
		}catch(Exception $e){			
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}*/
	
	
}

<?php
class QrCoder extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('header');
        $this->load->view('qr-code-reader');
        $this->load->view('footer');
    }


}
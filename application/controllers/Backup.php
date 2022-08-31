<?php
class Backup extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('Backup_model');
        if (isset($_POST['backup-db'])) {
            $this->Backup_model->backup_db();
        }

        if (isset($_POST['backup-files'])) {
            $this->Backup_model->backup_attachments();
        }
        $this->load->view('header');
        $this->load->view('amministrazione');
        $this->load->view('footer');
    }
}
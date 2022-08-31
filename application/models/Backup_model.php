<?php
class Backup_model extends CI_model {
    public function __construct() {
        parent::__construct();
    }

    public function backup_db() {
        // Load the DB utility class
        $this->load->dbutil();
    
        // Backup your entire database and assign it to a variable
        $backup = $this->dbutil->backup($pref);

        // Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file(FCPATH.'/db-backup/sql_backup.gz', $backup);

        // Load the download helper and send the file to your desktop
        $this->load->helper('download');
        force_download('sql_backup.gz', $backup);
    }

    public function backup_attachments() {
        $this->load->library('zip');
        $this->load->helper('directory');
        $path = FCPATH.'uploads';
        $map = directory_map($path);
        $this->zip->archive($map);
        $this->zip->download('allegati.zip');
        
    }
}
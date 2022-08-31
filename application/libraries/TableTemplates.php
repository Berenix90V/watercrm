<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tabletemplates
{
    function __construct() {
        $CI =& get_instance();
        $this->CI =$CI;
    }
    public function basic_template($table_id = 'datatable', $table_class = '') {
        $basic_template = array(
            'table_open' => '<table class="table table-bordered '.$table_class.'" id="'.$table_id.'" width="100%" cellspacing="0">',

            'thead_open'            => '<thead class = "text-primary">',
            'thead_close'           => '</thead>',

            'heading_row_start'     => '<tr>',
            'heading_row_end'       => '</tr>',
            'heading_cell_start'    => '<th>',
            'heading_cell_end'      => '</th>',

            'tbody_open'            => '<tbody>',
            'tbody_close'           => '</tbody>',
            
            'row_start'             => '<tr>',
            'row_end'               => '</tr>',
            'cell_start'            => '<td>',
            'cell_end'              => '</td>',

            'row_alt_start'         => '<tr>',
            'row_alt_end'           => '</tr>',
            'cell_alt_start'        => '<td>',
            'cell_alt_end'          => '</td>',
            
            'table_close'           => '</table>'

        );
        return $basic_template;
    }
    public function home_template($table_id = 'datatable', $table_class = '') {
        $home_template = array(
            'table_open' => '<table class="table table-bordered '.$table_class.'" id="'.$table_id.'" width="100%" cellspacing="0">',

            'thead_open'            => '<thead class = "text-primary">',
            'thead_close'           => '</thead>',

            'heading_row_start'     => '<tr>',
            'heading_row_end'       => '</tr>',
            'heading_cell_start'    => '<th>',
            'heading_cell_end'      => '</th>',

            'tbody_open'            => '<tbody>',
            'tbody_close'           => '</tbody>',
            
            'row_start'             => '<tr>',
            'row_end'               => '</tr>',
            'cell_start'            => '<td>',
            'cell_end'              => '</td>',

            'row_alt_start'         => '<tr>',
            'row_alt_end'           => '</tr>',
            'cell_alt_start'        => '<td>',
            'cell_alt_end'          => '</td>',
            
            'table_close'           => '</table>'

        );
        return $home_template;
    }
}
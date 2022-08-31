<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('DOMPDF_ENABLE_AUTOLOAD', false);
//require_once(FCPATH."application/libraries/dompdf/vendor/dompdf/dompdf/dompdf_config.inc.php");
require_once(FCPATH."application/libraries/dompdf/vendor/autoload.php");
use Dompdf\Dompdf;


class Pdfgenerator {

  public function generate($html, $filename='', $stream=TRUE, $paper = 'A4', $orientation = "portrait")
  {

    $dompdf = new DOMPDF();
    $dompdf->set_option('setIsRemoteEnabled', true);
    $dompdf->set_option('isHtml5ParserEnabled', true);
    $dompdf->set_option("isPhpEnabled", true);
    $dompdf->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
    $dompdf->load_html($html);
    $dompdf->set_paper($paper, $orientation);
      
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf", array("Attachment" => 0));
    } else {
        return $dompdf->output();
    }
  }
}
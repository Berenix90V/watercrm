<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- se è settato $title passato dal controller allora il titolo sarà visibile-->
    <?php
    if(isset($title)):
    ?>
    <title><?php echo $title; ?></title>
    <?php 
    else:
    endif;
    ?>
<!-- fogli di stile e librerie varie-->
<link href="<?php echo base_url();?>assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css"> <!-- bootstrap -->
<link href="https://fonts.googleapis.com/css?family=Montserrat+Alternates:300,400,500,600&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/DataTables/datatables.min.css"> <!-- datatables -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">


<!-- riga aggiunta x correggere errore favicon-->
<link rel="shortcut icon" href="">
</head>
<body>
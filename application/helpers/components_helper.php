<?php

/*
* funzione per controllare se una variabile è compilata o è vuota,
* ritorna true se è piena, false se è vuota
*/

function control_varible($var) {
    return $var != '' ? true : false;
}

/*
* funzione per generare il titolo nelle diverse pagine
*/

function main_header_component($title, $subtitle, $specialActions) {
    ?>
    <div class="sub-title"><?= $subtitle;?></div>
    <h1 class="table-title"><?= $title;?></h1>
    <?php
}


//generatore del menu principale, inerente le tabelle principali: lista clienti, lista appuntamenti, lista scadenze
function menu_generator() {
    ?>
    <div id="action-list" class="clearfix">
        <span class = "main-option"><a href="<?php echo site_url('Registry/registry_add_form'); ?>"><i class = "fas fa-user"></i> NUOVO CLIENTE</a></span>
        <span class = "main-option"><a href="<?php echo site_url('Home/registry_table'); ?>"><i class = "fas fa-users"></i> LISTA CLIENTI</a></span>
        <span class = "main-option"><a href="<?php echo site_url('Home/deadlines_table'); ?>"><i class = "fas fa-hourglass-end"></i> SCADENZE</a></span>
    </div>
    <?php
}
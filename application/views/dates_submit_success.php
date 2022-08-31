
    <div class="section-header"> <!-- bootstrap class: card-body my2 id_card-body -->
        <h1>Appuntamento salvato con successo</h1>  <!-- bootstrap class: card-header text-primary -->
    </div> <!-- close card -->
    
    <!-- BOTTONE X AGGIUNGI NUOVO E BOTTONI X LISTA CLIENTI, APPUNTAMENTI E SCADENZE -->
    <div id="action-list" class="clearfix">
        <span class = "main-option"><a href="<?php echo site_url('Dates/dates_table').'?cl='.$cl_id; ?>"><i class = "fas fa-angle-left"></i> Indietro</a></span>
        <span class = "main-option"><a href="<?php echo site_url('Home/registry_table'); ?>"><i class = "fas fa-users"></i> LISTA CLIENTI</a></span>
        <span class = "main-option"><a href="<?php echo site_url('Home/dates_table'); ?>"><i class = "fas fa-calendar-alt"></i> APPUNTAMENTI</a></span>
        <span class = "main-option"><a href="<?php echo site_url('Home/deadlines_table'); ?>"><i class = "fas fa-hourglass-end"></i> SCADENZE</a></span>    
    </div>
<div class = "container-fluid">	 <!-- bootstrap class: container-fluid -->
    <div id = "card-body" class="card-body my-2"> <!-- bootstrap class: card-body my2 id_card-body -->
        <h1 class = "card-header text-primary">Scadenza salvata con successo</h1>  <!-- bootstrap class: card-header text-primary -->
       
         <!-- BOTTONE X AGGIUNGI NUOVO E BOTTONI X LISTA CLIENTI, APPUNTAMENTI E SCADENZE -->
         <div id="action-list" class="clearfix">
            <span class = "main-option"><a href="<?php echo site_url('Deadlines/deadlines_table').'?cl='.$cl_id; ?>"><i class = "fas fa-angle-left"></i>Indietro</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Home/registry_table'); ?>"><i class = "fas fa-users"></i> LISTA CLIENTI</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Home/deadlines_table'); ?>"><i class = "fas fa-hourglass-end"></i> SCADENZE</a></span>
        </div>
        
       
    </div> <!-- close card -->
</div> <!-- close container-fluid -->
<div class = "container-fluid">	 <!-- bootstrap class: container-fluid -->
    <div id = "card-body" class="card-body my-2"> <!-- bootstrap class: card-body my2 id_card-body -->
        <h1 class = "card-header text-primary">Vuoi eliminare la scadenza?</h1>  <!-- bootstrap class: card-header text-primary -->

        <span class = ""><a href="<?php echo site_url('Deadlines/deadlines_delete').'?cl='.$cl_id.'&dl='.$dl_id; ?>">Sì</a></span>
        <span class = ""><a href="<?php echo site_url('Deadlines/deadlines_table').'?cl='.$cl_id; ?>">No</a></span>       
        
       
    </div> <!-- close card -->
</div> <!-- close container-fluid -->
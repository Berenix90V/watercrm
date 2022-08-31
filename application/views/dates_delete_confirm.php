<div class = "container-fluid">	 <!-- bootstrap class: container-fluid -->
    <div id = "card-body" class="card-body my-2"> <!-- bootstrap class: card-body my2 id_card-body -->
        <h1 class = "card-header text-primary">Vuoi eliminare l'appuntamento?</h1>  <!-- bootstrap class: card-header text-primary -->

        <span class = ""><a href="<?php echo site_url('Dates/dates_delete').'?cl='.$cl_id.'&dat='.$dat_id; ?>">Sì</a></span>
        <span class = ""><a href="<?php echo site_url('Dates/dates_table').'?cl='.$cl_id; ?>">No</a></span>       
        
       
    </div> <!-- close card -->
</div> <!-- close container-fluid -->
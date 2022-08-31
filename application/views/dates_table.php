
        <!-- TITOLO -->
        <div class = "section-header">
        <h1>Appuntamenti cliente</h1>  <!-- bootstrap class: card-header text-primary -->
        </div> <!-- end card-header-->
        
        <?php
        // pesco l'id del cliente da passare nei link
        $cl_id = $_GET['cl'];
        ?>

        <!-- BOTTONE X AGGIUNGI NUOVO E BOTTONI X LISTA CLIENTI, APPUNTAMENTI E SCADENZE -->
        <div id="action-list" class="clearfix">
            <span class = "main-option"><a href="<?php echo site_url('Dates/dates_add_form').'?cl='.$cl_id; ?>"><i class = "fas fa-user"></i> Nuovo appuntamento</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Home/registry_table'); ?>"><i class = "fas fa-users"></i> LISTA CLIENTI</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Home/dates_table'); ?>"><i class = "fas fa-calendar-alt"></i> APPUNTAMENTI</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Home/deadlines_table'); ?>"><i class = "fas fa-hourglass-end"></i> SCADENZE</a></span>
        </div>
        <div id="table-responsive">
        <?php 

        //COLONNE E LORO GESTIONE
        // colonne della tabella presenti nel db
        $db_fields = array('CL_name', 'CL_surname', 'DAT_date', 'DAT_time');

        // Titoli colonne della tabella
        $labels = array(
            'status' => 'Status',
            'CL_name' => 'Nome',
            'CL_surname' => 'Cognome',
            'DAT_date' => 'Data',
            'DAT_time' => 'Ora',
            'actions' => 'Actions'
        );

        // RICAVO ID CLIENTE 
        if(isset($_GET['cl'])){
            $cl_id = $_GET['cl'];
        }
        
        
        // ICONE
        $view = '<a href = "'.site_url('Dates/dates_single_view').'" class = "dates-button view"><span class = "dates"><i class = "fas fa-search"></i></span><span class="text"> Visualizza</span></a>';
        $edit = '<a href = "'.site_url('Dates/dates_edit_form').'" class = "dates-button edit"><span class = "dates"><i class = "fas fa-edit"></i></span><span class="text"> Modifica</span></a>';
        $delete = '<a href = "'.site_url('Dates/dates_delete_confirm').'" class = "dates-button delete"><span class = "dates"><i class = "fas fa-trash-alt"></i></span><span class="text"> Elimina</span></a>';     
        $status = '<span class = "status"><i class = "fas fa-circle 2x"></i></span>';

        // Colonne extra e loro contenuto fisso o '', obbligatorio!! il campo deve essere o qui o in db_fields
        $extracol = array(
            'actions' => '<div class="actions-button">'.$view.'</div><div class="actions-button">'.$edit.'</div><div class="actions-button">'.$delete.'</div>',
            'status' => $status
        );
        
        $header_result = $this->Dates_model->table_view($db_fields, $labels, $extracol, $cl_id);
        if(isset($header_result['no_dates'])): ?>

            <span class = 'no-dates'>Non sono presenti appuntamenti per questo cliente</span>

        <?php 
        else:
        $header = $header_result['header'];
        $result = $header_result['result'];

        //TEMPLATE TABELLA
        //scelgo un template dalla library tabletemplate
        $table_id = 'home_table';
        $table_class = 'datatable status_order d_order'; //  se si vuole ordinamento x status mettere la classe status_order
        $tabletempl = $this->tabletemplates->home_template($table_id, $table_class);

        
        //assegno l'header
        $this->table->set_heading($header);
        $this->table->set_template($tabletempl);
        echo $this->table->generate($result);

        endif;
        ?>
        </div>
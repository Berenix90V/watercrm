
        <!-- TITOLO -->
        <div class = "section-header">
        <h1>Lista Scadenze</h1>  <!-- bootstrap class: card-header text-primary -->
        </div> <!-- end card-header-->
        <!-- BOTTONE X AGGIUNGI NUOVO E BOTTONI X LISTA CLIENTI, APPUNTAMENTI E SCADENZE -->
        <div id="action-list" class="clearfix">
            <span class = "main-option"><a href="<?php echo site_url('Home/registry_table'); ?>"><i class = "fas fa-users"></i> CLIENTI</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Home/deadlines_table'); ?>"><i class = "fas fa-hourglass-end"></i> SCADENZE</a></span>
        </div>
        
        <?php

       

        // ------------------------- GESTIONE TABELLA ----------------------------//
       
//----------------------------------------------- SCADENZE -------------------------//
            //COLONNE E LORO GESTIONE
            // colonne della tabella presenti nel db
            $db_fields = array('CL_name', 'CL_surname', 'DL_date');

            // Titoli colonne della tabella
            $labels = array(
                'status' => 'Status',
                'CL_name' => 'Nome',
                'CL_surname' => 'Cognome',
                'DL_date' => 'Data',
                'actions' => 'Actions',
            );
            
            
            // ICONE
            $anagrafica = '<a href = "'.site_url('Registry/registry_single_view').'" class="registry-button"><span class = "registry"><i class = "fas fa-user"></i></span> <span class="text"> Anagrafica</span></a>';
            $scadenziario = '<a href = "'.site_url('Deadlines/deadlines_table').'" class = "deadline-button"><span class = "deadlines"><i class = "fas fa-hourglass-end"></i></span><span class="text"> Scadenze</span></a>';
            
            // cambia e aggiungi le classi come vuoi, basta che mi lasci le 2 distintive in ultima (add_date e view_date) 
            // mi servono per un str_replace nel model in modo da mettere display none nel bottone che non serve
           
            $status = '<span class = "status"><i class = "fas fa-circle 2x"></i></span>';
            
            

            // Colonne extra e loro contenuto fisso o '', obbligatorio!! il campo deve essere o qui o in db_fields
            $extracol = array(
                'actions' => '<div class="actions-button">'.$anagrafica.'</div><div class="actions-button">'.$scadenziario.'</div>',
                'status' => $status
            );
            
            $header_result = $this->Home_model->table_deadlines_view($db_fields, $labels, $extracol);
            

            //TEMPLATE TABELLA
            //scelgo un template dalla library tabletemplate
            $table_id = 'home_table';
            $table_class = 'datatable d_order status_order';
            $tabletempl = $this->tabletemplates->home_template($table_id, $table_class);

        //----------------------------PRINT TABELLA-------------------------//
        ?>

        <div id = "table-responsive">               <!-- inserisco div di rif x poter fare l'append da js in un secondo momento -->

            <?php
            // RICAVO HEADER E RESULT DALL'ARRAY FORNITO DAL MODEL
            $header = $header_result['header'];
            $result = $header_result['result'];

            //assegno header, result e table template
            $this->table->set_heading($header);
            $this->table->set_template($tabletempl);
            echo $this->table->generate($result);

            ?>
        </div> <!-- close table-responsive -->

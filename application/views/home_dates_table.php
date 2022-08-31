
        <!-- TITOLO -->
        <div class = "section-header">
        <h1>Lista Appuntamenti</h1>  <!-- bootstrap class: card-header text-primary -->
        </div> <!-- end card-header-->
        <!-- BOTTONE X AGGIUNGI NUOVO E BOTTONI X LISTA CLIENTI, APPUNTAMENTI E SCADENZE -->
        <?php 
            //da reperire nel component_helper
            menu_generator();
        ?>
        
        <?php


        // ------------------------- GESTIONE DELLA TABELLA ----------------------------//

        
            // -------------------------------------------- APPUNTAMENTI ------------------------------//
            //COLONNE E LORO GESTIONE
            // colonne della tabella presenti nel db
            $db_fields = array('CL_name', 'CL_surname', 'DAT_date');

            // Titoli colonne della tabella
            $labels = array(
                'status' => 'Status',
                'CL_name' => 'Nome',
                'CL_surname' => 'Cognome',
                'DAT_date' => 'Data',
                'actions' => 'Actions',
            );
            
            
            // ICONE
            $anagrafica = '<a href = "'.site_url('Registry/registry_single_view').'" class="registry-button"><span class = "registry"><i class = "fas fa-user"></i></span> <span class="text"> Anagrafica</span></a>';
            $appuntamenti = '<a href = "'.site_url('Dates/dates_table').'" class="dates-button"><span class = "dates"><i class = "fas fa-calendar-alt"></i></span> <span class="text"> Appuntamenti</span></a>';
            $status = '<span class = "status"><i class = "fas fa-circle 2x"></i></span>';
          
            // Colonne extra e loro contenuto fisso o '', obbligatorio!! il campo deve essere o qui o in db_fields
            $extracol = array(
                'actions' => '<div class="actions-button">'.$anagrafica.'</div><div class="actions-button">'.$appuntamenti.'</div>',
                'status' => $status
            );
            
            $header_result = $this->Home_model->table_dates_view($db_fields, $labels, $extracol);
            

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

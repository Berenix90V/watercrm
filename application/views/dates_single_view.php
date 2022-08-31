

        <!-- TITOLO -->
        <!-- pesca da una variabile che gli viene passata in controller a seconda del metodo che sia in add o editing -->
        <div class = "section-header">
        <h1><?php echo (isset($card_title))? $card_title: 'Aggiungi nuovo Appuntamento';?></h1>  <!-- bootstrap class: card-header text-primary -->
        </div> <!-- fine header -->

        <?php
        // pesco id cliente e appuntamento
        $cl_id = $_GET['cl'];
        // id appuntamento c'è solo in edit
        if(isset($_GET['dat'])){
            $dat_id = $_GET['dat'];
        }
        if(isset($_GET['dl'])){
            $dl_id = $_GET['dl'];
        }
        ?>

         <!-- BOTTONE X AGGIUNGI NUOVO E BOTTONI X LISTA CLIENTI, APPUNTAMENTI E SCADENZE -->
         <div id="action-list" class="clearfix">
            <!--span class = "main-option"><a href="<?php echo site_url('Dates/dates_table').'?cl='.$cl_id; ?>"><i class = "fas fa-angle-left"></i> INDIETRO</a></span-->
            <span class = "main-option"><a href="<?php echo site_url('Home/registry_table'); ?>"><i class = "fas fa-users"></i> LISTA CLIENTI</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Home/dates_table'); ?>"><i class = "fas fa-calendar-alt"></i> APPUNTAMENTI</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Home/deadlines_table'); ?>"><i class = "fas fa-hourglass-end"></i> SCADENZE</a></span>
         <!-- MODIFICA ED ELIMINA -->
            <span class = "main-option"><a href="<?php echo site_url('Dates/dates_edit_form').'?cl='.$cl_id.'&dat='.$dat_id; ?>"><i class = "fas fa-edit"></i> MODIFICA</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Dates/dates_delete_confirm').'?dat='.$dat_id; ?>"><i class = "fas fa-trash-alt"></i> ELIMINA</a></span>

        
        </div>
        
        <div id="table-responsive">
        <?php

        //SETTINGS X I CAMPI (lista visibili, ordine ecc)

        // SE NON SI USANO LE SEZIONI
        //lista campi da inserire per id, così è facile da frontend perchè basta vedere l'id e non serve maneggiare il model
        $fields_list = array('nome', 'surname', 'mobile', 'city', 'dat_title', 'dat_description', 'dat_date', 'dat_time');

        // SEZIONI FORM //
        // array per suddividere i campi in diverse sezioni
        /*
        L'array è così organizzato:
        $sezioni = array(
            'nome_sezione => array(
                'titolo' => 'Titolo che si desidera in view x la sezione'
                'campi' => array('name_del_campo1', 'name_del_campo2')  // array con elenco degli id dei campi della sezione
            )
        )
        se si divide in sezioni chiaramente bisogna elencare TUTTI i campi suddivisi in sezioni, quelli non elencati non compariranno
        per togliere la suddivisione basta commentare l'array, togliere il primo foreach
        l'echo dell'h2 e togliere l'if seguente il secondo foreach
        */
        
        
        //se ho la scadenza correlata la inserisco come sezione altrimenti no
        if(isset($formfields['DL_date']['value'])){
            $sezioni = array(
                'anagrafica' => array(
                    'titolo' => 'Dati anagrafici',
                    'campi' => array('nome', 'surname', 'mobile', 'city'),
                ),
                'scadenza_correlata' => array(
                    'titolo' => 'Scadenza correlata',
                    'campi' => array('dl_title', 'dl_date'),
                ),
                'appuntamento' => array(
                    'titolo' => 'Dati appuntamento',
                    'campi' => array('dat_title', 'dat_description', 'dat_date', 'dat_time')
                )
            );
        } else{
            $sezioni = array(
                'anagrafica' => array(
                    'titolo' => 'Dati anagrafici',
                    'campi' => array('nome', 'surname', 'mobile', 'city'),
                ),
                'appuntamento' => array(
                    'titolo' => 'Dati appuntamento',
                    'campi' => array('dat_title', 'dat_description', 'dat_date', 'dat_time')
                )
            );
        }
        // FINE SEZIONI
        
        foreach($sezioni as $sezione => $par_sez){                          // faccio ciclare le sezioni, da togliere se non voglio le sezioni
            ?>

            <h2><?php echo $par_sez['titolo'];?></h2> <!-- titolo sezione -->

            <?php
            foreach($formfields as $db_field => $par_list){
                //da qui filtro in base a cosa ho messo nell'array $fields_list
                if(in_array($par_list['id'], $par_sez['campi'])){
                    echo "<div class='single-data'>";
                    // qui x modificare visualizzazione dati
                    echo "<div class='label'>".$par_list['label']."</div>";
                    echo "<div class='value'>".$par_list['value']."</div>";
                    echo "</div>";
                }           
            }
            echo "<div style='clear:both'></div>";
        }
        // FINE CICLO SEZIONI
       

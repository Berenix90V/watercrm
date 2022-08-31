

        <!-- TITOLO -->
        <!-- pesca da una variabile che gli viene passata in controller a seconda del metodo che sia in add o editing -->
        <div class = "section-header">
        <h1><?php echo (isset($card_title))? $card_title: 'Aggiungi nuova Scadenza';?></h1>  <!-- bootstrap class: card-header text-primary -->
        </div> <!-- dine header -->
        
        <?php
        // pesco id cliente e appuntamento
        $cl_id = $_GET['cl'];
        // id appuntamento c'è solo in edit
        if(isset($_GET['dl'])){
            $dl_id = $_GET['dl'];
        }
        
        ?>

         <!-- BOTTONE X AGGIUNGI NUOVO E BOTTONI X LISTA CLIENTI, APPUNTAMENTI E SCADENZE -->
         <div id="action-list" class="clearfix">
            <span class = "main-option"><a href="<?php echo site_url('Deadlines/deadlines_table').'?cl='.$cl_id; ?>"><i class = "fas fa-angle-left"></i> INDIETRO</a></span>
        <!-- GENERAZIONE DEL PDF-->
        <?php if (isset($_GET['cl']) && isset($_GET['dl'])) { ?>
            <span class = "main-option"><a href="<?php echo site_url('Deadlines/deadline_single_view_pdf');?>?cl=<?php echo $cl_id; ?>&dl=<?php echo $_GET['dl'];?>">CREA RAPPORTINO</a></span>
        <?php } ?>
        <!-- MODIFICA ED ELIMINA -->
            <span class = "main-option"><a href="<?php echo site_url('Deadlines/deadlines_edit_form').'?cl='.$cl_id.'&dl='.$dl_id; ?>"><i class = "fas fa-edit"></i> MODIFICA</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Deadlines/deadlines_delete_confirm').'?dl='.$dl_id; ?>"><i class = "fas fa-trash-alt"></i> ELIMINA</a></span>
        </div>
        
        <?php

        //SETTINGS X I CAMPI (lista visibili, ordine ecc)
        //lista campi da inserire per id, così è facile da frontend perchè basta vedere l'id e non serve maneggiare il model
        $fields_list = array('nome', 'surname', 'mobile', 'city', 'dl_title', 'dl_description', 'dl_date', 'dl_time', 'dat_title', 'dat_description', 'dat_date');

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

        $sezioni = array(
            'anagrafica' => array(
                'titolo' => 'Dati anagrafici',
                'campi' => array('nome', 'surname', 'mobile', 'city'),
            ),
            'scadenza' => array(
                'titolo' => 'Dati scadenza',
                'campi' => array('dl_title', 'dl_description', 'dl_date', 'report')
            ),
        );
        // FINE SEZIONI

        //CICLO CAMPI
            foreach($sezioni as $sezione => $par_sez){
                ?>

                <h2><?php echo $par_sez['titolo'];?></h2> <!-- titolo sezione -->

                <?php
                foreach($formfields as $db_field => $par_list){
                    if(isset($par_list['value']) && $par_list['value'] !=='' || isset($par_list['uploaded']) && $par_list['uploaded'] !== ''):
                        //da qui filtro in base a cosa ho messo nell'array $fields_list
                        if(in_array($par_list['id'], $par_sez['campi'])){
                            echo "<div class='single-data'>";
                            // qui x modificare visualizzazione dati
                            echo "<div class='label'>".$par_list['label']."</div>";
                            if(isset($par_list['uploaded'])){
                                // valido per campi upload
                                echo "<div class='value'><a href = '".base_url().$par_list['uploaded']."'>".$par_list['file_name']."</a></div>";
                            }else{
                                //valido x tutti gli altri
                                echo "<div class='value'>".$par_list['value']."</div>";
                            }
                            echo "</div>";
                        } 
                    endif;          
                }
                echo "<div style='clear:both'></div>";
            }
        ?>
        
  
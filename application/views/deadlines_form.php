

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

         <!-- correzione a seconda che sia in add o in edit -->
         <?php
         if(isset($dl_id)):
            ?>
        <div id="action-list" class="clearfix">
            <span class = "main-option"><a href="<?php echo site_url('Deadlines/deadlines_single_view').'?cl='.$cl_id.'&dl='.$dl_id;?>"><i class="fas fa-angle-left"></i> INDIETRO</a></span>
        </div>
        <?php
        else:
            ?>
        <div id="action-list" class="clearfix">
            <span class = "main-option"><a href="<?php echo site_url('Deadlines/deadlines_table').'?cl='.$cl_id;?>"><i class="fas fa-angle-left"></i> INDIETRO</a></span>
        </div>
        <?php
        endif;

        //SETTINGS X I CAMPI (lista visibili, ordine ecc)
        //lista campi da inserire per id, così è facile da frontend perchè basta vedere l'id e non serve maneggiare il model
        $fields_list = array('nome', 'surname', 'mobile', 'city', 'd_title', 'd_description', 'd_date', 'd_time');

        //FORM
        // id o classi varie da dargli se possono servire x frontend
        $formattributes = array(
            'id' => 'daeadlines_form',
            'class' => 'deadlines_form form'
        );

        //campi hidden
        if(isset($_GET['cl'])){
            $hiddenfields = array(
                'CL_ID' => $_GET['cl'],
            );
            if(isset($_GET['dl'])){
                $hiddenfields['DL_ID'] = $_GET['dl'];
            }
        } else{
            $hiddenfields = array();
        }

        // il $submit_form gli viene passato dal controller, nel caso fosse utile differenziare tra le modalità editing ed add new
        echo form_open_multipart(site_url($submit_form), $formattributes, $hiddenfields);

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
            )
        );
        // FINE SEZIONI
        
        foreach($sezioni as $sezione => $par_sez){                          // faccio ciclare le sezioni, da togliere se non voglio le sezioni
        ?>
        <div style='clear:both'></div>
        <h2><?php echo $par_sez['titolo'];?></h2> <!-- titolo sezione -->

        <?php
        
        //CICLO CAMPI
            foreach($formfields as $db_field => $par_list){
                if(in_array($par_list['name'], $par_sez['campi'])):
                // qui parte il form
            ?>
            <div class="form-group col-md-6"> <!-- classe bootstrap form-group col-md-6-->
                
                <!-- LABEL -->
                <?php 
                // array per eventuali settings
                $label_attributes = array();

                // se serve personalizzare usare questo codice con scelta tra le 2 if a seconda che si debba cambiare 1 o + campi:
                /*$list_name = array('nome1', 'nome2');
                if(in_array($par_list['name'], $list_name))
                if($par_list['name'] == 'INSERISCI_NOME' ){
                    $label_attributes = array(
                        'class' => 'MY_CLASS',
                        'style' => 'COLOR: #000'
                    );
                } else{
                    $label_attributes = array();
                }
                */
                echo form_label($par_list['label'] ,$par_list['id'], $label_attributes); 
                ?>

                <!-- CAMPO DI INPUT -->
                <?php
                // prendo in considerazione i principali campi che posso avere: input, select, textarea
                /* per la select servono delle opzioni in + nell'array, cioè 
                    'options' = array delle opzioni della select 
                    'selected' = key dell'opzione selezionata di default

                Se serve fare qualche modifica, purchè DIVERSA dall'id (se serve cambiare id farlo direttamente da model):
                    $list_name_bis = array('nome1', 'nome2');
                    if(in_array($par_list['name'], $list_name_bis))
                    if($par_list['name'] == 'INSERISCI_NOME' ){
                        $formfields[$dbfield]['PARAMETRO_DA_CAMBIARE'] = VALORE
                    } 
                */
                    unset($par_list['label']);
                    switch($par_list['f_type']){
                        case 'input':
                            echo form_input($par_list);
                        break;
                        case 'select':
                            $name = $par_list['name'];
                            unset($par_list['name']);
                            echo form_dropdown($name, $par_list['options'], $par_list['selected'], $par_list);
                        break;
                        case 'textarea':
                            echo form_textarea($par_list);
                        break;
                        case 'upload':
                            if(isset($par_list['uploaded'])){       // se ho già un file caricato pesco il path da 'uploaded e il nome dal file_name
                                
                                echo '<div class ="form-control" ><a href = "'.base_url().$par_list['uploaded'].'">'.$par_list['file_name'].'</a></div>';
                            }
                            echo form_upload($par_list);
                        break;
                        default:
                        echo form_input($par_list);
                       
                    }
                ?>
            
            </div> <!-- end from group -->
            <?php
            endif;
            }
        // FINE CICLO CAMPI
        }

        // costruisco array x bottone di submit
        $data_submit = array(
            'type' => 'submit',
            'name' => 'submit',
            'id' => 'general_submit',
            'value' => 'Salva',
            'class' => 'btn btn-primary', //messa x bootstrap
        );
        echo "<div style='clear:both'></div>";
        
        echo form_submit($data_submit); //bottone di submit
        echo form_close(); 
        ?>
        
  
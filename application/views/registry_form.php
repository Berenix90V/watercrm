        <!-- TITOLO -->
        <!-- pesca da una variabile che gli viene passata in controller a seconda del metodo che sia in add o editing -->
        <div class = "section-header">
        <h1><?php echo (isset($card_title))? $card_title: 'Aggiungi nuovo cliente';?></h1>  <!-- bootstrap class: card-header text-primary -->
        </div> <!-- fine header -->

        <!-- PESCO ID DEL CLIENTE -->
        <?php
        $cl_id='';
        if(isset($_GET['cl'])){
            $cl_id = $_GET['cl'];
        } 
        //funzione per il bottone indietro
        $backUrl = $cl_id != '' ? site_url('Registry/registry_single_view').'?cl='.$cl_id : site_url('Home/registry_table');
        //se sono in modifica
        //se sono in aggiungi nuovo
        ?>

        <!-- BOTTONE X AGGIUNGI NUOVO E BOTTONI X LISTA CLIENTI, APPUNTAMENTI E SCADENZE -->
        <div id="action-list" class="clearfix">
            <span class = "main-option"><a href="<?php echo $backUrl;?>"><i class="fas fa-angle-left"></i> INDIETRO</a></span>  

        <!-- se è settato in modifica metto il bottone 'elimina cliente' -->
            <?php
            if($cl_id != ''){
                ?>
                <span class = "main-option"><a href="<?php echo site_url('Registry/registry_delete').'?cl='.$cl_id; ?>"><i class = "fas fa-trash-alt"></i> ELIMINA CLIENTE</a></span>
                <?php
            }
            ?>
            
        </div>
        
        
        <?php

        //SETTINGS X I CAMPI (lista visibili, ordine ecc)
        //lista campi da inserire per id, così è facile da frontend perchè basta vedere l'id e non serve maneggiare il model
        $fields_list = array('nome', 'surname', 'mobile', 'address', 'city');

        //FORM
        // id o classi varie da dargli se possono servire x frontend
        $formattributes = array(
            'id' => 'clients_form',
            'class' => 'clients_form form'
        );
        //campi hidden
        if(isset($_GET['cl'])){
            $hiddenfields = array(
                'CL_ID' => $_GET['cl'],
            );
        } else{
            $hiddenfields = array();
        }
        // il $submit_form gli viene passato dal controller, x differenziare tra le modalità editing ed add new
        echo form_open(site_url($submit_form), $formattributes, $hiddenfields);

        //CICLO CAMPI
            foreach($formfields as $db_field => $par_list){
                //da qui filtro in base a cosa ho messo nell'array $fields_list
                if(in_array($par_list['id'], $fields_list)):
                    
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
                        default:
                        echo form_input($par_list);
                       
                    }
                ?>
            
            </div> <!-- end from group -->
            <?php
            endif;
            }
        // FINE CICLO CAMPI

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
        

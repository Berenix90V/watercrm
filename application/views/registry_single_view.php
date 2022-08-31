        <!-- TITOLO -->
        <!-- pesca da una variabile che gli viene passata in controller a seconda del metodo che sia in add o editing -->
        <div class = "section-header">
        <h1><?php echo (isset($card_title))? $card_title: 'Aggiungi nuovo cliente';?></h1>  <!-- bootstrap class: card-header text-primary -->
        </div> <!-- fine header -->

        <!-- PESCO ID DEL CLIENTE -->
        <?php
        if(isset($_GET['cl'])){
            $cl_id = $_GET['cl'];
        }      
        ?>

        <!-- BOTTONE X AGGIUNGI NUOVO E BOTTONI X LISTA CLIENTI, APPUNTAMENTI E SCADENZE -->
        <div id="action-list" class="clearfix">
        <span class = "main-option"><a href="<?php echo site_url('Home/registry_table'); ?>"><i class = "fas fa-users"></i> CLIENTI</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Home/deadlines_table'); ?>"><i class = "fas fa-hourglass-end"></i> SCADENZE</a></span>      
        <!-- BOTTONI DI MODIFICA ED ELIMINA -->
            <span class = "main-option"><a href="<?php echo site_url('Registry/registry_edit_form').'?cl='.$cl_id; ?>"><i class = "fas fa-edit"></i> MODIFICA</a></span>
            <span class = "main-option"><a href="<?php echo site_url('Registry/registry_delete').'?cl='.$cl_id; ?>"><i class = "fas fa-trash-alt"></i> ELIMINA</a></span>
        
            
        </div>
        
        
        <?php

        //SETTINGS X I CAMPI (lista visibili, ordine ecc)
        //lista campi da inserire per id, così è facile da frontend perchè basta vedere l'id e non serve maneggiare il model
        $fields_list = array('nome', 'surname', 'address', 'city', 'mobile');

        //FORM
       

        //CICLO CAMPI
            foreach($formfields as $db_field => $par_list){
                //da qui filtro in base a cosa ho messo nell'array $fields_list
                if(in_array($par_list['id'], $fields_list)){
                    echo "<div class='single-data'>";
                    // qui x modificare visualizzazione dati
                    echo "<div class='label'>".$par_list['label']."</div>";
                    echo "<div class='value'>".$par_list['value']."</div>";
                    echo "</div>";
                }  
            
            }
        // FINE CICLO CAMPI
        /*
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
        */
        ?>
        

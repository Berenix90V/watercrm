
        <style>
            @page {
                margin:1cm;
            }
            body {
                font-family: 'Sans-serif';
            }
            h1 {
                font-size:30px;
                font-weight:600;
                color:#1E5DC7;
                border-bottom:2px solid #1E5DC7;
                opacity:0.8;
                -webkit-opacity:0.8;
                -moz-opacity:0.8;
                -ms-opacity:0.8;
                -o-opacity:0.8;
            }

            header {
                position:fixed;
                top:0;
                left:0;
                right:0;
                height:80px;
                opacity:0.5;
                -webkit-opacity:0.5;
                -moz-opacity:0.5;
                -ms-opacity:0.5;
                -o-opacity:0.5;
            }

            #table-responsive {
                margin-top:50px!important;
                width:100%;
            }

            header #left-section {
                float:left;
            }

            header #right-section {
                float:right;
            }

            input[type=checkbox] {
                vertical-align:middle;
                margin:0!important;
                padding:0!important;
                display:inline;
                font-size:20px;
            }

            .single-data {
               display:inline-block;
               width:50%;
            }
            table {
                width:100%;
                border:none!important;
            }

            table tr, table td, table th {
                border:none!important;
            }

            table td {
                padding:5px;
            }

            table tr:nth-child(2n+1) td {
                background-color:#f4f4f4;
                
            }

            table tr:nth-child(2n) td {
                background-color:#FFF;
            }

            table tr {
                padding:5px!important;
            }
            table td.strong {
                font-weight:600;
                text-transform:uppercase;
                font-size:12px;
                width:50%;
                border:none!important;
            }

            table td.data {
                text-align:right;
                font-size:12px;
                border:none!important;
            }

            #typology {
                width:40%;
                padding:10px;
                float:left;
            }

            #client-data {
                width: 40%;
                padding:10px;
                float:right;
                border:1px solid #bbb;
            }

            #client-data h2 {
                opacity:0.7;
                margin-bottom:10px;
            }

            clear {
                clear:both;
                display:block;
            }

            h2 {
                margin:0;
                padding:0;
                font-size:16px;
            }

            #deadline-container, .description-container {
                border:1px solid #bbb;
                padding:20px;
                margin-top:20px;
            }

            #deadline-container h2 {
                margin-bottom:20px;
            }

            #first-section {
                margin-bottom:20px;
            }

            #inner-description {
                height:180px;
            }
        </style>
        <header>
            <div id="left-section">
                DATA DI STAMPA <strong><?php echo date('d/m/Y'); ?></strong>
            </div>
            <div id="right-section">
                SCADENZA DEL <strong><?php echo $formfields['DL_date']['value'];?></strong>
            </div>
        </header>
        <footer>
        </footer>
        <!-- TITOLO -->
        <!-- pesca da una variabile che gli viene passata in controller a seconda del metodo che sia in add o editing -->
        <div class = "section-header">
            <h1>RAPPORTINO INTERVENTO</h1>  <!-- bootstrap class: card-header text-primary -->
        </div> <!-- fine header -->
        <!-- anagrafica del cliente -->
        <!-- definizione della tipologia di intervento -->
        <div id="first-section">
        <div id="typology">
            <img src="<?php echo FCPATH;?>assets/images/igwou.png" style="width:80%">
        </div>

        <div id="client-data">
            <h2>ANAGRAFICA CLIENTE</h2>
            <table>
            <?php
            //prendo tutti gli elementi di formfield con tag CL_
            foreach ($formfields as $key => $value){
                
                
                if (strpos($key, 'CL_') !== false) {
            ?>
                <tr>
                    <td class="strong"><?= $value['label'];?></td><td class="data"> <?= $value['value'];?></td>
                </tr>
            <?php
                }
            }
            ?>
            </table>
               
            
        </div>
        <div style="clear:both!important"></div>
        </div><!-- ./#first-section-->
        
        
        <div id="deadline-container">
        <h2>DATI SCADENZA</h2>
            <table>
                <tr>
                    <td class="strong">Titolo</td><td class="data"> <?= $formfields['DL_title']['value'];?></td>
                </tr>
                <tr>
                    <td class="strong">Dettagli</td><td class="data"> <?= $formfields['DL_description']['value'];?></td>
                </tr>
            </table>
        </div>

        <div class="description-container">
        <h2>DESCRIZIONE INTERVENTO</h2>
            <div id="inner-description"></div>
        </div>

        <div class="description-container">
        <h2>PRIVACY</h2>
        <input type="checkbox"> Autorizzo il trattamento dei dati personali contenuti nel presente documento in base all’art. 13 del D. Lgs. 196/2003 e all’art. 13 del Regolamento UE 2016/679 relativo alla protezione delle persone fisiche con riguardo al trattamento dei dati personali.
        </div>

        <div class="description-container">
            <div style="float:left; width:300px">
                <label>FIRMA ADDETTO</label><br><br>
                _______________________________
            </div>
            <div style="float:right; width:400px; text-align:right">
            <label>FIRMA CLIENTE PER ACCETTAZIONE</label><br><br>
                _________________________________
            </div>
        </div>
        
        
<!-- TITOLO -->
<div class = "section-header">
    <h1>Trova cliente</h1>
</div> <!-- fine header -->
<?php menu_generator();?>
<div id="qr-Coder">
    <?php
    //di seguito il div per selezionare la telecamera
    ?>
    <div class="select-camera">
    </div>
    <?php
    //di seguito il blocco dove viene riversato il video per caricare il QRCode
    ?>
    <div class="videocamera">
        <video id="preview"></video>
    </div>
</div>
<?php
//codice che a riportato all'interno del codice js
//nella relativa sezione
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/instascan.min.js"></script>
<script>
        //instanzio la classe per la l'utilizzo della webcam
        let scanner = new Instascan.Scanner(
            {
                video: document.getElementById('preview'),
                mirror: false
            }
        );
        //scanner che verifica la presenza di un qrcode
        scanner.addListener('scan', function(content) {
            let accept = confirm('Rilevato il seguente link: ' + content + ', continuare?');
            if (accept == true) {
                location.href=content;
            }
        });
        //reperisce l'elenco delle telecamere e le elenca nel sistema
        let listOfCams = Instascan.Camera.getCameras();
        listOfCams.then((cams) => {
            //reperiso il numero di telecamere del dispositivo
            let nCams = cams.length;
            for (var i = 0; i < nCams; i++) {
                $('.select-camera').append('<div class="choose-camera" data-camera="'+i+'">'+cams[i].name+'</div>');
            } 
            
            //ora vedo cosa succede in funzione di quello che clicco
            $('.choose-camera').on('click', function() {
                let = camID = $(this).attr('data-camera');
                scanner.start(cams[camID]);
                console.log(cams[camID]);
            });
        });
        
        
</script>
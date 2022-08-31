<script>
var base_url = '<?php echo base_url();?>';
var site_url = '<?php echo site_url();?>';
</script>

<!-- CARICO SCRIPT JS VARI DIVERSI DA GROCERY -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>    <!-- jquery -->                      <!-- main, file custom -->                       
<script src="<?php echo base_url(); ?>assets/js/popper.js"></script>                        <!-- popper, libreria collegata a bootstrap va caricata prima sennò dà errore -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>                 <!-- bootstrap -->
<script src="<?php echo base_url(); ?>assets/DataTables/datatables.min.js"></script>        <!-- datatables -->
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/alt-string.js"></script> <!--plugin x custom sorting -->

<script src = "<?php echo base_url(); ?>assets/js/main.js"></script>  
<script src = "<?php echo base_url(); ?>assets/js/advanced-scripts.js"></script>  

<script type="text/javascript">
$('.status_order').dataTable( {
    columnDefs: [
      { type: 'alt-string', targets: 0 }
    ]
});
$(document).ready(function() {
    $('.datatable').DataTable();
});

</script>
<script src="<?php echo base_url().'assets/js/PWA/app.js';?>"></script>
</body>
</html>
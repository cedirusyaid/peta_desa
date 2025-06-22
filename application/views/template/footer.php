
            </div>


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url('assets/sb_admin/') ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/sb_admin/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url('assets/sb_admin/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url('assets/sb_admin/') ?>js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo base_url('assets/sb_admin/') ?>vendor/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url('assets/sb_admin/') ?>vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo base_url('assets/sb_admin/') ?>js/demo/datatables-demo.js"></script>
    <script src="<?php echo base_url('assets/sb_admin/') ?>vendor/chart.js/Chart.min.js"></script>


    <!-- datatable -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap4.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap4.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.colVis.min.js"></script>

<script>
    const base_url = "<?php echo base_url(); ?>";
</script>
<script src="<?php echo base_url('assets/js/myscript.js') ?>"></script>

<?php
// if (isset($map)) {echo $map['js'];}
?>
<!-- 
<script>
$(document).ready(function() {
    // Track visit via AJAX
    $.ajax({
        url: '<?php echo site_url("counter/track_visit?url_awal=".$this->input->get('REQUEST_URI')); ?>',
        type: 'GET',
        dataType: 'text',
        success: function(response) {
            if (response === 'SKIP_COUNTER_PAGE') {
                console.log('Tracking skipped for counter page');
            }
        }
    });
});
</script>
 -->


<!-- counter -->
<?php 
    // $hidden_counter = isset($hidden_counter) ? $hidden_counter : false;
    // if ($hidden_counter==false) {
    //     $this->Counter_model->track_visit();
    // }

?>

</body>

</html>
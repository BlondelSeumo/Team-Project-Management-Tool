<script>
base_url = "<?=base_url();?>";
date_format_js = "<?=system_date_format_js();?>";
time_format_js = "<?=system_time_format_js();?>";
</script>

<!-- General JS Scripts -->
<script src="<?=base_url('assets/modules/jquery.min.js')?>"></script>
<script src="<?=base_url('assets/modules/popper.js')?>"></script>
<script src="<?=base_url('assets/modules/tooltip.js')?>"></script>
<script src="<?=base_url('assets/modules/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?=base_url('assets/modules/nicescroll/jquery.nicescroll.min.js')?>"></script>
<script src="<?=base_url('assets/modules/moment.min.js')?>"></script>
<script src="<?=base_url('assets/js/stisla.js')?>"></script>
 
<!-- JS Libraies -->
<script src="<?=base_url('assets/modules/bootstrap-daterangepicker/daterangepicker.js')?>"></script>
<script src="<?=base_url('assets/modules/chart.min.js')?>"></script>
<script src="<?=base_url('assets/modules/select2/dist/js/select2.full.min.js')?>"></script>
<script src="<?=base_url('assets/modules/bootstrap-table/bootstrap-table.min.js');?>"></script>
<script src="<?=base_url('assets/modules/bootstrap-table/bootstrap-table-mobile.js');?>"></script>
<script src="<?=base_url('assets/modules/izitoast/js/iziToast.min.js');?>"></script>
<script src="<?=base_url('assets/modules/sweetalert/sweetalert.min.js');?>"></script>
<script src="<?=base_url('assets/modules/dropzonejs/min/dropzone.min.js');?>"></script>

<!-- Template JS File -->
<script src="<?=base_url('assets/js/scripts.js')?>"></script>
<script src="<?=base_url('assets/js/custom.js')?>"></script>

<?php if($this->session->flashdata('message') && $this->session->flashdata('message_type') == 'success'){ ?>
  <script>
  iziToast.success({
    title: "<?=$this->session->flashdata('message');?>",
    message: "",
    position: 'topRight'
  });
  </script>
<?php } ?>
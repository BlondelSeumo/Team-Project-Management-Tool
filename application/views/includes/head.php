  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?=htmlspecialchars($page_title)?></title>

  <link rel="shortcut icon" href="<?=base_url('assets/uploads/logos/'.favicon())?>">
 
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?=base_url('assets/modules/bootstrap/css/bootstrap.min.css')?>">
  <link rel="stylesheet" href="<?=base_url('assets/modules/fontawesome/css/all.min.css')?>">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?=base_url('assets/modules/bootstrap-daterangepicker/daterangepicker.css')?>">
  <link rel="stylesheet" href="<?=base_url('assets/modules/select2/dist/css/select2.min.css')?>">
  <link rel="stylesheet" href="<?=base_url('assets/modules/bootstrap-table/bootstrap-table.min.css');?>">
  <link rel="stylesheet" href="<?=base_url('assets/modules/izitoast/css/iziToast.min.css');?>">
  <link rel="stylesheet" href="<?=base_url('assets/modules/dropzonejs/dropzone.css');?>">
  <link rel="stylesheet" href="<?=base_url('assets/modules/dragula/dragula.min.css');?>" >

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?=base_url('assets/css/style.css')?>">
  <link rel="stylesheet" href="<?=base_url('assets/css/components.css')?>">
  <link rel="stylesheet" href="<?=base_url('assets/css/custom.css')?>">
  
<?php $google_analytics = google_analytics(); if($google_analytics){ ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?=htmlspecialchars($google_analytics)?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?=htmlspecialchars($google_analytics)?>');
  </script>
<?php } ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=isset($judul)?$judul:(isset($title)?$title:NAMA_APP); ?> </title>

    
    <!-- Custom fonts for this template -->
    <link href="<?php echo base_url('assets/sb_admin/') ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('assets/sb_admin/') ?>css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo base_url('assets/sb_admin/') ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- export datatable -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <!-- end export datatable -->

        <style type="text/css" media="screen">
     #peta {
            float: top;
            margin: 10px;
            /*          background-color: grey;*/
            margin: 0px;
            left: 0;
            width: 100%;
            height: auto;
            border: 0px solid #808080;
            margin-left: auto;
            margin-right: auto;
            min-height: 600px;          }
            #peta a {
            color: maroon;
            text-decoration: none;

            }           
            #peta p {    font-size: small;
            }

        </style>




</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->

    
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
			     
			                <!-- End of Topbar -->
			    <?php if ($this->session->flashdata('message')) : ?>
			        <div class="alert alert-success alert-dismissible fade show" role="alert">
			            <?= $this->session->flashdata('message') ?>
			            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			                <span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			    <?php endif; ?>

			    <?php if ($this->session->flashdata('success')): 
			            // $this->Telegram_model->send_alert('success', $this->session->flashdata('success'));
			        ?>
			        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
			    <?php endif; ?>
			    
			    <?php if ($this->session->flashdata('error')): 
			            // $this->Telegram_model->send_alert('error', $this->session->flashdata('error'));
			        ?>
			        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
			    <?php endif; ?>


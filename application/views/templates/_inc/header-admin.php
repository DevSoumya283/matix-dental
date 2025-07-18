<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, height=device-height">
        <title></title>
        <meta name="description" content="">
        <!-- Paths -->
        <?php $this->load->view('templates/_inc/icons.php'); ?>
        <!-- build:css css/main.min.css -->
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" type="text/css">
        <!-- endbuild -->
        <!-- Libraries -->
        <link href="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>lib/animate-css/animate.css " rel="stylesheet" type="text/css">
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <style>
            @media screen and (max-width: 700px) {
                .sidebar {
                    width: auto !important;
                }
                .modal--m .modal__wrapper {
                    width: 90vw;
                }
                .footer.footer__bottom{
                    height: 65px;
                }
            }
        </style>
 
    </head>
    <body>
        <header class="header header__main">
            <!-- Top Navigation -->
            <!-- NOTE: This block is only displayed if a user is logged in -->
            <nav class="nav nav__top" >
                <div class="wrapper wrapper--fixed">
                    <div class="wrapper__inner">
                        <!-- User Info -->
                        <ul class="list list--inline list--reversed list--divided fontSize--s fontWeight--2">
                            <li class="item">Welcome, <?php echo $_SESSION['user_name']; ?></li>
                            <!--li class="item"><a href="<?php echo base_url(); ?>templates/inbox">Inbox (0)</a></li-->
                        </ul>
                    </div>
                    <div class="nav-right" style="float: right;margin-top:5px">
                        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
                    </div>
                </div>
            </nav>
            <!-- /Top Navigation -->
            <!-- Main Navigation -->
            <nav class="nav nav__main no--pad-t">
                <div class="wrapper wrapper--fixed">
                  <div class="wrapper__inner">
                            <a class="logo logo__main" href="<?php echo base_url(); ?>home"><!-- templates/browse -->
                                <img src="<?php echo base_url() . 'assets/img/logos/' . $this->config->item('logo'); ?>" style="width: 140px;margin-top: 10px;" alt=""/>
                            </a>
                    </div>
                    <div class="wrapper__inner align--right">
                        <div id="accountDropdown" class="link link--dropdown fontSize--l">
                            My Account
                            <div class="popover fontSize--m" style="min-width:176px;">
                                
                                <div class="popover__bottom fontSize--s">
                                    Not <?php echo $_SESSION['user_name']; ?>?
                                    <a class="link" href="<?php echo base_url(); ?>user-logOut">Sign Out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- /Main Navigation -->
        </header>
        <?php if ($this->session->flashdata('success') != "") { ?>
            <div class="banner is--pos">
                <span class="banner__text">
                    <?php echo $this->session->flashdata('success') ?>
                </span>
                <a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a>
            </div>
            <br />
        <?php } ?>
        <?php if ($this->session->flashdata('error') != "") { ?>
            <div class="banner is--neg">
                <span class="banner__text">
                    <?php echo $this->session->flashdata('error') ?>
                </span>
                <a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a>
            </div>
            <br />
        <?php } ?>
        <div id="sidebarOverlay" onclick="toggleSidebar()"></div>

<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.toggle('sidebar--open');
        overlay.classList.toggle('active');
    }
</script>
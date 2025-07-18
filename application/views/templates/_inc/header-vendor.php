<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, height=device-height">
        <title><?php echo $this->config->item('name'); ?></title>
        <?php define('ROOT_PATH', '/'); ?>
        <!-- Icons -->
        <?php include(INCLUDE_PATH . '/_inc/icons.php'); ?>
        <!-- build:css css/main.min.css -->
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" type="text/css">
        <!-- custom overrides -->
        <style>
            .nav__top{ background-color: #<?php echo $this->config->item('bg-color'); ?> !important; }
            .navbar{ background-color: #<?php echo $this->config->item('bg-color'); ?> !important; }
            .btn--primary{ background-color: #<?php echo $this->config->item('btn-color-2'); ?> !important; }
            .btn.is--pos{ background-color: #<?php echo $this->config->item('btn-color-1'); ?> !important; }
                
            /* Flex container for top nav */
            .nav-top-flex {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            /* Sidebar toggle button */
            .sidebar-toggle {
                background: #2893ff;
                color: white;
                border: none;
                padding: 6px 12px;
                font-size: 16px;
                border-radius: 4px;
                cursor: pointer;
                display: none; /* hidden by default */
            }

            /* Mobile styles */
            @media (max-width: 991px) {
                .sidebar {
                    position: fixed;
                    z-index: 999;
                    top: 0;
                    left: 0;
                    height: 100%;
                    width: 260px;
                    background: #fff;
                    transform: translateX(-100%);
                    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
                    overflow-y: auto;
                    transition: transform 0.3s ease;
                }

                .sidebar.sidebar--open {
                    transform: translateX(0);
                }

                .sidebar-toggle {
                    display: inline-block;
                }

                #sidebarOverlay {
                    display: none;
                    position: fixed;
                    top: 0; left: 0;
                    width: 100vw;
                    height: 100vh;
                    background-color: rgba(0, 0, 0, 0.4);
                    z-index: 998;
                }

                #sidebarOverlay.active {
                    display: block;
                }
            }

            @media (min-width: 992px) {
                .sidebar-toggle {
                    display: none;
                }

                #sidebarOverlay {
                    display: none !important;
                }
            }
            @media screen and (max-width: 700px) {
            .row {
                margin-left: 0px !important;
            }

            .row.row--full-height .col {
                height: 100%;
                width: 100%;
            }

            .content__wrapper .sidebar,
            .content__wrapper .content {
                padding-top: 56px;
                padding-bottom: 72px;

            }
            .col--push-1-of-12 {
                margin-left: 0%;
            }

            .col {
                margin-right: 0em;
                padding-left: 0px;
            }

            .footer.footer__bottom{
                height: 65px;
            }
            .sidebar {
                width: auto !important;
            }

            .table-scroll{
                overflow: hidden;
                overflow-x: scroll;
            }

            .content__block{
                margin-left: 10px;
            }
        }

        </style>
        <!-- endbuild -->
        <!-- Libraries -->
        <link href="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>lib/animate-css/animate.css" rel="stylesheet" type="text/css">
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    </head>
    <body>
        <header class="header header__main">
            <!-- Top Navigation -->
            <!-- NOTE: This block is only displayed if a user is logged in -->
           <nav class="nav nav__top">
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
                        <?php if (isset($_SESSION["role_id"]) && ($_SESSION["role_id"] == "11")) { ?>
                            <a class="logo logo__main" href="<?php echo base_url(); ?>home"><!-- templates/browse -->
                                <img src="<?php echo base_url() . 'assets/img/logos/' . $this->config->item('logo'); ?>" style="width: 140px;margin-top: 10px;" alt=""/>
                            </a>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="wrapper__inner align--right">
                        <div id="accountDropdown" class="link link--dropdown fontSize--l">
                            My Account
                            <div class="popover fontSize--m" style="min-width:176px;">
                                <div class="popover__inner">
                                    <ul class="list">
                                        <li>
                                            <a class="link" href="<?php echo base_url(); ?>vendor-dashboard">Dashboard</a>
                                        </li>
                                    </ul>
                                </div>
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

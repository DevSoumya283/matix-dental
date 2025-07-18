<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <meta name="description" content="">

        <?php define('ROOT_PATH', '/'); ?>

        <!-- Icons -->
        <?php include(INCLUDE_PATH . '/_inc/icons.php'); ?>

        <!-- build:css css/main.min.css -->
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" type="text/css">
        <!-- endbuild -->

        <!-- Libraries -->
        <link href="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>lib/animate-css/animate.css" rel="stylesheet" type="text/css">

    </head>

    <body class="login bg--lightest-gray">

        <!-- Content Section -->
        <section class="panel__wrapper wrapper">
            <div class="wrapper__inner align--center">
                <a class="logo logo__main" href="<?php echo base_url(); ?>">
                    <img src="<?php echo ROOT_PATH; ?>assets/img/logo-matix.svg" alt=""/>
                </a>
                <div class="panel panel--s">

                    <!-- Create New Password -->
                    <div id="tabPassword" class="panel__tab">
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
                        <h3 class="align--center textColor--dark-gray">Set a New Password</h3>
                        <form id="formRegister" class="form__group margin--l no--margin-lr no--margin-b" action="<?php echo base_url(); ?>change-user-password" method="post">
                            <input type="hidden" name="id" value="<?php echo $user_token->id; ?>">
                            <input type="hidden" name="reset_token" value="<?php echo $user_token->reset_password_token; ?>">
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="password" name="password" class="input" type="password" placeholder="At least 6 characters" minlength="6" required>
                                    <label class="label" for="password">Password</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="passwordAgain" name="passwordAgain" class="input" type="password" placeholder="Type it again" required>
                                    <label class="label" for="passwordAgain">Confirm Password</label>
                                </div>
                            </div>
                            <div class="row">
                                <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#formRegister">Save Password</button>
                            </div>
                            <div class="row border border--t border--1 border--dashed border--light padding--s no--pad-lr no--pad-b margin--s no--margin-lr no--margin-b">
                                Don't want to do this now? <a class="link margin--xxs no--margin-b fontWeight--2" href="<?php echo ROOT_PATH . 'templates/login'; ?>">Cancel</a>
                            </div>
                        </form>
                    </div>
                    <!-- /Create New Password -->

                </div>
            </div>
        </section>
        <!-- /Content Section -->

        <footer>&copy; Copyright <?php echo date("Y"); ?>, Dentomatix, LLC</footer>

        <!-- Scripts & Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.detect-card.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
        <script src="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <script src="<?php echo base_url(); ?>lib/jquery-validate/jquery.validate.min.js"></script>

        <!-- build:js js/main.min.js -->
        <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/awesome.min.js"></script>
        <!-- endbuild -->

    </body>

</html>

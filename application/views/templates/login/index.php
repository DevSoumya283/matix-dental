<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <meta name="description" content="">
        <!-- Icons -->
        <?php include(INCLUDE_PATH . '/_inc/icons.php'); ?>
        <!-- build:css css/main.min.css -->
        <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/main.css">
        <!-- endbuild -->
        <!-- Libraries -->
        <link href="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>lib/animate-css/animate.css" rel="stylesheet" type="text/css">
        <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/responsive.css">
    </head>
    <body class="login bg--lightest-gray">
        <!-- Content Section -->
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
        <section class="panel__wrapper wrapper">
            <div class="wrapper__inner align--center">
                <a class="logo logo__main" href="<?php echo base_url('browse'); ?>">
                    <img src="<?php echo base_url() . 'assets/img/logos/' . $this->config->item('logo'); ?>" style="width: 300px;" alt=""/>
                </a>
                <div class="panel panel--s">
                    <!-- Sign In -->
                    <div id="tabLogin" class="panel__tab is--visible">
                        <h3 class="align--center textColor--dark-gray">Sign In</h3>
                        <form id="formLogin" class="form__group margin--l no--margin-lr" action="<?php echo base_url(); ?>user-login" method="post">
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="accountEmail" name="accountEmail" class="input" type="email" placeholder="k.mac@company.com" pattern=.*\S.* required>
                                    <label class="label" for="accountEmail">Email Address</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="accountPW" name="accountPW" class="input" type="password" placeholder="Shhh... it's a secret!" required>
                                    <label class="label" for="accountPW">Password</label>
                                </div>
                            </div>
                            <div class="row">
                                <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#formLogin" type="submit">Sign In</button>
                                <a class="link margin--xs no--margin-lr no--margin-b fontWeight--2 fontSize--s panel--toggle" data-target="#tabForgot">Forgot Password?</a>
                            </div>
                        </form>
                        <div class="row border border--t border--1 border--dashed border--light padding--s no--pad-lr no--pad-b margin--s no--margin-lr no--margin-b">
                            <button class="btn btn--s btn--tertiary btn--block panel--toggle" data-target="#tabRegister">Create an Account</button>
                        </div>
                    </div>
                    <!-- /Sign In -->
                    <!-- Create Account -->
                    <div id="tabRegister" class="panel__tab is--hidden">
                        <h3 class="align--center textColor--dark-gray">Sign Up</h3>
                        <form id="formRegister" class="form__group margin--l no--margin-lr no--margin-b" action="<?php echo base_url('complete-registration'); ?>" method="post">
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="accountNewEmail" name="accountNewEmail" class="input" type="email" placeholder="k.mac@company.com" pattern=.*\S.* required>
                                    <label class="label" for="accountNewEmail">Email Address</label>
                                </div>
                            </div>
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
                                <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#formRegister" type="submit">Create Account</button>
                            </div>
                            <div class="row border border--t border--1 border--dashed border--light padding--s no--pad-lr no--pad-b margin--s no--margin-lr no--margin-b">
                                Already have an account? <a class="link margin--xxs no--margin-b fontWeight--2 panel--toggle" data-target="#tabLogin">Sign In</a>
                            </div>
                        </form>
                    </div>
                    <!-- /Create Account -->
                    <!-- Forgot Password -->
                    <div id="tabForgot" class="panel__tab is--hidden">
                        <h3 class="align--center textColor--dark-gray">Forgot Password?</h3>
                        <form id="formForgot" class="form__group margin--l no--margin-lr no--margin-b" action="<?php echo base_url(); ?>forgot-password" method="post">
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="accountForgotEmail" name="accountForgotEmail" class="input" type="email" placeholder="k.mac@company.com" pattern=.*\S.* required>
                                    <label class="label" for="accountForgotEmail">Email Address</label>
                                </div>
                            </div>
                            <div class="row">
                                <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#formForgot" type="submit">Reset Password</button>
                            </div>
                            <div class="row border border--t border--1 border--dashed border--light padding--s no--pad-lr no--pad-b margin--s no--margin-lr no--margin-b">
                                <a class="link margin--xxs no--margin-b fontWeight--2 panel--toggle" data-target="#tabLogin">Go Back</a>
                            </div>
                        </form>
                    </div>
                    <!-- /Forgot Password -->
                </div>
            </div>
        </section>
        <!-- /Content Section -->
        <footer>&copy; Copyright <?php echo date("Y"); ?>, Dentomatix, LLC</footer>
        <!-- Scripts & Libraries -->
<!--        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>-->
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.detect-card.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
        <script src="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <script src="<?php echo base_url(); ?>lib/jquery-validate/jquery.validate.min.js"></script>
        <!-- build:js js/main.min.js -->
        <script type="text/javascript">
            var base_url = "<?php echo base_url(); ?>";
            var image_url = "<?php echo image_url(); ?>";
        </script>
        <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/awesome.min.js"></script>
        <!-- endbuild -->
    </body>
</html>
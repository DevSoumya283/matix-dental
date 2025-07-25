<!-- Bottom Footer -->
<footer class="footer footer__bottom">
    <ul class="list list--inline list--divided">
        <li class="item colophon">
            &copy; Copyright <?php echo date("Y"); ?>, Dentomatix, LLC
        </li>
		<li class="item item__icon">
            <a class="link link--icon textColor--white" href="#"><svg class="icon icon--facebook"><use xlink:href="#icon-facebook"></use></svg></a>
        </li>
        <li class="item item__icon">
            <a class="link link--icon textColor--white" href="#"><svg class="icon icon--twitter"><use xlink:href="#icon-twitter"></use></svg></a>
        </li>
        <li class="item item__icon">
            <a class="link link--icon textColor--white" href="#"><svg class="icon icon--google-plus"><use xlink:href="#icon-google-plus"></use></svg></a>
        </li>
        <li class="item item__icon">
            <a class="link link--icon textColor--white" href="#"><svg class="icon icon--rss"><use xlink:href="#icon-rss"></use></svg></a>
        </li>
    </ul>
</footer>
<!-- /Bottom Footer -->

<!-- Vendor Modals -->
<?php  $this->load->view('templates/_inc/shared/modals/confirm-qty-change.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/cancel-order.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/accept-request.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/deny-request.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/create-new-product.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/create-new-code.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/edit-existing-code.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/delete-confirmation.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/create-shipping-method.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/edit-shipping-method.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/bulk-edit-pricing.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/invite-new-user.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/edit-user.php'); ?>
<?php  $this->load->view('templates/_inc/shared/modals/delete-user.php'); ?>

<!-- Universal Modals -->
<?php  $this->load->view('templates/_inc/shared/modals/help-shopping-mode.php'); ?>

<!-- Scripts & Libraries -->
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>-->
<!--payment-->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://cdn.plaid.com/link/stable/link-initialize.js"></script>
<!--payment-->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.detect-card.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
<script src="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>lib/bootstrap-timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-maskmoney/jquery.maskMoney.min.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-validate/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-typeahead/typeahead.bundle.min.js"></script>
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var image_url = "<?php echo image_url(); ?>";
    Stripe.setPublishableKey('<?php echo $this->config->item('stripe')['pk_'.$this->config->item('stripe')['mode']];?>');
</script>
<!-- build:js js/main.min.js -->
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<script src="<?php echo base_url(); ?>assets/js/admin-vendor.js?time=<?php echo time(); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.tabletoCSV.js?time=<?php echo time(); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/main-addition.js"></script>
<script src="<?php echo base_url(); ?>assets/js/awesome.min.js"></script>
<!-- endbuild -->

</body>

</html>

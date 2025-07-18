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
<?php include(INCLUDE_PATH.'/_inc/shared/modals/confirm-qty-change.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/cancel-order.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/accept-request.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/deny-request.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/create-new-product.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/create-new-code.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/edit-existing-code.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/delete-confirmation.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/create-shipping-method.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/edit-shipping-method.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/bulk-edit-pricing.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/invite-new-user.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/edit-user.php'); ?>
<?php include(INCLUDE_PATH.'/_inc/shared/modals/delete-user.php'); ?>


<!-- Universal Modals -->
<?php include(INCLUDE_PATH.'/_inc/shared/modals/help-shopping-mode.php'); ?>

<!-- Scripts & Libraries -->
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>-->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.detect-card.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
<script src="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>lib/bootstrap-timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-maskmoney/jquery.maskMoney.min.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-validate/jquery.validate.min.js"></script>

<!-- Froala Editor -->
<script src="<?php echo base_url(); ?>lib/froala-editor/js/froala_editor.min.js"></script>
<!-- Froala Plugins -->
<script src="<?php echo base_url(); ?>lib/froala-editor/js/plugins/lists.min.js"></script>
<script src="<?php echo base_url(); ?>lib/froala-editor/js/plugins/link.min.js"></script>

<!-- build:js js/main.min.js -->
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var image_url = "<?php echo image_url(); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<script src="<?php echo base_url(); ?>assets/js/admin-vendor.js?time=<?php echo time(); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/main-addition.js"></script>
<script src="<?php echo base_url(); ?>assets/js/awesome.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.tabletoCSV.js?time=<?php echo time(); ?>"></script>
<!-- endbuild -->

</body>

</html>

<!-- Main Footer -->
<div class="container-fluid no-padding">
<footer class="footer footer__main">
    <div class="wrapper wrapper--fixed">
        <div class="container">
        <div class="row mobile-center">

            <div class="col-md-3 col-xs-12">
             <?php  if (isset($_SESSION["user_id"])) {
                     if($_SESSION['role_id']=='3' || $_SESSION['role_id']=='4' || $_SESSION['role_id']=='5' || $_SESSION['role_id']=='6' || $_SESSION['role_id']=='7' || $_SESSION['role_id']=='8' || $_SESSION['role_id']=='9' || $_SESSION['role_id']=='10'){
                ?>
                <ul class="list list--titled list--reversed fontSize--s">
                    <li class="item fontSize--m fontWeight--2">
                        Your Account
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url('history'); ?>">Order History</a>
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url('profile'); ?>">Account Settings</a>
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url('request-lists'); ?>">Request Lists</a>
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url('shopping-lists'); ?>">Shopping Lists</a>
                    </li>
                </ul>
                <?php } } ?>
            </div>
            <div class="col-md-3 col-xs-12">
                <ul class="list list--titled list--reversed fontSize--s">
                    <li class="item fontSize--m fontWeight--2">
                        We're Here to Help
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url(); ?>returns-policy"><?php echo $this->config->item('menu-links')['returns']; ?></a>
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url(); ?>shipping-policy"><?php echo $this->config->item('menu-links')['shipping']; ?></a>
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url(); ?>faq"><?php echo $this->config->item('menu-links')['faq']; ?></a>
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url('contact'); ?>"><?php echo $this->config->item('menu-links')['contact']; ?></a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-xs-12">
                <ul class="list list--titled list--reversed fontSize--s">
                    <li class="item fontSize--m fontWeight--2">
                        Learn About Us
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url(); ?>how-it-works"><?php echo $this->config->item('menu-links')['about']; ?></a>
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url(); ?>mission"><?php echo $this->config->item('menu-links')['mission']; ?></a>
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url(); ?>privacy-policy"><?php echo $this->config->item('menu-links')['privacy']; ?></a>
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url(); ?>terms-conditions"><?php echo $this->config->item('menu-links')['terms']; ?></a>
                    </li>
                </ul>
            </div>
            <?php if(empty($this->config->item( 'whitelabel' ))){ ?>
            <div class="col-md-3 col-xs-12">
                <ul class="list list--titled list--reversed fontSize--s">
                    <li class="item fontSize--m fontWeight--2">
                        Vendor Relations
                    </li>
                    <li class="item">
                        <a class="link" href="<?php echo base_url('contact'); ?>">Become a Matix Vendor</a>
                    </li>
                </ul>
            </div>
        <?php } ?>
        </div>
    </div>
    </div>
</footer>
    </div>

<!-- /Main Footer -->

<!-- Bottom Footer -->
<footer class="footer footer__bottom">
    <ul class="list list--inline list--divided">
        <li class="item colophon">
            &copy; Copyright <?php echo date("Y"); ?>, Dentomatix, LLC
        </li>
        <li class="item item__icon fbicon">
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

<!-- Universal Modals -->
<?php include(INCLUDE_PATH.'/_inc/shared/modals/help-shopping-mode.php'); ?>

<!-- Scripts & Libraries -->
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>-->
<!--payment-->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://cdn.plaid.com/link/stable/link-initialize.js"></script>
<!--payment-->
<script src="<?php echo base_url(); ?>assets/js/jquery.detect-card.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
<script src="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>lib/bootstrap-timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-maskmoney/jquery.maskMoney.min.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-validate/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-flexslider2/jquery.flexslider-min.js"></script>
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var image_url = "<?php echo image_url(); ?>";
    var rate_data="";
    var purchased = "";
    var license="";
    var category="";
    var procedure = "";
    var vendor_id="";
    var manufacturer="";
    var listid="";
    var option_value="";
    var page="";
    var per_page="";
    var grid=0;
    var search="<?php echo (isset($search_term))?$search_term:""?>";
    Stripe.setPublishableKey('<?php echo $this->config->item('stripe')['pk_'.$this->config->item('stripe')['mode']];?>');

    var Config = {
        plaid: {
            env: "<?php echo $this->config->item('plaid')['env'] ?>",
            public_key: "<?php echo $this->config->item('plaid')['public-key'] ?>",
        }
    }
</script>
<!-- build:js js/main.min.js -->
<script src="<?php echo base_url(); ?>assets/js/main.js?v=<?php echo $this->config->item('jsVersion'); ?>&time=<?php echo time();?>"></script>
<script src="<?php echo base_url(); ?>assets/js/admin-vendor.js?time=<?php echo time(); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main-addition.js"></script>
<script src="<?php echo base_url(); ?>assets/js/awesome.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/search.js"></script>
<script src="<?php echo base_url(); ?>assets/js/browse-menu.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.tabletoCSV.js?time=<?php echo time(); ?>"></script>
<script src="/assets/js/sortable.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

<!-- endbuild -->
</body>
</html>

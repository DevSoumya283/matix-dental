<?php include(INCLUDE_PATH . '/_inc/header-' . $userType . '.php'); ?>
<script src="/assets/js/jscolor.js"></script>
<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php //include(INCLUDE_PATH . '/' . (User_model::can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor-admin') . '/_inc/nav.php'); ?>
                    <?php
                        $folder = User_model::can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor-admin';
                        $this->load->view('templates/' . $folder . '/_inc/nav.php'); 

                    ?>
                </div>
                <!-- /Sidebar -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <div class="heading__group border--dashed">
                        <form id="addSiteForm" class="form__group" action="/white-labels/save" method="post"  enctype="multipart/form-data">
                            <div class="modal__content">
                                <input type="hidden" name="id" id="id" value="<?php echo $site->id; ?>">
                                <input type="hidden" name="whitelabel" id="whitelabel" value="1">
                                <?php if(!empty($site->logo)) { ?>
                                    <div class="row no--margin-l">
                                        <img id="logo" src="/assets/img/logos/<?php echo $site->logo; ?>" style="width: 200px; " />
                                    </div>
                                <?php } ?>
                                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                                    <input id="name" name="name" class="input<?php echo (!empty($site->name)) ? ' not--empty' : ''; ?>" type="text" value="<?php echo $site->name; ?>" required>
                                    <label class="label" for="name">Name</label>
                                </div>
                                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                                    <input id="short_name" name="short_name" class="input<?php echo (!empty($site->short_name)) ? ' not--empty' : ''; ?>" type="text"  value="<?php echo $site->short_name; ?>" required>
                                    <label class="label" for="short_name">Short Name</label>
                                </div>

                                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                                    <input id="domain" name="domain" class="input<?php echo (!empty($site->domain)) ? ' not--empty' : ''; ?>" type="text"  value="<?php echo $site->domain; ?>">
                                    <label class="label" for="domain">Vanity URL</label>
                                </div>
                                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                                    <input id="bg_color" name="bg_color" class="input not--empty jscolor" type="text"  value="<?php echo ($site->bg_color) ? $site->bg_color : $this->config->item('bg-color'); ?>">
                                    <label class="label" for="domain">Header Background Color</label>
                                </div>
                                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                                    <input id="btn_color_1" name="btn_color_1" class="input not--empty jscolor" type="text"  value="<?php echo ($site->btn_color_1) ? $site->btn_color_1 : $this->config->item('btn-color-1'); ?>">
                                    <label class="label" for="domain">Button Color 1</label>
                                </div>
                                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                                    <input id="btn_color_2" name="btn_color_2" class="input not--empty jscolor" type="text"  value="<?php echo ($site->btn_color_2) ? $site->btn_color_2 : $this->config->item('btn-color-2'); ?>">
                                    <label class="label" for="domain">Button Color 2</label>
                                </div>
                                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t select">
                                    <select name="vendor_id" id="vendor_id" required>
                                        <option value="">Select Vendor</option>
                                        <?php foreach($vendors as $vendor){
                                            echo '<option value="' . $vendor->id . '" ' . (($site->vendor_id == $vendor->id) ? 'selected="selected"' : '') . '>' . $vendor->name . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="control control__checkbox">
                                        <input type="checkbox" name="limit_to_vendor_products" class="singleCheckbox" value="1" <?php if ($site->limit_to_vendor_products) { echo 'checked="checked"'; } ?> >
                                        <div class="control__indicator"></div>
                                        Limit to vendor products
                                    </label>
                                </div>
                                <!-- <div>
                                    <label class="control control__checkbox">
                                        <input type="checkbox" name="hide_selected_products_1" class="singleCheckbox" value="1" <?php if ($site->hide_selected_products_1) { echo 'checked="checked"'; } ?> >
                                        <div class="control__indicator"></div>
                                        Hide selected products 1
                                    </label>
                                </div>
                                <div>
                                    <label class="control control__checkbox">
                                        <input type="checkbox" name="hide_selected_products_2" class="singleCheckbox" value="1" <?php if ($site->hide_selected_products_2) { echo 'checked="checked"'; } ?> >
                                        <div class="control__indicator"></div>
                                        Hide selected products 2
                                    </label>
                                </div> -->
                                <div class="row no--margin-l">
                                    <h3>Upload Logo</h3>
                                    <input id="whitelabelLogo" name="whitelabelLogo" class="input input--file not--empty" type="file" >
                                </div>

                                <div class="center center--h footer__group">
                                    <input type="submit" class="btn btn--m btn--primary is--pos btn--dir is--next addWhiteLabel" value="Update Custom Site">
                                </div>
                                <br />
                                <?php foreach($pages as $page){
                                    echo '<div class="btn pageTrigger modal--toggle " data-whitelabel-id="' . $site->id . '" data-name="' . $page->name . '" data-target="#editPageModal" >' . $page->name . '</div>';
                                } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="/assets/js/whitelabels.js?v=<?php echo $this->config->item('jsVersion'); ?>"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.92/jodit.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.92/jodit.min.js"></script>

<?php //include(INCLUDE_PATH . '/_inc/shared/modals/edit-page.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/add-white-label.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-' . $userType . '.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/edit-page.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/add-white-label.php'); ?>
<?php $this->load->view('templates/_inc/footer-' . $userType . '.php'); ?>
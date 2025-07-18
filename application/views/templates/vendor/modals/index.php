<?php include(INCLUDE_PATH . '/_inc/header.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper">
        <div class="content__main">
            <div class="row">
                <!-- Content Area -->
                <div class="content col align--center">
                    <div class="row">
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#qtyChangeModal">Confirm Quantity Change</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#acceptRequestModal">Accept Request</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#denyRequestModal">Deny Request</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#createNewProductModal">Create New Product</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#createNewCodeModal">Create New Code</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#editExistingCodeModal">Edit Existing</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#deleteConfirmationModal">Delete Confirmation</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#createShippingMethodModal">Create Method</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#editShippingMethodModal">Edit Existing</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#deleteConfirmationModal">Delete Method</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#bulkEditPricingModal">Bulk Edit Pricing</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#inviteUserModal">Invite New User</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#editUserModal">Edit User</button></div>
                        <div class="col col--2-of-10 margin--s no--margin-lr no--margin-t align--center"><button class="btn btn--primary btn--m modal--toggle" data-target="#deleteUserModal">Delete User</button></div>
                    </div>
                </div>
                <!-- /Content Area -->
            </div>
        </div>
    </section>
    <!-- /Content Section -->

    <?php include(INCLUDE_PATH . '/_inc/shared/modals/confirm-qty-change.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/accept-request.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/deny-request.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/create-new-product.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/create-new-code.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/edit-existing-code.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/delete-confirmation.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/create-shipping-method.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/edit-shipping-method.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/bulk-edit-pricing.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/invite-new-user.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/edit-user.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/delete-user.php'); ?>

    <?php
    include(INCLUDE_PATH . '/_inc/footer.php');
    
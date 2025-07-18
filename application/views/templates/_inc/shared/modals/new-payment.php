<!-- New Payment Modal -->
<div id="newPaymentModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="mobile-center">Add a new payment</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <!-- Payment Method Form -->
                <?php require_once(dirname(__DIR__) . '/payment-form.php'); ?>
                <!-- /Payment Method Form -->
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /New Payment Modal -->
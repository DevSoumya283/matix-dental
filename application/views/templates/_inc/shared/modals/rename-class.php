<!-- Rename Prepopulated List Modal -->
<div id="renameClassModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="fontSize--l">Rename Class</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="renameClassForm" class="form__group" action="<?php echo base_url("update-class"); ?>" method="post" >
                <input type="hidden" name="class_id" class="c_id" value="">
                    <div class="input__group is--inline">
                        <input id="className" name="className" class="input not--empty class_name" type="text" value="" required>
                        <label class="label" for="className">Class Name</label>
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block form--submit page--reload" data-target="#renameClassForm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Rename Prepopulated List Modal -->

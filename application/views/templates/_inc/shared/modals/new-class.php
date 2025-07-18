<!-- Add New Class Modal -->
<div id="addNewClassModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Add New Class</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="formPassword" class="form__group" action="<?php echo base_url('createnew-class'); ?>" method="post">
                    <div class="row">
                        <div class="input__group is--inline">
                            <input id="className" name="className" class="input" type="text" required>
                            <label class="label" for="className">Name</label>
                        </div>
                    </div>
                    <div class="row footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" data-target="#formPassword" type="submit">Add new class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Add New Class Modal -->

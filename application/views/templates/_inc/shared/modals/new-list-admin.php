<!-- Create New Prepopulated List Modal -->
<div id="newPrepopulatedListAdminModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="fontSize--l">Create New Prepopulated List</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">Users will be able to add items to request lists or carts from this list.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="newListForm" class="form__group" action="<?php echo base_url(); ?>create-newPopulatedList" method="post">
                    <div class="input__group is--inline">
                        <input type="hidden" name="product_id" id="product_id" value="">
                        <input id="listName" name="listName" class="input" type="text" placeholder="My Shopping List" required/>
                        <label class="label" for="listName">Enter list name*</label>
                        <input type="hidden" name="admin" value="1">
                    </div>
                    <div class="footer__group border--dashed">                                                
                        <button class="btn btn--m btn--primary  btn--block form--submit page--reload" data-target="#newListForm">Save List</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Create New Prepopulated List Modal -->
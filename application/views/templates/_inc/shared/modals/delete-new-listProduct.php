<!-- Delete Prepopulated List Modal -->

<div id="deleteListProductModal" class="modal modal--m"> 
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="fontSize--l">Are you sure?</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form  class="form__group" method="post" action="<?php echo base_url(); ?>delete-prepopulated-product">
                    <p class="margin--m no--margin-r no--margin-t no--margin-l">This will remove the selected products from the prepopulated list.</p>
                    <p class="fontWeight--2 textColor--darkest-gray" id="listname"></p>
                    <div class="footer__group border--dashed">                        
                        <input type="hidden" name="list_id" value="" id="product_id">
                        <input type="hidden" name="prepopulated_id" value="<?php echo $prepopulated_id; ?>">
                        <button class="btn btn--m btn--block is--neg form--submit page--reload" type="submit">Delete List</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Delete Prepopulated List Modal -->

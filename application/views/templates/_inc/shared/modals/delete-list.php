<!-- Delete Prepopulated List Modal -->
 <div id="deletePrepopulatedListModal" class="modal modal--m"> 
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="fontSize--l mobile-center">Delete Prepopulated List?</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
             <form  id="deleteList" class="form__group" action="<?php echo base_url("delete-shopping-list"); ?>" method="post">
                <p class="margin--m no--margin-r no--margin-t no--margin-l">This cannot be undone.</p>
                <p class="fontWeight--2 textColor--darkest-gray" id="listname"></p>
               <input type="hidden" name="listId" id="listId" value="">
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--block default--action is--neg form--submit page--reload" data-target="#deleteList">Delete List</button>
                </div>
                 </form>
            </div>
        </div>
        </form>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Delete Prepopulated List Modal -->
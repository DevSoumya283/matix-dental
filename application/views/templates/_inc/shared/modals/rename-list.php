<!-- Rename Prepopulated List Modal -->
<div id="renamePrepopulatedListModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="fontSize--l mobile-center">Rename Prepopulated List</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="renameListForm" class="form__group" action="<?php echo base_url("update-listname"); ?>" method="post">
                    <input type="hidden" name="list_id" class="list_id" value="">
                    <div class="input__group is--inline">
                        <input id="listName" name="listName" class="input not--empty listname" type="text" value="Shopping List 1" required>
                        <label class="label" for="listName">List Name*</label>
                    </div><br>
                    <!--                     <div class="select">
                                        <select name="location_id" aria-label="Select a Title" class="location" required>
                                          <option value="">----Select Sub Category-----</option>
                                        </select>
                                        </div>-->
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block form--submit" data-target="#renameListForm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Rename Prepopulated List Modal -->
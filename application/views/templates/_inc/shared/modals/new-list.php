<!-- Create New Prepopulated List Modal -->
<div id="newPrepopulatedListModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="fontSize--l mobile-center">Create New Prepopulated List</h2>
            <p id="newUser" class="margin--m no--margin-r no--margin-t no--margin-l">Users will be able to add items to request lists or carts from this list.</p>
            <span class="location_error" style="color: red"></span>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="newListonlyForm" class="form__group" action="" method="post">
                    <div class="input__group is--inline">
                        <input type="hidden" name="product_id" id="product_id" value="">
                        <input id="listName" name="listName" class="input test_class" type="text" placeholder="My Shopping List" required/>
                        <label class="label" for="listName">Enter list name*</label>
                    </div>
                    <input type="text" name="location_id" class="location" id="shoppinglist--locations" value="" style="display: none;">
                    <!--                    <br>
                                        <div class="select">
                                           <select name="location_id" aria-label="Select a Title" class="locations" required>
                                             <option value="">----Select Sub Category-----</option>
                                           </select>
                                       </div>-->
                    <hr>
                    <div id="condLocations" class="is--conditional starts--hidden no--pad margin--s no--margin-lr" style="border: medium none; display: block;">
                        <ul class="list list--entities locations">
                            <!-- User Locations -->
                        </ul>
                    </div>
                    <div class="footer__group border--dashed">                                                
                        <button class="btn btn--m btn--primary  btn--block form--submit page--reload save-list" data-target="#newListForm" type="button">Save List</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Create New Prepopulated List Modal -->
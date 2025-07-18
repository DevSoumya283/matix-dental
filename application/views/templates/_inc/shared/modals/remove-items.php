<!-- Remove Selected Items Modal -->
<div id="removeSelectedItemsModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left mobile-center">
            <h2>Remove selected items</h2>
            <p id="newUser" class="margin--m no--margin-r no--margin-t no--margin-l">They will be removed from inventory until they are purchased again.</p>
        </div>
        
        
        <div class="modal__body center center--h align--left cf">
        
         <input type="hidden" name="user_id" value="" id="user_id">
      
            <div class="modal__content modal-margin">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <ul class="list list--inline list--stats list--divided">
                                    <li class="item">
                                        <div class="text__group">
                                            <p class="no--margin textColor--darkest-gray locationName"></p>
                                        </div>
                                    </li>
                                    <li class="item item--stat stat-s">
                                        <div class="text__group">
                                            <p class="no--margin textColor--darkest-gray item_count">Items</p>
                                        </div>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="footer__group border--dashed">

                    <button class="btn btn--m btn--block default--action is--neg remove-multiple-requests no--refresh" data-target="#deleteRequest" type="button">Remove Selected Items</button>
                </div>
            </div>
        
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Remove Selected Items Modal

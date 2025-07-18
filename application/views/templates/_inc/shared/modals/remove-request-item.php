<!-- Remove Selected Items Modal -->
<div id="removeRequestItemsModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="mobile-center">Remove This item?</h2>
            <p id="newUser" class="margin--m no--margin-r no--margin-t no--margin-l">They will be removed from This Request List until they are added again.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <input type="hidden" name="request_id" value="" class="r_id">
            <div class="modal__content" id="newUser">
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--block default--action is--neg remove-requestitem no--refresh" data-target="#deleteRequest" type="button">Remove</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Remove Selected Items Modal
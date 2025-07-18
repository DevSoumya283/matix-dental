<!-- Add New License Modal -->
<div id="addBuyingClubModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="margin--m no--margin-lr no--margin-t">Create Buying Club</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="addPermissionForm" class="form__group" action="<?php echo base_url("add-buying-club"); ?>" method="post" >
                <div class="modal__content">
                    <input type="hidden" name="userId" id="userId" value="<?php echo $_SESSION['user_id']; ?>">
                    <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                        <input id="name" name="name" class="input" type="text"  required>
                        <label class="label" for="accountEmail">Name</label>
                    </div>

                    <div class="center center--h footer__group">
                        <a href="#" class="btn btn--m btn--primary is--pos btn--dir is--next addBuyingClub">Create Buying Club</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>


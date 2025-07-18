<!-- Add New License Modal -->
<div id="addWhiteLabelModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="margin--m no--margin-lr no--margin-t"><span class="action">Create</span> Custom Site</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="addSiteForm" class="form__group" action="/white-labels/save" method="post"  enctype="multipart/form-data">
                <div class="modal__content">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="whitelabel" id="whitelabel" value="1">
                    <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                        <input id="name" name="name" class="input" type="text"  required>
                        <label class="label" for="name">Name</label>
                    </div>
                    <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                        <input id="short_name" name="short_name" class="input" type="text"  required>
                        <label class="label" for="short_name">Short Name</label>
                    </div>
                    <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                        <input id="domain" name="domain" class="input" type="text" >
                        <label class="label" for="domain">Vanity URL</label>
                    </div>
                    <div class="input__group is--inline margin--xs no--margin-lr no--margin-t select">
                        <select name="vendor_id" id="vendor_id" required>
                            <option value="">Select Vendor</option>
                            <?php foreach($vendors as $vendor){
                                echo '<option value="' . $vendor->id . '">' . $vendor->name . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="row no--margin-l">
                        <img id="logo" src="" style="width: 200px; display: none;" />
                    </div>
                    <div class="row no--margin-l">
                        <div class="title">Upload Logo</div>
                        <input id="whitelabelLogo" name="whitelabelLogo" class="input input--file not--empty" type="file" >
                    </div>

                    <div class="center center--h footer__group">
                        <input type="submit" class="btn btn--m btn--primary is--pos btn--dir is--next addWhiteLabel" value="Create Custom Site">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>


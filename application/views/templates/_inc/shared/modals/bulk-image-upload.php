<div id="bulkUploadModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Bulk Image Upload</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="bulkUpload" class="form__group" action="<?php echo base_url(); ?>bulk-image-upload" method="post" enctype="multipart/form-data">
                    <div class="row" style="font-size: 14px; margin-bottom: 15px">
                        Upload a zip archive of your images. Each image should be named in this format: [manufacturer]_[mpn]_[image-#].[extension]. For example: "ManufacturerName_435-U_1.jpg"
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <input id="imageArchive" name="imageArchive" type="file" required>
                        </div>
                    </div>
                    <div class="footer__group border--dashed border--light">
                        <input type="submit" class="btn btn--m btn--primary btn--block save--toggle form--submit" value="Upload" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
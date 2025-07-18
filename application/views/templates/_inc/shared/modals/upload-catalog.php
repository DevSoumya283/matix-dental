<!-- Upload Catalog Modal -->
<div id="uploadCatalogModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="fontSize--l">Upload Catalog</h2>
            <div style="display:inline;"><span class="fontWeight--2 textColor--negative">Warning:</span> Uploading duplicate data will overwrite existing data. It's recommended that you <form name="products_export" method="post" action="<?php echo base_url(); ?>Backup-Products" style="display: inline;"><a class="link fontWeight--2" href="javascript:void(0)" onclick="document.products_export.submit();">download a backup</a> 
                </form>
                of your existing catalog before uploading new data.</div>
            <p></p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="formName" class="form__group" action="<?php echo base_url() ?>import" method="post"  enctype="multipart/form-data">
                    <div class="row no--margin-l">
                        <h5 class="title">Select a Vendor</h5>
                        <div class="select">
                            <select class="vendors_data" name="vendor_id" required="">
                                <?php for ($i = 0; $i < count($vendors); $i++) { ?>
                                    <option value="<?php echo $vendors[$i]->id ?>"><?php echo $vendors[$i]->name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <br />
                        <h5 class="title">Upload File (.csv or .xls)</h5>
                        <input id="productCatalogFile" name="productCatalogFile" class="input input--file not--empty" type="file" required>
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block user--profile" data-target="#formName" >Upload Catalog</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Upload Catalog Modal -->

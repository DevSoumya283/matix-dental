<!-- Add New License Modal -->
<div id="uploadBuyingClubDataModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="margin--m no--margin-lr no--margin-t">Upload <span class="type"></span></h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <div xlass="row no--margin-l">
                    <a href="/assets/xls/buying-club-organizations.csv" class="btn btn--m btn--primary btn--block upload-button" id="templateFile" >Download Template</a>

                </div>
                <br>
                <form id="uploadForm" class="form__group " action="<?php echo base_url() ?>buying-club-import" method="post"  enctype="multipart/form-data">
                    <input type="hidden" id="clubId" value="<?php echo $buyingClub->id; ?>">
                    <div class="row no--margin-l">
                        <div class="title">Upload File (buying-club-<span class="type"></span>.csv)</div>
                        <input id="buyingClubDataFile" name="buyingClubDataFile" class="input input--file not--empty" type="file" required>
                    </div>
                    <div class="center center--h footer__group">
                        <button class="btn btn--m btn--primary btn--block upload-button" id="importButton"  data-dismiss="modal" >Upload <span class="type"></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>


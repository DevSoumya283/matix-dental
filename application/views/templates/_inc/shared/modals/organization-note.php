<!-- Add Note Modal -->
<div id="organizationNoteModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Add a note</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="addNoteForm" class="form__group" action="<?php echo base_url(); ?>organization-notes-SPadminCatalog" method="post">
                    <div class="row">
                        <div class="input__group is--inline">
                            <!--                    Note : Used by the ADMIN Section-->
                            <input type="hidden" name="organization_id" value="" class="organization_id">
                            <!--   END   -->
                            <textarea name="note" placeholder="Enter your question... (max 400 characters)" class="input input--l input--show-placeholder" maxlength="400" required></textarea>
                        </div>
                    </div>
                    <div class="row margin--s no--margin-r no--margin-b no--margin-l">
                        <button class="btn btn--m btn--primary float--right save--toggle form--submit page--reload" data-target="#addNoteForm" style="width:100px;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Add Note Modal -->

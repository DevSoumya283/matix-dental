<!-- Unassign User Modal -->
<div id="unassignStudentModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Unassign this user?</h2>
            <p class="margin--l no--margin-r no--margin-t no--margin-l">They will no longer be able to manage this location.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="newListForm" class="form__group" action="<?php echo base_url("remove-student-class"); ?>" method="post">
                    <input type="hidden" name="student_id" class="class_id" class="">
                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                    <div class="card padding--s">
                        <div class="entity__group ">
                            <span class="student_image"></span>
                            <span class="sname"></span>
                        </div>
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--block is--neg form--submit">Unassign User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Unassign User Modal -->

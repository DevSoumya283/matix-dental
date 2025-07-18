<!-- Ask Question Modal -->
<div id="askQuestionModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Ask a question</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">Before asking a question, please be sure to review the existing questions and ensure it hasn't been answered yet.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="askQuestionForm" action="<?php echo base_url("ask-question"); ?>" class="form__group" method="post">
                    <input type="hidden" name="product_id" class="product_id" value="">
                    <div class="row">
                        <div class="input__group is--inline">
                            <textarea name="questions" maxlength="400" placeholder="Enter your question... (max 400 characters)" class="input input--l input--show-placeholder" maxlength="400" required></textarea>
                        </div>
                    </div>
                    <div class="row margin--s no--margin-r no--margin-b no--margin-l align--right">
                        <button type="submit" class="btn btn--m btn--primary btn--block form--submit" data-target="#askQuestionForm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Ask Question Modal -->

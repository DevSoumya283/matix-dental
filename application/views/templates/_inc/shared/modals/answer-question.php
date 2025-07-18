<!-- Asnswer Question Modal -->
<div id="answerQuestionModal" class="modal modal--l">
    <div class="modal__wrapper modal__wrapper--transition padding--l no--pad-r no--pad-l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Answer question</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <div class="margin--2 no--margin-r no--margin-t no--margin-l">
                    <div class="row">
                        <div class="col col--1-of-6">
                            <span class="fontSize--m fontWeight--2">Question</span>
                        </div>
                        <div class="col col--5-of-6">
                            <p class="question"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col--1-of-6">
                        <span class="fontSize--m fontWeight--2">Answer</span>
                    </div>
                    <div class="col col--5-of-6">
                        <form id="answerQuestionForm" class="form__group" action="<?php echo base_url("add-productsAnswer"); ?>" method="post">
                            <div class="row">
                                <input type="hidden" name="question_id" class="qstn_id" value="">
                                <div class="input__group is--inline">
                                    <textarea name="answerQuestion" maxlength="1000" placeholder="Enter your question... (max 1000 characters)" class="input input--l input--show-placeholder" required></textarea>
                                </div>
                            </div>
                            <div class="row margin--s no--margin-r no--margin-b no--margin-l">
                                <button class="btn btn--m btn--primary float--right save--toggle form--submit page--reload" data-target="#answerQuestionForm">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Answer Question Modal -->

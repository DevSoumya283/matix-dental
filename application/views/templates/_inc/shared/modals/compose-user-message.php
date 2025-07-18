<!-- New Message to User Modal -->
<div id="composeUserMessageModal" class="modal modal--l">
    <div class="modal__wrapper modal__wrapper--transition padding--l no--pad-r no--pad-l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Compose Message</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="newMessageForm" class="modal__content">
                <div class="margin--s no--margin-lr no--margin-t">
                    <div class="row">
                        <div class="col col--1-of-6 col--am">
                            <span class="fontSize--m fontWeight--2">Recipient</span>
                        </div>
                        <div class="col col--5-of-6 col--am">
                            <div class="input__group is--inline">
                                <input id="recipientName" name="recipientName" class="input not--empty" value="Kevin McCallister" type="text" required disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="margin--s no--margin-lr no--margin-t">
                    <div class="row">
                        <div class="col col--1-of-6 col--am">
                            <span class="fontSize--m fontWeight--2">Subject</span>
                        </div>
                        <div class="col col--5-of-6 col--am">
                            <div class="input__group is--inline">
                                <input id="messageSubject" name="messageSubject" class="input" type="text" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input__group is--inline">
                    <textarea name="" placeholder="Enter your message..." class="input input--l input--show-placeholder"></textarea>
                </div>
                <div class="margin--s no--margin-r no--margin-b no--margin-l">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#newMessageForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /New Message to User Modal -->

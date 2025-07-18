<!-- Confirm Quantity Change Modal -->
<div id="qtyChangeModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Change quantity?</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <div class="margin--m no--margin-t no--margin-lr padding--s no--pad-lr border--1 border--1 border--solid border--lightest border--tb">
                    <ul>
                        <li class="disp--ib margin--s no--margin-tb no--margin-l fontSize--m fontWeight--2 textColor--dark-gray"><span class="fontSize--s fontWeight--2">Original Qty:</span> 3</li>
                        <li class="disp--ib margin--s no--margin-tb no--margin-l">&#8594;</li>
                        <li class="disp--ib fontSize--m fontWeight--2"><span class="fontSize--s fontWeight--2">Picked Qty:</span> 4</li>
                    </ul>
                </div>
                <p class="fontSize--m fontWeight--2 textColor--darkest-gray margin--xs no--margin-t no--margin-lr">Customer contact info:</p>
                <div class="card padding--s">
                    <div class="entity__group wrapper">
                        <div class="wrapper__inner" style="width:40px;">
                            <div class="avatar avatar--s" style="background-image:url('<?php echo ROOT_PATH; ?>assets/img/ph-avatar.jpg');"></div>
                        </div>
                        <div class="wrapper__inner">Kevin McCallister, DDS (You) <br> <span class="fontSize--s textColor--dark-gray">(310) 555-5555</span></div>
                    </div>
                </div>
                <p class="margin--s no--margin-r no--margin-b no--margin-l">Only confirm this action if you cannot fulfill the originally requested quantity.</p>
                <div class="footer__group border--dashed">
                    <a class="btn btn--m btn--primary btn--block" href="javascript:;">Confirm Quantity Change</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Confirm Quantity Change Modal -->

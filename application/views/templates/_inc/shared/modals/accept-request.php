<!-- Accept Request Modal -->
<div id="acceptRequestModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Send return instructions</h2>
            <p class="margin--l no--margin-r no--margin-t no--margin-l">Did you know that returning on Matix.com is easy and informative?</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content"><?php // echo ROOT_PATH . 'templates/vendor-admin/returns/r/accepted';   ?>
                <form id="acceptReturnForm" action="<?php echo base_url(); ?>returnOrders-accepted" method="post">
                    <div class="wrapper padding--xxs margin--s no--margin-lr no--margin-t no--pad-t no--pad-lr border--1 border--dashed border--light border--b">
                        <div class="wrapper__inner">
                            <p class="no--margin fontWeight--2 textColor--darkest-gray">Return Instructions</p>
                        </div>
                        <input type="hidden" name="return_id" class="return_id" value="">
                        <div class="wrapper__inner align--right">
                            <a id="editInstructions" class="link link--expand fontWeight--2" style="font-size:14px;">Edit</a>
                        </div>
                    </div>
                    <div id="returnInstructionsForm" class="form__group">
                        <div class="row no--margin-l">
                            <div class="input__group is--inline">
                                <textarea name="" placeholder="" value="" disabled class="input input--l input--show-placeholder is--disabled" id="instructions">
Step One: Process Your Return

You can print a FREE UPS domestic shipping label right here! Here's what you need to do:

Log into your account from the My Account link located at the top right of our site.

Locate the order number which contains the item(s) you wish to return and select it.

Select "Return This Item" from the drop down menu next to each item you would like to return and click on the "Return Checked Items" button.

Please Note: If the items you are returning require more than one box, only select the items you will be sending in the first box and continue. Once you have completed this return, go back to My Account and repeat these steps to get additional return labels for each additional box. If you are returning items from multiple orders, please be sure to use separate boxes for each order. Do not combine returns from multiple orders in one box as this may delay your refund. Choose whether to display and print your return label, or have us email or mail the label to you. Click the “Return Items” button.

If you chose to display and print your label, click the large button at the top of the page to display it. Otherwise, you will receive your return label via email or mail shortly.

Questions? The Matix Customer Loyalty Team is here to help 24-7: 1-800-927-7671

Step Two: Your Item's Original Packaging

Products must be returned in the original shoe box and/or packaging. If an extreme circumstance comes up and you do not have the original packaging, please print out the Confirmation Page (the final page of the return process) and include it in your shipping box. This is considered a courtesy for our wonderful customers.

Step Three: Your Shipping Box

You can use your original Matix.com box you received, or any plain, unmarked cardboard box to ship your return.

If there are any existing shipping labels, stickers, or other materials on the shipping box from previous shipments, please remove them.

Attach your new return label to the shipping box.

Tape your UPS return label to your box then drop it off at any authorized UPS Shipping Center. PLEASE DO NOT USE A UPS DROP BOX - you must drop off your return at a UPS Service Center - thank you! Find the UPS Service Center Nearest to You. Once you drop off your return at UPS, please allow 4-5 business days for your return to reach our warehouse.
                                </textarea>
                            </div>
                        </div>
                        <div id="saveInstructions" class="row is--hidden no--margin-l cf">
                            <button class="btn btn--primary btn--s form--submit save--toggle" type="button" data-target="#companyNameForm">Save Changes</button>
                            <hr>
                        </div>
                    </div>
                    <div class="row no--margin-l">
                        <h5 class="title">Upload Shipping Label (optional)</h5>
                        <input id="returnShippingLabel" name="returnShippingLabel" class="input input--file" type="file">
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#acceptReturnForm">Accept Request &amp; Send Instructions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Accept Request Modal -->

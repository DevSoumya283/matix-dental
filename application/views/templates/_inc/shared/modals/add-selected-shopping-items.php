<!-- Add Selected Items to Cart Modal -->
<div id="addSelectionsListToCartModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left ">
            <h2 class="selecteditems mobile-center">Add selected items to the cart?</h2>
            <p id="newUser" class="margin--m no--margin-r no--margin-t no--margin-l">This will remove them from the request list.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form  id="addlistTocart" class="form__group" action="<?php echo base_url("selected-list-addtocart"); ?>" method="post">
                    <input type="hidden" name="list_id" id="user_id" value="" >
                    <input type="hidden" name="qty" class="qty" value="" >
                    <input type="hidden" name="lid" class="listlocationid" value="">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <ul class="list list--inline list--stats list--divided">
                                        <li class="item">
                                            <div class="text__group">
                                                <p class="no--margin textColor--darkest-gray locationName"></p>
                                            </div>
                                        </li>
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <p class="no--margin textColor--darkest-gray item_count"> Items</p>
                                            </div>
                                        </li>
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--sub ">Subtotal: $<span class="no--margin textColor--darkest-gray total"></span></span>
                                            </div>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" data-target="#addlistTocart">Add Items to Cart</button>
                        <!-- <a class="btn btn--m btn--primary btn--block save--toggle default--action">Add Items to Cart</a> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Add Selected Items to Cart Modal -->
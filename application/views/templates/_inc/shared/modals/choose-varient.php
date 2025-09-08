<style>
  .modal--l .modal__wrapper {
      width: 65%;
  }
  @media screen and (max-width: 776px) {
      .modal--l .modal__wrapper {
          width: 80%;
      }
  }
</style>
<div id="productOptionModal" class="modal modal--l">
  <div class="modal__wrapper modal__wrapper--transition padding--l">

    <!-- Close button -->
    <a href="javascript:void(0);" class="modal__close icon icon--x" id="closeOptionModal">
      <svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg>
    </a>

    <!-- Header -->
    <div class="modal__header center center--h align--left">
      <h2>Choose Options for <span id="modalProductName"></span></h2>
    </div>

    <!-- Body -->
    <div class="modal__body center center--h align--left cf">
      <div class="modal__content">
        <!-- Variant Selectors -->
        <div class="row justify-content-around" id="variantSelectors"></div>

        <!-- SKU and Price -->
        <div id="skuRow" style="display:none;" class="mt-3">
          <p>Selected SKU: <strong id="selectedSku1"></strong></p>
          <p>Price: <span class="retail-price"></span></p>
        </div>

        <!-- Quantity -->
        <div class="mt-3">
          <label class="form-label">Quantity</label>
          <input type="number" id="modalQty" class="input input--l input--show-placeholder" min="1" value="1">
          <div id="stockMsg" class="text-danger small mt-1" style="display:none;"></div>
        </div>

      </div>

      <div class="modal__content">

                <hr class="margin--xs no--margin-lr no--margin-t">

                <div class="padding--s no--pad-lr">
                    <table class="table table-responsive">
                        <thead class="empty-data">

                            <tr>

                                <th width="35%">

                                    <span class="fontSize--s fontWeight--2">Location</span>

                                </th>

                                <th width="15%">

                                    <span class="fontSize--s fontWeight--2">Qty</span>

                                </th>

                                <th width="25%">

                                    <span class="fontSize--s fontWeight--2">Total</span>

                                </th>

                                <th width="25%">

                                    &nbsp;

                                </th>

                            </tr>

                        </thead>

                        <tbody class="cart_details">

                            <?php $new = random_string('alnum', 16); ?>

                        <input type="text" name="location_id" class="location" id="list--locations" value="" style="display: none;">

                        <input type="text" name="product_id" class="product" value="" style="display: none;">

                        <input type="text" name="pro_price" class="pro_price" value="" style="display: none;">

                        <input type="text" name="qty" class="quantity" value="" style="display: none;">

                        <input type="text" name="pro_color" class="pro_color" value="" style="display: none;">

                        <input type="text" name="p_name" class="p_name" value="" style="display: none;">

                        <input type="text" name="vendor" class="vendor" value="" style="display: none;">

                        </tbody>

                    </table>

                </div>

                <hr />

                <span class="notempty">

                    <div class="row cf margin--s no--margin-r no--margin-b no--margin-l no-submit">

                        <button type="button" class="btn btn--m btn--primary float--right default--action addTocart">Keep Shopping</button>

                    </div>

                </span>

                <?php

                $tier1 = unserialize(ROLES_TIER1);

                if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier1))) {

                    ?>

                    <span class="empty" style="display: none;">

                        <div class="row cf margin--s no--margin-r no--margin-b no--margin-l add_location">

                            <a class=" modal--toggle btn btn--m btn--primary float--right" data-target="#addNewLocationModal">Add Location</a>

                        </div>

                    </span>

                <?php } ?>

            </div>
    </div>
  </div>

  <!-- Overlay -->
  <div class="modal__overlay" id="optionModalOverlay"></div>
</div>

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
        <div class="row" id="variantSelectors"></div>

        <!-- SKU and Price -->
        <div id="skuRow" style="display:none;" class="mt-3">
          <p>Selected SKU: <strong id="selectedSku1"></strong></p>
          <p>Price: <span class="retail-price"></span></p>
        </div>

        <!-- Quantity -->
        <div class="mt-3">
          <label class="form-label">Quantity</label>
          <input type="number" id="modalQty" class="input input--l input--show-placeholder" min="1" value="1">
        </div>

        <!-- Footer buttons -->
        <!-- <div class="row margin--s no--margin-r no--margin-b no--margin-l">
          <a  href="javascript:void(0);"  id="saveOptionsBtn" class="btn btn--m btn--primary float--right" style="width:100px;">
            Save
          </a> -->
      
        <!-- </div> -->

        
                        <div class="row cf margin--s no--margin-r no--margin-b no--margin-l add_location">

                            <a class=" modal--toggle btn btn--m btn--primary float--right"   data-target="#productOptionModal">Add Location</a>

                        </div>
      </div>
    </div>
  </div>

  <!-- Overlay -->
  <div class="modal__overlay" id="optionModalOverlay"></div>
</div>

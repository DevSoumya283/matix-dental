<!-- Filter Vendor Products Modal -->
<div id="filterVendorProductsModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Configure Filters</h2>
        </div>
        <hr class="margin--m no--margin-lr border--lightest">
        <div class="modal__body center center--h align--left cf">
            <form id="filterProductsForm" class="form__group" action="<?php echo base_url(); ?>vendor-products-dashboard" method="get">
                <div class="modal__content">
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select name="siteSelect">
                                    <option value="">Marketplace</option>
                                    <?php foreach($sites as $x => $site){
                                        echo '<option value="' . $site->id . '">' . $site->name . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="select">
                            <select name="categorySelect" class="Category_select">
                                <option value="">All Categories</option>
                                <option disabled="">&nbsp;</option>
                                <option disabled="">— Classic View</option>
                                <?php for ($i = 0; $i < count($classic); $i++) { ?>
                                    <option value="<?php echo $classic[$i]->id; ?>"><?php
                                        echo $classic[$i]->name;
                                        echo "&nbsp;" . "(" . $classic[$i]->count . ")";
                                        ?>
                                    </option>
                                <?php } ?>
                                <option disabled="">&nbsp;</option>
                                <option disabled="">— Dentist View</option>
                                <?php for ($i = 0; $i < count($dentist); $i++) { ?>
                                    <option value="<?php echo $dentist[$i]->id; ?>"><?php
                                        echo $dentist[$i]->name;
                                        echo "&nbsp;" . "(" . $dentist[$i]->count . ")";
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select name="productStatus" required>
                                    <option disabled selected value="default">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select name="promos" required>
                                    <option disabled selected value="default">Promo Status</option>
                                    <option value="1">No Promo</option>
                                    <option value="2">On Promo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer__group border--dashed">
                    <!--                data-target="#filterProductsForm"-->
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit">Apply</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Configure Report Modal -->

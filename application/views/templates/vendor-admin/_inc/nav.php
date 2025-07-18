<div class="sidebar__group is--main-nav">
    <div class="text__group has--icon">
        <svg class="icon icon--details textColor--gray"><use xlink:href="#icon-details"></use></svg>
        <h4>Operations</h4>
    </div>
    <ul class="list list--tree list--nav">
        <li class="item">
            <a class="link" href="<?php echo base_url();?>vendor-dashboard">Dashboard</a>
        </li>
        <li class="item item--parent">
            <a class="link has--badge" data-badge="<?php echo $NorderCount; ?>" href="<?php echo base_url();?>vendor-orders" value="orders">Orders</a>
            <ul class="list">
                <li class="item">
                    <a class="link" href="<?php echo base_url();?>vendor-orders-completed">Complete</a>
                </li>
            </ul>
        </li>
<!--        <li class="item item--parent">
            <a class="link has--badge" data-badge="<?php echo $ReturnCount; ?>" href="<?php echo base_url();?>vendorReturn-orders" value="orders">Returns</a>
            <ul class="list">
                <li class="item">
                    <a class="link" href="<?php echo base_url();?>vendor-orderReturns-open">Open (<?php echo $ReturnCount; ?>)</a>
                </li>
                <li class="item">
                    <a class="link" href="<?php echo base_url();?>vendor-orderReturn-closed">Closed</a>
                </li>
            </ul>
        </li>-->
        <li class="item">
            <a class="link" href="<?php echo base_url();?>vendors-customer-dashboard">Customers</a>
        </li>
    </ul>
</div>

<div class="sidebar__group is--main-nav">
    <div class="text__group has--icon">
        <svg class="icon icon--settings textColor--gray"><use xlink:href="#icon-settings"></use></svg>
        <h4>Admin</h4>
    </div>
    <ul class="list list--tree list--nav">
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>vendor-products-dashboard">Products</a>
        </li>
        <li class="item item--parent">
            <a class="link" href="<?php echo base_url(); ?>promoCode-status-vendors">Promos</a>
            <ul class="list">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>view-promo-product">Product</a>
                </li>
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>view-promo-code">Codes</a>
                </li>
            </ul>
        </li>
       <!--  <li class="item">
            <a class="link" href="<?php echo base_url(); ?>vendor-pricing-scales">Pricing Scales</a>
        </li> -->
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>vendor-shipping-partners">Shipping</a>
        </li>
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>pricing-scales">Pricing Scales</a>
        </li>
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>buying-clubs">Buying Clubs</a>
        </li>
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>white-labels">Customize</a>
        </li>
        <li class="item item--parent">
            <a class="link" href="<?php echo base_url();?>vendor-reports">Reports</a>
            <ul class="list">
                <li class="item">
                    <a class="link" href="<?php echo base_url();?>order-reports-Vendor">Orders</a>
                </li>
                <li class="item">
                    <a class="link" href="<?php echo base_url();?>order-reports-Sales">Sales</a>
                </li>
                <li class="item">
                    <a class="link" href="<?php echo base_url();?>order-reports-customer">Customer</a>
                </li>
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>order-Reports-Shipping">Shipping</a>
                </li>
            </ul>
        </li>
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>view-vendors">Users</a>
        </li>
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>vendor-settings-dashboard">Settings</a>
        </li>
    </ul>
</div>

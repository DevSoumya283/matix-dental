<div class="sidebar__group is--main-nav">
    <div class="text__group has--icon">
        <svg class="icon icon--details textColor--gray"><use xlink:href="#icon-details"></use></svg>
        <h4>Operations</h4>
    </div>
    <ul class="list list--tree list--nav">
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>vendorsIn-list">Vendors</a>
        </li>
        <li class="item item--parent">
            <a class="link has--badge" data-badge="<?php echo $user_approval; ?>" href="<?php echo base_url(); ?>customer-list" value="orders">Customers</a>
            <ul class="list">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>customerSection-accept-customers">Pending (<?php echo $user_approval; ?>)</a>
                </li>
            </ul>
        </li>
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>organizations-list">Organizations</a>
        </li>
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>product-catalog">Catalog</a>
        </li>
        <li class="item item--parent">
            <a class="link" href="<?php echo base_url(); ?>buying-clubs" value="orders">Market Places</a>
        </li>
        <li class="item item--parent">
            <a class="link" href="<?php echo base_url(); ?>pricing-scales" value="orders">Pricing Scales</a>
        </li>
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>white-labels">White-Labels</a>
        </li>
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>prepopulated-lists">Lists</a>
        </li>

        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>admin/roles">Roles</a>
        </li>
        <li class="item">
            <a class="link has--badge is--neg" data-badge="<?php echo $flagged_count; ?>" href="<?php echo base_url(); ?>superAdmin-Reviews">Reviews</a>
        </li>
        <li class="item">
            <a class="link has--badge is--neg" data-badge="<?php echo $answer_count; ?>" href="<?php echo base_url(); ?>superAdmin-Answers">Answers</a>
        </li>
    </ul>
</div>

<div class="sidebar__group is--main-nav">
    <div class="text__group has--icon">
        <svg class="icon icon--settings textColor--gray"><use xlink:href="#icon-settings"></use></svg>
        <h4>Settings</h4>
    </div>
    <ul class="list list--tree list--nav">
        <?php if ($_SESSION['role_id'] == 1) { ?>
            <li class="item">
                <a class="link" href="<?php echo base_url(); ?>superAdmins-Users">Admin Users</a>
            </li>
        <?php } ?>
        <li class="item">
            <a class="link" href="<?php echo base_url(); ?>superAdmins-Account">My Account</a>
        </li>
    </ul>
</div>

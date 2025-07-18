<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url('dashboard'); ?>">Account</a>
                </li>
                <li class="item is--active">
                    Payment Methods
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed">
        <div class="content__main">
            <div class="content row">
                <div class="col-md-6 col-xs-12 col--centered">
                    <div class="heading__group border--dashed">
                        <div class="mobile-center">
                            <h3>Payment Methods</h3>
                            <p>Manage your saved payment methods.</p>
                        </div>
                        <div class="wrapper__inner align--right">
                            <button class="btn btn--tertiary btn--m modal--toggle" data-target="#newPaymentModal">Add New</button>
                        </div>
                    </div>
                    <table class="table table--payments fontSize--s">
                        <tbody>
                            <?php
                            //JM: 08/15/18 Add conditional to prevent PHP error
                            if(count($users_payments) > 0) {
                                foreach ($users_payments as $row) {
                                    ?>
                                    <tr class="payment__method modal--toggle user_payment" data-id="<?php echo $row->id; ?>" data-card="<?php echo $row->cc_number; ?>" data-cname="<?php echo $row->cc_name; ?>" data-emonth="<?php echo $row->exp_month; ?>" data-eyear="<?php echo $row->exp_year; ?>" data-type="<?php echo $row->payment_type; ?>"  data-cvv="<?php echo $row->cc_code; ?>" data-target="#creditCardModal">
                                        <?php
                                        $card_number = preg_replace('/[^0-9]/', '', $row->cc_number);
                                        $inn = (int) mb_substr($card_number, 0, 2);
                                        $card_number = substr($row->cc_number, -4);
                                        $account_number = substr($row->ba_account_number, -4);
                                        if (($row->payment_type) == 'card') {
                                            ?>
                                            <td width="70%">
                                                <?php if ($row->card_type == 'Visa') { ?>
                                                <svg class="icon icon--cc icon--visa"></svg>
                                                <?php echo $row->card_type . " •••• "; ?><?php echo $row->cc_number; ?>
                                                <?php } else if ($row->card_type == 'MasterCard') {
                                                    ?>
                                                    <svg class="icon icon--cc icon--mastercard"></svg>
                                                    <?php
                                                    echo $row->card_type . " •••• ";
                                                    echo $row->cc_number;
                                                    ?>
                                                    <?php } else if ($row->card_type == 'Discover') {
                                                        ?>
                                                        <svg class="icon icon--cc icon--discover"></svg>
                                                        <?php
                                                        echo $row->card_type . " •••• ";
                                                        echo $row->cc_number;
                                                        ?>
                                                        <?php } else if ($row->card_type == 'American Express') {
                                                            ?>
                                                            <svg class="icon icon--cc icon--amex"></svg>
                                                            <?php
                                                            echo $row->card_type . " •••• ";
                                                            echo $row->cc_number;
                                                            ?>
                                                            <?php } else {
                                                                ?>
                                                                <svg class="icon icon--cc icon--undefined"></svg>
                                                                <?php
                                                                echo $type = 'undefined Card' . " •••• ";
                                                                echo $card_number;
                                                                ?>
                                                                <?php }
                                                                ?>
                                                            </td>
                                                            <td width="30%">
                                                                Exp. <?php
                                                                echo $row->exp_month . '/';
                                                                echo $row->exp_year;
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    } elseif (($row->payment_type) == 'bank') {
                                                        ?>
                                                        <tr class="payment__method modal--toggle user_bank" data-id="<?php echo $row->id; ?>" data-bank="<?php echo $row->bank_name; ?>" data-cname="<?php echo $row->cc_name; ?>" data-routing="<?php echo $row->ba_routing_number; ?>" data-type="<?php echo $row->payment_type; ?>" data-account="<?php echo $row->ba_account_number; ?>" data-target="#bankAccountModal">
                                                            <td width="70%">
                                                                <svg class="icon icon--cc icon--bank"></svg>
                                                                <?php echo $row->bank_name; ?> account •••• <?php print $account_number; ?>
                                                            </td>
                                                            <td width="30%">
                                                                <?php echo "Never Expires"; ?>
                                                            </td>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <?php }
                              // JM: end conditional
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <br>
                                        <?php
                                        if(in_array($user->role_id, unserialize(ROLES_TIER2)) && isset($users_parent_payments) && !empty($users_parent_payments)){
                                            ?>
                                            <div class="heading__group border--dashed wrapper">
                                                <div class="wrapper__inner">
                                                    <h3>Tier 1's Payment Methods</h3>
                                                    <p>Payment methods are read only.</p>
                                                </div>
                                            </div>
                                            <table class="table table--payments fontSize--s">
                                                <tbody>
                                                    <?php
                                                    foreach ($users_parent_payments as $row) {
                                                        ?>
                                                        <tr class="payment__method user_payment" data-id="<?php echo $row->id; ?>"
                                                            data-card="<?php echo $row->cc_number; ?>">
                                                            <?php
                                                            $card_number = preg_replace('/[^0-9]/', '', $row->cc_number);
                                                            $inn = (int)mb_substr($card_number, 0, 2);
                                                            $card_number = substr($row->cc_number, -4);
                                                            $account_number = substr($row->ba_account_number, -4);
                                                            if (($row->payment_type) == 'card') {
                                                                ?>
                                                                <td width="70%">
                                                                    <?php if ($row->card_type == 'Visa') { ?>
                                                                    <svg class="icon icon--cc icon--visa"></svg>
                                                                    <?php echo $row->card_type . " •••• "; ?><?php echo $row->cc_number; ?>
                                                                    <?php } else if ($row->card_type == 'MasterCard') {
                                                                        ?>
                                                                        <svg class="icon icon--cc icon--mastercard"></svg>
                                                                        <?php
                                                                        echo $row->card_type . " •••• ";
                                                                        echo $row->cc_number;
                                                                        ?>
                                                                        <?php } else if ($row->card_type == 'Discover') {
                                                                            ?>
                                                                            <svg class="icon icon--cc icon--discover"></svg>
                                                                            <?php
                                                                            echo $row->card_type . " •••• ";
                                                                            echo $row->cc_number;
                                                                            ?>
                                                                            <?php } else if ($row->card_type == 'American Express') {
                                                                                ?>
                                                                                <svg class="icon icon--cc icon--amex"></svg>
                                                                                <?php
                                                                                echo $row->card_type . " •••• ";
                                                                                echo $row->cc_number;
                                                                                ?>
                                                                                <?php } else {
                                                                                    ?>
                                                                                    <svg class="icon icon--cc icon--undefined"></svg>
                                                                                    <?php
                                                                                    echo $type = 'undefined Card' . " •••• ";
                                                                                    echo $card_number;
                                                                                    ?>
                                                                                    <?php }
                                                                                    ?>
                                                                                </td>
                                                                                <td width="30%">
                                                                                    Exp. <?php
                                                                                    echo $row->exp_month . '/';
                                                                                    echo $row->exp_year;
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                        } elseif (($row->payment_type) == 'bank') {
                                                                            ?>
                                                                            <tr class="payment__method modal--toggle user_bank" data-id="<?php echo $row->id; ?>" data-bank="<?php echo $row->bank_name; ?>" data-cname="<?php echo $row->cc_name; ?>" data-routing="<?php echo $row->ba_routing_number; ?>" data-type="<?php echo $row->payment_type; ?>" data-account="<?php echo $row->ba_account_number; ?>" data-target="#bankAccountModal">
                                                                                <td width="70%">
                                                                                    <svg class="icon icon--cc icon--bank"></svg>
                                                                                    <?php echo $row->bank_name; ?> account •••• <?php print $account_number; ?>
                                                                                </td>
                                                                                <td width="30%">
                                                                                    <?php echo "Never Expires"; ?>
                                                                                </td>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                                <?php
                                                            }
                                                            elseif (in_array($user->role_id, unserialize(ROLES_TIER2)) && isset($users_parent_payments) && empty($users_parent_payments)) {
                                                                ?>
                                                                <div class="heading__group border--dashed wrapper">
                                                                    <div class="wrapper__inner mobile-center">
                                                                        <h3>Tier 1 Payment Methods</h3>
                                                                        <p>Tier 1 accounts have no payment methods.</p>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                            elseif (in_array($user->role_id, unserialize(ROLES_TIER1)) && isset($users_child_payments) && !empty($users_child_payments)) {
                                                                ?>
                                                                <div class="heading__group border--dashed wrapper">
                                                                    <div class="wrapper__inner mobile-center">
                                                                        <h3>Tier 2's Payment Methods</h3>
                                                                        <p>Payment methods are read only.</p>
                                                                    </div>
                                                                </div>
                                                                <table class="table table--payments fontSize--s">
                                                                    <tbody>
                                                                        <?php
                                                                        foreach ($users_child_payments as $row) {
                                                                            ?>
                                                                            <tr class="payment__method user_payment" data-id="<?php echo $row->id; ?>"
                                                                                data-card="<?php echo $row->cc_number; ?>">
                                                                                <?php
                                                                                $card_number = preg_replace('/[^0-9]/', '', $row->cc_number);
                                                                                $inn = (int)mb_substr($card_number, 0, 2);
                                                                                $card_number = substr($row->cc_number, -4);
                                                                                $account_number = substr($row->ba_account_number, -4);
                                                                                if (($row->payment_type) == 'card') {
                                                                                    ?>
                                                                                    <td width="70%">
                                                                                        <?php if ($row->card_type == 'Visa') { ?>
                                                                                        <svg class="icon icon--cc icon--visa"></svg>
                                                                                        <?php echo $row->card_type . " •••• "; ?><?php echo $row->cc_number; ?>
                                                                                        <?php } else if ($row->card_type == 'MasterCard') {
                                                                                            ?>
                                                                                            <svg class="icon icon--cc icon--mastercard"></svg>
                                                                                            <?php
                                                                                            echo $row->card_type . " •••• ";
                                                                                            echo $row->cc_number;
                                                                                            ?>
                                                                                            <?php } else if ($row->card_type == 'Discover') {
                                                                                                ?>
                                                                                                <svg class="icon icon--cc icon--discover"></svg>
                                                                                                <?php
                                                                                                echo $row->card_type . " •••• ";
                                                                                                echo $row->cc_number;
                                                                                                ?>
                                                                                                <?php } else if ($row->card_type == 'American Express') {
                                                                                                    ?>
                                                                                                    <svg class="icon icon--cc icon--amex"></svg>
                                                                                                    <?php
                                                                                                    echo $row->card_type . " •••• ";
                                                                                                    echo $row->cc_number;
                                                                                                    ?>
                                                                                                    <?php } else {
                                                                                                        ?>
                                                                                                        <svg class="icon icon--cc icon--undefined"></svg>
                                                                                                        <?php
                                                                                                        echo $type = 'undefined Card' . " •••• ";
                                                                                                        echo $card_number;
                                                                                                        ?>
                                                                                                        <?php }
                                                                                                        ?>
                                                                                                    </td>
                                                                                                    <td width="30%">
                                                                                                        Exp. <?php
                                                                                                        echo $row->exp_month . '/';
                                                                                                        echo $row->exp_year;
                                                                                                        ?>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <?php
                                                                                            } elseif (($row->payment_type) == 'bank') {
                                                                                                ?>
                                                                                                <tr class="payment__method modal--toggle user_bank" data-id="<?php echo $row->id; ?>" data-bank="<?php echo $row->bank_name; ?>" data-cname="<?php echo $row->cc_name; ?>" data-routing="<?php echo $row->ba_routing_number; ?>" data-type="<?php echo $row->payment_type; ?>" data-account="<?php echo $row->ba_account_number; ?>" data-target="#bankAccountModal">
                                                                                                    <td width="70%">
                                                                                                        <svg class="icon icon--cc icon--bank"></svg>
                                                                                                        <?php echo $row->bank_name; ?> account •••• <?php print $account_number; ?>
                                                                                                    </td>
                                                                                                    <td width="30%">
                                                                                                        <?php echo "Never Expires"; ?>
                                                                                                    </td>
                                                                                                    <?php
                                                                                                }
                                                                                                ?>
                                                                                            </tr>
                                                                                            <?php } ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <?php
                                                                                }
                                                                                elseif (in_array($user->role_id, unserialize(ROLES_TIER1)) && isset($users_child_payments) && empty($users_child_payments)) {
                                                                                    ?>
                                                                                    <div class="heading__group border--dashed">
                                                                                        <div class="wrapper__inner mobile-center">
                                                                                            <h3>Tier 2 Payment Methods</h3>
                                                                                            <p>Tier 2 accounts have no payment methods.</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                ?>
                    <!--
                        NOTE: If no payment methods have been saved, display the payment method form directly on the page:
                        '/_inc/shared/payment-form.php'
                    -->
                </div>
            </div>
        </div>
    </section>
    <!-- /Main Content -->
</div>
<!-- /Content Section -->
<!-- Modals -->
<?php $this->load->view('templates/_inc/shared/modals/new-payment.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/edit-credit.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/edit-bank.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/delete-payment.php'); ?>

<!-- <?php include(INCLUDE_PATH . '/_inc/shared/modals/new-payment.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/edit-credit.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/edit-bank.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/delete-payment.php'); ?> -->
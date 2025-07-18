<div class="modal__content padding--l bg--lightest-gray">
    <ul class="list list--entities">
        <?php if ($pre_populatedLists != null) { ?>
            <input type="hidden" name="product_id" value="" id="product_id">
            <?php foreach ($pre_populatedLists as $lists) { ?>
                <li class="item card padding--xs cf">
                    <div class="wrapper">
                        <div class="wrapper__inner">
                            <?php echo $lists->listname; ?>
                        </div>
                        <div class="wrapper__inner align--right">
                            <button class="btn btn--s btn--tertiary btn--toggle width--fixed-75 includeProToPrepopList" data-list_id="<?php echo $lists->id; ?>" data-before="Select" data-after="&#10003;" type="button"></button>
                        </div>
                    </div>
                </li>
            <?php } ?>
        <?php } else { ?>
            <li class="item card padding--xs cf">
                <div class="wrapper">
                    <div class="wrapper__inner">
                        No Pre-Populated List Created Yet.
                    </div>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>
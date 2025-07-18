<!-- Add to Existing Lists Modal -->
<div id="existingListsModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition no--pad">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left padding--l">
            <h2>Assign to Pre-Populated List(s)</h2>
            <form action="">
                <div class="input__group input__group--inline">
                    <input id="site-search" class="input input__text prepopSearch" type="search" value="" placeholder="Search by name..." name="search" required>
                    <label for="site-search" class="label">
                        <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                    </label>
                </div>
            </form>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="ListSearch">
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
            </div>
            <div class="modal__footer padding--l">
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block default--action" onclick="location.reload();">Done</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Add to Existing Lists Modal -->

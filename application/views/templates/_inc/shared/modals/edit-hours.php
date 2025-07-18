<!-- Edit Hours Modal -->
<div id="editHoursModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Edit Business Hours</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <hr>
                <?php if ($business_hours != null) { ?>
                    <form method="post" action="<?php echo base_url(); ?>vendor-Business-hours">
                        <?php $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]; ?>
                        <?php foreach ($days as $day) { ?>
                            <?php $bDay = false; ?>
                            <?php for ($i = 0; $i < count($business_hours); $i++) { ?>
                                <?php if ($day == $business_hours[$i]->day) { ?>
                                    <?php $bDay = true; ?>
                                    <div class="row">
                                        <label class="control control__checkbox">
                                            <input class="control__conditional" type="checkbox"  name="<?php echo strtolower($day) ?>" <?php echo ($business_hours[$i]->status == 1) ? "checked" : ""; ?> data-target="#cond<?php echo $business_hours[$i]->day; ?>">
                                            <div class="control__indicator"></div>
                                            <?php echo $business_hours[$i]->day; ?>
                                        </label>
                                        <div id="cond<?php echo $business_hours[$i]->day; ?>" class="is--conditional starts--hidden" <?php echo ($business_hours[$i]->status == 1) ? "style='display: block;'" : ""; ?>>
                                            <div class="well bg--lightest-gray">
                                                <div class="input__group input__group--range is--inline"></use></svg>
                                                    <input type="text" class="input input--time" name="<?php echo strtolower($day) ?>_startT" value="<?php echo date('g:i A', strtotime($business_hours[$i]->open_time)); ?>" required>
                                                    <input type="text" class="input input--time"name="<?php echo strtolower($day) ?>_endT" value="<?php echo date('g:i A', strtotime($business_hours[$i]->close_time)); ?>" required>
                                                </div>
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                    <?php continue; ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($bDay == false) { ?>
                                <div class="row">
                                    <label class="control control__checkbox">
                                        <input name="<?php echo strtolower($day) ?>" class="control__conditional" type="checkbox" data-target="#cond<?php echo $day ?>">
                                        <div class="control__indicator"></div>
                                        <?php echo $day ?>
                                    </label>
                                    <div id="cond<?php echo $day ?>" class="is--conditional starts--hidden">
                                        <div class="well bg--lightest-gray">
                                            <div class="input__group input__group--range is--inline"></use></svg>
                                                <input type="text" class="input input--time" name="<?php echo strtolower($day) ?>_startT" required>
                                                <input type="text" class="input input--time" name="<?php echo strtolower($day) ?>_endT" required>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="row">
                            <button class="btn btn--primary btn--m btn--block form--submit save--toggle " data-target="#companyHours">Save Changes</button>
                            <!--                        <button class="btn btn--primary btn--m btn--block form--submit save--toggle ">Save Changes</button>-->
                        </div>
                    </form>
                <?php } else { ?>
                    <form id="companyHours" class="form__group" method="post" action="<?php echo base_url(); ?>vendor-Business-hours">
                        <div class="row">
                            <label class="control control__checkbox">
                                <input name="monday" class="control__conditional" type="checkbox" data-target="#condMonday">
                                <div class="control__indicator"></div>
                                Monday
                            </label>
                            <div id="condMonday" class="is--conditional starts--hidden">
                                <div class="well bg--lightest-gray">
                                    <div class="input__group input__group--range is--inline"></use></svg>
                                        <input type="text" class="input input--time" name="monday_startT" required>
                                        <input type="text" class="input input--time" name="monday_endT" required>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control control__checkbox">
                                <input name="tuesday" class="control__conditional" type="checkbox" data-target="#condTuesday">
                                <div class="control__indicator"></div>
                                Tuesday
                            </label>
                            <div id="condTuesday" class="is--conditional starts--hidden">
                                <div class="well bg--lightest-gray">
                                    <div class="input__group input__group--range is--inline"></use></svg>
                                        <input type="text" class="input input--time" name="tuesday_startT" required>
                                        <input type="text" class="input input--time" name="tuesday_endT" required>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control control__checkbox">
                                <input name="wednesday" class="control__conditional" type="checkbox" data-target="#condWednesday">
                                <div class="control__indicator"></div>
                                Wednesday
                            </label>
                            <div id="condWednesday" class="is--conditional starts--hidden">
                                <div class="well bg--lightest-gray">
                                    <div class="input__group input__group--range is--inline"></use></svg>
                                        <input type="text" class="input input--time" name="wednesday_startT" required>
                                        <input type="text" class="input input--time" name="wednesday_endT" required>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control control__checkbox">
                                <input  name="thursday" class="control__conditional" type="checkbox" data-target="#condThursday">
                                <div class="control__indicator"></div>
                                Thursday
                            </label>
                            <div id="condThursday" class="is--conditional starts--hidden">
                                <div class="well bg--lightest-gray">
                                    <div class="input__group input__group--range is--inline"></use></svg>
                                        <input type="text" class="input input--time" name="thursday_startT" required>
                                        <input type="text" class="input input--time" name="thursday_endT" required>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control control__checkbox">
                                <input name="friday" class="control__conditional" type="checkbox" data-target="#condFriday">
                                <div class="control__indicator"></div>
                                Friday
                            </label>
                            <div id="condFriday" class="is--conditional starts--hidden">
                                <div class="well bg--lightest-gray">
                                    <div class="input__group input__group--range is--inline"></use></svg>
                                        <input type="text" class="input input--time" name="friday_startT" required>
                                        <input type="text" class="input input--time" name="friday_endT" required>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control control__checkbox">
                                <input name="saturday" class="control__conditional" type="checkbox" data-target="#condSaturday">
                                <div class="control__indicator"></div>
                                Saturday
                            </label>
                            <div id="condSaturday" class="is--conditional starts--hidden">
                                <div class="well bg--lightest-gray">
                                    <div class="input__group input__group--range is--inline"></use></svg>
                                        <input type="text" class="input input--time" name="saturday_startT" required>
                                        <input type="text" class="input input--time" name="saturday_endT" required>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control control__checkbox">
                                <input name="sunday" class="control__conditional" type="checkbox" data-target="#condSunday">
                                <div class="control__indicator"></div>
                                Sunday
                            </label>
                            <div id="condSunday" class="is--conditional starts--hidden">
                                <div class="well bg--lightest-gray">
                                    <div class="input__group input__group--range is--inline"></use></svg>
                                        <input type="text" class="input input--time" name="sunday_startT" required>
                                        <input type="text" class="input input--time" name="sunday_endT" required>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <button class="btn btn--primary btn--m btn--block form--submit save--toggle " data-target="#companyHours">Save Changes</button>
                            <!--                        <button class="btn btn--primary btn--m btn--block form--submit save--toggle ">Save Changes</button>-->
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Edit Hours Modal -->

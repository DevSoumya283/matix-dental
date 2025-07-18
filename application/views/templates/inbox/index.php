<?php require_once('../_inc/header.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Account Bar -->
    <div class="bar padding--m bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <div class="wrapper__inner">
                <h3 class="no--margin-b">Message Center</h3>
            </div>
            <div class="wrapper__inner align--right">
                <button class="btn btn--m btn--tertiary modal--toggle" data-target="#composeMessageModal">New Message</button>
            </div>
        </div>
    </div>
    <!-- /Account Bar -->

    <!-- Main Content -->
    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--4-of-12" style="padding-right:32px;">

                    <!-- Message Types -->
                    <div class="tab__group tab--block" data-target="#messageList">
                        <label class="tab" value="is--received">
                            <input type="radio" name="groupName" checked>
                            <span>Received</span>
                        </label>
                        <label class="tab" value="is--sent">
                            <input type="radio" name="groupName">
                            <span>Sent</span>
                        </label>
                    </div>
                    <!-- /Message Types -->

                    <hr>

                    <div id="messageList" class="is--received">

                        <!-- Messages Received -->
                        <div id="messagesReceived">
                            <!-- Unread Messages -->
                            <div class="sidebar__group" data-controls="#controlsUnread">
                                <div class="group__heading">
                                    <div class="wrapper">
                                        <div class="wrapper__inner">
                                            <h4 class="disp--ib no--margin-b">Unread (1)</h4>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <div id="controlsUnread" class="contextual__controls disp--ib">
                                                <ul class="list list--inline fontWeight--2 fontSize--s is--contextual is--off">
                                                    <li class="item">
                                                        <a class="link">Mark as Read</a>
                                                    </li>
                                                    <li class="item is--contextual is--off">
                                                        <a class="link">Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list list--cards">
                                    <!-- Message -->
                                    <li class="item card message is--unread">
                                        <div class="message__meta wrapper">
                                            <div class="wrapper__inner">
                                                <label class="control control__checkbox">
                                                    <input type="checkbox" name="checkboxRow">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="message__timestamp wrapper__inner align--right">
                                                Yesterday
                                            </div>
                                        </div>
                                        <div class="wrapper">
                                            <div class="entity__group entity--m">
                                                <div class="avatar avatar--m" style="background-image:url('<?php echo ROOT_PATH; ?>assets/img/ph-avatar.jpg');"></div>
                                                <div class="text__group">
                                                    <span class="line--main">Sender Name</span>
                                                    <span class="line--sub truncate">Lorem ipsum dolor sit amet another ipsum dolorem ispum.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Unread Messages -->

                            <!-- Read Messages -->
                            <div class="sidebar__group" data-controls="#controlsRead">
                                <div class="group__heading">
                                    <div class="wrapper">
                                        <div class="wrapper__inner">
                                            <h4 class="disp--ib no--margin-b">Read (1)</h4>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <div id="controlsRead" class="contextual__controls disp--ib">
                                                <ul class="list list--inline fontWeight--2 fontSize--s is--contextual is--off">
                                                    <li class="item">
                                                        <a class="link">Mark as Unread</a>
                                                    </li>
                                                    <li class="item is--contextual is--off">
                                                        <a class="link">Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list list--cards">
                                    <!-- Message -->
                                    <li class="item card message">
                                        <div class="message__meta wrapper">
                                            <div class="wrapper__inner">
                                                <label class="control control__checkbox">
                                                    <input type="checkbox" name="checkboxRow">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="message__timestamp wrapper__inner align--right">
                                                Sep. 27, 2016
                                            </div>
                                        </div>
                                        <div class="wrapper">
                                            <div class="entity__group entity--m">
                                                <div class="avatar avatar--m" style="background-image:url('<?php echo ROOT_PATH; ?>assets/img/ph-avatar.jpg');"></div>
                                                <div class="text__group">
                                                    <span class="line--main">Sender Name</span>
                                                    <span class="line--sub truncate">Lorem ipsum dolor sit amet another ipsum dolorem ispum.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Read Messages -->
                        </div>
                        <!-- /Messages Received -->

                        <!-- Messages Sent -->
                        <div id="messagesSent">
                            <!-- Messages -->
                            <div class="sidebar__group">
                                <ul class="list list--cards list--empty">
                                    <!-- Message -->
                                    <li class="item align--center">
                                        No messages to display.
                                    </li>
                                </ul>
                            </div>
                            <!-- /Messages -->
                        </div>
                        <!-- /Messages Sent -->

                    </div>

                </div>

                <!-- Content Area -->
                <div class="content col col--7-of-12 col--push-1-of-12">
                    <div id="messageID" class="message__body">

                        <!-- Message Meta -->
                        <div class="padding--s no--pad-lr no--pad-t border--1 border--dashed border--light border--b margin--l no--margin-lr no--margin-t">
                            <div class="wrapper">
                                <div class="wrapper__inner width--50">
                                    <div class="entity__group">
                                        <div class="avatar avatar--s" style="background-image:url('<?php echo ROOT_PATH; ?>assets/img/ph-avatar.jpg');"></div>
                                        <div class="text__group">
                                            <span class="line--main">From: Sender Name</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="wrapper__inner align--right">
                                    Sep. 17, 2016 (10:43 AM)
                                </div>
                            </div>
                        </div>
                        <!-- /Message Meta -->

                        <!-- Message Body -->
                        <h3>Message Subject Line</h3>
                        <p>Hi Kevin,</p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque mauris nibh, vulputate eget fringilla et, auctor condimentum tortor. Nam ac consectetur nulla. Donec neque lectus, efficitur ut nisi et, hendrerit egestas ligula. Curabitur et felis quis dui vestibulum lacinia. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Integer hendrerit urna vel tellus maximus, nec ornare massa laoreet.
                        </p>
                        <hr>
                        <div class="row margin--s no--margin-lr">
                            <h4>Type your response:</h4>
                            <div class="input__group is--inline">
                                <textarea name="" placeholder="Enter a short description as to why you're submitting this request..." class="input input--l input--show-placeholder" required></textarea>
                            </div>
                        </div>
                        <div class="row cf">
                            <button class="btn btn--primary btn--m float--right">Send</button>
                        </div>
                        <!-- /Message Body -->

                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- /Main Content -->

</div>
<!-- /Content Section -->

<?php require_once('../_inc/shared/modals/compose-message.php'); ?>
<?php require_once('../_inc/footer.php'); ?>

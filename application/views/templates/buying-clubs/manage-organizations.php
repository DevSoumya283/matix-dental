<?php include(INCLUDE_PATH . '/_inc/header-' . $userType . '.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding:12px">
                    <?php include(INCLUDE_PATH . '/' . (User_model::can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor-admin') . '/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                        <div class="col col--9-of-12">
                            <h3>My Buying Clubs - <?php echo $buyingClub->name; ?> - Organizations</h3>
                        </div>
                    </div>

                    <div style="overflow: hidden; overflow-x: scroll;">
                        <table class="table" data-controls="#controlsTable">
                        <thead>
                            <tr>
                                <th>Organization Name</th>
                                <th></th>
                            </tr>
                        </thead>
                    <?php foreach($organizations as $organization){ ?>
                        <tr>
                            <td style="width: 100%"><?php echo $organization->name; ?></td>
                            <td><a href="/buying-club/delete-organization?organization_id=<?php echo $organization->organization_id; ?>&buying_club_id=<?php echo $buyingClub->id; ?>" class="btn btn--s btn--primary btn--block">Delete</td>
                        </tr>
                    <?php } ?>
                    </table>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </section>
</div>

<script src="/assets/js/buyingClubs.js"></script>

<?php include(INCLUDE_PATH . '/_inc/shared/modals/upload-buying-club-data.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/edit-list.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/footer-' . $userType . '.php'); ?>
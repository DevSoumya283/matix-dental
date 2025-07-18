
<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px;">
                    <?php //include(INCLUDE_PATH.'/admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <!-- Customers -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Organizations</h3>
                            </div>
                            <div class="wrapper__inner">
                                <form method="get" action="<?php echo base_url();?>organizations-list">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by customer name, company, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--primary btn--m contextual--hide modal--toggle" data-target="#newOrganizationModal">New Organization</button>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar col col--2-of-12" style="padding: 12px;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Organization
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Created
                                </th>
                                <th>
                                    Role
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Return -->
                            <?php if(isset($organizations_list)) { ?>
                            <?php foreach ($organizations_list as $list) { ?>
                            <tr>
                                <td>
                                    <a class="link fontWeight--2" href="<?php echo base_url();?>organization-details-page?organization_id=<?php echo $list->id; ?>"><?php echo $list->organization_name; ?></a>
                                </td>
                                <td>
                                    <?php echo $list->organization_type; ?>
                                </td>
                                <td>
                                    <?php echo date('M d, Y',strtotime($list->created_at)); ?>
                                </td>
                                <td>
                                    <?php echo $list->role_name; ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                                <tr>
                                <td colspan="5">No organization(s) found</td>
                            </tr>
                            <?php } ?>
                            <!-- Single Return -->
                        </tbody>
                    </table>
                    </div>
                    <!-- /Customers -->
                    <?php echo $this->pagination->create_links(); ?>
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH.'/_inc/shared/modals/new-organization.php');?>
<?php $this->load->view('templates/_inc/shared/modals/new-organization.php'); ?>



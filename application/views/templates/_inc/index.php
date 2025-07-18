<?php require_once('../_inc/header.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-r">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Content Area -->
                <div class="content col col--8-of-12 padding--l no--pad-l">

                    <!-- Vendor Info -->
                    <div class="product product--list row multi--vendor has--promos has--sale content__block">
                        <div class="product__image col col--3-of-12 col--am">
                            <div class="product__thumb no--border" style="background-image:url('<?php echo ROOT_PATH . 'assets/img/ph-vendor-logo.png'; ?>');"></div>
                        </div>
                        <div class="product__data col col--8-of-12 col--push-1-of-12 col--am">
                            <h2>Star Dental Supply, Inc., Inc.</h2>
                            <p>Star Dental Supply, Inc. is a full-service dental supply company and authorized distributor of the world's top dental and healthcare equipment/supply manufacturers.</p>
                            <div class="ratings__box">
                                <div class="ratings__wrapper show--qty" data-raters="59" style="margin-left:-4px;">
                                    <div class="ratings">
                                        <div class="stars" data-rating="100"></div>
                                    </div>
                                </div>
                                <a class="link" href="#reviews">Read customer reviews</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Vendor Info -->
                    <hr>
                    <!-- About Us -->
                    <div id="about" class="content__block">
                        <h3 class="title textColor--dark-gray">
                            <svg class="icon icon--info"><use xlink:href="#icon-info"></use></svg>
                            About Us
                        </h3>
                        <p>At Star, we believe that real value is not only in saving you money on the dental supplies you need, but also in saving you time and effort in the process. That is why we built an environment for our customers around simple ordering, clearly posted savings, thousands of the leading brands and end-to-end customer service. And with our one-click promotion redemption feature you save time and effort on the free stuff too.</p>
                        <p>From online ordering made simple to truly knowledgeable Client Service Specialists at your service, Star helps you find what you need, save where you want and get on with your day. Start simplifying your business.</p>
                    </div>
                    <!-- /About Us -->
                    <hr>
                    <!-- Ratings & Reviews -->
                    <div id="reviews" class="content__block">
                        <div class="heading__group wrapper">
                            <div class="wrapper__inner">
                                <h3 class="title textColor--dark-gray">
                                    <svg class="icon icon--star-outline"><use xlink:href="#icon-star-outline"></use></svg>
                                    Ratings &amp; Reviews
                                </h3>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--primary btn--s modal--toggle" data-target="#vendorReviewModal">Write a Review</button>
                            </div>
                        </div>
                        <ul class="list list--ratings list--inline list--divided padding--m no--pad-lr no--pad-t">
                            <li class="item">
                                <h5>Overall</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="100"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5>Shipping Speed</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="100"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5>Customer Service</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="100"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5>Ease of Transaction</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="100"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5>Responsiveness</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="100"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="reviews__all">
                            <div class="padding--xs no--pad-lr border--1 border--solid border--light border--tb">
                                <div class="wrapper">
                                    <div class="wrapper__inner">
                                        <h4 class="no--margin">All Reviews</h4>
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <div class="select select--text">
                                            <label class="label">Sort by</label>
                                            <select aria-label="Select a Sorting Option">
                                                <option selected>Top Rated</option>
                                                <option value="1">Most Recent</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Review -->
                            <div class="review">
                                <h3 class="title">Always fast and reliable</h3>
                                <span class="review__meta">
                                    Top Rated Review by Kate McCallister on March 7, 2016
                                    <div class="ratings__wrapper ratings--s">
                                        <div class="ratings">
                                            <div class="stars" data-rating="80"></div>
                                        </div>
                                    </div>
                                </span>
                                <p class="review__text">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque non volutpat purus. Aliquam erat volutpat. Suspendisse porttitor mollis arcu. Phasellus efficitur porttitor mauris, sit amet congue mauris suscipit aliquam. Ut vel tincidunt lectu.
                                </p>
                                <span class="voting__meta">
                                    <span class="fontWeight--2">213</span>
                                    people found Kate's review helpful. Was this helpful to you?
                                    <ul class="voting__links list list--inline">
                                        <li><a class="link" href="">Yes</a></li>
                                        <li><a class="link" href="">No</a></li>
                                        <li>|</li>
                                        <li><a class="link modal--toggle" data-target="#flagReviewModal" href="javascript:void(0);">Flag It</a></li>
                                    </ul>
                                </span>
                            </div>
                            <!-- /Single Review -->

                            <a class="link link--expand" href="javascript:;">(+) Load more reviews</a>
                        </div>
                    </div>
                    <!-- /Ratings & Reviews -->
                </div>
                <!-- /Content Area -->

                <!-- Sidebar -->
                <div class="sidebar col col--4-of-12">

                    <!-- View Products -->
                    <div class="sidebar__group">
                        <h4 class="textColor--dark-gray">Product Catalog</h4>
                        <button class="btn btn--primary btn--l btn--block is--link" data-target="<?php echo ROOT_PATH . 'templates/browse'; ?>">View Our Products</button>
                    </div>
                    <!-- /View Products -->

                    <!-- Contact Info -->
                    <div class="sidebar__group">
                        <h4>Contact Information</h4>
                        <div class="list__combo">
                            <ul class="list list--box">
                                <li class="item">
                                    <div class="text__group group--equal">
                                        <span class="line--main">Mailing Address</span>
                                        <span class="line--main">
                                            28703 Industry Drive<br>
                                            Valencia, CA 91355
                                        </span>
                                    </div>
                                    <div class="text__group">
                                        <span class="line--main">Phone Number</span>
                                        <span class="line--main">
                                            1-800-350-4568
                                        </span>
                                    </div>
                                    <div class="text__group">
                                        <span class="line--main">Business Hours</span>
                                        <span class="line--main">
                                            M&ndash;F 6:30AM &mdash; 5:00PM PST
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /Contact Info -->

                    <!-- Vendor Policies -->
                    <div class="sidebar__group">
                        <h4>Vendor Policies</h4>
                        <ul class="list list--box">
                            <li class="item">
                                <div class="text__group">
                                    <span class="line--main">Free Shipping</span>
                                    <span class="line--sub">All orders over $500</span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="text__group">
                                    <span class="line--main">Free Returns</span>
                                    <span class="line--sub">On all eligible products</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /Vendor Policies -->

                    <!-- Shipping Options -->
                    <div class="sidebar__group">
                        <h4>Shipping Options</h4>
                        <ul class="list list--box">
                            <li class="item">
                                <div class="wrapper">
                                    <div class="wrapper__inner">
                                        <div class="text__group">
                                            <span class="line--main">
                                                Standard Ground
                                            </span>
                                        </div>
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <div class="text__group">
                                            <span class="line--main">
                                                5&ndash;7
                                                <span class="fontWeight--1">
                                                    Days
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="wrapper">
                                    <div class="wrapper__inner">
                                        <div class="text__group">
                                            <span class="line--main">
                                                Priority Ground
                                            </span>
                                        </div>
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <div class="text__group">
                                            <span class="line--main">
                                                4&ndash;6
                                                <span class="fontWeight--1">
                                                    Days
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="wrapper">
                                    <div class="wrapper__inner">
                                        <div class="text__group">
                                            <span class="line--main">
                                                Shipping Option Name
                                            </span>
                                        </div>
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <div class="text__group">
                                            <span class="line--main">
                                                1&ndash;2
                                                <span class="fontWeight--1">
                                                    Days
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /Shipping Options -->

                </div>
                <!-- /Sidebar -->

            </div>
        </div>
    </section>
<!-- /Content Section -->

<?php require_once('../_inc/shared/modals/ask-question.php'); ?>
<?php require_once('../_inc/shared/modals/answer-question.php'); ?>
<?php require_once('../_inc/shared/modals/vendor-review.php'); ?>
<?php require_once('../_inc/shared/modals/flag-review.php'); ?>

<?php require_once('../_inc/footer.php'); ?>

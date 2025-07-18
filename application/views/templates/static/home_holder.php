<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <?php if($herotype == 'image' ){ ?>

    <style>
        #bannerHome {
            background-image: url(/assets/img/heros/<?php echo $page->hero; ?>) !important;
        }
    </style>
    <div id="bannerHome" class="banner banner--m">
        <div class="wrapper">
            <div class="wrapper__inner">
                <h1 class="textColor--white" style="background-color: #<?php echo $this->config->item('bg-color'); ?>; opacity: .5;">
                    <span class="fontSize--l disp--block margin--xs no--margin-t no--margin-lr">Welcome to <?php echo $this->config->item('name'); ?></span>
                    <?php echo $page->tagline; ?>
                </h1>
                <a class="btn btn--l btn--primary is--pos btn--dir is--next" href="/signin">Sign Up Today</a>
                <a class="btn btn--l btn--primary btn--dir is--next" href="/how-it-works">How It Works</a>
            </div>
        </div>
    </div>
    <?php } else if( $herotype == 'video'){ ?>
    <div class="">
        <div id="home-vid" style="">
            <video id="bgvid" style="width: 100%;" playsinline autoplay muted loop>
                <!--
                - Video needs to be muted, since Chrome 66+ will not autoplay video with sound.
                WCAG general accessibility recommendation is that media such as background video play through only once. Loop turned on for the purposes of illustration; if removed, the end of the video will fade in the same way created by pressing the "Pause" button  -->

                <source src="/assets/img/heros/<?php echo $page->hero; ?>" type="video/mp4">
            </video>
            <div id="float-text">
                <h1 class="textColor--white" style="background-color: #<?php echo $this->config->item('bg-color'); ?>; opacity: .5;">
                    <span class="fontSize--l disp--block margin--xs no--margin-t no--margin-lr">Welcome to <?php echo $this->config->item('name'); ?></span>
                    <?php echo $page->tagline; ?>
                </h1>
                <a class="btn btn--l btn--primary is--pos btn--dir is--next" href="/signin">Sign Up Today</a>
                <a class="btn btn--l btn--primary btn--dir is--next" href="/how-it-works">How It Works</a>
            </div>
        </div>
    </div>
    <?php } ?>


  <section class="gap70">

    <div class="container">
        <h2 class="text-center">Popular Categories</h2>
        <div id="catowl" class=" owl-carousel owl-theme">
                <?php
                foreach($categoryLinks as $link){ ?>
                    <!-- <div class="col col--1-of-6">
                        <a class="card card--img is--link align--center" data-target="/home?category=<?php echo $link->category_id; ?>&parent=1&catRoot=classic">
                            <img src="/uploads/sites/catmenu/<?php echo $link->category_image; ?>" alt="Dental Burs">
                            <span class="card__label"><?php echo $link->category_name; ?></span>
                        </a>
                    </div> -->

                    <div class="item item-box">
                      <div class="item-img">
                        <a href="/home?category=<?php echo $link->category_id; ?>&parent=1catRoot=classic">
                          <img src="/uploads/sites/catmenu/<?php echo $link->category_image; ?>" alt="Gloves">
                        </a>
                      </div>
                      <h4 class="text-center"><?php echo $link->category_name; ?></h4>
                    </div>
                <?php } ?>
        </div>
            <!-- /Content Area -->
    </section>



</div>
<!-- /Content Section -->

<section class="gap70">
    <div class="container">
        <?php echo $page->content; ?>
    </div>
</section>

<script>

  $('#catowl').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    dots:true,
    slideSpeed: 10,
    autoplay:true,
    autoplayHoverPause:true,
    responsive:{
      0:{
        items:1
      },
      460:{
        items:1
      },
      600:{
        items:3,
        slideBy: 1
      },
      1000:{
        items:3,
        slideBy: 1
      },
      1100:{
        items:5,
        slideBy: 1
      }
    }
  })
</script>

<!-- Content Section -->
<div class="overlay__wrapper">
  <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

  <div class="container-fluid no-padding">
    <div id="bannerHome" class="banner banner--m">
      <div class="wrapper">
        <div class="wrapper__inner">-
          <h1 class="textColor--white">
            <span class="fontSize--l disp--block margin--xs no--margin-t no--margin-lr">Welcome to <?php echo $this->config->item('name'); ?></span>
            <?php echo $this->config->item('tagline'); ?>
          </h1>
          <a class="btn btn--l btn-primary is--pos btn--dir is--next" href="/signin">Sign Up Today</a>
          <a class="btn btn--l btn--primary btn--dir is--next" href="/how-it-works">How It Works</a>
        </div>
      </div>
    </div>
  </div>
  <!-- END OF BANNER -->
  <section class="gap70">

    <div class="container">
      <h2 class="text-center">Popular Categories</h2>
      <div id="catowl" class=" owl-carousel owl-theme">
        <div class="item item-box">
          <div class="item-img">
            <a href="/home?category=24001&parent=87&catRoot=dentist">
              <img src="/assets/img/cat-cadcam.jpg" alt="Restorative">
            </a>
          </div>
          <h4 class="text-center">Restorative</h4>

        </div>
        <div class="item item-box">
          <div class="item-img">
            <a href="/home?category=12&parent=1&catRoot=classic">
              <img src="/assets/img/placeholder/cats/cat-burs.jpg" alt="Burs">
            </a>
          </div>
          <h4 class="text-center">Burs</h4>
        </div>
        <div class="item item-box">
          <div class="item-img">
            <a href="/home?category=392&parent=29catRoot=classic">
              <img src="/assets/img/placeholder/cats/cat-burs.jpg" alt="Gloves">
            </a>
          </div>
          <h4 class="text-center">Gloves</h4>
        </div>
        <div class="item item-box">
          <div class="item-img">
            <a href="/home?category=28&parent=1&catRoot=classic">
              <img src="/assets/img/placeholder/cats/cat-trays.jpg" alt="Impressions">
            </a>
          </div>
          <h4 class="text-center">Impressions</h4>
        </div>
        <div class="item item-box">
          <div class="item-img">
            <a href="/home?category=30&parent=1&catRoot=classic">
              <img src="/assets/img/cat-instruments.jpg" alt="Instruments">
            </a>
          </div>
          <h4 class="text-center">Instruments</h4>
        </div>
        <div class="item item-box">
          <div class="item-img">
            <a href="/home?category=44&parent=1&catRoot=classic">
              <img src="/assets/img/placeholder/cats/cat-orthodontics.jpg" alt="Orthodontics">
            </a>
          </div>
          <h4 class="text-center">Orthodontics</h4>
        </div>
      </div>
      <div class="text-center padding--m no--pad-lr no--pad-b browse-products">
        <a class="link fontSize--s fontWeight--2" href="/templates/browse">Browse All Products</a>
      </div>
    </div>




  </section>


  <section class="gap50">
   <div class="container">
     <div class="align--center margin--xxxl no--margin-lr no--margin-t">
       <h4>The Matix Advantage</h4>
       <h2>Less time. Better management. Cost saving.</h2>
     </div>
     <div class="topgap50">
       <div class="row">
         <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
           <div class="">
            <img class="img-fluid" src="/assets/img/home-tiered-users.jpg" alt="image">
          </div>
        </div>
        <div class="col-xl-7 offset-xl-1 col-lg-7 offset-lg-1 col-md-7 offset-md-1 col-sm-8 col-xs-12">
          <h2 class="fontSize--l">Time is Money</h2>
          <p>Creating a high performing, patient-centric dental practice means focusing less on administrative tasks, and more on patient care. Matix reduces the administrative workload, while restoring your control over the bottom line.</p>
          <hr style="width:25%;">
          <p class="fontSize--l">Tiered Users</p>
          <p>Delegation through limited authorization allows the majority of searching, price comparison, inventory and ordering tasks to junior staff while maintaining total control over purchases and practice data.  The decision-making is back where it belongs, in your hands.</p>
          <a class="btn btn--m btn--tertiary" href="/login">Sign Up</a>
        </div>
      </div>

      <div class="row gap40">
        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-8 col-xs-12">
         <h2 class="fontSize--l">Trusted Vendors</h2>
         <p>The Matix model lets you search and shop directly from your favorite vendor or manufacturer and, from multiple vendors or manufacturers all from one platform. Whether you want to compare prices, find special offers or explore product options the Matix platform gives you instant access to a variety of suppliers and their pricing before making a purchase decision.</p>
         <a class="btn btn--m btn--tertiary" href="/login">Sign Up</a>
       </div>
       <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
         <div class="">
          <img class="img-fluid" src="/assets/img/home-price-transparency.jpg" alt="image">
        </div>
      </div>
    </div>

    <div class="row">
     <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
       <div class="">
        <img class="img-fluid" src="/assets/img/home-multiple-locations.jpg" alt="image">
      </div>
    </div>
    <div class="col-xl-7 offset-xl-1 col-lg-7 offset-lg-1 col-md-7 offset-md-1 col-sm-8 col-xs-12">
      <h2 class="fontSize--l">Manage Multiple Locations</h2>
      <p>Multiple locations? No Problem!  Our simplified management system allows a dentist-owner to order, ship, track, report and reorder for multiple locations. With Matix, you set budgets and analyze spending for a single location and on a practice wide basis. </p>
      <hr style="width:25%;">
      <p class="fontWeight--2">Cost Analysis Reporting</p>
      <p>Easy-to-use reporting tools improve decision making and help maintain cost efficiency. Matix gives you the tools to analyze spending patterns, product preferences, user and location activity, historic data, and more.</p>
      <a class="btn btn--m btn--tertiary" href="/login">Sign Up</a>
    </div>
  </div>

</div>

</div>

</section>



</div>
<!-- /Content Section -->

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

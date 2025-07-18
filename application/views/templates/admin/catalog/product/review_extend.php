<!-- Single Review -->
<div class="Extended_review">
    <?php
    for ($i = 0; $i < count($product_review); $i++) {
        $originalDate = $product_review[$i]->updated_at;
        $newDate = date("F j, Y", strtotime($originalDate));
        $db_rating = floatval($product_review[$i]->rating);
        $ratings = $db_rating * 20;
        ?>
        <div class="review">
            <h3 class="title"><?php echo ucfirst($product_review[$i]->title); ?></h3>
            <span class="review__meta">
                Top Rated Review by <?php echo ucfirst($product_review[$i]->users->first_name); ?> on <?php echo $newDate; ?>
                <div class="ratings__wrapper ratings--s">
                    <div class="ratings">
                        <div class="stars" data-rating="<?php echo $ratings; ?>"></div>
                    </div>
                </div>
            </span>
            <p class="review__text">
                <?php echo ucfirst($product_review[$i]->comment); ?>
            </p>
            <span class="voting__meta">
                <span class="fontWeight--2"><?php echo $product_review[$i]->upvotes; ?></span>
                <?php if ($product_review[$i]->upvotes != null) { ?>
                    people found this helpful. Was this helpful to you?
                <?php } else { ?> Was this helpful to you? <?php } ?>
                <a class="link fontSize--s fontWeight--2 is--neg" href="<?php echo base_url() ?>delete-Product-Review?review_id=<?php echo $product_review[$i]->id; ?> &product_id=<?php echo $product_review[$i]->model_id; ?>">Delete</a>
            </span>
        </div>
    <?php } ?>
    <!-- /Single Review -->
</div>


<!-- Include Font Awesome. -->
<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<!-- Include Editor style. -->
<link href="<?php echo base_url(); ?>lib/froala-editor/css/froala_editor.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>lib/froala-editor/css/froala_style.min.css" rel="stylesheet" type="text/css" />

<!-- Content Section -->
<form id="productEditForm" action="<?php echo base_url(); ?>update-product-SPdashboard" method="post" enctype="multipart/form-data">

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>product-catalog">Catalog</a>
                </li>
                <li class="item is--active">
                    Product
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->

    <section class="content__wrapper has--sidebar-r">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Content Area -->
                <div class="content col col--8-of-12 padding--l no--pad-l">

                    <h3 class="title textColor--dark-gray">
                        <svg class="icon icon--info"><use xlink:href="#icon-details"></use></svg>
                        Product Description
                    </h3>
                    <div class="form__group">
                        <div class="row">
                            <div class="input__group is--inline">
                                <input id="productID" name="productId" type="hidden" value="<?php echo $product_details->id; ?>">
                                <input id="productName" name="productName" type="text" class="input not--empty" value="<?php echo $product_details->name; ?>" required>
                                <label class="label" for="productName">Product Name</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- Product Description -->
                    <textarea class="input input--wysiwyg" name="description">
                        <?php echo $product_details->description; ?>
                    </textarea>
                    <!-- /Product Description -->
                    <hr>
                    <!-- Product Details -->
                    <div id="details">
                        <h3 class="title textColor--dark-gray">
                            <svg class="icon icon--info"><use xlink:href="#icon-info"></use></svg>
                            Product Details
                        </h3>
                        <h5 class="title">Required Info:</h5>
                        <table class="table table--horizontal table--align-lr">
                            <tbody>
                                <tr>
                                    <td width="40%">Manufacturer</td>
                                    <td width="60%">
                                        <div class="input__group is--inline">
                                            <input id="productMfr" name="productMfr" type="text" class="input not--empty" value="<?php echo $product_details->manufacturer; ?>" required>
                                            <label class="label" for="productMfr">Manufacturer</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>License Required</td>
                                    <td>
                                        <div class="select">
                                            <select name="license_required">
                                                <option disabled>Make a Selection</option>
                                                <option value="Yes" <?php echo ($product_details->license_required == "Yes" ) ? "selected" : ""; ?>>
                                                    Required
                                                </option>
                                                <option value="No" <?php echo ($product_details->license_required == "" || $product_details->license_required == "No" ) ? "selected" : ""; ?>>
                                                    Not Required
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quantity/Unit</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="productQtyPerUnit" name="productQtyPerUnit" class="input not--empty" value="<?php echo $product_details->quantity_per_box; ?>">
                                            <label class="label" for="productQtyPerUnit">Quantity/Unit</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Returnable</td>
                                    <td>
                                        <div class="select">
                                            <select name="returnable">
                                                <option disabled>Make a Selection</option>
                                                <option selected value="Yes" <?php echo ($product_details->returnable == "Yes") ? "selected" : ""; ?>>
                                                    Yes
                                                </option>
                                                <option value="No" <?php echo ($product_details->returnable == "No") ? "selected" : ""; ?>>
                                                    No
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Contents</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="productContents" name="productContents" type="text" class="input not--empty" value="<?php echo $product_details->contents; ?>">
                                            <label class="label" for="productContents">Contents</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Set Rate</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="productSetRate" name="set_rate" type="text" class="input not--empty" value="<?php echo $product_details->set_rate; ?>">
                                            <label class="label" for="productSetRate">Set Rate</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Viscosity</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="productViscosity" name="viscosity" type="text" class="input not--empty" value="<?php echo $product_details->viscosity; ?>">
                                            <label class="label" for="productViscosity">Viscosity</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Band Thickness</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="productband_thickness" name="band_thickness" type="text" class="input not--empty" value="<?php echo $product_details->band_thickness; ?>">
                                            <label class="label" for="productband_thickness">Band Thickness</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Weight</td>
                                    <td>
                                        <div class="row">
                                            <div class="col col--8-of-12">
                                                <div class="input__group is--inline">
                                                    <input id="productWeight" name="productWeight" type="number" class="input not--empty" value="<?php echo $product_details->weight; ?>">
                                                </div>
                                            </div>
                                            <div class="col col--4-of-12 no--margin-r">
                                                <div class="select">
                                                    <select name="weight_type">
                                                        <option disabled>Make a Selection</option>
                                                        <option <?php if ($product_details->weight_type == 'Ounces') echo 'selected'; ?>  value="1">Ounces</option>
                                                        <option <?php if ($product_details->weight_type == 'Pounds') echo 'selected'; ?>  value="2">Pounds</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!--                                NEWLY ADDED FIELDS-->
                                <tr>
                                    <td>Handle Size</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="handle_size" type="text" class="input not--empty" value="<?php echo $product_details->handle_size; ?>">
                                            <label class="label" for="producttax_per_state">Handle Size</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Handle Finish</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="handle_finish" type="text" class="input not--empty" value="<?php echo $product_details->handle_finish; ?>">
                                            <label class="label" for="producttax_per_state">Handle Finish</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tip Finish</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="tip_finish" type="text" class="input not--empty" value="<?php echo $product_details->tip_finish; ?>">
                                            <label class="label" for="producttax_per_state">Tip Finish</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tip Diameter</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="tip_diameter" type="text" class="input not--empty" value="<?php echo $product_details->tip_diameter; ?>">
                                            <label class="label" for="producttax_per_state">Tip Diameter</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tip Material</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="tip_material" type="text" class="input not--empty" value="<?php echo $product_details->tip_material; ?>">
                                            <label class="label" for="producttax_per_state">Tip Material</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Head Diameter</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="head_diameter" type="text" class="input not--empty" value="<?php echo $product_details->head_diameter; ?>">
                                            <label class="label" for="producttax_per_state">Head Diameter</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Head Length</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="head_length" type="text" class="input not--empty" value="<?php echo $product_details->head_length; ?>">
                                            <label class="label" for="producttax_per_state">Head Length</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Diameter</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="diameter" type="text" class="input not--empty" value="<?php echo $product_details->diameter; ?>">
                                            <label class="label" for="producttax_per_state">Diameter</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Category Code</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="category_code" type="text" class="input not--empty" value='<?php echo $product_details->category_id; ?>'>
                                            <label class="label" for="producttax_per_state">Category Code</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Arch</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="arch" type="text" class="input not--empty" value="<?php echo $product_details->arch; ?>">
                                            <label class="label" for="producttax_per_state">Arch</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shaft Dimensions</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="shaft_dimensions" type="text" class="input not--empty" value="<?php echo $product_details->shaft_dimensions; ?>">
                                            <label class="label" for="producttax_per_state">Shaft Dimensions</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shaft Description</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="shaft_description" type="text" class="input not--empty" value="<?php echo $product_details->shaft_description; ?>">
                                            <label class="label" for="producttax_per_state">Shaft Description</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Blade Description</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="blade_description" type="text" class="input not--empty" value="<?php echo $product_details->blade_description; ?>">
                                            <label class="label" for="producttax_per_state">Blade Description</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Anatomic Use</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="anatomic_use" name="anatomic_use" type="text" class="input not--empty" value="<?php echo $product_details->anatomic_use; ?>">
                                            <label class="label" for="anatomic_use">Anatomic Use</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Instrument Description</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="instrument_description" name="instrument_description" type="text" class="input not--empty" value="<?php echo $product_details->instrument_description; ?>">
                                            <label class="label" for="instrument_description">Instrument Description</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Palm Thickness</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="palm_thickness" type="text" class="input not--empty" value="<?php echo $product_details->palm_thickness; ?>">
                                            <label class="label" for="producttax_per_state">Palm Thickness</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Finger Thickness</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="finger_thickness" type="text" class="input not--empty" value="<?php echo $product_details->finger_thickness; ?>">
                                            <label class="label" for="producttax_per_state">Finger Thickness</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Texture</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="texture" type="text" class="input not--empty" value="<?php echo $product_details->texture; ?>">
                                            <label class="label" for="producttax_per_state">Texture</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Delivery System</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="productdelivery_system" name="delivery_system" type="text" class="input not--empty" value="<?php echo $product_details->delivery_system; ?>">
                                            <label class="label" for="productdelivery_system">Delivery System</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Volume</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="productvolume" name="volume" type="text" class="input not--empty" value="<?php echo $product_details->volume; ?>">
                                            <label class="label" for="productvolume">Volume</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dimensions</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="productdimensions" name="dimensions" type="text" class="input not--empty" value="<?php echo $product_details->dimensions; ?>">
                                            <label class="label" for="productdimensions">Dimensions</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Stone Type</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="productstone_type" name="stone_type" type="text" class="input not--empty" value="<?php echo $product_details->stone_type; ?>">
                                            <label class="label" for="productstone_type">Stone Type</label>
                                        </div>
                                    </td>
                                </tr>






                                <!--                                NEWLY ADDED FIELDS-->



                                <tr>
                                    <td>Tax Per State</td>
                                    <td>
                                        <div class="input__group is--inline">
                                            <input id="producttax_per_state" name="tax_per_state" type="text" class="input not--empty" value="<?php echo $product_details->tax_per_state; ?>">
                                            <label class="label" for="producttax_per_state">Tax Per State</label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <h5 class="title">Additional Info:</h5>
                        <table class="table table--horizontal table--addable table--align-lr">
                            <tbody>
                                <?php if ($product_customes != null) { ?>
                                    <?php foreach ($product_customes as $value) { ?>
                                        <tr>
                                            <td width="40%">
                                                <div class="input__group is--inline">
                                                    <input id="productKey1" name="productKey1[]" type="text" class="input <?php echo ($value->field != "") ? "not--empty" : ""; ?>" value="<?php echo $value->field; ?>">
                                                    <label class="label" for="productKey1">Parameter</label>
                                                </div>
                                            </td>
                                            <td width="46%">
                                                <div class="input__group is--inline">
                                                    <input id="productValue1" name="productValue1[]" type="text" class="input  <?php echo ($value->value != "") ? "not--empty" : ""; ?>" value="<?php echo $value->value; ?>">
                                                    <label class="label" for="productValue1">Value</label>
                                                </div>
                                            </td>
                                            <td class="align--center">
                                                <button class="btn btn--s btn--icon btn--tertiary delete--row" type="button"><svg class="icon icon--minus"><use xlink:href="#icon-minus"></use></svg></button>
                                                <button class="btn btn--s btn--icon btn--tertiary add--row" type="button"><svg class="icon icon--plus"><use xlink:href="#icon-plus"></use></svg></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td width="40%">
                                            <div class="input__group is--inline">
                                                <input id="productKey1" name="productKey1[]" type="text" class="input" value="">
                                                <label class="label" for="productKey1">Parameter</label>
                                            </div>
                                        </td>
                                        <td width="46%">
                                            <div class="input__group is--inline">
                                                <input id="productValue1" name="productValue1[]" type="text" class="input" value="">
                                                <label class="label" for="productValue1">Value</label>
                                            </div>
                                        </td>
                                        <td class="align--center">
                                            <button class="btn btn--s btn--icon btn--tertiary delete--row" type="button"><svg class="icon icon--minus"><use xlink:href="#icon-minus"></use></svg></button>
                                            <button class="btn btn--s btn--icon btn--tertiary add--row" type="button"><svg class="icon icon--plus"><use xlink:href="#icon-plus"></use></svg></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /Product Details -->
                    <hr>
                    <!-- Questions & Answers -->
                    <div id="qa">
                        <div class="heading__group">
                            <h3>
                                <svg class="icon icon--questions"><use xlink:href="#icon-questions"></use></svg>
                                Questions &amp; Answers
                            </h3>
                        </div>

                        <!-- /Single Q&A Block (Multiple Answers) -->
                        <div class="qa">
                            <?php for ($i = 0; $i < count($product_questions); $i++) { ?>
                                <!-- Question -->
                                <div class="question row fontWeight--2">
                                    <div class="col col--1-of-8">
                                        <span class="fontSize--s">Question</span>
                                    </div>
                                    <div class="col col--7-of-8">
                                        <?php echo $product_questions[$i]->question; ?>
                                        <a class="link fontSize--s is--neg" href="<?php echo base_url(); ?>delete-product-Question?question_id=<?php echo $product_questions[$i]->id; ?> &product_id=<?php echo $product_details->id; ?>">Delete</a>
                                    </div>
                                </div>
                                <!-- /Question -->

                                <!-- /Top Answer -->
                                <?php
                                if ($product_questions[$i]->answers != null) {
                                    $answer_total = count($product_questions[$i]->answers);
                                    if ($answer_total > 0) {
                                        $answer_total = ($answer_total > 2) ? 2 : $answer_total;
                                        for ($j = 0; $j < $answer_total; $j++) {
                                            ?>
                                            <div class="answers__top row">
                                                <div class="col col--1-of-8">
                <!--                                                <span class="fontSize--s fontWeight--2">Top Answer</span>-->
                                                    <span class="fontSize--s fontWeight--2">Answer</span>
                                                </div>
                                                <div class="col col--7-of-8">
                                                    <div class="answer">
                                                        <?php print_r($product_questions[$i]->answers[$j]->answer); ?>
                                                        <span class="voting__meta">
                                                            <?php if ($product_questions[$i]->answers[$j]->upvotes != 0) { ?>
                                                                <span class="fontWeight--2">
                                                                    <?php
                                                                    print_r($product_questions[$i]->answers[$j]->upvotes);
                                                                    ?>
                                                                </span>
                                                                people found <span class="fontWeight--2"></span> answer helpful.
                                                            <?php } ?>
                                                            <a class="link fontSize--s is--neg" href="<?php echo base_url(); ?>delete-product-answer?answer_id=<?php echo $product_questions[$i]->answers[$j]->id; ?> &product_id=<?php echo $product_details->id; ?>">Delete</a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo "Not answered yet.";
                                    }
                                    ?>
                                    <div style="display:none;" id="more_answers_<?php echo $product_questions[$i]->id ?>">
                                        <div class="answers__top row">
                                            <div class="col col--1-of-8">
            <!--                                                <span class="fontSize--s fontWeight--2">Top Answer</span>-->
                                                <span class="fontSize--s fontWeight--2">Other Answers</span>
                                            </div>
                                            <div class="col col--7-of-8">
                                                <?php for ($j = 2; $j < count($product_questions[$i]->answers); $j++) { ?>
                                                    <div class="answer">
                                                        <?php print_r($product_questions[$i]->answers[$j]->answer); ?>
                                                        <span class="voting__meta">
                                                            <?php if ($product_questions[$i]->answers[$j]->upvotes != 0) { ?>
                                                                <span class="fontWeight--2">
                                                                    <?php
                                                                    print_r($product_questions[$i]->answers[$j]->upvotes);
                                                                    ?>
                                                                </span>
                                                                people found <span class="fontWeight--2"></span> answer helpful.
                                                            <?php } ?>
                                                            <a class="link fontSize--s is--neg" href="<?php echo base_url(); ?>delete-product-answer?answer_id=<?php echo $product_questions[$i]->answers[$j]->id; ?> &product_id=<?php echo $product_details->id; ?>">Delete</a>
                                                        </span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <?php if (($product_questions[$i]->answers != null) && count($product_questions[$i]->answers > 2)) { ?>
                                    <a onclick="$('#more_answers_<?php echo $product_questions[$i]->id ?>').toggle('show')" class="link link--expand" href="javascript:void(0);">(+) Show (<?php echo (count($product_questions[$i]->answers) - 2) ?>) more answers</a>
                                <?php } ?>
                            <?php } ?>
                            <!-- /Other Answers -->
                        </div>
                        <!-- /Single Q&A Block (Multiple Answers) -->

                    </div>
                    <!-- /Questions & Answers -->
                    <hr>
                    <!-- Ratings & Reviews -->
                    <div id="reviews">
                        <div class="heading__group">
                            <h3>
                                <svg class="icon icon--star-outline"><use xlink:href="#icon-star-outline"></use></svg>
                                Ratings &amp; Reviews
                            </h3>
                        </div>
                        <ul class="list list--ratings list--inline list--divided padding--m no--pad-lr no--pad-t">
                            <li class="item">
                                <h5><?php echo ($five_star != null) ? $five_star : "0"; ?> rated it:</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="100"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5><?php echo ($four_star != null) ? $four_star : "0"; ?> rated it:</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="80"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5><?php echo ($three_star != null) ? $three_star : "0"; ?> rated it:</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="60"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5><?php echo ($two_star != null) ? $two_star : "0"; ?> rated it:</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="40"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5><?php echo ($one_star != null) ? $one_star : "0"; ?> rated it:</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="20"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <div class="reviews__all">
                            <?php if ($product_review != null) { ?>
                                <div class="padding--xs no--pad-lr border--1 border--solid border--light border--tb">
                                    <div class="wrapper">
                                        <div class="wrapper__inner">
                                            <h4 class="no--margin">All Reviews</h4>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <div class="select select--text">
                                                <label class="label">Sort by</label>
                                                <select aria-label="Select a Sorting Option" class="AdminReview_Sort">
                                                    <option  value="rating" <?php echo ($selection == "created_at") ? "selected" : ""; ?>>Top Rated</option>
                                                    <option value="created_at" <?php echo ($selection == "created_at") ? "selected" : ""; ?>>Most Recent</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Single Review -->

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
                                            Top Rated Review by <?php echo ($product_review[$i]->users != null) ? ucfirst($product_review[$i]->users->first_name) : ""; ?> on <?php echo $newDate; ?>
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
                                            <a class="link fontSize--s fontWeight--2 is--neg" href="<?php echo base_url() ?>delete-Product-Review?review_id=<?php echo $product_review[$i]->id; ?> &product_id=<?php echo $product_details->id; ?>">Delete</a>
                                        </span>
                                    </div>
                                <?php } ?>
                                <div class="Extended_ReviewTwo">
                                </div>
                                <!-- /Single Review -->
                                <a class="link link--expand GetTwoMoreReviews"  href="javascript:;">(+) Load more reviews</a>
                            <?php } else { ?>
                                <p>No Reviews Found </p>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- /Ratings & Reviews -->

                </div>
                <!-- /Content Area -->

                <!-- Sidebar -->
                <div class="sidebar col col--4-of-12">
                    <div class="sidebar__group">
                        <h4>Tools:</h4>
                        <div class="well well--sectioned card">
                            <div class="well__row">
                                <div class="well__section">
                                    <div class="tab__group tab--block">
                                        <label class="tab">
                                            <input type="radio" name="active" value="1"<?php echo ($product_pricing->active == 1) ? "checked" : ""; ?>>
                                            <span>Active</span>
                                        </label>
                                        <label class="tab">
                                            <input type="radio" name="active" value="0"<?php echo ($product_pricing->active == 0) ? "checked" : ""; ?>>
                                            <span>Inactive</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="well__row bg--lightest-gray">
                                <div class="well__section">
                                    <button class="btn btn--primary btn--m btn--block save--toggle form--submit" data-target="#productEditForm">Publish Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar__group">
                        <h4>Product Images:</h4>
                        <div class="list__combo">
                            <ul class="list list--box has--btn">
                                <!-- Single Uploaded Image -->
                                <?php if ($product_images != null) { ?>
                                    <?php foreach ($product_images as $images) { ?>
                                        <?php if (file_exists("uploads/products/images/" . $images->photo)) { ?>
                                            <li class="item">
                                                <div class="wrapper">
                                                    <div class="wrapper__inner">
                                                        <label class="control control__radio has--promo">
                                                            <input type="radio" name="productImage" <?php echo ($images->image_type == 'mainimg') ? "checked" : ""; ?> value="<?php echo $images->id; ?>">
                                                            <div class="control__indicator"></div>
                                                            <div class="control__text">
                                                                <img width="32" src="<?php echo base_url(); ?>uploads/products/images/<?php echo $images->photo; ?>">
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="wrapper__inner align--right">
                                                        <div class="text__group">
                                                            <span class="line--main">
                                                                <?php echo number_format(filesize("uploads/products/images/" . $images->photo) / 1024, 2) ?> kB
                                                            </span>

                                                            <span class="line--sub"><a class="link is--neg" href="<?php echo base_url(); ?>product-image-delete?product_id=<?php echo $images->model_id; ?>&image_id=<?php echo $images->id; ?>">Delete</a></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                        <?php
                                    }
                                }
                                ?>
                                <!-- /Single Uploaded Image -->
                            </ul>
                            <div class="list__combo-footer">
                                <input type="file" name="productImages[]" multiple>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar__group">
                        <h4>Vendors:</h4>
                        <div class="well well--sectioned well--tight card">
                            <div class="well__row">
                                <div class="well__section">
                                    <div class="input__group input__group--inline">
                                        <input type="hidden" name="product_id" class="product_id" value="<?php echo $product_details->id; ?>">
                                        <input id="searchVendors" name="searchVendors" class="input input__text" type="search" placeholder="Search by name...">
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="well__row bg--lightest-gray">
                                <div class="well__section scroll--l">
                                    <ul class="list list--entities" id="vendor_results">
                                        <!-- Single Vendor -->
                                        <?php if ($vendor_list != null) { ?>
                                            <?php foreach ($vendor_list as $vendors) { ?>
                                                <li class="item card padding--xs cf">
                                                    <div class="wrapper">
                                                        <div class="wrapper__inner">
                                                            <?php echo $vendors->name; ?>
                                                        </div>
                                                        <div class="wrapper__inner align--right">
                                                            <?php if ($vendors->product_active != null) { ?>
                                                                <button class="btn btn--s <?php echo ($vendors->product_active->active == 1) ? "is--pos  inactiveProductPricing" : "btn--tertiary  AddingProductPricing"; ?> btn--toggle width--fixed-75 " data-before="Select" data-after="&#10003;" type="button" data-product_id="<?php echo $product_details->id; ?>" data-productPricing_id="<?php echo $vendors->product_active->id; ?>" value="<?php echo $vendors->id; ?>" <?php echo ($product_pricing->active == 0) ? "disabled" : "" ?>></button>
                                                            <?php } else { ?>
                                                                <button class="btn btn--s btn--tertiary btn--toggle width--fixed-75 AddingProductPricing" data-before="Select" data-after="&#10003;" type="button" data-product_id="<?php echo $product_details->id; ?>"  value="<?php echo $vendors->id; ?>" <?php echo ($product_pricing->active == 0) ? "disabled" : "" ?>></button>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <!-- /Single Vendor -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar__group">
                        <div class="well card">
                            <h5 class="title">Danger Zone</h5>
                            <button class="btn btn--s btn--block is--neg modal--toggle" data-target="#deleteProductModal" type="button">Delete Product</button>
                        </div>
                    </div>

                </div>
                <!-- /Sidebar -->

            </div>
        </div>
    </section>
</form>
<!-- /Content Section -->

<!-- Modals -->
<?php
include(INCLUDE_PATH . '/_inc/shared/modals/delete-product.php');

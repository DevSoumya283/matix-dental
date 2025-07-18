<!-- Add New License Modal -->
<div id="editPageModal" class="modal modal--xl">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header--xl center center--h align--left">
            <h2 class="margin--m no--margin-lr no--margin-t">Edit page - <span class="pageName"></span></h2>
        </div>

        <div class="modal__body--xl center center--h align--left cf">

            <form id="categoryForm" class="form__group" enctype="multipart/form-data" >
                <input type="hidden" name="site_id" class="site_id" />
                <input type="hidden" name="vendor_id" class="vendor_id" />
                <div class="row no--margin-l category-images is--hidden">
                    <h3>Category Links</h3>
                    <div class="row" id="catLinks">

                    </div>
                <hr>
                    <div class="row add-category">
                        <h3>
                            Add New Category Link
                        </h3>
                        <div class="col col--3-of-12">
                            <div class="input__group is--inline margin--xs no--margin-lr no--margin-t select">

                                <select name="category_id" id="categories">
                                    <option value="">Select Category</option>

                                </select>
                            </div>

                            <div class="input__group is--inline margin--xs no--margin-lr no--margin-t select" id="child_cat_holder" style="display: none;">
                                <select name="child_category_id" id="child_categories">
                                    <option value="">All</option>

                                </select>
                            </div>
                        </div>
                        <div class="col col--3-of-12">
                            <input id="categoryImage" name="categoryImage" class="input input--file not--empty" type="file" >
                        </div>
                        <div class="col col--3-of-12">
                            <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                                <input type="text" class="input" name="category_name" >
                                <label class="label" for="domain">Title</label>
                            </div>
                        </div>
                        <div class="col col--3-of-12">
                            <input type="submit" class="btn btn--m btn--primary is--pos btn--dir is--next addHomeCategory" value="Add Category Link">
                        </div>
                    </div>
                </div>
            </form>
                <hr>
            <form id="editPageForm" class="form__group" action="/white-labels/save-page" method="post" enctype="multipart/form-data" >
                <div class="row no--margin-l hero is--hidden" style="width:100%; height:300px; overflow:hidden;"></div>
                <div class="row no--margin-l hero-uploader is--hidden">
                    <div class="title">Upload Hero Banner</div>
                    <input id="whitelabelLogo" name="whitelabelHero" class="input input--file not--empty" type="file" >
                </div>
                <hr>
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="site_id" class="site_id" />
                <input type="hidden" name="name" id="page_name" />
                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t edit-page-title">
                    <input class="input" name="page_title" id="page_title" />
                    <label class="label" for="page_title">Page Title</label>
                </div>
                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t edit-page-title">
                    <input class="input" name="tagline" id="tagline" />
                    <label class="label" for="tagline">Tagline</label>
                </div>
                <div class="modal__content">
                    <textarea id="editor" name="content"></textarea>
                </div>
                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                    <input type="submit" class="btn btn--primary btn--m btn--dir is--pos" value="Update Page" />
                </div>
            </form>
        </div>

    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>

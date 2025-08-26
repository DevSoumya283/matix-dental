<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dropdown-menu {
            width: 700px;
        }

        .category-parent {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            cursor: pointer;
        }

        .category-parent a {
            color: #333;
            text-decoration: none;
        }

        .category-child {
            color: #666;
            font-size: 0.9em;
            margin-left: 15px;
            margin-bottom: 3px;
            cursor: pointer;
        }

        .category-child a {
            color: #666;
            text-decoration: none;
        }

        .category-child a:hover,
        .category-parent a:hover {
            color: #007bff;
        }

        .product-name {
            cursor: pointer;
            color: #007bff;
        }

        .product-name:hover {
            text-decoration: underline;
        }

        .product-list {
            display: block;
        }

        .product-details {
            display: none;
        }

        .table th {
            background-color: #2893FF !important;
        }

        .back-btn {
            margin-bottom: 20px;
        }

        .btn-secondary,
        .bg-dark {
            background-color: #2893FF !important;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('new-products') ?>">Matix-Dental</a>
            <ul class="navbar-nav me-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Categories</a>
                    <div class="dropdown-menu p-3" style="min-width:700px; height:400px; overflow:scroll;">
                        <div class="row">
                            <?php
                            // Loop through all parent categories
                            $col_count = 0;
                            foreach ($nav_categories as $cat):
                                if ($col_count % 3 == 0 && $col_count > 0): ?>
                        </div>
                        <div class="row">
                        <?php endif; ?>

                        <div class="col-md-4">
                            <h6><a href="<?= site_url('new-products/category/' . $cat->id) ?>" class="text-decoration-none"><?= $cat->name ?></a></h6>
                            <ul class="list-unstyled">
                                <?php
                                // Get subcategories for this parent category
                                $subcategories = $this->db->where('parent_id', $cat->id)->get('categories')->result();
                                foreach ($subcategories as $sub): ?>
                                    <li><a href="<?= site_url('new-products/category/' . $sub->id) ?>" class="text-muted small text-decoration-none"><?= $sub->name ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php
                                $col_count++;
                            endforeach; ?>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid mt-3">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <?php if (!empty($current_category)): ?>

                    <!-- Show parent if available -->
                    <?php if (!empty($sidebar_parent)): ?>
                        <div class="category-parent">
                            <a href="<?= site_url('new-products/category/' . $sidebar_parent->id) ?>">
                                <?= $sidebar_parent->name ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Show current category -->
                    <div class="category-current fw-bold text-primary">
                        <a href="<?= site_url('new-products/category/' . $current_category->id) ?>">
                            <?= $current_category->name ?>
                        </a>
                    </div>

                    <!-- Show children if available -->
                    <?php if (!empty($sidebar_children)): ?>
                        <ul class="category-children list-unstyled ms-3">
                            <?php foreach ($sidebar_children as $child): ?>
                                <li>
                                    <a href="<?= site_url('new-products/category/' . $child->id) ?>">
                                        <?= $child->name ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="alert alert-warning">No Category Selected!</div>
                <?php endif; ?>
            </div>





            <!-- Products -->
            <div class="col-md-9">
                <!-- Product List View -->
                <div id="product-list" class="product-list">
                    <?php if (!empty($products)): ?>
                        <div class="mb-3">
                            <h4>Products <?= isset($sidebar_category) && $sidebar_category ? 'in ' . $sidebar_category->name : '' ?> (<?= count($products) ?>)</h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>MPN</th>
                                        <th>Category</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $index => $p): ?>
                                        <tr>
                                            <td>
                                                <span class="product-name" onclick="showProductDetails(<?= $index ?>)">
                                                    <?= htmlspecialchars($p->name) ?>
                                                </span>
                                            </td>
                                            <td>$<?= number_format($p->base_price, 2) ?></td>
                                            <td><?= htmlspecialchars($p->mpn) ?></td>
                                            <td><?= isset($p->category_name) ? htmlspecialchars($p->category_name) : 'N/A' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <h5>No products found</h5>
                            <p>There are no products in this category.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Details View -->
                <div id="product-details" class="product-details">
                    <button class="btn btn-secondary back-btn" onclick="showProductList()">
                        ← Back to Products
                    </button>

                    <div id="product-details-content">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store product data for JavaScript access
        const products = <?= json_encode($products) ?>;

        function showProductDetails(index) {
            const product = products[index];

            document.getElementById('product-list').style.display = 'none';
            document.getElementById('product-details').style.display = 'block';

            let detailsHTML = `
                <div class="card">
                    <div class="card-header">
                        <h3>${product.name}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Matix ID:</strong> ${product.matix_id}</p>
                                <p><strong>MPN:</strong> ${product.mpn}</p>
                                <p><strong>Manufacturer:</strong> ${product.manufacturer}</p> 
                                <p><strong>Brand:</strong> ${product.brand}</p> 
                                <p><strong>Category:</strong> ${product.category_name || 'N/A'}</p>
                                <p><strong>Base Price:</strong> $${parseFloat(product.base_price).toFixed(2)}</p> 
                            </div>
                            
                        </div>
                        
            `;

            // Add Pricings
            if (product.pricings && product.pricings.length > 0) {
                detailsHTML += `
                    <h5>Product Pricings</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Retail Price</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${product.pricings.map(pricing => `
                                    <tr>
                                        <td>${pricing.sku}</td>
                                        <td>$${parseFloat(pricing.price).toFixed(2)}</td>
                                        <td>$${parseFloat(pricing.retail_price).toFixed(2)}</td>
                                        <td>${pricing.quantity}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            // Add SKUs
            if (product.skus && product.skus.length > 0) {
                detailsHTML += `
                    <h5>VARIENTS</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Sku Code</th>
                                    <th>Price</th>
                                    <th>Retail Price</th>
                                    <th>Stock</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${product.skus.map(sku => `
                                    <tr>
                                        <td>${sku.sku_code}</td>
                                        <td>$${parseFloat(sku.price).toFixed(2)}</td>
                                        <td>$${parseFloat(sku.retail_price).toFixed(2)}</td>
                                        <td>${sku.stock_quantity}</td>
                                        <td>
                                            ${sku.options ? sku.options.map(opt => `<span class="badge bg-secondary">${opt.option_type}: ${opt.value}</span>`).join(' ') : ''}
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            // Add Options
            if (product.options && product.options.length > 0) {
                detailsHTML += `
                    <h5>Available Options</h5>
                    <ul>
                        ${product.options.map(opt => `<li>${opt.option_type}: ${opt.value}</li>`).join('')}
                    </ul>
                `;
            }

            detailsHTML += `
                    </div>
                </div>
            `;

            document.getElementById('product-details-content').innerHTML = detailsHTML;
        }

        function showProductList() {
            document.getElementById('product-details').style.display = 'none';
            document.getElementById('product-list').style.display = 'block';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
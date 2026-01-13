<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <style>
        table th,
        table td {
            white-space: nowrap;
        }
    </style>
</head>

<body class="p-4 bg-light">

    <div class="container">
        <h2 class="mb-4">Product Management System</h2>
        <ul class="nav nav-tabs" id="productTab" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#baseProduct">Base Product</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#addOption">Add Option</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#updateprice">Update Price</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#showtoption">Product Option's</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#varientproduct">Varient Product's</button></li>
        </ul>

        <div class="tab-content border border-top-0 p-3 bg-white" style="overflow: scroll;">

            <!-- BASE PRODUCT TAB -->
            <div class="tab-pane fade show active" id="baseProduct">
                <input type="file" id="baseExcel" class="form-control mb-3">
                <button id="saveToDb" class="btn btn-success my-3">Save to Database</button>
                <!-- <button id="exportAllBtn" class="btn btn-primary mt-3">Export All</button> -->
                <a href="<?php echo base_url('exportproducts'); ?>" class="btn btn-primary my-3">Export All</a>
                <div id="basePreview"></div>
            </div>

            <!-- ADD OPTION TAB -->
            <div class="tab-pane fade" id="addOption">
                <input type="file" id="baseExcel2" class="form-control mb-3">


                <!-- <select class="form-select" aria-label="Default select example" id="vendor_id" name="vendor_id" required>
                    <option value="">Loading vendors...</option>
                </select> -->


                <button id="saveToDb2" class="btn btn-success my-3">Upload Options</button>
                <a href="<?php echo base_url('export-newoptions'); ?>" class="btn btn-primary my-3">Export Options</a>

                <div id="productTable" class="table-responsive"></div>
            </div>


            <!-- Update Price TAB -->
            <div class="tab-pane fade" id="updateprice">
                <input type="file" id="updatepriceExcel" class="form-control mb-3">
                <button id="saveupdateprice" class="btn btn-success my-3">Save to Database</button>

                <div id="previewupdateprice"></div>
            </div>

            <!-- UPLOAD TAB -->

            <div class="tab-pane fade" id="showtoption">
                <div id="optionPreview"></div>
            </div>

            <!-- Varient Tab -->

            <div class="tab-pane fade" id="varientproduct">
                <table id="variantProductsTable" class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAllVariantProducts">
                            </th>
                            <th>Matix ID</th>
                            <th>Name</th>
                            <th>MPN</th>
                            <!-- <th>Parent Product</th> -->
                            <!-- <th>Variants</th> -->
                        </tr>
                    </thead>
                </table>
                <div class="mb-2">
                    <button id="downloadExcelVariant"
                            class="btn btn-success"
                            disabled>
                        Download Excel
                    </button>
                </div>
            </div>

        </div>
    </div>



    <script>
        let baseData = [];
        let allProducts = [];
        let excelData = [];

        $('#baseExcel').on('click', function() {
            this.value = ''; // Clear to allow same file re-upload
        });

        $('#baseExcel').change(function(e) {
            if (!e.target.files[0]) return;

            // Clear old data immediately
            $('#basePreview').html('Loading...');

            let reader = new FileReader();
           reader.onload = function(e) {
            let data = new Uint8Array(e.target.result);
            let workbook = XLSX.read(data, { type: 'array' });
            let sheet = workbook.Sheets[workbook.SheetNames[0]];
            let rawData = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });

            // Filter non-blank columns
            baseData = filterColumns(rawData);

            // ✅ Validate base price column (row[12])
            let errors = [];
            for (let i = 1; i < baseData.length; i++) {
                let row = baseData[i];
                let basePrice = row[13]; 
                if (basePrice === undefined || basePrice === null || basePrice.toString().trim() === '') {
                    errors.push(i + 1); 
                }
            }

        // If errors found, stop here
        if (errors.length > 0) {
            $('#basePreview').html('<div class="alert alert-danger">Validation failed</div>');
            showAlert(
                'error',
                `Base price mandatory at row(s): ${errors.join(', ')}`
            );
            return; 
        }

        // Build excelData only if valid
        excelData = baseData.slice(1).map(row => {
            let obj = {};
            baseData[0].forEach((key, i) => obj[key] = row[i]);
            return obj;
        });

        // Render table
        renderTable('#basePreview', baseData);
    };

            reader.readAsArrayBuffer(e.target.files[0]);
        });

        function filterColumns(data) {
            if (!data.length) return data;

            let keepColumns = [];
            for (let i = 0; i < data[0].length; i++) {
                let hasData = data.some(row => row[i] && row[i].toString().trim() !== '');
                if (hasData) keepColumns.push(i);
            }

            return data.map(row => keepColumns.map(i => row[i] || ''));
        }

        function renderTable(container, data) {
            if (!data.length) return;

            let html = '<table class="table table-bordered"><thead><tr>';
            data[0].forEach(h => html += `<th>${h}</th>`);
            html += '</tr></thead><tbody>';

            for (let i = 1; i < data.length; i++) {
                html += '<tr>';
                data[i].forEach(cell => html += `<td>${cell || ''}</td>`);
                html += '</tr>';
            }
            html += '</tbody></table>';
            $(container).html(html);
        }

        function showAlert(type, message) {
            const titleMap = {
                success: 'Success!',
                info: 'Info',
                warning: 'Warning!',
                error: 'Error!'
            };

            const iconMap = {
                success: 'success',
                info: 'info',
                warning: 'warning',
                error: 'error'
            };

            // Detect if message contains HTML tags
            const containsHTML = /<\/?[a-z][\s\S]*>/i.test(message);

            Swal.fire({
                icon: iconMap[type] || 'info',
                title: titleMap[type] || 'Notice',
                ...(containsHTML ? {
                    html: message
                } : {
                    text: message
                }),
                confirmButtonText: 'OK',
                showConfirmButton: true,
                allowOutsideClick: true
            });
        }


        // Call this on save button click
        $('#saveToDb').on('click', saveToDatabase);

        function saveToDatabase() {
            if (baseData.length === 0) {
                showAlert('error', 'No data to save. Please upload and process an Excel file first.');
                return;
            }

            const headers = baseData[0].map(h => h.trim()); // First row = headers
            const dataRows = baseData.slice(1); // All other rows

            // Ensure each row matches header length (pad if needed)
            const formattedRows = dataRows.map(row => {
                const paddedRow = [...row];
                while (paddedRow.length < headers.length) {
                    paddedRow.push('');
                }
                return paddedRow;
            });

            // Final excel data: header row + all data rows
            const excelData = [headers, ...formattedRows];

            const saveBtn = $('#saveToDb');
            const originalText = saveBtn.html();
            saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');

            $.ajax({
                url: 'upload', // Call your PHP controller method save_data()
                type: 'POST',
                data: {
                    excel_data: JSON.stringify(excelData),
                    file_name: 'uploaded_file.xlsx'
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        let message = result.message || 'Products saved successfully.';

                        if (result.warning) {
                            message += '<br><strong>Warning:</strong> ' + result.warning;
                            console.warn("Skipped Rows:", result.skipped_rows);
                        }

                        baseData = [];
                        $('#basePreview').html('');
                        $('#baseExcel').val('');
                        showAlert('success', message);

                        location.reload();
                    } else {
                        showAlert('error', result.message || 'Unknown error occurred');
                    }
                },
                error: function() {
                    showAlert('error', 'Failed to save data. Please check your network or server logs.');
                },
                complete: function() {
                    saveBtn.prop('disabled', false).html(originalText);
                }
            });
        }

        function loadBaseProducts() {
            fetch("<?php echo base_url('get_all_products'); ?>")
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.data.length > 0) {
                        let html = "<table class='table table-bordered table-striped'>";
                        html += "<thead><tr>";

                        // Table headers
                        Object.keys(data.data[0]).forEach(key => {
                            html += `<th>${key}</th>`;
                        });
                        html += "</tr></thead><tbody>";

                        // Table rows
                        data.data.forEach(row => {
                            html += "<tr>";
                            Object.values(row).forEach(val => {
                                html += `<td>${val}</td>`;
                            });
                            html += "</tr>";
                        });

                        html += "</tbody></table>";
                        document.getElementById('basePreview').innerHTML = html;
                    } else {
                        document.getElementById('basePreview').innerHTML = "<p class='alert alert-danger'>No data found In table!</p>";
                    }
                })
                .catch(() => {
                    showAlert('error', 'Failed to load product data.');
                });
        }

        // Trigger when switching tabs
        document.querySelector('[data-bs-target="#baseProduct"]').addEventListener('shown.bs.tab', loadBaseProducts);

        // Also trigger immediately if it's the active tab on page load
        if (document.querySelector('#baseProduct').classList.contains('active')) {
            loadBaseProducts();
        }



        // For 2nd Tab
        document.querySelector('[data-bs-target="#addOption"]').addEventListener('shown.bs.tab', function() {
            fetch("<?php echo base_url('get_all_options'); ?>")
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.data.length > 0) {
                        let html = "<table class='table table-bordered table-striped'>";
                        html += "<thead><tr>";

                        // Table headers
                        Object.keys(data.data[0]).forEach(key => {
                            html += `<th>${key}</th>`;
                        });
                        html += "</tr></thead><tbody>";

                        // Table rows
                        data.data.forEach(row => {
                            html += "<tr>";
                            Object.values(row).forEach(val => {
                                html += `<td>${val}</td>`;
                            });
                            html += "</tr>";
                        });

                        html += "</tbody></table>";
                        document.getElementById('productTable').innerHTML = html;
                    } else {
                        document.getElementById('productTable').innerHTML = "<p class='alert alert-danger'>No data found In table!</p>";
                    }
                });
        });

        document.getElementById('baseExcel2').addEventListener('change', function(e) {
            let file = e.target.files[0];
            if (!file) return;

            let reader = new FileReader();
            reader.onload = function(e) {
                let data = new Uint8Array(e.target.result);
                let workbook = XLSX.read(data, {
                    type: 'array'
                });

                let sheetName = workbook.SheetNames[0];
                let sheet = workbook.Sheets[sheetName];

                // Read raw data
                let rows = XLSX.utils.sheet_to_json(sheet, {
                    header: 1,
                    defval: ""
                });

                // Remove blank columns (based on header row)
                let header = rows[0];
                let keepIndexes = header.map((col, i) => col !== "" ? i : null).filter(i => i !== null);
                let filteredRows = rows.map(row => keepIndexes.map(i => row[i] || ""));

                let html = "<table class='table table-bordered table-striped'>";
                html += "<thead><tr>";
                filteredRows[0].forEach(header => {
                    html += `<th>${header}</th>`;
                });
                html += "</tr></thead><tbody>";

                for (let i = 1; i < filteredRows.length; i++) {
                    html += "<tr>";
                    filteredRows[i].forEach(cell => {
                        html += `<td>${cell}</td>`;
                    });
                    html += "</tr>";
                }
                html += "</tbody></table>";

                document.getElementById('productTable').innerHTML = html;

                // Store filtered rows for upload
                document.getElementById('saveToDb2').dataset.rows = JSON.stringify(filteredRows);
            };
            reader.readAsArrayBuffer(file);
        });

        // venodr Load
        // document.addEventListener("DOMContentLoaded", function() {
        //     fetch("<?php echo base_url('ajax_get_vendors'); ?>")
        //         .then(res => res.json())
        //         .then(vendors => {
        //             let vendorSelect = document.getElementById("vendor_id");
        //             vendorSelect.innerHTML = '<option value="">-- Select Vendor --</option>';
        //             vendors.forEach(v => {
        //                 let opt = document.createElement("option");
        //                 opt.value = v.id;
        //                 opt.textContent = v.name;
        //                 vendorSelect.appendChild(opt);
        //             });
        //         })
        //         .catch(() => {
        //             alert("Failed to load vendors.");
        //         });
        // });

        document.getElementById('saveToDb2').addEventListener('click', function() {
            let rows = this.dataset.rows;
            // let vendor_id = document.getElementById('vendor_id').value;

            if (!rows) {
                showAlert('warning', 'Please upload an Excel file first.');
                return;
            }
            // if (!vendor_id) {
            //     showAlert('warning', 'Please select a vendor.');
            //     return;
            // } + "&vendor_id=" + encodeURIComponent(vendor_id)

            fetch("<?php echo base_url('save_options_excel'); ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "rows=" + encodeURIComponent(rows) 
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'warning' && data.missing_products) {
                        showAlert('warning', `Product(s) not found: ${data.missing_products.join(', ')}`);
                    } else if (data.status === 'success') {
                        showAlert('success', data.message);
                    } else if (data.status === 'error') {
                        showAlert('error', data.message);
                    }

                    location.reload();
                })
                .catch(err => {
                    showAlert('error', 'Something went wrong while saving data.');
                });
        });



        // 4th tab 

        document.querySelector('[data-bs-target="#showtoption"]').addEventListener('shown.bs.tab', function () {

            fetch("<?= base_url('get_all_products_with_options'); ?>")
                .then(res => res.json())
                .then(data => {

                    if (data.status !== 'success' || data.data.length === 0) {
                        document.getElementById('optionPreview').innerHTML =
                            "<div class='alert alert-warning'>No data found.</div>";
                        return;
                    }

                    let html = `
                        <table id="optionTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>MPN</th>
                                    <th>SKU</th>
                                    <th>Option ID</th>
                                    <th>Option Type</th>
                                    <th>Option Code</th>
                                    <th>Option Value</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    data.data.forEach(row => {
                        html += `
                            <tr>
                                <td>${row.product_id ?? ''}</td>
                                <td>${row.name ?? ''}</td>
                                <td>${row.mpn ?? ''}</td>
                                <td>${row.sku_code ?? ''}</td>
                                <td>${row.option_id ?? ''}</td>
                                <td>${row.option_name ?? ''}</td>
                                <td>${row.option_code ?? ''}</td>
                                <td>${row.option_value ?? ''}</td>
                            </tr>
                        `;
                    });

                    html += `</tbody></table>`;

                    document.getElementById('optionPreview').innerHTML = html;

                    if ($.fn.DataTable.isDataTable('#optionTable')) {
                        $('#optionTable').DataTable().destroy();
                    }

                    // ✅ Initialize DataTable
                    $('#optionTable').DataTable({
                        pageLength: 10,
                        lengthMenu: [10, 25, 50, 100],
                        order: [[4, 'desc']],
                        scrollX: true
                    });
                });
        });



        // update price tab 

        document.querySelector('[data-bs-target="#updateprice"]').addEventListener('shown.bs.tab', function () {

            fetch("<?= base_url('get_vendors_list') ?>")
                .then(res => res.json())
                .then(response => {

                    if (response.status !== 'success') {
                        document.getElementById('previewupdateprice').innerHTML =
                            '<div class="alert alert-danger">Failed to load vendors</div>';
                        return;
                    }

                    let html = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Vendor ID</th>
                                    <th>Vendor Name</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    response.data.forEach(v => {
                        html += `
                            <tr>
                                <td>${v.id}</td>
                                <td>${v.name}</td>
                            </tr>
                        `;
                    });

                    html += `</tbody></table>`;

                    document.getElementById('previewupdateprice').innerHTML = html;
                })
                .catch(() => {
                    document.getElementById('previewupdateprice').innerHTML =
                        '<div class="alert alert-danger">Something went wrong</div>';
                });

        });

        let updatePriceRows = [];

        document.getElementById('updatepriceExcel').addEventListener('change', function(e) {
            let file = e.target.files[0];
            if (!file) return;

            let reader = new FileReader();
            reader.onload = function(event) {
                let data = new Uint8Array(event.target.result);
                let workbook = XLSX.read(data, {
                    type: 'array'
                });
                let sheet = workbook.Sheets[workbook.SheetNames[0]];
                updatePriceRows = XLSX.utils.sheet_to_json(sheet, {
                    header: 1
                });

                if (updatePriceRows.length > 0) {
                    let headers = updatePriceRows[0];
                    let html = "<table class='table table-bordered'><thead><tr>";
                    headers.forEach(h => html += "<th>" + h + "</th>");
                    html += "</tr></thead><tbody>";

                    for (let i = 1; i < updatePriceRows.length; i++) {
                        html += "<tr>";
                        updatePriceRows[i].forEach(c => html += "<td>" + (c ?? '') + "</td>");
                        html += "</tr>";
                    }
                    html += "</tbody></table>";

                    document.getElementById("previewupdateprice").innerHTML = html;
                }
            };
            reader.readAsArrayBuffer(file);
        });

        // Save to backend
        document.getElementById('saveupdateprice').addEventListener('click', function() {
            if (updatePriceRows.length < 2) {
                showAlert('error', 'No data to save. Please upload and process an Excel file first.');
                return;
            }

            fetch("<?= base_url('updateskuprice') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        rows: updatePriceRows
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'warning' && data.missing_products) {
                        showAlert('warning', `Product(s) not found: ${data.missing_products.join(', ')}`);
                    } else if (data.status === 'success') {
                        showAlert('success', data.message);
                    } else if (data.status === 'error') {
                        showAlert('error', data.message);
                    }

                    // 🔄 reload page after showing alert
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(err => {
                    showAlert('error', 'Something went wrong while saving data.');
                });
        });



        // For Varient Product 

        var parentProducts = {};
        var selectedVariantProducts = {};

        $(document).ready(function() {
            var table = $('#variantProductsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?= site_url('get-products') ?>",
                    type: "POST"
                }
            });

            // Parent product selection
            $(document).on('change', '.set-parent', function() {
                var matixId = $(this).data('id');
                var name = $(this).data('name');
                var isChecked = $(this).is(':checked') ? 1 : 0;

                $.post("<?= site_url('set-parent-product') ?>", {
                    matix_id: matixId,
                    status: isChecked
                }, function(res) {
                    var response = JSON.parse(res);
                    if (response.status === 'success') {
                        if (isChecked == 1) {
                            parentProducts[matixId] = name;
                            // ✅ Add parent option to all variant dropdowns except the parent itself
                            $('.variant-select').each(function() {
                                if ($(this).data('id') != matixId) {
                                    if ($(this).find('option[value="' + matixId + '"]').length == 0) {
                                        $(this).append('<option value="' + matixId + '">' + name + ' (' + matixId + ')</option>');
                                    }
                                }
                            });
                        } else {
                            delete parentProducts[matixId];
                            // ✅ Remove parent option if unchecked
                            $('.variant-select option[value="' + matixId + '"]').remove();
                        }
                        // Reload table row so UI updates correctly
                        $('#variantProductsTable').DataTable().ajax.reload(null, false);
                    } else {
                        showAlert('error', response.message);
                    }
                });
            });

            // Variant product selection - UPDATED
            $(document).on('change', '.variant-select', function() {
                var matixId = $(this).data('id');
                var parentId = $(this).val();
                var currentRow = $(this).closest('tr');

                if (parentId !== "") {
                    // Set/Update variant product
                    $.post("<?= site_url('set-variant-product') ?>", {
                        matix_id: matixId,
                        parent_id: parentId
                    }, function(res) {
                        var response = JSON.parse(res);
                        if (response.status === 'success') {
                            // Replace checkbox with "Variant Product" span
                            var checkboxCell = currentRow.find('.set-parent').closest('td');
                            checkboxCell.html('<span class="badge bg-info">Variant Product</span>');

                            // Keep the dropdown but show success message
                            showAlert('success', 'Parent product updated successfully');
                        } else {
                            showAlert('error', response.message);
                        }
                    }.bind(this));
                } else {
                    // Remove parent relationship (when "-- Select Parent --" is selected)
                    $.post("<?= base_url('remove-variant-product') ?>", {
                        matix_id: matixId
                    }, function(res) {
                        var response = JSON.parse(res);
                        if (response.status === 'success') {
                            // Restore the checkbox to its original state
                            var checkboxCell = currentRow.find('td').eq(3); // Assuming checkbox is in 4th column (index 3)
                            var productName = currentRow.find('td').eq(1).text(); // Get product name from 2nd column
                            checkboxCell.html('<input type="checkbox" class="set-parent" data-id="' + matixId + '" data-name="' + productName + '">');

                            showAlert('success', 'Parent relationship removed successfully');
                        } else {
                            showAlert('error', response.message);
                        }
                    });
                }
            });
        });
        $(document).on('change', '.variant-product-checkbox', function () {
            let id = $(this).val();

            if ($(this).is(':checked')) {
                selectedVariantProducts[id] = true;
            } else {
                delete selectedVariantProducts[id];
            }

            $('#downloadExcelVariant').prop(
                'disabled',
                Object.keys(selectedVariantProducts).length === 0
            );
        });

        // Select all
        $('#selectAllVariantProducts').on('change', function () {
            $('.variant-product-checkbox')
                .prop('checked', this.checked)
                .trigger('change');
        });

        // download excel button
        $('#downloadExcelVariant').on('click', function () {

            let ids = Object.keys(selectedVariantProducts);
            if (ids.length === 0) return;

            let form = $('<form>', {
                method: 'POST',
                action: "<?= base_url('download-variant-sku-excel') ?>"
            });

            ids.forEach(id => {
                form.append(
                    $('<input>', {
                        type: 'hidden',
                        name: 'matix_ids[]',
                        value: id
                    })
                );
            });

            $('body').append(form);
            form.submit();
            form.remove();
        });

    </script>
</body>

</html>

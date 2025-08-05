<!DOCTYPE html>
<html>

<head>
    <title>Product System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        table th,
        table td {
            white-space: nowrap;
        }
    </style>
</head>

<body class="p-4 bg-light">
    <!-- Alert Messages -->
    <!-- <div id="alertContainer"></div> -->

    <div class="container">
        <h2 class="mb-4">Product Management System</h2>
        <ul class="nav nav-tabs" id="productTab" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#baseProduct">Base Product</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#addOption">Add Option</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#upload">Upload</button></li>
        </ul>

        <div class="tab-content border border-top-0 p-3 bg-white" style="overflow: scroll;">

            <!-- BASE PRODUCT TAB -->
            <div class="tab-pane fade show active" id="baseProduct">
                <input type="file" id="baseExcel" class="form-control mb-3">
                <div id="basePreview"></div>
                <button id="saveToDb" class="btn btn-success mt-3">Save to Database</button>
                <!-- <button id="exportAllBtn" class="btn btn-primary mt-3">Export All</button> -->
                <a href="<?php echo base_url('export'); ?>" class="btn btn-primary mt-3">Export All</a>
            </div>

            <!-- ADD OPTION TAB -->
            <div class="tab-pane fade" id="addOption">
                <input type="file" id="baseExcel2" class="form-control mb-3">
                <button id="saveToDb2" class="btn btn-success my-3">Upload Options</button>
                <a href="<?php echo base_url('export-options'); ?>" class="btn btn-primary my-3">Export Options</a>

                <div id="productTable" class="table-responsive"></div>
            </div>

            <!-- UPLOAD TAB -->
            <div class="tab-pane fade" id="upload">
                <input type="file" id="uploadExcel" class="form-control mb-3">
                <div id="uploadPreview"></div>
            </div>

        </div>
    </div>

    <!-- Modal for Add Columns -->
    <div class="modal fade" id="addColumnModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea id="columnNames" class="form-control" rows="3" placeholder="size,color,weight..."></textarea>
                    <input type="hidden" id="selectedProductId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="addColumnBtn">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Export Range -->
    <div class="modal fade" id="exportRangeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Start Row:</label>
                    <input type="number" id="rangeStart" class="form-control mb-2">
                    <label>End Row:</label>
                    <input type="number" id="rangeEnd" class="form-control">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="downloadRangeBtn">Download</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let baseData = [];
        let allProducts = [];
        let excelData = [];

        $('#baseExcel').change(function(e) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let data = new Uint8Array(e.target.result);
                let workbook = XLSX.read(data, {
                    type: 'array'
                });
                let sheet = workbook.Sheets[workbook.SheetNames[0]];
                baseData = XLSX.utils.sheet_to_json(sheet, {
                    header: 1,
                    defval: ''
                });

                // ✅ Build excelData from baseData
                excelData = baseData.slice(1).map(row => {
                    let obj = {};
                    baseData[0].forEach((key, i) => obj[key] = row[i]);
                    return obj;
                });

                renderTable('#basePreview', baseData);
            };
            reader.readAsArrayBuffer(e.target.files[0]);
        });

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
                ...(containsHTML ? { html: message } : { text: message }),
                confirmButtonText: 'OK',
                showConfirmButton: true,
                allowOutsideClick: false
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

        let headers2 = [];
        let baseData2 = [];

        $('#baseExcel2').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });
                const sheetName = workbook.SheetNames[0];
                const sheet = workbook.Sheets[sheetName];
                const jsonData = XLSX.utils.sheet_to_json(sheet, {
                    header: 1
                });

                if (jsonData.length > 0) {
                    headers2 = jsonData[0];
                    baseData2 = jsonData;
                    $('#productTable').html(`<div class="alert alert-info">Excel file loaded. <strong>${baseData2.length - 1}</strong> records ready for upload.</div>`);
                } else {
                    showAlert('error', 'No data found in the Excel sheet.');
                }
            };
            reader.readAsArrayBuffer(file);
        });
        $('#saveToDb2').on('click', insertOption);
                function insertOption() {
            if (baseData2.length === 0) {
                showAlert('error', 'No data to save. Please upload and process an Excel file first.');
                return;
            }

            const dataRows = baseData2.slice(1);
            const formattedRows = dataRows.map(row => {
                const paddedRow = [...row];
                while (paddedRow.length < headers2.length) {
                    paddedRow.push('');
                }
                return paddedRow;
            });

            const excelData = [headers2, ...formattedRows];

            const saveBtn = $('#saveToDb2');
            const originalText = saveBtn.html();
            saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');

            $.ajax({
                url: 'insert-options',
                type: 'POST',
                data: {
                    excel_data: JSON.stringify(excelData),
                    file_name: 'uploaded_options.xlsx'
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        let message = result.message || 'Options inserted successfully.';
                        if (result.warning) {
                            message += `<br><strong>Warning:</strong> ${result.warning}`;
                            console.warn("Skipped Rows:", result.skipped_rows);
                        }
                        showAlert('success', message);
                    } else {
                        showAlert('error', result.message || 'Error inserting options.');
                    }
                },
                error: function() {
                    showAlert('error', 'Failed to save options. Please check your server or network.');
                },
                complete: function() {
                    saveBtn.prop('disabled', false).html(originalText);
                }
            });
        }      

        $('#downloadRangeBtn').on('click', function() {
            let start = parseInt($('#rangeStart').val(), 10) - 1;
            let end = parseInt($('#rangeEnd').val(), 10);
            let selected = allProducts.slice(start, end);
            if (!selected.length) return alert("No rows selected.");
            let headers = Object.keys(selected[0]);
            let rows = selected.map(row => headers.map(key => row[key]));
            let data = [headers, ...rows];
            let worksheet = XLSX.utils.aoa_to_sheet(data);
            let workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Export");
            XLSX.writeFile(workbook, `products_${start + 1}_to_${end}.xlsx`);
        });

        // Add Option Tab
        $('button[data-bs-target="#addOption"]').on('click', function () {
            $.getJSON('product-system/get-product-options-json', function (data) {
                if (!data.length) return $('#productTable').html('<p>No data found</p>');

                let html = '<table class="table table-bordered"><thead><tr>';
                html += '<th>ID</th><th>Matrix ID</th><th>MPN</th><th>Name</th><th>Options</th><th>Actions</th>';
                html += '</tr></thead><tbody>';

                data.forEach((row, index) => {
                    html += `<tr>
                                <td>${row.id}</td>
                                <td>${row.matix_id ?? ''}</td>
                                <td>${row.mpn ?? ''}</td>
                                <td>${row.name ?? ''}</td>
                                <td>${row.options}</td>
                                <td>
                                    <button class="btn btn-sm btn-info me-1" onclick="openModal(${row.id})">Add Option's</button>
                                    <button class="btn btn-sm btn-primary" onclick="downloadOptionRow(${index})">Download</button>
                                </td>
                            </tr>`;
                });

                html += '</tbody></table>';
                $('#productTable').html(html);

                // Store data for single-row download
                window._optionHeaders = ['id', 'matix_id', 'mpn', 'name', 'options'];
                window._optionRows = data;
            });
        });



        function openModal(id) {
            $('#selectedProductId').val(id);
            $('#columnNames').val('');
            new bootstrap.Modal(document.getElementById('addColumnModal')).show();
        }

        $('#addColumnBtn').click(function() {
            const product_id = $('#selectedProductId').val();
            const columns = $('#columnNames').val();
            $.post('product-system/options', {
                product_id,
                columns
            }, function() {
                alert('Options added');
                bootstrap.Modal.getInstance(document.getElementById('addColumnModal')).hide();
            });
        });

        // Upload Tab
        $('#uploadExcel').change(function (e) {
            let reader = new FileReader();
            reader.onload = function (e) {
                let data = new Uint8Array(e.target.result);
                let workbook = XLSX.read(data, { type: 'array' });
                let sheet = workbook.Sheets[workbook.SheetNames[0]];
                let excelData = XLSX.utils.sheet_to_json(sheet, {
                    header: 1,
                    defval: ''
                });

                let headers = excelData[0];
                let rows = excelData.slice(1);

                let skuIndex = headers.indexOf('sku');
                let mpnIndex = headers.indexOf('mpn');

                for (let i = 0; i < rows.length; i++) {
                    let row = rows[i];
                    let mpnRaw = row[mpnIndex];

                    // Collect values from all columns *after* "sku"
                    let suffixParts = [];
                    for (let j = skuIndex + 1; j < headers.length; j++) {
                        suffixParts.push((row[j] || '').toString().trim());
                    }

                    let sku = `SKU-${mpnRaw}`;
                    if (suffixParts.length > 0) {
                        sku += `-${suffixParts.join('-')}`;
                    }

                    row[skuIndex] = sku;
                }

                renderTable2('#uploadPreview', [headers, ...rows]);
            };
            reader.readAsArrayBuffer(e.target.files[0]);
        });


        function renderTable(container, data) {
            let html = '<table class="table table-bordered"><thead><tr>';
            data[0].forEach(h => html += `<th>${h}</th>`);
            html += '</tr></thead><tbody>';
            for (let i = 1; i < data.length; i++) {
                html += '<tr>';
                data[i].forEach(cell => html += `<td>${cell ?? ''}</td>`);
                html += '</tr>';
            }
            html += '</tbody></table>';
            $(container).html(html);
        }

        function renderTable2(container, data) {
            const headers = data[0];
            const rows = data.slice(1);

            let html = '<table class="table table-bordered"><thead><tr>';
            headers.forEach(h => html += `<th>${h}</th>`);
            html += '<th>Actions</th></tr></thead><tbody>';

            rows.forEach((row, i) => {
                html += `<tr data-row="${i}">`;
                row.forEach((cell, j) => {
                    html += `<td title="Double click to edit" ondblclick="makeEditable(this)" 
                                onblur="updateRowInSession(${i}, this, ${j})">${cell}</td>`;
                });
                html += `<td><button class="btn btn-sm btn-danger" onclick="deleteRow(${i})">Delete</button></td>`;
                html += `</tr>`;
            });

            html += '</tbody></table>';

            $(container).html(html);
        }
     
        function downloadOptionRow(index) {
            const headers = window._optionHeaders;
            const rowData = window._optionRows[index];
            if (!headers || !rowData) return alert("Data not ready");

            const row = headers.map(h => rowData[h]);
            const data = [headers, row]; // include header and single row

            const worksheet = XLSX.utils.aoa_to_sheet(data);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "RowExport");
            XLSX.writeFile(workbook, `option_row_${index + 1}.xlsx`);
        }
    </script>
</body>

</html>
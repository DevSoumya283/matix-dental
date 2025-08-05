<!DOCTYPE html>
<html>

<head>
    <title>Product System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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

                <button id="saveToDb2" class="btn btn-success mt-3">Save to Database</button>
                <button id="exportAllBtn2" class="btn btn-primary mt-3">Export All</button>
                
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
                let workbook = XLSX.read(data, { type: 'array' });
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
            const alertClass = type === 'success' ? 'alert-success' :
                type === 'info' ? 'alert-info' :
                type === 'warning' ? 'alert-warning' : 'alert-danger';
            const icon = type === 'success' ? 'check-circle' :
                type === 'info' ? 'info-circle' :
                type === 'warning' ? 'exclamation-triangle' : 'exclamation-triangle';

            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${icon} me-2"></i>
                    <strong>${type.charAt(0).toUpperCase() + type.slice(1)}:</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            $('#alertContainer').html(alertHtml);

            // Auto-hide success and info messages
            if (type === 'success' || type === 'info') {
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }
        }

        // $('#saveToDb').click(function() {
        //     let keys = baseData[0];
        //     let rows = baseData.slice(1).map(r => {
        //         let obj = {};
        //         keys.forEach((k, i) => obj[k] = r[i]);
        //         return obj;
        //     });
        //     $.post('upload', {
        //         data: rows
        //     }, function(res) {
        //         alert('Saved!');
        //     });
        // });

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





        // Export All Logic
        // $('#exportAllBtn').on('click', function() {
        //     $.getJSON('product-system/products', function(response) {
        //         if (Array.isArray(response)) {
        //             // Normal flow with data
        //             allProducts = response;
        //             window._optionHeaders = Object.keys(response[0]);
        //             window._optionRows = response;

        //             $('#rangeStart').val(1);
        //             $('#rangeEnd').val(response.length);
        //             $('#exportRangeModal').modal('show');
        //         } else if (response.headers_only) {
        //             // No rows, but return headers from backend
        //             const worksheet = XLSX.utils.aoa_to_sheet([response.headers_only]);
        //             const workbook = XLSX.utils.book_new();
        //             XLSX.utils.book_append_sheet(workbook, worksheet, "HeadersOnly");
        //             XLSX.writeFile(workbook, "products_headers_only.xlsx");
        //         } else {
        //             alert("Unexpected response from server.");
        //         }
        //     });
        // });


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
        $('button[data-bs-target="#addOption"]').on('click', function() {
            $.getJSON('product-system/products', function(data) {
                if (!data.length) return $('#productTable').html('<p>No data found</p>');

                window._optionHeaders = Object.keys(data[0]); // store headers
                window._optionRows = data; // store rows

                let html = '<table class="table table-bordered"><thead><tr>';
                for (let key in data[0]) html += `<th>${key}</th>`;
                html += '<th>Actions</th></tr></thead><tbody>';

                data.forEach((row, index) => {
                    html += '<tr>';
                    for (let k in row) html += `<td>${row[k]}</td>`;
                    html += `<td>
                                <button class="btn btn-sm btn-info me-1" onclick="openModal(${row.id})">Add Column</button>
                                <button class="btn btn-sm btn-primary" onclick="downloadOptionRow(${index})">Download</button>
                            </td></tr>`;
                });

                html += '</tbody></table>';
                $('#productTable').html(html);
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
        $('#uploadExcel').change(function(e) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let data = new Uint8Array(e.target.result);
                let workbook = XLSX.read(data, {
                    type: 'array'
                });
                let sheet = workbook.Sheets[workbook.SheetNames[0]];
                let excelData = XLSX.utils.sheet_to_json(sheet, {
                    header: 1,
                    defval: ''
                });

                let headers = excelData[0];
                let rows = excelData.slice(1);

                const skipCols = ['id', 'matix_id', 'name', 'price', 'retail_price', 'created_at', 'updated_at'];
                let skuIndex = headers.indexOf('sku');

                for (let i = 0; i < rows.length; i++) {
                    let row = {};
                    headers.forEach((k, j) => row[k] = rows[i][j]);
                    let sku = headers
                        .filter(k => k !== 'sku' && !skipCols.includes(k))
                        .map(k => (row[k] || '').toString().trim())
                        .join('-').toLowerCase();
                    rows[i][skuIndex] = sku;
                }

                renderTable('#uploadPreview', [headers, ...rows]);
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
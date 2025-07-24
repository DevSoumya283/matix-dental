<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .excel-table {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }

        .editable-cell {
            cursor: pointer;
            position: relative;
            min-width: 100px;
            transition: background-color 0.2s;
        }

        .editable-cell:hover {
            background-color: #e3f2fd !important;
            border: 1px solid #2196f3;
        }

        .cell-input {
            width: 100%;
            border: 2px solid #0d6efd;
            background: #fff;
            padding: 8px;
            border-radius: 4px;
            font-size: 14px;
        }

        .action-buttons {
            white-space: nowrap;
            text-align: center;
        }

        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 5px 5px;
            /* text-align: center;   */
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .upload-area:hover {
            border-color: #0d6efd;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }

        .upload-area.dragover {
            border-color: #0d6efd;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
        }

        .table-container {
            display: none;
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading {
            display: none;
        }

        .data-preview {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
        }

        .row-number {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            font-weight: bold;
            text-align: center;
            min-width: 60px;
            border-right: 2px solid #fff;
        }

        .column-header {
            background: linear-gradient(135deg, #343a40 0%, #212529 100%);
            color: white;
            text-align: center;
            font-weight: bold;
            border-bottom: 2px solid #fff;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-bottom: 1px solid #dee2e6;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .badge {
            border-radius: 6px;
            font-weight: 500;
        }

        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-content {
            border-radius: 12px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            border-radius: 12px 12px 0 0;
        }

        .table-striped>tbody>tr:nth-of-type(odd)>td {
            background-color: rgba(0, 0, 0, .02);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.1);
        }

        h2 {
            color: #2c3e50;
            font-weight: 600;
        }

        h5 {
            color: #495057;
            font-weight: 600;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .upload-icon {
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .upload-area:hover .upload-icon {
            color: #0d6efd;
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-file-excel text-success me-3"></i>Excel File Manager</h2>
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Maximum 100 rows per file
                    </div>
                </div>

                <!-- Alert Messages -->
                <div id="alertContainer"></div>

                <!-- Upload Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-upload me-2"></i>Upload Excel File</h5>
                    </div>
                    <div class="card-body">
                        <div class="upload-area" id="uploadArea">
                            <div class="d-flex justify-content-around align-items-center gap-2">
                            <i class="fas fa-cloud-upload-alt fa-4x upload-icon mb-3"></i>
                            <h5 class="mb-3">Upload Excel File</h5>
                            </div>
                            <input type="file" id="excelFile" class="form-control" accept=".xlsx,.xls" style="display: none;">
                            <button type="button" class="btn btn-primary btn-lg px-4" onclick="document.getElementById('excelFile').click();" style="max-height: 64px;">
                                <i class="fas fa-file-upload me-2"></i>Choose File
                            </button>
                        </div>
                        <div class="loading text-center py-5">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">
                                <i class="fas fa-cog fa-spin me-2"></i>Processing Excel file...
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Data Table Section -->
                <div class="table-container">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 me-3">
                                    <i class="fas fa-table me-2"></i>Excel Data
                                </h5>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                    <span id="fileName" class="fw-bold text-primary"></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 mt-2 mt-md-0">
                                <span class="badge bg-info fs-6" id="rowCount">0 rows</span>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success" id="saveToDb">
                                        <i class="fas fa-save me-2"></i>Save to Database
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="clearData">
                                        <i class="fas fa-times me-2"></i>Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="alert alert-info mx-3 mt-3 mb-0" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Instructions:</strong>
                                Double-click any cell to edit its content. Click the trash icon to delete a row.
                            </div>
                            <div class="excel-table">
                                <table class="table table-bordered table-hover table-sm mb-0" id="excelTable">
                                    <thead class="sticky-top">
                                        <tr id="tableHeader"></tr>
                                    </thead>
                                    <tbody id="tableBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>

    <!-- View Data Modal -->
    <div class="modal fade" id="viewDataModal" tabindex="-1" aria-labelledby="viewDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDataModalLabel">
                        <i class="fas fa-eye me-2"></i>View Excel Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        This is a read-only preview of the saved Excel data.
                    </div>
                    <div class="data-preview">
                        <table class="table table-bordered table-hover table-sm" id="previewTable">
                            <thead class="table-dark">
                                <tr id="previewTableHeader"></tr>
                            </thead>
                            <tbody id="previewTableBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SheetJS Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let excelData = [];
        let editingCell = null;
        let currentFileName = '';

        $(document).ready(function() {
            loadSavedData();
            initializeDragDrop();

            // File upload handler
            $('#excelFile').change(function() {
                const file = this.files[0];
                if (file) {
                    processExcelFile(file);
                }
            });

            // Save to database
            $('#saveToDb').click(function() {
                saveToDatabase();
            });

            // Clear data
            $('#clearData').click(function() {
                clearData();
            });

            // Double click to edit cell
            $(document).on('dblclick', '.editable-cell', function() {
                editCell(this);
            });

            // Delete row
            $(document).on('click', '.delete-row', function() {
                const row = $(this).data('row');
                deleteRow(row);
            });
        });

        function initializeDragDrop() {
            const uploadArea = document.getElementById('uploadArea');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                uploadArea.classList.add('dragover');
            }

            function unhighlight(e) {
                uploadArea.classList.remove('dragover');
            }

            uploadArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    processExcelFile(files[0]);
                }
            }
        }

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

        function processExcelFile(file) {
            if (!file.name.match(/\.(xlsx|xls)$/)) {
                showAlert('error', 'Please select a valid Excel file (.xlsx or .xls)');
                return;
            }

            $('.loading').show();
            $('.upload-area').hide();
            currentFileName = file.name;

            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    const sheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[sheetName];
                    const jsonData = XLSX.utils.sheet_to_json(worksheet, {
                        header: 1,
                        defval: ''
                    });

                    // Remove completely empty rows
                    const filteredData = jsonData.filter(row =>
                        row.some(cell => cell !== null && cell !== undefined && cell !== '')
                    );

                    if (filteredData.length === 0) {
                        showAlert('error', 'Excel file is empty or contains no valid data');
                        $('.loading').hide();
                        $('.upload-area').show();
                        return;
                    }

                    if (filteredData.length > 100) {
                        showAlert('error', `Excel file contains ${filteredData.length} rows. Maximum 100 rows allowed.`);
                        $('.loading').hide();
                        $('.upload-area').show();
                        return;
                    }

                    // Ensure all rows have the same number of columns
                    var maxCols = Math.max(...filteredData.map(row => row.length));

                    // excelData = filteredData.map(row => {
                    //     while (row.length < maxCols) {
                    //         row.push('');
                    //     }
                    //     return row;
                    // });
                    excelData = filteredData.map((row, index) => {
                        while (row.length < maxCols) {
                            row.push('');
                        }

                        if (index === 0) {
                            // Insert "Custom_SKU" header if not present
                            if (!row.includes('Custom_SKU')) {
                                row[2] = 'Custom_SKU'; // Assuming column C is index 2
                            }
                            return row;
                        }

                        // Extract mpn and name columns
                        const mpn = row[3] || `RND${Math.floor(Math.random() * 9000 + 1000)}`; // Column D (index 3)
                        const name = row[5] || '';

                        // Extract number from name (e.g., 33gm or 17ml)
                        const match = name.match(/(\d+\.?\d*)/);
                        const number = match ? match[1] : Math.floor(Math.random() * 9000 + 1000);

                        // Build custom SKU
                        row[2] = `SKU-${mpn}-${number}`; // Column C (index 2)

                        return row;
                    });

                    $('.loading').hide();
                    $('.upload-area').show();
                    displayData();

                    // Store data in session when file is processed
                    $.ajax({
                        url: '<?php echo base_url("SuperAdminDashboard/store_session_data"); ?>',
                        type: 'POST',
                        data: {
                            excel_data: JSON.stringify(excelData),
                            file_name: currentFileName
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.status === 'success') {
                                showAlert('success', `Excel file processed successfully! ${excelData.length} rows loaded and ready for editing.`);
                            } else {
                                showAlert('warning', 'File processed but session storage failed. Please re-upload if you encounter issues.');
                            }
                        },
                        error: function() {
                            showAlert('warning', 'File processed but session storage failed. Please re-upload if you encounter issues.');
                        }
                    });

                } catch (error) {
                    $('.loading').hide();
                    $('.upload-area').show();
                    showAlert('error', 'Error processing Excel file: ' + error.message);
                }
            };

            reader.onerror = function() {
                $('.loading').hide();
                $('.upload-area').show();
                showAlert('error', 'Error reading file');
            };

            reader.readAsArrayBuffer(file);
        }

        function displayData() {
            if (excelData.length === 0) return;

            $('#fileName').text(currentFileName);
            $('#rowCount').text(`${excelData.length} rows`);

            // Create header
            let headerHtml = '<th class="row-number">#</th>';
            for (let i = 0; i < excelData[0].length; i++) {
                headerHtml += `<th class="column-header">Column ${String.fromCharCode(65 + i)}</th>`;
            }
            headerHtml += '<th class="column-header" style="width: 100px;">Actions</th>';
            $('#tableHeader').html(headerHtml);

            // Create body
            let bodyHtml = '';
            excelData.forEach((row, rowIndex) => {
                bodyHtml += `<tr>`;
                bodyHtml += `<td class="row-number">${rowIndex + 1}</td>`;

                row.forEach((cell, colIndex) => {
                    const cellValue = cell === null || cell === undefined ? '' : String(cell).replace(/"/g, '&quot;');

                    bodyHtml += `<td class="editable-cell" data-row="${rowIndex}" data-col="${colIndex}" title="Double-click to edit">${cellValue}</td>`;
                });

                bodyHtml += `<td class="action-buttons">
                    <button class="btn btn-sm btn-outline-danger delete-row" data-row="${rowIndex}" title="Delete Row">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>`;
                bodyHtml += `</tr>`;
            });

            $('#tableBody').html(bodyHtml);
            $('.table-container').show().addClass('fade-in');
        }

        function editCell(cell) {
            if (editingCell) return;

            editingCell = cell;
            const currentValue = $(cell).text();
            const row = $(cell).data('row');
            const col = $(cell).data('col');

            $(cell).html(`<input type="text" class="cell-input" value="${currentValue.replace(/&quot;/g, '"')}" data-row="${row}" data-col="${col}">`);
            const input = $(cell).find('.cell-input');
            input.focus().select();

            // Handle save on Enter or blur
            input.on('blur keypress', function(e) {
                if (e.type === 'keypress' && e.which !== 13) return;

                const newValue = $(this).val();
                saveEditedCell(row, col, newValue);
            });

            // Handle escape to cancel
            input.on('keydown', function(e) {
                if (e.which === 27) { // Escape key
                    $(editingCell).html(currentValue);
                    editingCell = null;
                }
            });
        }

        function saveEditedCell(row, col, value) {
            // Update local data
            excelData[row][col] = value;

            // Update session data on server
            $.ajax({
                url: '<?php echo base_url("SuperAdminDashboard/update_cell"); ?>',
                type: 'POST',
                data: {
                    row: row,
                    col: col,
                    value: value
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        // Update display
                        $(editingCell).html(String(value).replace(/"/g, '&quot;'));
                        editingCell = null;
                        showAlert('info', 'Cell updated successfully');
                    } else {
                        showAlert('error', 'Error updating cell: ' + result.message);
                        // Revert the change
                        $(editingCell).html(String(excelData[row][col]).replace(/"/g, '&quot;'));
                        editingCell = null;
                    }
                },
                error: function() {
                    showAlert('error', 'Error updating cell. Please try again.');
                    // Revert the change
                    $(editingCell).html(String(excelData[row][col]).replace(/"/g, '&quot;'));
                    editingCell = null;
                }
            });
        }

        function deleteRow(rowIndex) {
            if (confirm('Are you sure you want to delete this row? This action cannot be undone.')) {
                // Update server session data first
                $.ajax({
                    url: '<?php echo base_url("SuperAdminDashboard/delete_row"); ?>',
                    type: 'POST',
                    data: {
                        row: rowIndex
                    },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            // Update local data
                            excelData.splice(rowIndex, 1);
                            displayData();
                            showAlert('success', 'Row deleted successfully');
                        } else {
                            showAlert('error', 'Error deleting row: ' + result.message);
                        }
                    },
                    error: function() {
                        showAlert('error', 'Error deleting row. Please try again.');
                    }
                });
            }
        }

        function saveToDatabase() {
            if (excelData.length === 0) {
                showAlert('error', 'No data to save. Please upload and process an Excel file first.');
                return;
            }

            // Show loading state
            const saveBtn = $('#saveToDb');
            const originalText = saveBtn.html();
            saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');

            // Save current local data to database
           $.ajax({
                url: '<?php echo base_url("SuperAdminDashboard/save_data"); ?>',
                type: 'POST',
                data: {
                    excel_data: JSON.stringify(excelData),
                    file_name: currentFileName
                },
                success: function(response) {
                    const result = JSON.parse(response);

                    if (result.status === 'success') {
                        let message = result.message + ' You can now upload a new file or view saved data below.';

                        // ✅ Show warning if some rows had missing MPN
                        if (result.warning) {
                            message += '\ Warning: ' + result.warning;

                            // Optional: Show skipped rows in console or UI
                            console.warn("Skipped Rows (no MPN):", result.skipped_rows);
                        }

                        showAlert('success', message);
                        clearData();
                        loadSavedData();
                    } else {
                        showAlert('error', result.message || 'Unknown error occurred');
                    }
                },
                error: function() {
                    showAlert('error', 'Error saving data to database. Please check your connection and try again.');
                },
                complete: function() {
                    saveBtn.prop('disabled', false).html(originalText);
                }
            });

        }

        function clearData() {
            excelData = [];
            currentFileName = '';
            $('.table-container').hide();
            $('#excelFile').val('');
        }

        function loadSavedData() {
            $.ajax({
                url: '<?php echo base_url("SuperAdminDashboard/get_saved_data"); ?>',
                type: 'GET',
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        displaySavedData(result.data);
                    } else {
                        $('#savedDataBody').html('<tr><td colspan="5" class="text-center text-danger py-3">Error loading saved data</td></tr>');
                    }
                },
                error: function() {
                    $('#savedDataBody').html('<tr><td colspan="5" class="text-center text-danger py-3"><i class="fas fa-exclamation-triangle me-2"></i>Error loading saved data</td></tr>');
                }
            });
        }

        function displaySavedData(data) {
            let html = '';
            if (data.length === 0) {
                html = '<tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No saved files found<br><small>Upload and save some Excel files to see them here</small></td></tr>';
            } else {
                data.forEach(item => {
                    const createdDate = new Date(item.created_at).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    html += `
                        <tr>
                            <td><strong class="text-primary">${item.id}</strong></td>
                            <td>
                                <i class="fas fa-file-excel text-success me-2"></i>
                                <span class="fw-bold">${item.file_name}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">${item.row_count} rows</span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>${createdDate}
                                </small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-info" onclick="viewData(${item.id})" title="View Data">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteSavedData(${item.id})" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }
            $('#savedDataBody').html(html);
        }

        function viewData(id) {
            $.ajax({
                url: '<?php echo base_url("SuperAdminDashboard/view_data/"); ?>' + id,
                type: 'GET',
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        displayPreviewData(result.data);
                        $('#viewDataModal').modal('show');
                    } else {
                        showAlert('error', result.message);
                    }
                },
                error: function() {
                    showAlert('error', 'Error loading data for preview');
                }
            });
        }

        function displayPreviewData(data) {
            const excelData = JSON.parse(data.excel_data);

            // Update modal title with file name
            $('#viewDataModalLabel').html(`<i class="fas fa-eye me-2"></i>View Excel Data - ${data.file_name}`);

            // Create header
            let headerHtml = '<th class="row-number">#</th>';
            for (let i = 0; i < excelData[0].length; i++) {
                headerHtml += `<th class="column-header">Column ${String.fromCharCode(65 + i)}</th>`;
            }
            $('#previewTableHeader').html(headerHtml);

            // Create body
            let bodyHtml = '';
            excelData.forEach((row, rowIndex) => {
                bodyHtml += `<tr>`;
                bodyHtml += `<td class="row-number">${rowIndex + 1}</td>`;

                row.forEach((cell) => {
                    const cellValue = cell === null || cell === undefined ? '' : String(cell).replace(/"/g, '&quot;');
                    bodyHtml += `<td>${cellValue}</td>`;
                });

                bodyHtml += `</tr>`;
            });

            $('#previewTableBody').html(bodyHtml);
        }

        function deleteSavedData(id) {
            if (confirm('Are you sure you want to delete this saved Excel file? This action cannot be undone.')) {
                $.ajax({
                    url: '<?php echo base_url("SuperAdminDashboard/delete_saved_data/"); ?>' + id,
                    type: 'POST',
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            showAlert('success', result.message);
                            loadSavedData();
                        } else {
                            showAlert('error', result.message);
                        }
                    },
                    error: function() {
                        showAlert('error', 'Error deleting saved data');
                    }
                });
            }
        }

        // Keyboard shortcuts
        $(document).keydown(function(e) {
            // Escape key to cancel cell editing
            if (e.which === 27 && editingCell) {
                const currentValue = $(editingCell).data('original-value') || '';
                $(editingCell).html(currentValue);
                editingCell = null;
            }

            // Ctrl+S to save to database
            if (e.ctrlKey && e.which === 83) {
                e.preventDefault();
                if (excelData.length > 0) {
                    saveToDatabase();
                }
            }
        });

        // Prevent accidental page navigation when data is loaded
        window.addEventListener('beforeunload', function(e) {
            if (excelData.length > 0) {
                e.preventDefault();
                e.returnValue = 'You have unsaved Excel data. Are you sure you want to leave?';
            }
        });

        // Initialize tooltips
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
</body>

</html>
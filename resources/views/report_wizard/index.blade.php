@extends('layouts.app')
@section('page-title', __('Reports'))
@section('page-heading', __('Report Wizard'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Report Wizard')
    </li>
@stop

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
.custom-dropdown {
    position: relative;
    display: inline-block;
}

.custom-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 10rem;
    padding: 0.5rem 0;
    margin: 0.125rem 0 0;
    font-size: 1rem;
    color: #212529;
    text-align: left;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 0.25rem;
    max-height: 300px;
    overflow-y: auto;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.custom-dropdown-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    clear: both;
    font-weight: 400;
    color: #212529;
    text-align: inherit;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
    cursor: pointer;
}

.custom-dropdown-item:hover {
    background-color: #f8f9fa;
}

.custom-dropdown-item input[type="checkbox"] {
    margin-right: 8px;
}

.form-check-label {
    margin-bottom: 0;
    cursor: pointer;
    user-select: none;
}

.p-2 > .custom-dropdown-item {
    margin-bottom: 4px;
}

.custom-dropdown-menu {
    position: absolute !important;
    z-index: 9999 !important;
}

</style>
@endsection

@section('content')
@include('partials.messages')

<div class="card">
    <form id="reportForm">
        @csrf
        <div class="card-body"> 
            <div class="d-flex flex-wrap align-items-center">
                <div class="form-group mr-2">
                    <label for="type">Select Type:</label>
                    <select id="type" name="type" class="form-control px-4">
                        <option value="users">Users</option>
                        <option value="assets">Assets</option>
                        <option value="asset_assignments">Asset Assignments</option>
                        <option value="payroll">Payroll</option>
                        <option value="master_payroll">Master Payroll</option>
                        <option value="training">Training Attendance</option>
                        <option value="contracts">Contracts</option>
                        <option value="support_issues">Support Issues</option>
                    </select>
                </div>
                <div id="filterContainer" class="mb-2 d-flex flex-wrap"></div>
            </div>
        </div>

        <div class="form-group mb-3" style="padding-right: 1.75rem; padding-left: 1.75rem;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="custom-dropdown">
                    <button type="button" class="btn btn-secondary" id="columnDropdown">
                        Select Columns
                    </button>
                    <div class="custom-dropdown-menu" id="columnList">
                        <!-- Column checkboxes will be dynamically inserted here -->
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </div>
    </form>
</div>

<div id="reportContainer" class="card mt-4">
    <div class="card-body">
        <div id="exportButtons" class="mb-2" style="display: none;">
            <button id="exportCSV" class="btn btn-secondary">Export CSV</button>
            <button id="exportPDF" class="btn btn-secondary">Export PDF</button>
            <button id="exportXLSX" class="btn btn-secondary">Export XLSX</button>
            <button id="exportJSON" class="btn btn-secondary">Export JSON</button>
        </div>
        <div id="tableContainer" class="table-responsive table-borderless" style="height:100vh"></div>
        <div id="paginationContainer" class="mt-3"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Constants declarations
    const userColumns = @json($userColumns);
    const assetColumns = @json($assetColumns);
    const assetAssignmentColumns = @json($assetAssignmentColumns);
    const payrollColumns = @json($payrollColumns);
    const masterPayrollColumns = @json($masterPayrollColumns);
    const trainingColumns = @json($trainingColumns);
    const contractColumns = @json($contractColumns);
    const supportIssueColumns = @json($supportIssueColumns);

    const userFilters = {
        county_id: @json($counties),
        role_id: @json($roles),
        status: @json($userStatuses),
        contract_status: @json($contractStatuses),
        completed: @json($completedStatuses)
    };

    const assetFilters = {
        category: ['Consumable', 'Durable'],
        status: ['New', 'Good', 'Poor', 'Damaged']
    };

    const assetAssignmentFilters = {
        physical_condition: ['Good', 'New', 'Damaged', 'Poor'],
        assignment_status: ['Assigned', 'Returned'],
        county_id: @json($counties),
        role_id: @json($roles)
    };

    const trainingFilters = {
        county_id: @json($counties),
        training_status: @json($trainingStatuses),
        completed: {
            'completed': 'Completed',
            'incomplete': 'Incomplete'
        },
        phone_verified: {
            'verified': 'Verified',
            'unverified': 'Not Verified'
        },
        date_range: 'date'
    };

    const payrollFilters = {
        cycle: @json($paymentCycles),
        county: @json($counties),
        status: @json($paymentStatuses),
        role: @json($roles)
    };

    const masterPayrollFilters = {
    cycle: @json($paymentCycles),
    county: @json($counties),
    status: {
        'Pending': 'Pending',
        'Processing': 'Processing',
        'Invoice Uploaded': 'Invoice Uploaded'
    },
    approval_status: {
        'Approved': 'Approved',
        'Rejected': 'Rejected'
    },
    date_range: 'date'
};

    const contractFilters = {
        title: @json($contractTitles),
        status: @json($contractStatuses),
        active: {
            '1': 'Active',
            '0': 'Inactive'
        },
        date_range: 'date'
    };

    const supportIssueFilters = {
    status: @json($issueStatuses),
    priority: @json($issuePriorities),
    category_id: @json($issueCategories),
    county_id: @json($counties),
    date_range: 'date'
};

    // State management
    let currentPage = 1;
    let reportData = [];
    let reportColumns = [];

    // Core functions
    function updateFilters() {
        const type = document.getElementById('type').value;
        const filterContainer = document.getElementById('filterContainer');
        filterContainer.innerHTML = '';

        let filters;
        switch (type) {
            case 'users': filters = userFilters; break;
            case 'assets': filters = assetFilters; break;
            case 'asset_assignments': filters = assetAssignmentFilters; break;
            case 'payroll': filters = payrollFilters; break;
            case 'master_payroll': filters = masterPayrollFilters; break;
            case 'training': filters = trainingFilters; break;
            case 'contracts': filters = contractFilters; break;
            case 'support_issues':filters = supportIssueFilters; break;
            default: filters = {};
        }

        for (const [filterName, filterOptions] of Object.entries(filters)) {
            const formGroup = document.createElement('div');
            formGroup.className = 'form-group mb-2 mr-2';
            formGroup.id = `${filterName}Group`;

            const label = document.createElement('label');
            label.textContent = filterName.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            label.className = 'form-label';

            if (filterOptions === 'date') {
                createDateRangePicker(formGroup, label, filterName);
            } else {
                createSelectFilter(formGroup, label, filterName, filterOptions);
            }

            filterContainer.appendChild(formGroup);
        }

        updateColumns();
    }

    function createDateRangePicker(formGroup, label, filterName) {
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control';
        input.name = `filters[${filterName}]`;
        input.id = `${filterName}Input`;
        input.placeholder = 'Select date range';
        
        formGroup.appendChild(label);
        formGroup.appendChild(input);
        
        $(`#${filterName}Input`).daterangepicker({
            autoUpdateInput: false,
            locale: { cancelLabel: 'Clear' }
        });

        $(`#${filterName}Input`).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $(`#${filterName}Input`).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    }

    function createSelectFilter(formGroup, label, filterName, filterOptions) {
        const select = document.createElement('select');
        select.className = 'form-control';
        select.name = `filters[${filterName}]`;
        select.id = `${filterName}Select`;
        select.style.width = 'auto';

        const defaultOption = document.createElement('option');
        defaultOption.text = `Select ${label.textContent}`;
        defaultOption.value = '';
        select.appendChild(defaultOption);

        if (typeof filterOptions === 'object' && !Array.isArray(filterOptions)) {
            for (const [value, text] of Object.entries(filterOptions)) {
                const option = document.createElement('option');
                option.value = value;
                option.text = text;
                select.appendChild(option);
            }
        } else {
            filterOptions.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option;
                optionElement.text = option;
                select.appendChild(optionElement);
            });
        }

        formGroup.appendChild(label);
        formGroup.appendChild(select);
    }

    function updateColumns() {
        const type = document.getElementById('type').value;
        const columnList = document.getElementById('columnList');
        columnList.innerHTML = '';

        let columns;
        switch (type) {
            case 'users': columns = userColumns; break;
            case 'assets': columns = assetColumns; break;
            case 'asset_assignments': columns = assetAssignmentColumns; break;
            case 'payroll': columns = payrollColumns; break;
            case 'master_payroll': columns = masterPayrollColumns; break;
            case 'training': columns = trainingColumns; break;
            case 'contracts': columns = contractColumns; break;
            case 'support_issues': columns = supportIssueColumns; break;
            default: columns = {};
        }

        const checkboxContainer = document.createElement('div');
        checkboxContainer.className = 'p-2';

        // Add "Select All" checkbox
        createSelectAllCheckbox(checkboxContainer);

        // Add divider
        const divider = document.createElement('hr');
        divider.className = 'my-2';
        checkboxContainer.appendChild(divider);

        // Add individual column checkboxes
        for (const [column, label] of Object.entries(columns)) {
            createColumnCheckbox(checkboxContainer, column, label);
        }

        columnList.appendChild(checkboxContainer);
        setupCheckboxEventListeners();
    }

    function createSelectAllCheckbox(container) {
        const selectAllDiv = document.createElement('div');
        selectAllDiv.className = 'custom-dropdown-item mb-2';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'form-check-input me-2';
        checkbox.id = 'select-all-columns';
        checkbox.checked = true;

        const label = document.createElement('label');
        label.className = 'form-check-label';
        label.htmlFor = 'select-all-columns';
        label.textContent = 'Select All';

        selectAllDiv.appendChild(checkbox);
        selectAllDiv.appendChild(label);
        container.appendChild(selectAllDiv);
    }

    function createColumnCheckbox(container, column, label) {
        const div = document.createElement('div');
        div.className = 'custom-dropdown-item';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'form-check-input me-2 column-checkbox';
        checkbox.name = 'columns[]';
        checkbox.value = column;
        checkbox.id = `column_${column}`;
        checkbox.checked = true;

        const labelElement = document.createElement('label');
        labelElement.className = 'form-check-label';
        labelElement.htmlFor = `column_${column}`;
        labelElement.textContent = label;

        div.appendChild(checkbox);
        div.appendChild(labelElement);
        container.appendChild(div);
    }

    function setupCheckboxEventListeners() {
        const selectAllCheckbox = document.getElementById('select-all-columns');
        const columnCheckboxes = document.querySelectorAll('.column-checkbox');

        selectAllCheckbox.addEventListener('change', function() {
            columnCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        columnCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(columnCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });
    }

    // UI Helper Functions
    function showLoading() {
        hideLoading();
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'loadingIndicator';
        loadingDiv.className = 'alert alert-info';
        loadingDiv.textContent = 'Generating report...';
        document.getElementById('reportContainer').prepend(loadingDiv);
    }

    function hideLoading() {
        const existingLoaders = document.querySelectorAll('#loadingIndicator');
        existingLoaders.forEach(loader => loader.remove());
    }

    function showMessage(message, type = 'success') {
        const messageDiv = document.createElement('div');
        messageDiv.id = 'reportMessage';
        messageDiv.className = `alert alert-${type}`;
        messageDiv.textContent = message;
        document.getElementById('reportContainer').prepend(messageDiv);

        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }

    // Data Formatting Functions
    function getColumnDisplayName(column) {
        if (typeof column === 'string') {
            return column.split(' as ').pop().replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        } else if (typeof column === 'object' && column !== null) {
            if (column.name) {
                return column.name.split(' as ').pop().replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            } else {
                return JSON.stringify(column);
            }
        }
        return 'Unknown';
    }

    function getColumnName(column) {
        if (typeof column === 'string') {
            return column.split(' as ').pop();
        } else if (typeof column === 'object' && column !== null) {
            return column.name ? column.name.split(' as ').pop() : JSON.stringify(column);
        }
        return '';
    }

    function formatCurrency(value) {
        if (value === null || value === undefined) return '';
        return new Intl.NumberFormat('en-KE', {
            style: 'currency',
            currency: 'KES',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = moment(dateString);
        return date.isValid() ? date.format('DD/MM/YYYY HH:mm') : '';
    }

    function formatNumber(value) {
        if (value === null || value === undefined) return '';
        return new Intl.NumberFormat('en-KE', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(value);
    }

    // Table Rendering Functions
    function renderTable(data, columns) {
        const tableContainer = document.getElementById('tableContainer');
        tableContainer.innerHTML = '';

        if (!data || data.length === 0) {
            const noDataDiv = document.createElement('div');
            noDataDiv.className = 'alert alert-info';
            noDataDiv.textContent = 'No data available for the selected criteria';
            tableContainer.appendChild(noDataDiv);
            return;
        }

        const table = document.createElement('table');
        table.className = 'table table-striped table-bordered table-hover';
        
        const thead = document.createElement('thead');
        thead.className = 'thead-light';
        
        const tbody = document.createElement('tbody');

        // Create header row
        const uniqueColumns = [...new Set(columns)];
        const headerRow = document.createElement('tr');
        uniqueColumns.forEach(column => {
            const th = document.createElement('th');
            th.className = 'align-middle';
            th.textContent = getColumnDisplayName(column);
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);

        // Create data rows
        data.forEach(row => {
            const dataRow = document.createElement('tr');
            uniqueColumns.forEach(column => {
                const td = document.createElement('td');
                const columnName = getColumnName(column);
                let value = row[columnName];

                if (columnName === 'name' && row.attendee_name) {
                    value = row.attendee_name;
                }

                formatTableCell(td, columnName, value);
                dataRow.appendChild(td);
            });
            tbody.appendChild(dataRow);
        });

        table.appendChild(thead);
        table.appendChild(tbody);
        tableContainer.appendChild(table);
    }

    function formatTableCell(td, columnName, value) {
    switch (columnName) {
        // Financial Aggregations
        case 'total_amount_payable':
        case 'total_tax':
        case 'total_advance':
        case 'total_net_payable':
        case 'total_approved_amount':
        case 'amount_payable':
        case 'advance_pay':
        case 'net_payable':
        case 'total_amount':
        case 'daily_amount':
            td.textContent = formatCurrency(value);
            td.className = 'text-right';
            break;

        // Productivity Metrics
        case 'avg_productivity':
        case 'min_productivity':
        case 'max_productivity':
            td.textContent = `${formatNumber(value)}%`;
            td.className = 'text-right';
            break;

        // Date Fields
        case 'first_payment_date':
        case 'last_payment_date':
        case 'start_date':
        case 'end_date':
        case 'created_at':
        case 'updated_at':
            td.textContent = formatDate(value);
            break;

        // Count Fields
        case 'total_cycles':
        case 'total_invoices':
        case 'total_approved_payments':
        case 'total_payments':
        case 'days_attended':
        case 'quantity':
        case 'remainder':
            td.textContent = formatNumber(value);
            td.className = 'text-right';
            break;

        // Status & Verification Badges
        case 'phone_verified':
            td.innerHTML = createBadge(value, {
                'Verified': 'success',
                'Not Verified': 'warning'
            });
            break;

        case 'completed':
            td.innerHTML = createBadge(value, {
                'Completed': 'success',
                'Incomplete': 'info'
            });
            break;

        case 'physical_condition':
            td.innerHTML = createBadge(value, {
                'Good': 'success',
                'New': 'info',
                'Poor': 'warning',
                'Damaged': 'danger'
            });
            break;

        case 'assignment_status':
            td.innerHTML = createBadge(value, {
                'Assigned': 'primary',
                'Returned': 'secondary'
            });
            break;

        case 'status':
            td.innerHTML = createBadge(value, {
                'Pending': 'warning',
                'Open': 'info',
                'Closed': 'success',
                'Escalated': 'danger',
                'Processing': 'info',
                'Invoice Uploaded': 'primary'
            });
            break;

        case 'approval_status':
            td.innerHTML = createBadge(value, {
                'Approved': 'success',
                'Rejected': 'danger'
            });
            break;

        case 'priority':
            td.innerHTML = createBadge(value, {
                'High': 'danger',
                'Medium': 'warning',
                'Low': 'success'
            });
            break;

        // Status Summaries
        case 'status_summary':
        case 'approval_status_summary':
            if (value) {
                const statuses = value.map(status => 
                    `<span class="badge badge-${getBadgeColor(status.status)}">${status.status} (${status.count})</span>`
                );
                td.innerHTML = statuses.join(' ');
            } else {
                td.textContent = '-';
            }
            break;

        // Invoice Details
        case 'invoice_details':
            if (value && value.length > 0) {
                const baseUrl = window.location.origin;
                const invoiceList = value.map(invoice => `
                    <div class="mb-1">
                        <strong>${invoice.cycle}</strong><br>
                        <a href="${baseUrl}/storage/${invoice.file}" target="_blank">View Invoice</a>
                        <small class="text-muted">(${invoice.date})</small>
                    </div>
                `);
                td.innerHTML = invoiceList.join('');
            } else {
                td.textContent = 'No Invoices';
            }
            break;

        case 'invoice_files':
        case 'new_invoice_files':
            if (value) {
                const baseUrl = window.location.origin;
                const invoices = value.split(',').map(invoice => {
                    const [label, path] = invoice.trim().split(': ');
                    return `<div>${label}: <a href="${baseUrl}/storage/${path}" target="_blank">View</a></div>`;
                });
                td.innerHTML = invoices.join('');
            } else {
                td.innerHTML = 'No Invoices';
            }
            break;

        // Cycle Details
        case 'cycle_details':
            if (value && value.length > 0) {
                const cycleList = value.map(cycle => `
                    <div class="mb-1">
                        <strong>${cycle.name}</strong><br>
                        <small>${cycle.date_range}</small><br>
                        <span class="text-right">${formatCurrency(cycle.amount)}</span>
                    </div>
                `);
                td.innerHTML = cycleList.join('');
            } else {
                td.textContent = '-';
            }
            break;

        case 'payment_cycles':
            if (value) {
                const cycles = value.split(',').map(cycle => cycle.trim());
                td.innerHTML = cycles.join('<br>');
            } else {
                td.textContent = '-';
            }
            break;
            case 'approved_for_payment':
            td.innerHTML = createBadge(value, {
                'Yes': 'success',
                'No': 'danger'
            });
            break;

        // Default case for all other fields
        default:
            td.textContent = value !== undefined && value !== null ? value : '';
            break;
    }
}

function getBadgeColor(status) {
    const colors = {
        'Pending': 'warning',
        'Processing': 'info',
        'Invoice Uploaded': 'primary',
        'Approved': 'success',
        'Rejected': 'danger',
        'Open': 'info',
        'Closed': 'success',
        'Escalated': 'danger'
    };
    return colors[status] || 'secondary';
}
    function createBadge(value, colorMap) {
        const color = colorMap[value] || 'secondary';
        return `<span class="badge badge-${color}">${value}</span>`;
    }

    // Pagination Functions
    function renderPagination(pagination) {
        const paginationContainer = document.getElementById('paginationContainer');
        paginationContainer.innerHTML = '';
        
        if (pagination.last_page <= 1) return;

        const ul = document.createElement('ul');
        ul.className = 'pagination';

        for (let page = 1; page <= pagination.last_page; page++) {
            const li = document.createElement('li');
            li.className = `page-item ${page === pagination.current_page ? 'active' : ''}`;
            const a = document.createElement('a');
            a.className = 'page-link';
            a.textContent = page;
            a.href = '#';
            a.dataset.page = page;

            a.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = page;
                fetchReport();
            });

            li.appendChild(a);
            ul.appendChild(li);
        }

        paginationContainer.appendChild(ul);
    }

    // Form and Data Handling Functions
    function getSelectedColumns() {
        const checkboxes = document.querySelectorAll('#columnList input[type="checkbox"]:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }

    function validateForm() {
        const selectedColumns = getSelectedColumns();
        if (selectedColumns.length === 0) {
            showMessage('Please select at least one column', 'warning');
            return false;
        }
        return true;
    }

    function isValidDate(dateString) {
        if (!dateString) return false;
        const date = moment(dateString);
        return date.isValid();
    }

    // API Functions
    async function fetchReport() {
        showLoading();
        
        const selectedColumns = getSelectedColumns();
        if (!validateForm()) {
            hideLoading();
            return;
        }

        const formData = new FormData(document.getElementById('reportForm'));
        formData.append('page', currentPage);
        selectedColumns.forEach(column => formData.append('columns[]', column));

        try {
            const response = await fetch("{{ route('report-wizard.generate') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            const contentType = response.headers.get('content-type');
            
            if (!response.ok) {
                throw new Error(await handleErrorResponse(response));
            }

            if (!contentType || !contentType.includes('application/json')) {
                throw new TypeError("Expected JSON response but got " + contentType);
            }

            const data = await response.json();
            handleReportSuccess(data);

        } catch (error) {
            handleReportError(error);
        } finally {
            hideLoading();
        }
    }

    // Export Functions
    async function fetchReportForExport(exportType) {
        try {
            showLoading();
            
            if (!validateForm()) {
                hideLoading();
                return;
            }

            const formData = new FormData(document.getElementById('reportForm'));
            formData.set('download', '1');
            getSelectedColumns().forEach(column => formData.append('columns[]', column));

            const response = await fetch("{{ route('report-wizard.generate') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(await handleErrorResponse(response));
            }

            const data = await response.json();
            
            if (!data.data || !data.columns) {
                showMessage('No data available for export.', 'warning');
                return;
            }

            handleExport(exportType, data);
            showMessage(`${exportType.toUpperCase()} export completed successfully`, 'success');

        } catch (error) {
            console.error('Export error:', error);
            showMessage(`Export failed: ${error.message}`, 'danger');
        } finally {
            hideLoading();
        }
    }

    async function handleErrorResponse(response) {
    const text = await response.text();
    try {
        const json = JSON.parse(text);
        return json.error || json.message || 'An error occurred';
    } catch (e) {
        const match = text.match(/<title>(.*?)<\/title>/);
        return match ? match[1] : text;
    }
}

function handleReportSuccess(response) {
    if (response.data && response.columns) {
        reportData = response.data;
        reportColumns = response.columns;
        renderTable(response.data, response.columns);
        renderPagination(response);
        document.getElementById('exportButtons').style.display = 'block';
    } else {
        showMessage('No data available.', 'warning');
    }
}

function handleReportError(error) {
    console.error('Report error:', error);
    showMessage(
        error.message && !error.message.includes('<!DOCTYPE') 
            ? error.message 
            : 'An error occurred while generating the report. Please check the logs.',
        'danger'
    );
}

function handleExport(exportType, data) {
    switch (exportType) {
        case 'csv':
            exportCSV(data.data, data.columns);
            break;
        case 'pdf':
            exportPDF(data.data, data.columns);
            break;
        case 'xlsx':
            exportXLSX(data.data, data.columns);
            break;
        case 'json':
            exportJSON(data.data);
            break;
    }
}

function exportCSV(data, columns) {
    const fileName = getExportFileName(data, 'csv');
    const csvRows = [columns.map(getColumnDisplayName).join(',')];
    
    data.forEach(row => {
        const csvRow = columns.map(column => {
            const columnName = getColumnName(column);
            let value = formatValueForExport(columnName, row[columnName]);
            if (typeof value === 'string' && value.includes(',')) {
                value = `"${value}"`;
            }
            return value || '';
        }).join(',');
        csvRows.push(csvRow);
    });
    
    downloadFile(csvRows.join('\n'), fileName, 'text/csv;charset=utf-8;');
}

function exportPDF(data, columns) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const fileName = getExportFileName(data, 'pdf');
    
    const tableData = data.map(row => 
        columns.map(column => formatValueForExport(getColumnName(column), row[getColumnName(column)]))
    );
    
    doc.autoTable({
        head: [columns.map(getColumnDisplayName)],
        body: tableData,
        styles: { fontSize: 8 },
        margin: { top: 10 }
    });
    
    doc.save(fileName);
}

function exportXLSX(data, columns) {
    const wb = XLSX.utils.book_new();
    const fileName = getExportFileName(data, 'xlsx');
    
    // Get the report type
    const reportType = document.getElementById('type').value;
    
    // Create header row
    const wsData = [columns.map(getColumnDisplayName)];
    
    // Add data rows
    data.forEach(row => {
        const rowData = columns.map(column => formatValueForExport(getColumnName(column), row[getColumnName(column)]));
        wsData.push(rowData);
    });
    
    // Create worksheet
    const ws = XLSX.utils.aoa_to_sheet(wsData);
    
    // Style configurations
    const greenStyle = {
        fill: {
            fgColor: { rgb: "32CD32" } // Bright green
        }
    };
    
    // Initialize !rows if it doesn't exist
    ws['!rows'] = ws['!rows'] || [];
    
    // Apply styling for approved users
    if (reportType === 'payroll' || reportType === 'master_payroll') {
        for (let i = 1; i < wsData.length; i++) { // Start from 1 to skip header
            const row = data[i-1]; // Get corresponding data row
            
            // Check if user is approved for payment
            if (row.approved_for_payment) {
                // Set row properties
                ws['!rows'][i] = ws['!rows'][i] || {};
                
                // Apply styling to each cell in the row
                for (let j = 0; j < columns.length; j++) {
                    const cellRef = XLSX.utils.encode_cell({r: i, c: j});
                    ws[cellRef] = ws[cellRef] || { v: '', t: 's' };
                    ws[cellRef].s = {
                        fill: {
                            patternType: "solid",
                            fgColor: { rgb: "32CD32" }
                        }
                    };
                }
            }
        }
    }
    
    // Set column widths
    const colWidths = columns.map(() => ({ wch: 15 }));
    ws['!cols'] = colWidths;
    
    // Add worksheet to workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Report');
    
    // Write file
    XLSX.writeFile(wb, fileName);
}

function exportJSON(data) {
    const fileName = getExportFileName(data, 'json');
    downloadFile(
        JSON.stringify(data, null, 2),
        fileName,
        'application/json'
    );
}

function formatValueForExport(columnName, value) {
    if (value === null || value === undefined) return '';
    
    switch (columnName) {
        case 'approved_for_payment':
            return value;
        case 'invoice_files':
        case 'new_invoice_files':
            if (value) {
                const baseUrl = window.location.origin;
                const invoices = value.split(',').map(invoice => {
                    const [label, path] = invoice.trim().split(': ');
                    return `${label}: ${baseUrl}/storage/${path}`;
                });
                return invoices.join('\n');
            }
            return 'No Invoices';
            
        case 'payment_cycles':
            return value ? value.split(',').map(cycle => cycle.trim()).join(', ') : '';
        case 'total_amount':
        case 'daily_amount':
        case 'amount_payable':
        case 'tax':
        case 'productivity':
            // Return raw number without currency symbol or formatting
            return typeof value === 'string' ? 
                value.replace(/[^0-9.]/g, '') : 
                value.toString();
            
        case 'start_date':
        case 'end_date':
        case 'created_at':
        case 'updated_at':
            return formatDate(value);
            
        case 'days_attended':
        case 'quantity':
        case 'remainder':
            return typeof value === 'number' ? 
                value.toString() : 
                value.replace(/[^0-9.]/g, '');
            
        case 'account_name':
            return value.toUpperCase();
            
        case 'phone_number':
            if (!value) return '';
            // Standardize phone numbers to start with 254
            const cleaned = value.replace(/\D/g, '');
            if (cleaned.length >= 9) {
                const lastNine = cleaned.slice(-9);
                return `254${lastNine}`;
            }
            return value;
        case 'advance_pay':
        case 'net_payable':
            return typeof value === 'string' ? 
                value.replace(/[^0-9.]/g, '') : 
                value.toString();
            
        case 'branch_name':
        case 'branch_code':
            return value || '';
            
        default:
            return value;
    }
}

function getExportFileName(data, extension) {
    const reportType = document.getElementById('type').value;
    const date = moment().format('YYYY-MM-DD');
    
    // Special handling for payroll reports
    if (reportType === 'payroll' && data.length > 0 && data[0].cycle) {
        const cycleName = data[0].cycle.replace(/\s+/g, '_').toLowerCase();
        return `${cycleName}_${date}.${extension}`;
    }
    
    // For other report types
    return `${reportType}_report_${date}.${extension}`;
}

function downloadFile(content, filename, type) {
    const blob = new Blob([content], { type });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.style.display = 'none';
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}

    // Initialize everything
    function initializeEventListeners() {
        document.getElementById('type').addEventListener('change', updateFilters);

        const columnDropdown = document.getElementById('columnDropdown');
        const columnList = document.getElementById('columnList');

        columnDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            columnList.style.display = columnList.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function(e) {
            if (!columnDropdown.contains(e.target) && !columnList.contains(e.target)) {
                columnList.style.display = 'none';
            }
        });

        columnList.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            currentPage = 1;
            fetchReport();
        });

        document.getElementById('exportCSV').addEventListener('click', () => fetchReportForExport('csv'));
        document.getElementById('exportPDF').addEventListener('click', () => fetchReportForExport('pdf'));
        document.getElementById('exportXLSX').addEventListener('click', () => fetchReportForExport('xlsx'));
        document.getElementById('exportJSON').addEventListener('click', () => fetchReportForExport('json'));
    }

    // Initialize the application
    initializeEventListeners();
    updateFilters();
});
</script>
@endpush
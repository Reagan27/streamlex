@extends('layouts.app')

@section('page-title', 'Edit Back to Office Report')
@section('page-heading', 'Edit Back to Office Report')

@section('content')
<div class="container-fluid">
    @include('partials.messages')

    <form action="{{ route('back-to-office-reports.update', $report) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-9">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Back to Office Report</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="project_name" class="form-label"><strong>PROJECT NAME</strong></label>
                                <input type="text" class="form-control" id="project_name" name="project_name" value="{{ old('project_name', $report->project_name) }}" placeholder="Enter project name">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="activity_date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="activity_date" name="activity_date" value="{{ old('activity_date', $report->activity_date?->format('Y-m-d')) }}">
                                <small class="form-text text-muted">Capture date of activity</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="activity" class="form-label">Activity</label>
                                <input type="text" class="form-control" id="activity" name="activity" value="{{ old('activity', $report->activity) }}" placeholder="Describe the activity">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="venue" class="form-label">Venue</label>
                                <input type="text" class="form-control" id="venue" name="venue" value="{{ old('venue', $report->venue) }}" placeholder="Venue of activity">
                            </div>
                        </div>
                        <hr>
                        <h6 class="mb-3 text-primary">PARTICIPANTS <small class="text-muted">(Show numbers by age, gender, Disability & VMGs)</small></h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle text-center" id="participants-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Category</th>
                                        <th>18–35 Years</th>
                                        <th>35–59 Years</th>
                                        <th>Above 60 Years</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(['Male', 'Female', 'Disability', 'VMGs'] as $cat)
                                    <tr>
                                        <td>{{ $cat }}</td>
                                        @for($i=0; $i<3; $i++)
                                        <td><input type="number" class="form-control participant-input" name="participants[{{ strtolower($cat) }}][{{ $i }}]" min="0" value="{{ old('participants.'.strtolower($cat).'.'.$i, $report->participants[strtolower($cat)][$i] ?? '') }}" oninput="calculateRowTotal(this)"></td>
                                        @endfor
                                        <td><input type="number" class="form-control total-cell" name="participants[{{ strtolower($cat) }}][total]" value="{{ old('participants.'.strtolower($cat).'.total', $report->participants[strtolower($cat)]['total'] ?? '') }}" readonly></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <h6 class="mb-3 text-primary">NARRATIVE SECTIONS</h6>
                        <div class="mb-3">
                            <label for="introduction" class="form-label">Introduction</label>
                            <textarea class="form-control" id="introduction" name="introduction" rows="3">{{ old('introduction', $report->introduction) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="objective" class="form-label">Objective</label>
                            <textarea class="form-control" id="objective" name="objective" rows="3">{{ old('objective', $report->objective) }}</textarea>
                        </div>
                        <hr>
                        <h6 class="mb-3 text-primary">BUDGET / EXPENDITURE</h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle text-center" id="budget-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Planned</th>
                                        <th>Actual</th>
                                        <th>Variance</th>
                                        <th>Comment</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="budget-tbody">
                                    @if(isset($report->budget['item']) && is_array($report->budget['item']))
                                        @foreach($report->budget['item'] as $i => $item)
                                        <tr>
                                            <td><input type="text" class="form-control" name="budget[item][]" value="{{ old('budget.item.'.$i, $item) }}"></td>
                                            <td><input type="number" class="form-control planned-input" name="budget[planned][]" value="{{ old('budget.planned.'.$i, $report->budget['planned'][$i] ?? '') }}" oninput="calculateVariance(this)"></td>
                                            <td><input type="number" class="form-control actual-input" name="budget[actual][]" value="{{ old('budget.actual.'.$i, $report->budget['actual'][$i] ?? '') }}" oninput="calculateVariance(this)"></td>
                                            <td><input type="number" class="form-control variance-cell" name="budget[variance][]" value="{{ old('budget.variance.'.$i, $report->budget['variance'][$i] ?? '') }}" readonly></td>
                                            <td><input type="text" class="form-control" name="budget[comment][]" value="{{ old('budget.comment.'.$i, $report->budget['comment'][$i] ?? '') }}"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeBudgetRow(this)"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td><input type="text" class="form-control" name="budget[item][]" placeholder="Item"></td>
                                            <td><input type="number" class="form-control planned-input" name="budget[planned][]" placeholder="Planned" oninput="calculateVariance(this)"></td>
                                            <td><input type="number" class="form-control actual-input" name="budget[actual][]" placeholder="Actual" oninput="calculateVariance(this)"></td>
                                            <td><input type="number" class="form-control variance-cell" name="budget[variance][]" placeholder="Variance" readonly></td>
                                            <td><input type="text" class="form-control" name="budget[comment][]" placeholder="Comment"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeBudgetRow(this)"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm" onclick="addBudgetRow()"><i class="fas fa-plus"></i> Add Item</button>
                        </div>
                        <div class="mb-3">
                            <label for="output" class="form-label">Output</label>
                            <textarea class="form-control" id="output" name="output" rows="3">{{ old('output', $report->output) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="key_highlights" class="form-label">Key Highlights</label>
                            <textarea class="form-control" id="key_highlights" name="key_highlights" rows="3">{{ old('key_highlights', $report->key_highlights) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="challenges_and_risks" class="form-label">Challenges & Risks</label>
                            <textarea class="form-control" id="challenges_and_risks" name="challenges_and_risks" rows="3">{{ old('challenges_and_risks', $report->challenges_and_risks) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="best_practices" class="form-label">Best Practices</label>
                            <textarea class="form-control" id="best_practices" name="best_practices" rows="3">{{ old('best_practices', $report->best_practices) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="lessons_learnt" class="form-label">Lessons Learnt</label>
                            <textarea class="form-control" id="lessons_learnt" name="lessons_learnt" rows="3">{{ old('lessons_learnt', $report->lessons_learnt) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="recommendations" class="form-label">Recommendations</label>
                            <textarea class="form-control" id="recommendations" name="recommendations" rows="3">{{ old('recommendations', $report->recommendations) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="way_forward" class="form-label">Way Forward</label>
                            <textarea class="form-control" id="way_forward" name="way_forward" rows="3">{{ old('way_forward', $report->way_forward) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Report Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="county_id" class="form-label">County (optional)</label>
                            <select class="form-control @error('county_id') is-invalid @enderror"
                                    id="county_id"
                                    name="county_id">
                                <option value="">Select County (optional)</option>
                                @foreach($counties as $county)
                                    <option value="{{ $county->id }}"
                                            {{ old('county_id', $report->county_id) == $county->id ? 'selected' : '' }}>
                                        {{ $county->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('county_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ======================================================= --}}
                {{-- ANNEXES — NO nested <form> tags. Uses fetch() instead.   --}}
                {{-- ======================================================= --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Annexes</h5>
                    </div>
                    <div class="card-body">
                        @if($report->attachments->count())
                        <div class="mb-3" id="existing-attachments">
                            <h6 class="mb-2">Current Annexes</h6>
                            @foreach($report->attachments as $attachment)
                            <div id="attachment-{{ $attachment->id }}" style="padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem;">
                                <div>
                                    <i class="fas fa-file me-2"></i>
                                    <span title="{{ $attachment->original_name }}">{{ Str::limit($attachment->original_name, 15) }}</span>
                                </div>
                                {{-- type="button" — does NOT submit the parent form --}}
                                <button type="button"
                                        class="btn btn-sm btn-danger remove-attachment"
                                        data-attachment-id="{{ $attachment->id }}"
                                        data-report-id="{{ $report->id }}"
                                        title="Remove file">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <input type="file"
                               id="file-upload"
                               name="attachments[]"
                               multiple
                               class="d-none">

                        <div class="file-upload-area"
                             onclick="document.getElementById('file-upload').click()"
                             style="border: 2px dashed #ddd; border-radius: 8px; padding: 15px; text-align: center; cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p class="mb-0 small">Click to upload</p>
                        </div>

                        <div id="file-preview-container" class="mt-3"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" name="status" value="draft" class="btn btn-secondary w-100 mb-2">
                            <i class="fas fa-save me-2"></i>Save as Draft
                        </button>
                        <button type="submit" name="status" value="submitted" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>Submit Report
                        </button>
                        <a href="{{ route('back-to-office-reports.show', $report) }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // -------------------------------------------------------
    // Remove existing attachment via fetch (no nested form)
    // -------------------------------------------------------
    document.querySelectorAll('.remove-attachment').forEach(function (button) {
        button.addEventListener('click', function () {
            if (!confirm('Are you sure you want to remove this file?')) return;

            const attachmentId = this.dataset.attachmentId;
            const reportId     = this.dataset.reportId;

            fetch(`/back-to-office-reports/${reportId}/attachments/${attachmentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(function (response) {
                if (!response.ok) throw new Error('Server error: ' + response.status);
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    const el = document.getElementById('attachment-' + attachmentId);
                    if (el) el.remove();
                } else {
                    alert('Failed to remove file. Please try again.');
                }
            })
            .catch(function (error) {
                console.error('Remove attachment error:', error);
                alert('An error occurred while removing the file. Please try again.');
            });
        });
    });

    // -------------------------------------------------------
    // New file upload preview
    // -------------------------------------------------------
    const fileUpload       = document.getElementById('file-upload');
    const previewContainer = document.getElementById('file-preview-container');

    fileUpload.addEventListener('change', function () {
        for (const file of this.files) {
            if (file.size > 2 * 1024 * 1024) {
                alert('File "' + file.name + '" exceeds the 2MB limit and was skipped.');
                continue;
            }

            const preview = document.createElement('div');
            preview.style.cssText = 'padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem;';
            preview.innerHTML = `
                <div>
                    <i class="fas fa-file me-2"></i>
                    <span>${file.name}</span>
                </div>
                <button type="button" class="btn btn-sm btn-danger">
                    <i class="fas fa-times"></i>
                </button>
            `;

            preview.querySelector('button').addEventListener('click', function () {
                preview.remove();
            });

            previewContainer.appendChild(preview);
        }
    });

    // -------------------------------------------------------
    // Participants row totals
    // -------------------------------------------------------
    window.calculateRowTotal = function (input) {
        const row    = input.closest('tr');
        const inputs = row.querySelectorAll('.participant-input');
        let total    = 0;
        inputs.forEach(function (i) { total += parseInt(i.value) || 0; });
        const totalCell = row.querySelector('.total-cell');
        if (totalCell) totalCell.value = total;
    };

    // -------------------------------------------------------
    // Budget variance
    // -------------------------------------------------------
    window.calculateVariance = function (input) {
        const row      = input.closest('tr');
        const planned  = parseFloat(row.querySelector('.planned-input')?.value) || 0;
        const actual   = parseFloat(row.querySelector('.actual-input')?.value)  || 0;
        const variance = row.querySelector('.variance-cell');
        if (variance) variance.value = (planned - actual).toFixed(2);
    };

    // -------------------------------------------------------
    // Budget table row management
    // -------------------------------------------------------
    window.addBudgetRow = function () {
        const tbody = document.getElementById('budget-tbody');
        const tr    = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text"   class="form-control"                   name="budget[item][]"     placeholder="Item"></td>
            <td><input type="number" class="form-control planned-input"     name="budget[planned][]"  placeholder="Planned" oninput="calculateVariance(this)"></td>
            <td><input type="number" class="form-control actual-input"      name="budget[actual][]"   placeholder="Actual"  oninput="calculateVariance(this)"></td>
            <td><input type="number" class="form-control variance-cell"     name="budget[variance][]" placeholder="Variance" readonly></td>
            <td><input type="text"   class="form-control"                   name="budget[comment][]"  placeholder="Comment"></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeBudgetRow(this)"><i class="fas fa-trash"></i></button></td>
        `;
        tbody.appendChild(tr);
    };

    window.removeBudgetRow = function (button) {
        const tbody = document.getElementById('budget-tbody');
        if (tbody.rows.length > 1) {
            button.closest('tr').remove();
        } else {
            alert('At least one budget row is required.');
        }
    };

});
</script>
@endsection
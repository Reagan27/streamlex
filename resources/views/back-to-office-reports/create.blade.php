@extends('layouts.app')

@section('page-title', __('Create Back to Office Report'))
@section('page-heading', __('Create New Back to Office Report'))

@section('content')
<div class="container-fluid">
    @include('partials.messages')

    <form action="{{ route('back-to-office-reports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-lg-9">
                <!-- Main Content -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Back to Office Report</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="project_name" class="form-label"><strong>PROJECT NAME</strong></label>
                                <input type="text" class="form-control" id="project_name" name="project_name" value="{{ old('project_name') }}" placeholder="Enter project name">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="activity_date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="activity_date" name="activity_date" value="{{ old('activity_date') }}">
                                <small class="form-text text-muted">Capture date of activity</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="activity" class="form-label">Activity</label>
                                <input type="text" class="form-control" id="activity" name="activity" value="{{ old('activity') }}" placeholder="Describe the activity">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="venue" class="form-label">Venue</label>
                                <input type="text" class="form-control" id="venue" name="venue" value="{{ old('venue') }}" placeholder="Venue of activity">
                            </div>
                        </div>
                        <hr>
                        <h6 class="mb-3 text-primary">PARTICIPANTS <small class="text-muted">(Show numbers by age, gender, Disability & VMGs)</small></h6>
                        <small class="form-text text-muted">Fill in the number of participants for each category and age bracket.</small>
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
                                        <td><input type="number" class="form-control participant-input" name="participants[{{ strtolower($cat) }}][{{ $i }}]" min="0" value="{{ old('participants.'.strtolower($cat).'.'.$i) }}" oninput="calculateRowTotal(this)"></td>
                                        @endfor
                                        <td><input type="number" class="form-control total-cell" name="participants[{{ strtolower($cat) }}][total]" readonly></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <h6 class="mb-3 text-primary">NARRATIVE SECTIONS</h6>
                        <div class="mb-3">
                            <label for="introduction" class="form-label">Introduction</label>
                            <textarea class="form-control" id="introduction" name="introduction" rows="3" placeholder="Describe activity including who were involved and provide numbers by gender, Disability & VMGs">{{ old('introduction') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="objective" class="form-label">Objective</label>
                            <textarea class="form-control" id="objective" name="objective" rows="3" placeholder="Describe the reason why activity was being undertaken">{{ old('objective') }}</textarea>
                        </div>
                        <hr>
                        <h6 class="mb-3 text-primary">BUDGET / EXPENDITURE <small class="text-muted">(Show how much was planned for activity and actual incurred)</small></h6>
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
                                    <tr>
                                        <td><input type="text" class="form-control" name="budget[item][]" placeholder="Item"></td>
                                        <td><input type="number" class="form-control planned-input" name="budget[planned][]" placeholder="Planned" oninput="calculateVariance(this)"></td>
                                        <td><input type="number" class="form-control actual-input" name="budget[actual][]" placeholder="Actual" oninput="calculateVariance(this)"></td>
                                        <td><input type="number" class="form-control variance-cell" name="budget[variance][]" placeholder="Variance" readonly></td>
                                        <td><input type="text" class="form-control" name="budget[comment][]" placeholder="Comment"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeBudgetRow(this)"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm" onclick="addBudgetRow()"><i class="fas fa-plus"></i> Add Item</button>
                        </div>
                        <div class="mb-3">
                            <label for="output" class="form-label">Output</label>
                            <textarea class="form-control" id="output" name="output" rows="3" placeholder="What the activity was meant to generally achieve or help achieve in the immediate in relation to the long-term">{{ old('output') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="key_highlights" class="form-label">Key Highlights</label>
                            <textarea class="form-control" id="key_highlights" name="key_highlights" rows="3" placeholder="Present a brief narration on activity progressed — snap view of key issues">{{ old('key_highlights') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="challenges_and_risks" class="form-label">Challenges & Risks</label>
                            <textarea class="form-control" id="challenges_and_risks" name="challenges_and_risks" rows="3" placeholder="State any challenges and emerging risks experienced in implementing the particular activity">{{ old('challenges_and_risks') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="best_practices" class="form-label">Best Practices</label>
                            <textarea class="form-control" id="best_practices" name="best_practices" rows="3" placeholder="State any best practices reported & where">{{ old('best_practices') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="lessons_learnt" class="form-label">Lessons Learnt</label>
                            <textarea class="form-control" id="lessons_learnt" name="lessons_learnt" rows="3" placeholder="State any lessons learnt & where">{{ old('lessons_learnt') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="recommendations" class="form-label">Recommendations</label>
                            <textarea class="form-control" id="recommendations" name="recommendations" rows="3" placeholder="Provide recommendations agreed upon">{{ old('recommendations') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="way_forward" class="form-label">Way Forward</label>
                            <textarea class="form-control" id="way_forward" name="way_forward" rows="3" placeholder="State the way forward with regard to the specific activity">{{ old('way_forward') }}</textarea>
                        </div>
                        <!-- Removed ANNEXES section as requested -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <!-- Sidebar -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Report Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                           <label for="county_id" class="form-label">County <span class="text-danger">*</span></label>
                           <select class="form-control @error('county_id') is-invalid @enderror" 
                                    id="county_id" 
                                    name="county_id"
                                    required>
                                <option value="">Select County</option>
                                @foreach($counties as $county)
                                    <option value="{{ $county->id }}" 
                                            {{ old('county_id') == $county->id ? 'selected' : '' }}>
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

                <!-- File Upload Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Annexes</h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">You can upload as many documents as needed:</p>
                        <script>
                        // PARTICIPANTS: Auto-calculate row totals
                        function calculateRowTotal(input) {
                            const row = input.closest('tr');
                            let total = 0;
                            row.querySelectorAll('.participant-input').forEach(cell => {
                                total += parseInt(cell.value) || 0;
                            });
                            row.querySelector('.total-cell').value = total;
                        }

                        // BUDGET: Dynamic rows and auto-calculate variance
                        function addBudgetRow() {
                            const tbody = document.getElementById('budget-tbody');
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td><input type="text" class="form-control" name="budget[item][]" placeholder="Item"></td>
                                <td><input type="number" class="form-control planned-input" name="budget[planned][]" placeholder="Planned" oninput="calculateVariance(this)"></td>
                                <td><input type="number" class="form-control actual-input" name="budget[actual][]" placeholder="Actual" oninput="calculateVariance(this)"></td>
                                <td><input type="number" class="form-control variance-cell" name="budget[variance][]" placeholder="Variance" readonly></td>
                                <td><input type="text" class="form-control" name="budget[comment][]" placeholder="Comment"></td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeBudgetRow(this)"><i class="fas fa-trash"></i></button></td>
                            `;
                            tbody.appendChild(tr);
                        }
                        function removeBudgetRow(btn) {
                            btn.closest('tr').remove();
                        }
                        function calculateVariance(input) {
                            const row = input.closest('tr');
                            const planned = parseFloat(row.querySelector('.planned-input').value) || 0;
                            const actual = parseFloat(row.querySelector('.actual-input').value) || 0;
                            row.querySelector('.variance-cell').value = planned - actual;
                        }
                        </script>
                        
                        <input type="file" 
                               id="file-upload" 
                               name="attachments[]" 
                               multiple 
                               class="d-none">

                        <div class="file-upload-area" onclick="document.getElementById('file-upload').click()" style="border: 2px dashed #ddd; border-radius: 8px; padding: 15px; text-align: center; cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p class="mb-0 small">Click to upload</p>
                        </div>

                        <div id="file-preview-container" class="mt-3"></div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" name="status" value="draft" class="btn btn-secondary w-100 mb-2">
                            <i class="fas fa-save me-2"></i>Save as Draft
                        </button>
                        <button type="submit" name="status" value="submitted" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>Submit Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileUpload = document.getElementById('file-upload');
    const previewContainer = document.getElementById('file-preview-container');

    fileUpload.addEventListener('change', handleFileSelect);

    function handleFileSelect(e) {
        const files = this.files;
        for (const file of files) {
            if (file.size > 2 * 1024 * 1024) {
                alert(`File ${file.name} is larger than 2MB`);
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

            preview.querySelector('button').onclick = function(e) {
                e.preventDefault();
                preview.remove();
            };

            previewContainer.appendChild(preview);
        }
    }
});
</script>
@endsection

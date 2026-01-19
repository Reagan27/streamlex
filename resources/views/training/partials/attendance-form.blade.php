<div class="mb-4">
    <label for="search_id">Enter Your ID Number</label>
    <div class="input-group custom-search-form">
        <input type="text"
               class="form-control"
               id="search_id"
               placeholder="Enter your ID number"
               pattern="[0-9]{5,9}"
               title="ID number must be between 5 and 9 digits">
        <span class="input-group-append">
            <button class="btn btn-light" type="button" onclick="clearSearch()">
                <i class="fas fa-times text-muted"></i>
            </button>
            <button class="btn btn-light" type="button" onclick="searchAttendee()">
                <i class="fas fa-search text-muted"></i>
            </button>
        </span>
    </div>
</div>

<div id="search-result-section" style="display: none;"></div>

{{-- Registration Form --}}


<div id="registration-form-section" style="display: none;">
    <form id="registration-form" action="{{ route('training.store-attendance', $event->slug) }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="name">Full Name</label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   id="name" 
                   name="name" 
                   required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="designation">Designation</label>
            <input type="text" 
                   class="form-control @error('designation') is-invalid @enderror" 
                   id="designation" 
                   name="designation" 
                   placeholder="e.g., Quality Assurance"
                   required>
            @error('designation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="id_number">ID Number</label>
            <input type="text" 
                   class="form-control @error('id_number') is-invalid @enderror" 
                   id="id_number" 
                   name="id_number" 
                   required 
                   readonly>
            @error('id_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="phone_number">Phone Number</label>
            <input type="text" 
                   class="form-control @error('phone_number') is-invalid @enderror" 
                   id="phone_number" 
                   name="phone_number" 
                   placeholder="e.g., 0712345678"
                   required>
            <small class="text-muted">Format: 07XXXXXXXX or 01XXXXXXXX</small>
        </div>

        <div class="form-group mb-3">
            <label for="email">Email Address</label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @include('training.partials.signature-pad')

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Register Attendance</button>
        </div>
    </form>
</div>

{{-- Attendance Form --}}

<div id="attendance-form-section" style="display: none;">
    <form id="attendance-form" action="{{ route('training.store-attendance', $event->slug) }}" method="POST">
        @csrf
        <input type="hidden" name="is_existing" value="1">
        <div id="attendee-details" class="mb-4"></div>
        
        <input type="hidden" name="id_number" id="hidden_id_number">
        <input type="hidden" name="name" id="hidden_name">
        <input type="hidden" name="email" id="hidden_email">
        <input type="hidden" name="phone_number" id="hidden_phone_number">
        
        @if(Carbon\Carbon::today()->isSameDay($event->end_date))
        <div class="alert alert-info mb-3">
            <h5 class="alert-heading">Last Day Notice</h5>
            <p>This is the last day of training. Please verify your phone number to process payment.</p>
            <div class="mt-3">
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="verify_phone" 
                           placeholder="Enter phone number (07XXXXXXXX)">
                    <button class="btn btn-outline-secondary" 
                            type="button"
                            onclick="verifyPhone()">
                        Verify Phone
                    </button>
                </div>
                <small class="text-muted">Format: 07XXXXXXXX or 01XXXXXXXX</small>
            </div>
            <div id="phone-verify-status" class="mt-2" style="display: none;"></div>
        </div>
        @endif

        <div id="attendance-status" class="alert alert-warning d-none mb-3"></div>
        @include('training.partials.signature-pad')

        <button type="submit" id="submit-attendance" class="btn btn-primary">Confirm Attendance</button>
    </form>
</div>

@push('scripts')
<script>
// Function to get user's location
function getLocation() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error('Geolocation is not supported by your browser'));
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                });
            },
            (error) => {
                reject(error);
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    });
}

// Search attendee function
async function searchAttendee() {
    try {
        const idNumber = document.getElementById('search_id').value;
        if (!idNumber) {
            alert('Please enter an ID number');
            return;
        }

        document.getElementById('search_id').disabled = true;
        const submitBtn = document.querySelector('button[onclick="searchAttendee()"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Searching...';

        const response = await fetch(`/t/search/${encodeURIComponent('{{ $event->slug }}')}?id_number=${idNumber}`);
        const data = await response.json();

        document.querySelectorAll('[id$="-form-section"], #search-result-section').forEach(el => el.style.display = 'none');

        if (data.banned) {
            document.getElementById('search-result-section').innerHTML = `
                <div class="alert alert-danger">
                    <h4 class="alert-heading">Access Denied</h4>
                    <p><strong>You have been banned from attending trainings</strong></p>
                    <p><strong>Reason:</strong> ${data.reason}</p>
                    <p><strong>Banned on:</strong> ${data.banned_at}</p>
                    <hr>
                    <p class="mb-0">If you believe this is an error, please contact your supervisor.</p>
                </div>
            `;
            document.getElementById('search-result-section').style.display = 'block';
            return;
        }

        if (data.found) {
            const formSection = document.getElementById('attendance-form-section');
            formSection.style.display = 'block';
            
            document.getElementById('hidden_id_number').value = idNumber;
            document.getElementById('hidden_name').value = data.data.name;
            document.getElementById('hidden_email').value = data.data.email;
            document.getElementById('hidden_phone_number').value = data.data.phone_number;

            const submitButton = document.getElementById('submit-attendance');
            const statusDiv = document.getElementById('attendance-status');
            
            if (data.data.attended_today) {
                submitButton.disabled = true;
                statusDiv.classList.remove('d-none', 'alert-info');
                statusDiv.classList.add('alert-warning');
                statusDiv.innerHTML = `
                    <strong>Already Attended Today</strong><br>
                    You have already recorded your attendance for today.
                `;
            } else {
                submitButton.disabled = false;
                statusDiv.classList.remove('d-none', 'alert-warning');
                statusDiv.classList.add('alert-info');
                statusDiv.innerHTML = `
                    <strong>Ready to Sign In</strong><br>
                    Please add your signature to confirm your attendance.
                `;
            }

            updateAttendeeDisplay(data.data);
        } 
        else if (data.canRegister) {
            const formSection = document.getElementById('registration-form-section');
            formSection.style.display = 'block';
            document.getElementById('id_number').value = idNumber;
        } 
        else {
            document.getElementById('search-result-section').innerHTML = `
                <div class="alert alert-warning">
                    <p>${data.message}</p>
                </div>
            `;
            document.getElementById('search-result-section').style.display = 'block';
        }

    } catch (error) {
        console.error('Error searching attendee:', error);
        alert('Error searching for attendee. Please try again.');
        document.getElementById('search-result-section').innerHTML = `
            <div class="alert alert-danger">
                <p>An error occurred while searching. Please try again later.</p>
            </div>
        `;
        document.getElementById('search-result-section').style.display = 'block';
    } finally {
        document.getElementById('search_id').disabled = false;
        const submitBtn = document.querySelector('button[onclick="searchAttendee()"]');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-search text-muted"></i>';
    }
}

// Clear search function
function clearSearch() {
    document.getElementById('search_id').value = '';
    document.querySelectorAll('[id$="-form-section"], #search-result-section').forEach(el => el.style.display = 'none');
}

// Phone verification function
async function verifyPhone() {
    const phoneInput = document.getElementById('verify_phone');
    const statusDiv = document.getElementById('phone-verify-status');
    const idNumber = document.getElementById('hidden_id_number').value;

    try {
        const response = await fetch('{{ route("training.verify-phone", $event->slug) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                id_number: idNumber,
                phone_number: phoneInput.value
            })
        });

        const data = await response.json();
        statusDiv.style.display = 'block';

        if (data.success) {
            statusDiv.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Phone number verified successfully
                </div>`;
            phoneInput.disabled = true;
            // Update attendee display to show verified status
            const attendeeDetails = document.getElementById('attendee-details');
            if (attendeeDetails) {
                const currentDetails = attendeeDetails.querySelector('.card-body');
                if (currentDetails) {
                    const phoneElement = currentDetails.querySelector('p:contains("Phone:")');
                    if (phoneElement) {
                        phoneElement.innerHTML = `
                            <strong>Phone:</strong> ${data.formatted_number}
                            <span class="badge bg-success ms-2">Verified</span>
                        `;
                    }
                }
            }
        } else {
            statusDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> ${data.message}
                </div>`;
        }
    } catch (error) {
        console.error('Phone verification error:', error);
        statusDiv.innerHTML = `
            <div class="badge bg-success ">
               Phone number verified
            </div>`;
    }
}

// Update attendee display function
function updateAttendeeDisplay(data) {
    const isLastDay = {{ Carbon\Carbon::today()->isSameDay($event->end_date) ? 'true' : 'false' }};
    const detailsHtml = `
        <div class="card">
            <div class="card-body">
                <h5>${data.name}</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Title:</strong> ${data.title || 'N/A'}</p>
                        <p><strong>Designation:</strong> ${data.designation || 'N/A'}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p>
                            <strong>Phone:</strong> ${data.phone_number}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Days Attended:</strong> ${data.days_attended}</p>
                        <p><strong>Current Total:</strong> ${data.total_amount}</p>
                    </div>
                </div>
                <p><strong>Last Attendance:</strong> ${data.last_attendance_date}</p>
                ${data.is_completed ? '<p class="text-success"><strong>Training Completed</strong></p>' : ''}
                ${(!data.phone_verified && isLastDay) ? `
                    <div class="alert alert-warning mt-2">
                    </div>
                ` : ''}
            </div>
        </div>`;

    document.getElementById('attendee-details').innerHTML = detailsHtml;
}
// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Search input enter key handler
    document.getElementById('search_id').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            searchAttendee();
        }
    });

    // Form submission handlers
    const forms = document.querySelectorAll('#registration-form, #attendance-form');
    forms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            if ({{ $event->enforce_location ? 'true' : 'false' }}) {
                e.preventDefault();
                
                try {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying location...';
                    
                    const position = await getLocation();
                    
                    const latInput = document.createElement('input');
                    latInput.type = 'hidden';
                    latInput.name = 'latitude';
                    latInput.value = position.latitude;
                    
                    const lngInput = document.createElement('input');
                    lngInput.type = 'hidden';
                    lngInput.name = 'longitude';
                    lngInput.value = position.longitude;
                    
                    this.appendChild(latInput);
                    this.appendChild(lngInput);
                    
                    this.submit();
                } catch (error) {
                    console.error('Location error:', error);
                    alert('Error: You must allow location access to register attendance');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            }
        });
    });
});
</script>
@endpush

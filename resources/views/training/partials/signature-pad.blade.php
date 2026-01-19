<div class="form-group">
    <label for="signature">Signature</label>
    <div class="signature-pad">
        <canvas class="signature-canvas"></canvas>
    </div>
    <input type="hidden" name="signature" class="signature-data">
    <button type="button" class="btn btn-secondary clear-signature">Clear Signature</button>
</div>

<style>
    .signature-pad {
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    .signature-pad canvas {
        width: 100%;
        height: 200px;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    function initializeSignaturePad(formElement) {
        const canvas = formElement.querySelector('.signature-canvas');
        const clearButton = formElement.querySelector('.clear-signature');
        const signatureInput = formElement.querySelector('.signature-data');

        // Set canvas dimensions
        canvas.width = 400;
        canvas.height = 200;

        // Initialize SignaturePad
        const signaturePad = new SignaturePad(canvas);

        // Clear signature handler
        clearButton.addEventListener('click', () => {
            signaturePad.clear();
        });

        // Form submission handler
        formElement.addEventListener('submit', function(e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert('Please provide a signature');
                return false;
            }
            
            const signatureData = signaturePad.toDataURL();
            signatureInput.value = signatureData;
        });

        return signaturePad;
    }

    // Initialize all signature pads when the document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize for registration form
        const registrationForm = document.getElementById('registration-form');
        if (registrationForm) {
            const regSignaturePad = initializeSignaturePad(registrationForm);
            registrationForm.signaturePad = regSignaturePad;
        }

        // Initialize for attendance form
        const attendanceForm = document.getElementById('attendance-form');
        if (attendanceForm) {
            const attSignaturePad = initializeSignaturePad(attendanceForm);
            attendanceForm.signaturePad = attSignaturePad;
        }
    });
</script>


<script>
    async function searchAttendee() {
        try {
            const idNumber = document.getElementById('search_id').value;
            if (!idNumber) {
                alert('Please enter an ID number');
                return;
            }

            // Show loading state
            document.getElementById('search_id').disabled = true;
            const submitBtn = document.querySelector('button[onclick="searchAttendee()"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Searching...';

            const response = await fetch(`/t/search/${encodeURIComponent('{{ $event->slug }}')}?id_number=${idNumber}`);
            const data = await response.json();

            // Hide all form sections and clear their signature pads
            document.querySelectorAll('[id$="-form-section"]').forEach(el => {
                el.style.display = 'none';
                const form = el.querySelector('form');
                if (form && form.signaturePad) {
                    form.signaturePad.clear();
                }
            });

            if (data.found) {
                // Show attendance form for existing attendee
                const formSection = document.getElementById('attendance-form-section');
                formSection.style.display = 'block';

                // Set hidden fields
                document.getElementById('hidden_id_number').value = idNumber;
                document.getElementById('hidden_name').value = data.data.name;
                document.getElementById('hidden_email').value = data.data.email;
                document.getElementById('hidden_phone_number').value = data.data.phone_number;

                // Check attendance status
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

                // Update attendee details display
                document.getElementById('attendee-details').innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <h5>${data.data.name}</h5>
                            <p><strong>Email:</strong> ${data.data.email}</p>
                            <p><strong>Phone:</strong> ${data.data.phone_number}</p>
                            <p><strong>Days Attended:</strong> ${data.data.days_attended}</p>
                            <p><strong>Current Total:</strong> ${data.data.total_amount}</p>
                            <p><strong>Last Attendance:</strong> ${data.data.last_attendance_date}</p>
                        </div>
                    </div>
                `;
            } else {
                // Show registration form for new attendee
                const formSection = document.getElementById('registration-form-section');
                formSection.style.display = 'block';
                document.getElementById('id_number').value = idNumber;
            }
        } catch (error) {
            console.error('Error searching attendee:', error);
            alert('Error searching for attendee. Please try again.');
        } finally {
            // Reset loading state
            document.getElementById('search_id').disabled = false;
            const submitBtn = document.querySelector('button[onclick="searchAttendee()"]');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Search';
        }
    }

    // Add event listener for enter key on search input
    document.getElementById('search_id').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            searchAttendee();
        }
    });
</script>
@endpush
// Approve/Reject for Expenses and Logistics (Overview tab)
function approveExpenseLog(apiId, type, dateKey, idx, status) {
    // type: 'expenses' or 'logistics'
    fetch(`/api/field-activities/${apiId}/review-${type}`, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ date: dateKey, idx, status })
    })
    .then(res => res.json())
    .then(data => {
        if (data && data.success) {
            showToast('✓ Entry ' + status);
            // Reload the activity profile for immediate feedback
            if (typeof openActivityDetail === 'function') {
                openActivityDetail(currentActivity);
            } else if (typeof renderLogsheet === 'function') {
                renderLogsheet();
            }
        } else {
            showToast('Failed to update');
        }
    })
    .catch(() => showToast('Failed to update'));
}
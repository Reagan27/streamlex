<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var issuesData = @json($issuesData);
    
    // Calculate totals for each status
    var totals = {
        Pending: 0,
        Open: 0,
        Closed: 0
    };
    
    Object.values(issuesData).forEach(dateData => {
        Object.values(dateData).forEach(categoryData => {
            Object.entries(categoryData).forEach(([status, count]) => {
                totals[status] += count;
            });
        });
    });
    
    var ctx = document.getElementById('supportIssuesPieChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Open', 'Closed'],
            datasets: [{
                data: [totals.Pending, totals.Open, totals.Closed],
                backgroundColor: [
                    'rgba(146, 201, 154 0.8)',  // Pending
                    'rgba(23, 153, 112, 0.8)', // Open
                    'rgba(86, 204, 242, 0.8)'  // Closed
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Total Support Issues by Status'
                },
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.raw || 0;
                            var total = context.dataset.data.reduce((a, b) => a + b, 0);
                            var percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
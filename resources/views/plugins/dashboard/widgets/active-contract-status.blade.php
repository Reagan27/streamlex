<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <div style="height: 350px;">
                    <canvas id="contractStatusPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var contractData = @json($contractData);
    var statuses = @json($statuses);
    
    var colors = {
        'draft': 'rgba(230, 230, 250, 0.7)',    //lavendar
        'approved': 'rgba(75, 192, 192, 0.7)',  // Teal
        'accepted': 'rgba(54, 162, 235, 0.7)'   // Blue
    };

    // Prepare data for pie chart
    var data = statuses.map(status => contractData[status] || 0);
    var statusLabels = statuses.map(status => status.charAt(0).toUpperCase() + status.slice(1));

    // Pie Chart
    var ctxPie = document.getElementById('contractStatusPieChart').getContext('2d');
    var pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                data: data,
                backgroundColor: statuses.map(status => colors[status])
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Active Contract Distribution'
                },
                legend: {
                    display: true,
                    position: 'bottom'
                },
                tooltip: {
                    enabled: true
                },
                datalabels: {
                    color: '#000',
                    anchor: 'end',
                    align: 'start',
                    offset: 10,
                    font: {
                        size: 14,
                        weight: 'bold'
                    },
                    formatter: (value, ctx) => {
                        let sum = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        let percentage = sum > 0 ? (value * 100 / sum).toFixed(1) + "%" : "0%";
                        return `${value}\n${percentage}`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});
</script>
@endpush
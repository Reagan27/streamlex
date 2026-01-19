<div class="card">
    <h6 class="card-header">
        @lang('Support Issues')
    </h6>

    <div class="card-body">
    <div class="row">
        <div class="col-md-7 col-12 mb-3">
            <div style="height: 400px;">
                <canvas id="supportIssuesChart"></canvas>
            </div>
        </div>
        <div class="col-md-4 col-12 d-flex justify-content-center">
            <div style="height: 350px;">
                <canvas id="supportIssuesPieChart"></canvas>
            </div>
        </div>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var issuesData = @json($issuesData);
    var categories = @json($categories);
    console.log('Issues Data:', issuesData);
    var statuses = ['Pending', 'Open', 'Closed'];
    var colors = {
        'Pending': 'rgba(255, 206, 86, 0.7)',
        'Open': 'rgba(75, 192, 192, 0.7)',
        'Closed': 'rgba(153, 102, 255, 0.7)'
    };

    // Prepare data for bar chart
    var datasets = statuses.map(status => ({
        label: status,
        data: categories.map(category => issuesData[category][status]),
        backgroundColor: colors[status]
    }));

    // Bar Chart
    var ctxBar = document.getElementById('supportIssuesChart').getContext('2d');
    var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: categories,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    barPercentage: 0.2,
                    categoryPercentage: 0.7
                },
                y: {
                    stacked: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Support Issues by Category and Status'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });

    // Calculate totals for Pie Chart
    var totals = {
        Pending: 0,
        Open: 0,
        Closed: 0
    };
    
    Object.values(issuesData).forEach(categoryData => {
        Object.entries(categoryData).forEach(([status, count]) => {
            totals[status] += count;
        });
    });

    // Pie Chart
    var ctxPie = document.getElementById('supportIssuesPieChart').getContext('2d');
    var pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Open', 'Closed'],
            datasets: [{
                data: [totals.Pending, totals.Open, totals.Closed],
                backgroundColor: Object.values(colors)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Total Support Issues by Status'
                },
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
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
                        let percentage = (value * 100 / sum).toFixed(1) + "%";
                        return `${value}\n${percentage}`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});
</script>
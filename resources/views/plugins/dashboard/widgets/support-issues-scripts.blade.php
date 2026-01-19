<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
var issuesData = @json($issuesData);
console.log('Issues Data:', issuesData);
var dates = Object.keys(issuesData);
var categories = ['system', 'payment', 'incidence'];
var statuses = ['Pending', 'Open', 'Closed'];
var colors = {
    'Pending': 'rgba(48, 53, 62, 0.7)',
    'Open': 'rgba(23, 153, 112, 0.7)',
    'Closed': 'rgba(86, 204, 242, 0.8)'
};

var datasets = categories.flatMap(category =>
    statuses.map(status => {
        var data = dates.map(date => issuesData[date][category]?.[status] || 0);
        console.log(`${category} - ${status}:`, data);
        return {
            label: `${category.charAt(0).toUpperCase() + category.slice(1)} - ${status}`,
            data: data,
            backgroundColor: colors[status],
            stack: category
        };
    })
);

console.log('Datasets:', datasets);

document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('supportIssuesChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true,
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
                    position: 'bottom',
                    labels: {
                        generateLabels: function(chart) {
                            return statuses.map(status => ({
                                text: status,
                                fillStyle: colors[status],
                                hidden: false
                            }));
                        }
                    }
                },
                datalabels: {
                    display: function(context) {
                        // Only show label for the first dataset in each stack
                        return context.datasetIndex % statuses.length === 0;
                    },
                    align: 'center',
                    anchor: 'center',
                    color: '#000', // Label text color
                    rotation: -90, // Rotate labels vertically
                    formatter: function(value, context) {
                        return context.dataset.stack; // Show category name
                    },
                    font: {
                        size: 10
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});
</script>
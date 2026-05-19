<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Stats Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; }
        .header { text-align: center; margin-bottom: 40px; }
        .charts { display: flex; flex-wrap: wrap; gap: 20px; }
        .chart-box { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); flex: 1; min-width: 300px; }
        h2 { margin-top: 0; text-align: center; font-size: 1.2rem; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Visitor Statistics</h1>
        </div>
        <div class="charts">
            <div class="chart-box" style="flex: 2;">
                <h2>Visits (Last 24 Hours)</h2>
                <canvas id="hourlyChart"></canvas>
            </div>
            <div class="chart-box" style="flex: 1;">
                <h2>Visits by City</h2>
                <canvas id="cityChart"></canvas>
            </div>
        </div>
    </div>
    <script>
        fetch('/api/stats')
            .then(res => res.json())
            .then(data => {
                // Hourly Chart
                new Chart(document.getElementById('hourlyChart'), {
                    type: 'line',
                    data: {
                        labels: data.hourly.labels,
                        datasets: [{
                            label: 'Visits',
                            data: data.hourly.data,
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.2)',
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                });

                // City Chart
                new Chart(document.getElementById('cityChart'), {
                    type: 'pie',
                    data: {
                        labels: data.cities.labels,
                        datasets: [{
                            data: data.cities.data,
                            backgroundColor: ['#e74c3c', '#2ecc71', '#f1c40f', '#9b59b6', '#34495e', '#1abc9c']
                        }]
                    }
                });
            });
    </script>
</body>
</html>

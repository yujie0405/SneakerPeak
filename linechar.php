<?php
// Perform your SQL query and fetch the results into an array
include('database/config.php');

// Check if the user has submitted a date range
if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    // Get the start and end dates from the user input
    $start_date = date('d-m-Y', strtotime($_POST['start_date']));
    $end_date = date('d-m-Y', strtotime($_POST['end_date']));

    // Prepare the SQL query with the user-provided date range
    $queryt = "SELECT Order_Date AS order_date, SUM(Order_TotalAmmount) AS total_sales FROM oder WHERE Order_Date BETWEEN '$start_date' AND '$end_date' GROUP BY Order_Date";
} else {
    // If no date range is provided, display data for the current week
    $currentDate = date('d-m-Y');
    $weekStart = date('d-m-Y', strtotime('monday this week', strtotime($currentDate)));
    $weekEnd = date('d-m-Y', strtotime('sunday this week', strtotime($currentDate)));
    
    // Prepare the SQL query for the current week
    $queryt = "SELECT Order_Date AS order_date, SUM(Order_TotalAmmount) AS total_sales FROM oder WHERE Order_Date BETWEEN '$weekStart' AND '$weekEnd' GROUP BY Order_Date";
}

// Assuming you have a valid database connection, execute the query using mysqli
$resultt = mysqli_query($conn, $queryt);
$resultst = [];
while ($row = mysqli_fetch_assoc($resultt)) {
    $resultst[] = $row;
}

// Prepare the data for the chart
$labels = [];
$data = [];
foreach ($resultst as $roii) {
    $labels[] = date('d-m-Y', strtotime($roii['order_date']));
    $data[] = $roii['total_sales'];
}

// Generate the line chart using Chart.js
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <form method="POST" action="">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date">
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date">
        <button type="submit">Submit</button>
    </form>

    <canvas id="lineChart"></canvas>

    <script>
        var ctx = document.getElementById('lineChart').getContext('2d');
        var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Sales',
                    data: <?php echo json_encode($data); ?>,
                    borderColor: 'red',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Order Date'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Total Sales'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

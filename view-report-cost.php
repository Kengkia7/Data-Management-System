<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

$connect = mysqli_connect("localhost", "root", "", "durian"); // Replace with your actual database credentials

// Check if the connection is successful
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch yearly cost data
$yearlyCostQuery = "SELECT YEAR(date) AS year, COALESCE(SUM(total), 0) AS total_cost FROM cost GROUP BY YEAR(date) ORDER BY YEAR(date)";
$yearlyCostResult = mysqli_query($connect, $yearlyCostQuery);

// Fetch cost type data
$costTypeQuery = "SELECT DISTINCT type FROM cost ORDER BY type";
$costTypeResult = mysqli_query($connect, $costTypeQuery);

?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left: 300px; margin-top: 43px;">

    <!-- Header -->
    <header class="w3-container" style="padding-top: 22px">
        <h5><b><i class="fa fa-dashboard"></i> Cost Management</b></h5>
    </header>

    <!-- Cost Report -->
    <div class="w3-container" style="padding-top: 22px">
        <div class="w3-row">
            <h2>Cost Report</h2>
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="table">
                    <tr>
                        <th>Year</th>
                        <?php
                        $costTypes = array(); // Array to store the cost types
                        while ($costTypeRow = mysqli_fetch_assoc($costTypeResult)) {
                            $costType = $costTypeRow['type'];
                            echo "<th>$costType</th>";
                            $costTypes[] = $costType; // Store the cost type in the array
                        }
                        ?>
                        <th>Total Cost (RM)</th>
                    </tr>
                    <?php
                    $grandTotal = 0;
                    $columnTotals = array_fill(0, count($costTypes), 0); // Array to store column totals
                    while ($yearlyRow = mysqli_fetch_assoc($yearlyCostResult)) {
                        $year = $yearlyRow['year'];
                        $yearlyTotalCost = $yearlyRow['total_cost'];
                        echo "<tr>";
                        echo "<td>$year</td>";
                        mysqli_data_seek($costTypeResult, 0); // Reset the pointer of cost type result
                        $totalCost = 0;
                        $columnIndex = 0; // Track the index of the column
                        while ($costTypeRow = mysqli_fetch_assoc($costTypeResult)) {
                            $costType = $costTypeRow['type'];

                            $costQuery = "SELECT COALESCE(SUM(total), 0) AS total_cost FROM cost WHERE YEAR(date) = '$year' AND type = '$costType'";
                            $costResult = mysqli_query($connect, $costQuery);
                            $costRow = mysqli_fetch_assoc($costResult);
                            $costTotal = $costRow['total_cost'];
                            $totalCost += $costTotal;

                            echo "<td>";
                            echo $costTotal;
                            echo "</td>";

                            // Add the cost to the column total
                            $columnTotals[$columnIndex] += $costTotal;
                            $columnIndex++;
                        }
                        echo "<td>";
                        echo $totalCost;
                        echo "</td>";
                        echo "</tr>";

                        $grandTotal += $totalCost;
                    }
                    ?>
                    <tr>
                        <td><strong>Total</strong></td>
                        <?php
                        foreach ($columnTotals as $columnTotal) {
                            echo "<td><strong>$columnTotal</strong></td>";
                        }
                        ?>
                        <td><strong><?php echo $grandTotal; ?></strong></td>
                    </tr>
                </table>
            </div><br>
            <div class="report-actions">
                <a href="download-report-cost.php" class="btn btn-primary"><i class="fa fa-download"></i> Download Report</a>
                <a href="#" class="btn btn-secondary" onclick="printReport()"><i class="fa fa-print"></i> Print Report</a>
            </div>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>

<?php
mysqli_close($connect);
?>

<script>
    function printReport() {
        window.print();
    }
</script>

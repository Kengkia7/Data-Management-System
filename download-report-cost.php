<?php
include 'setting/system.php';

$connect = mysqli_connect("localhost", "root", "", "durian");

// Fetch yearly cost data
$yearlyCostQuery = "SELECT YEAR(date) AS year, COALESCE(SUM(total), 0) AS total_cost FROM cost GROUP BY YEAR(date) ORDER BY YEAR(date)";
$yearlyCostResult = mysqli_query($connect, $yearlyCostQuery);

// Fetch cost type data
$costTypeQuery = "SELECT DISTINCT type FROM cost ORDER BY type";
$costTypeResult = mysqli_query($connect, $costTypeQuery);

// Create CSV content
$csvData = "Year,";
$costTypes = [];
while ($costTypeRow = mysqli_fetch_assoc($costTypeResult)) {
    $costType = $costTypeRow['type'];
    $csvData .= "$costType,";
    $costTypes[] = $costType;
}
$csvData .= "Total Cost\n";

while ($yearlyRow = mysqli_fetch_assoc($yearlyCostResult)) {
    $year = $yearlyRow['year'];
    $yearlyTotalCost = $yearlyRow['total_cost'];

    $csvData .= "$year,";
    foreach ($costTypes as $costType) {
        $costQuery = "SELECT COALESCE(SUM(total), 0) AS total_cost FROM cost WHERE YEAR(date) = '$year' AND type = '$costType'";
        $costResult = mysqli_query($connect, $costQuery);
        $costRow = mysqli_fetch_assoc($costResult);
        $costTotal = $costRow['total_cost'];
        $csvData .= "$costTotal,";
    }
    $csvData .= "$yearlyTotalCost\n";
}

// Set headers for file download
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=cost_report.csv");

// Output CSV data
echo $csvData;
exit();
?>

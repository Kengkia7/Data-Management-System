<?php
include 'setting/system.php';

// Fetch production data
$productionQuery = "SELECT YEAR(date) as year, type, grade, SUM(qty_in) as total_in, SUM(qty_out) as total_out, SUM(defect) as total_defect, SUM(balance) as total_balance FROM durian GROUP BY YEAR(date), type, grade";
$production = $db->query($productionQuery);
$fetchProduction = $production->fetchAll(PDO::FETCH_OBJ);

// Create CSV content
$csvData = "Year,Type of Durian,Grade,Total Durian In (kg),Total Durian Out (kg),Total Defect (kg),Total Durian Balance (kg),Durian Out (%),Defect (%),Unsold (%)\n";

foreach ($fetchProduction as $data) {
    $durianOutPercentage = ($data->total_out / $data->total_in) * 100;
    $defectPercentage = ($data->total_defect / $data->total_in) * 100;
    $unsoldPercentage = 100 - $defectPercentage - $durianOutPercentage;

    $csvData .= "{$data->year},{$data->type},{$data->grade},{$data->total_in},{$data->total_out},{$data->total_defect},{$data->total_balance},{$durianOutPercentage},{$defectPercentage},{$unsoldPercentage}\n";
}

// Set headers for file download
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=production_report.csv");

// Output CSV data
echo $csvData;
exit();
?>

<?php
include 'setting/system.php';

// Retrieve overall sales data
$all_sales = $db->query("SELECT YEAR(date) AS year, type, 
    SUM(CASE WHEN grade = 'A' THEN total ELSE 0 END) AS total_grade_a,
    SUM(CASE WHEN grade = 'B' THEN total ELSE 0 END) AS total_grade_b,
    SUM(CASE WHEN grade = 'C' THEN total ELSE 0 END) AS total_grade_c
    FROM revenue
    GROUP BY YEAR(date), type
    ORDER BY YEAR(date) ASC, type ASC");
$sales_data = $all_sales->fetchAll(PDO::FETCH_OBJ);

// Calculate the total sales
$total_sales = 0;
foreach ($sales_data as $data) {
    $total_sales += $data->total_grade_a + $data->total_grade_b + $data->total_grade_c;
}

// Create and open a new CSV file
$file = fopen('sales_report.csv', 'w');

// Write the header row
$header = array('Year', 'Type of Durian', 'Grade A', 'Grade B', 'Grade C', 'Total Sales');
fputcsv($file, $header);

// Write the data rows
foreach ($sales_data as $data) {
    $row = array($data->year, $data->type, $data->total_grade_a, $data->total_grade_b, $data->total_grade_c, $data->total_grade_a + $data->total_grade_b + $data->total_grade_c);
    fputcsv($file, $row);
}

// Write the total row
$total_row = array('', '', '', '', '', $total_sales);
fputcsv($file, $total_row);

// Close the file
fclose($file);

// Set the appropriate headers for the download
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="sales_report.csv"');

// Send the file to the browser for download
readfile('sales_report.csv');
exit;
?>

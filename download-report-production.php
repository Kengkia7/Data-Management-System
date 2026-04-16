<?php
include 'setting/system.php';

// Retrieve overall revenue data
$all_prod = $db->query("SELECT * FROM durian ORDER BY id DESC");
$prod_data = $all_prod->fetchAll(PDO::FETCH_OBJ);

// Generate the report content
$reportContent = "No., Date, Type of Durian, Grade, Durian In (Kg), Durian Out (Kg), Defect (Kg),Durian Balance (Kg)\n";

$number = 1;
foreach ($prod_data as $data) {
    $reportContent .= "$number, $data->date, $data->type, $data->grade, $data->qty_in, $data->qty_out, $data->defect,$data->balance\n";
    $number++;
}

// Set the filename for the downloaded file
$filename = "Production_report.csv";

// Set the appropriate headers for file download
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");

// Output the report content to the browser
echo $reportContent;
?>

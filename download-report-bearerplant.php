<?php
include 'setting/system.php';
include 'session.php';

// Retrieve overall revenue data
$all_bp = $db->query("SELECT * FROM bearerplant ORDER BY id DESC");
$bp_data = $all_bp->fetchAll(PDO::FETCH_OBJ);

// Group the data by durian type and condition
$grouped_data = [];
$condition_counts = [];
$type_counts = [];
$total_counts = [];

// Initialize condition counts for each type of durian
$conditions = ['Good', 'Poor', 'Bad'];
foreach ($bp_data as $data) {
    $type = $data->type;

    if (!isset($grouped_data[$type])) {
        $grouped_data[$type] = [];
        $type_counts[$type] = 0;
        $total_counts[$type] = 0;

        // Initialize condition counts for the type
        foreach ($conditions as $condition) {
            $grouped_data[$type][$condition] = 0;
            $condition_counts[$condition] = 0;
        }
    }

    $condition = $data->conditionbp;
    $grouped_data[$type][$condition]++;
    $condition_counts[$condition]++;
    $type_counts[$type]++;
    $total_counts[$type]++;
}

// Determine unique conditions
$unique_conditions = array_keys($condition_counts);

// Calculate the sum of total count for each condition
$total_sums = [];
foreach ($unique_conditions as $condition) {
    $total_sums[$condition] = array_sum(array_column($grouped_data, $condition));
}

// Calculate the sum of total count for all conditions
$total_sum = array_sum($total_counts);

// Group the data by durian type and age
$grouped_data_age = [];
$age_counts = [];
foreach ($bp_data as $data) {
    $type = $data->type;
    $age = $data->age;

    if (!isset($grouped_data_age[$type])) {
        $grouped_data_age[$type] = [];
        $age_counts[$type] = [];
    }

    if (!isset($grouped_data_age[$type][$age])) {
        $grouped_data_age[$type][$age] = 0;
        $age_counts[$type][$age] = 0;
    }

    $grouped_data_age[$type][$age]++;
    $age_counts[$type][$age]++;
}

// Determine unique ages
$unique_ages = [];
foreach ($grouped_data_age as $type => $ages) {
    $unique_ages[$type] = array_keys($ages);
}

// Generate the CSV content for Durian Type and Condition Report
$csv_content = "Durian Type,";
foreach ($unique_conditions as $condition) {
    $csv_content .= "$condition (Count),";
}
$csv_content .= "Total (Count)\n";

foreach ($grouped_data as $type => $conditions) {
    $csv_content .= "$type,";
    foreach ($unique_conditions as $condition) {
        $count = isset($conditions[$condition]) ? $conditions[$condition] : 0;
        $percentage = ($type_counts[$type] > 0) ? round(($count / $type_counts[$type]) * 100, 2) : 0;
        $csv_content .= "$percentage% ($count),";
    }
    $csv_content .= $total_counts[$type] . "\n";
}

$csv_content .= "Total,";
foreach ($unique_conditions as $condition) {
    $csv_content .= $total_sums[$condition] . ",";
}
$csv_content .= $total_sum . "\n\n";

// Generate the CSV content for Durian Type and Age Report
$csv_content .= "Durian Type,Age,Count\n";
foreach ($grouped_data_age as $type => $ages) {
    foreach ($ages as $age => $count) {
        $csv_content .= "$type,$age,$count\n";
    }
}

// Calculate the total count in Durian Type and Age Report
$total_count_age_report = 0;
foreach ($grouped_data_age as $ages) {
    foreach ($ages as $count) {
        $total_count_age_report += $count;
    }
}

// Append the total count to the CSV content
$csv_content .= ",Total Count,$total_count_age_report\n";

// Generate a unique filename for the CSV report
$filename = 'durian_trees_report_' . date('YmdHis') . '.csv';

// Set the appropriate headers for downloading the file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Output the CSV content for download
echo $csv_content;
exit;
?>

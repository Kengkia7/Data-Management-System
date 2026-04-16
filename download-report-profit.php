<?php
include 'setting/system.php';
include 'session.php';

// Get the filter dates from the GET request
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Prepare the SQL query with the date filter for revenue
$revenueQuery = $db->prepare("SELECT type, SUM(total) AS total_revenue FROM revenue WHERE date BETWEEN :start_date AND :end_date GROUP BY type");
$revenueQuery->bindParam(':start_date', $start_date);
$revenueQuery->bindParam(':end_date', $end_date);
$revenueQuery->execute();
$revenueData = $revenueQuery->fetchAll(PDO::FETCH_ASSOC);

// Calculate the total revenue
$totalRevenue = 0;
foreach ($revenueData as $revenue) {
    $totalRevenue += $revenue['total_revenue'];
}

// Prepare the SQL query with the date filter for expenses
$expensesQuery = $db->prepare("SELECT type, SUM(total) AS total_expenses FROM cost WHERE date BETWEEN :start_date AND :end_date GROUP BY type");
$expensesQuery->bindParam(':start_date', $start_date);
$expensesQuery->bindParam(':end_date', $end_date);
$expensesQuery->execute();
$expensesData = $expensesQuery->fetchAll(PDO::FETCH_ASSOC);

// Calculate the total expenses
$totalExpenses = 0;
foreach ($expensesData as $expenses) {
    $totalExpenses += $expenses['total_expenses'];
}

// Calculate the gross profit
$grossProfit = $totalRevenue - $totalExpenses;

// Generate the CSV file content
$csvData = "Gross Profit Report\n";
$csvData .= "Period: " . date('d-m-Y', strtotime($start_date)) . " to " . date('d-m-Y', strtotime($end_date)) . "\n\n";

$csvData .= "Revenue\n";
$csvData .= "Durian Type, Sales (RM), Percentage\n";
foreach ($revenueData as $revenue) {
    $csvData .= $revenue['type'] . ", " . number_format($revenue['total_revenue'], 2) . ", ";
    $csvData .= ($totalRevenue > 0) ? number_format(($revenue['total_revenue'] / $totalRevenue) * 100, 2) . "%" : "N/A";
    $csvData .= "\n";
}
$csvData .= "Total Sales, " . number_format($totalRevenue, 2) . ", 100%\n\n";

$csvData .= "Expenses\n";
$csvData .= "Expense Type, Expenses (RM), Percentage\n";
foreach ($expensesData as $expenses) {
    $csvData .= $expenses['type'] . ", " . number_format($expenses['total_expenses'], 2) . ", ";
    $csvData .= ($totalRevenue > 0) ? number_format(($expenses['total_expenses'] / $totalRevenue) * 100, 2) . "%" : "N/A";
    $csvData .= "\n";
}
$csvData .= "Total Expenses, " . number_format($totalExpenses, 2) . ", ";
$csvData .= ($totalRevenue > 0) ? number_format(($totalExpenses / $totalRevenue) * 100, 2) . "%" : "N/A";
$csvData .= "\n\n";

$csvData .= "Gross Profit, " . number_format($grossProfit, 2) . ", ";
$csvData .= ($totalRevenue > 0) ? number_format(($grossProfit / $totalRevenue) * 100, 2) . "%" : "N/A";
$csvData .= "\n";

// Set the content type and headers for CSV file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="gross_profit_report.csv"');

// Output the CSV data
echo $csvData;
exit;
?>

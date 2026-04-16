<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
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
if ($totalRevenue != 0) {
    $grossProfit = $totalRevenue - $totalExpenses;
} else {
    $grossProfit = 0;
}
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <!-- Header -->
	<div class="w3-container" style="padding-top: 22px;">
	  <h3><i class="fa fa-dashboard"></i> Date Filter</h3>
	  <div class="filter-form">
		<form action="" method="GET">
		  <div class="col-md-4">
			<label for="start_date">Start Date:</label>
			<input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
		  </div>
		  <div class="col-md-4">
			<label for="end_date">End Date:</label>
			<input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
		  </div>
		  <div class="col-md-12" style="margin-top: 1em;">
			<input type="submit" value="Apply" class="btn btn-primary">
		  </div>
		</form>
	  </div>
	</div>

    <div class="w3-container" style="padding-top:22px">
        <div class="w3-row">
            <div class="report-section">
                <h2 class="text-center"><b>Gross Profit Report with the Period: <?php echo date('d-m-Y', strtotime($start_date)); ?> - <?php echo date('d-m-Y', strtotime($end_date)); ?></b></h2>
            </div>
        </div>

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2>Sales</h2>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<tr>
                                    <th>Durian Type</th>
                                    <th>Sales (RM)</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($revenueData as $revenue) : ?>
                                    <tr>
                                        <td class="item"><?php echo $revenue['type']; ?></td>
                                        <td class="amount"><?php echo number_format($revenue['total_revenue'], 2); ?></td>
                                        <td class="percentage"><?php echo ($totalRevenue != 0) ? number_format(($revenue['total_revenue'] / $totalRevenue) * 100, 2) . '%' : '0%'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td><strong>Total Sales</strong></td>
                                    <td class="total"><?php echo number_format($totalRevenue, 2); ?></td>
                                    <td class="percentage">100%</td>
                                </tr>
                            </tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2>Expenses</h2>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<tr>
                                    <th>Expense Type</th>
                                    <th>Expenses (RM)</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expensesData as $expenses) : ?>
                                    <tr>
                                        <td class="item"><?php echo $expenses['type']; ?></td>
                                        <td class="amount"><?php echo number_format($expenses['total_expenses'], 2); ?></td>
                                        <td class="percentage"><?php echo ($totalRevenue != 0) ? number_format(($expenses['total_expenses'] / $totalRevenue) * 100, 2) . '%' : '0%'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td><strong>Total Expenses</strong></td>
                                    <td class="total"><?php echo number_format($totalExpenses, 2); ?></td>
                                    <td class="percentage"><?php echo ($totalRevenue != 0) ? number_format(($totalExpenses / $totalRevenue) * 100, 2) . '%' : '0%'; ?></td>
                                </tr>
                            </tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
        </div>

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2>Gross Profit</h2>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<tr>
                                    <th></th>
                                    <th>Amount (RM)</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Sales</td>
                                    <td class="amount"><?php echo number_format($totalRevenue, 2); ?></td>
                                    <td class="percentage"><?php echo ($totalRevenue != 0) ? number_format(($totalRevenue / $totalRevenue) * 100, 2) . '%' : '0%'; ?></td>
                                </tr>
                                <tr>
                                    <td>Total Expenses</td>
                                    
                                <td class="amount"><?php echo number_format($totalExpenses, 2); ?></td>
                                <td class="percentage"><?php echo ($totalRevenue != 0) ? number_format(($totalExpenses / $totalRevenue) * 100, 2) . '%' : '0%'; ?></td>
                            </tr>
                            <tr>
                                <td>Gross Profit</td>
                                <td class="amount"><?php echo number_format($grossProfit, 2); ?></td>
                                <td class="percentage"><?php echo ($totalRevenue != 0) ? number_format(($grossProfit / $totalRevenue) * 100, 2) . '%' : '0%'; ?></td>
                            </tr>
                            </tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w3-row">
            <div class="report-actions">
                <a href="download-report-profit.php?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="btn btn-primary"><i class="fa fa-download"></i> Download Report</a>
                <a href="#" class="btn btn-secondary" onclick="printReport()"><i class="fa fa-print"></i> Print Report</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS styles */
</style>

<script>
    function printReport() {
        window.print();
    }
</script>

<!-- Rest of the code -->

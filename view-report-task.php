<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Get the total number of tasks
$totalFertilizerTasks = $db->query("SELECT COUNT(*) AS total FROM task WHERE fertilizer = 1")->fetchColumn();
$totalPesticideTasks = $db->query("SELECT COUNT(*) AS total FROM task WHERE pesticide = 1")->fetchColumn();
$totalTasks = $totalFertilizerTasks + $totalPesticideTasks;

// !PAGE CONTENT!

?>

<div class="w3-main" style="margin-left: 300px; margin-top: 43px;">
    <!-- Header -->
    <header class="w3-container" style="padding-top: 22px">
        <h5><b><i class="fa fa-dashboard"></i> Task Management</b></h5>
    </header>

    <div class="w3-container" style="padding-top: 22px">
        <div class="w3-row">
            <h2>Task Report</h2>
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="table">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Frequency of Fertilizer</th>
                            <th>Frequency of Pesticide</th>
                            <th>Average Interval of Fertilizer</th>
                            <th>Average Interval of Pesticide</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						$taskSummary = [];
						$all_tasks = $db->query("SELECT * FROM task ORDER BY id DESC");
						$fetch = $all_tasks->fetchAll(PDO::FETCH_OBJ);

						foreach ($fetch as $index => $data) {
							$taskNo = $totalTasks - $index;
							$year = date('Y', strtotime($data->date));

							if (!isset($taskSummary[$year])) {
								$taskSummary[$year] = [
									'totalTasks' => 0,
									'fertilizerCount' => 0,
									'pesticideCount' => 0,
									'fertilizerIntervals' => [],
									'pesticideIntervals' => []
								];
							}

							$taskSummary[$year]['totalTasks']++;

							if ($data->fertilizer == 1) {
								$taskSummary[$year]['fertilizerCount']++;
								$taskSummary[$year]['fertilizerIntervals'][] = $data->date;
							}

							if ($data->pesticide == 1) {
								$taskSummary[$year]['pesticideCount']++;
								$taskSummary[$year]['pesticideIntervals'][] = $data->date;
							}
						}

						// Calculate average intervals and store them in the taskSummary array
						foreach ($taskSummary as $year => $summary) {
							$fertilizerCount = $summary['fertilizerCount'];
							$pesticideCount = $summary['pesticideCount'];
							$fertilizerIntervals = $summary['fertilizerIntervals'];
							$pesticideIntervals = $summary['pesticideIntervals'];

							if ($fertilizerCount > 1) {
								$fertilizerInterval = calculateAverageInterval($fertilizerIntervals);
								$taskSummary[$year]['fertilizerInterval'] = $fertilizerInterval;
							}

							if ($pesticideCount > 1) {
								$pesticideInterval = calculateAverageInterval($pesticideIntervals);
								$taskSummary[$year]['pesticideInterval'] = $pesticideInterval;
							}
						}
						?>
                        <?php foreach ($taskSummary as $year => $summary) { ?>
                            <tr>
                                <td><?php echo $year; ?></td>
                                <td><?php echo $summary['fertilizerCount']; ?></td>
                                <td><?php echo $summary['pesticideCount']; ?></td>
                                <td><?php echo calculateAverageInterval($summary['fertilizerIntervals']); ?> days</td>
                                <td><?php echo calculateAverageInterval($summary['pesticideIntervals']); ?> days</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
			<div class="report-actions">
				<a href="download-report-task.php" class="btn btn-primary"><i class="fa fa-download"></i> Download Report</a>
				<a href="#" class="btn btn-secondary" onclick="printReport()"><i class="fa fa-print"></i> Print Report</a>
			</div>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>

<?php
// Function to calculate the average interval
function calculateAverageInterval($dates)
{
    $intervals = [];
    for ($i = 1; $i < count($dates); $i++) {
        $interval = strtotime($dates[$i - 1]) - strtotime($dates[$i]);
        $intervals[] = abs($interval) / (60 * 60 * 24); // Convert interval to days
    }

    if (count($intervals) > 0) {
        $total = array_sum($intervals);
        return round($total / count($intervals), 2);
    } else {
        return 0;
    }
}
?>

<script>
    function printReport() {
        window.print();
    }
</script>

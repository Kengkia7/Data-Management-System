<?php
include 'setting/system.php';

$connect = mysqli_connect("localhost", "root", "", "durian");

// Get the total number of tasks
$totalTasks = $db->query("SELECT COUNT(*) AS total FROM task")->fetchColumn();

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

// Create CSV content
$csvData = "Year,Frequency of Fertilizer,Frequency of Pesticide,Average Interval of Fertilizer (days),Average Interval of Pesticide (days)\n";

foreach ($taskSummary as $year => $summary) {
    $fertilizerCount = $summary['fertilizerCount'];
    $pesticideCount = $summary['pesticideCount'];
    $fertilizerInterval = $summary['fertilizerInterval'] ?? '';
    $pesticideInterval = $summary['pesticideInterval'] ?? '';

    $csvData .= "$year,$fertilizerCount,$pesticideCount,$fertilizerInterval,$pesticideInterval\n";
}

// Set headers for file download
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=task_report.csv");

// Output CSV data
echo $csvData;

mysqli_close($connect);

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

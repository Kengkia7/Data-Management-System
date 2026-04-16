<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
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
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-report"></i></b></h5>
    </header>

    <div class="w3-container" style="padding-top:22px">
        <div class="w3-row">
            <h2>Durian Trees Report</h2>

            <!-- Durian Type and Condition Report -->
            <div class="report-section">
                <h3>Durian Type and Condition Report</h3>
                <div class="report-table">
                    <table class="table table-hover table-striped" id="table">
                        <thead>
                            <tr>
                                <th>Type of Durian</th>
                                <?php foreach ($unique_conditions as $condition) : ?>
                                    <th><?php echo $condition; ?> (Count)</th>
                                <?php endforeach; ?>
                                <th>Total (Count)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grouped_data as $type => $conditions) : ?>
                                <tr>
                                    <td><?php echo $type; ?></td>
                                    <?php foreach ($unique_conditions as $condition) : ?>
                                        <td>
                                            <?php
                                            $count = isset($conditions[$condition]) ? $conditions[$condition] : 0;
                                            $percentage = ($type_counts[$type] > 0) ? round(($count / $type_counts[$type]) * 100, 2) : 0;
                                            echo $percentage . "% (" . $count . ")";
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td><?php echo $total_counts[$type]; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($grouped_data)) : ?>
                                <tr>
                                    <td colspan="<?php echo count($unique_conditions) + 2; ?>">No data available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style="font-weight: bold;">Total</td>
                                <?php foreach ($unique_conditions as $condition) : ?>
                                    <td style="font-weight: bold;"><?php echo $total_sums[$condition]; ?></td>
                                <?php endforeach; ?>
                                <td style="font-weight: bold;"><?php echo $total_sum; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Durian Type and Age Report -->
            <div class="report-section">
                <h3>Durian Type and Age Report</h3>
                <div class="report-table">
                    <table class="table table-hover table-striped" id="table-age">
                        <thead>
                            <tr>
                                <th>Type of Durian</th>
                                <th>Age</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grouped_data_age as $type => $ages) : ?>
                                <?php foreach ($ages as $age => $count) : ?>
                                    <tr>
                                        <td><?php echo $type; ?></td>
                                        <td><?php echo $age; ?></td>
                                        <td><?php echo $count; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                            <?php if (empty($grouped_data_age)) : ?>
                                <tr>
                                    <td colspan="3">No data available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="font-weight: bold;">Total</td>
                                <td style="font-weight: bold;">
                                    <?php
                                    $total_count = 0;
                                    foreach ($grouped_data_age as $type => $ages) {
                                        foreach ($ages as $age => $count) {
                                            $total_count += $count;
                                        }
                                    }
                                    echo $total_count;
                                    ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="report-actions">
                <a href="download-report-bearerplant.php" class="btn btn-primary"><i class="fa fa-download"></i> Download Report</a>
                <a href="#" class="btn btn-secondary" onclick="printReport()"><i class="fa fa-print"></i> Print Report</a>
            </div>
        </div>
    </div>

    <!-- Rest of the code -->

</div>

<?php include 'theme/foot.php'; ?>

<script>
    function printReport() {
        window.print();
    }
</script>

<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Retrieve overall sales data
$all_sales = $db->query("SELECT YEAR(date) AS year, type, 
    SUM(CASE WHEN grade = 'A' THEN total ELSE 0 END) AS total_grade_a,
    SUM(CASE WHEN grade = 'B' THEN total ELSE 0 END) AS total_grade_b,
    SUM(CASE WHEN grade = 'C' THEN total ELSE 0 END) AS total_grade_c
    FROM revenue
    GROUP BY YEAR(date), type
    ORDER BY YEAR(date) ASC, type ASC");
$sales_data = $all_sales->fetchAll(PDO::FETCH_OBJ);
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <!-- Header -->
    <header class="w3-container" style="padding-top: 22px">
        <h5><b><i class="fa fa-dashboard"></i> Sales Management</b></h5>
    </header>

    <div class="w3-container" style="padding-top:22px">
        <div class="w3-row">
            <h2>Sales Report</h2>
            <div class="report-table">
                <table class="table table-hover table-striped" id="table">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Type of Durian</th>
                            <th>Grade A (RM)</th>
                            <th>Grade B (RM)</th>
                            <th>Grade C (RM)</th>
                            <th class="total-column">Total Sales (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $number = 1;
                        $current_year = null;
                        $total_sales = 0; // Initialize total sales variable
                        foreach ($sales_data as $data) {
                            if ($data->year != $current_year) {
                                echo '<tr><th colspan="6">' . $data->year . '</th></tr>';
                                $current_year = $data->year;
                            }
                            ?>
                            <tr>
                                <td><?php ?></td>
                                <td><?php echo $data->type ?></td>
                                <td><?php echo $data->total_grade_a ?></td>
                                <td><?php echo $data->total_grade_b ?></td>
                                <td><?php echo $data->total_grade_c ?></td>
                                <td class="total-column"><?php
                                    $total = $data->total_grade_a + $data->total_grade_b + $data->total_grade_c;
                                    echo $total;
                                    $total_sales += $total; // Add to the total sales
                                    ?></td>
                            </tr>
                            <?php
                            $number++;
                        }
                        ?>
                        <tr>
                            <td colspan="5" style="font-weight: bold; text-align: right;">Total Sales:</td>
                            <td class="total-column" style="font-weight: bold;"><?php echo $total_sales; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div><br>
            <div class="report-actions">
                <a href="download-report-sales.php" class="btn btn-primary"><i class="fa fa-download"></i> Download Report</a>
                <a href="#" class="btn btn-secondary" onclick="printReport()"><i class="fa fa-print"></i> Print Report</a>
            </div>
        </div>
    </div>

    <!-- Rest of the code -->

</div>

<?php include 'theme/foot.php'; ?>

<style>
    .total-column {
        font-weight: bold;
    }
</style>

<script>
    function printReport() {
        window.print();
    }
</script>

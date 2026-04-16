<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left: 300px; margin-top: 43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top: 22px">
    <h5><b><i class="fa fa-dashboard"></i> Production Management</b></h5>
  </header>

  <div class="w3-container" style="padding-top: 22px">
    <div class="w3-row">
      <h2>Production Report</h2>
      <div class="table-responsive">
        <table class="table table-hover table-striped" id="table">
          <thead>
            <tr>
              <th>Year</th>
              <th>Type of Durian</th>
              <th>Grade</th>
              <th>Total Durian In (kg)</th>
              <th>Total Durian Out (kg)</th>
              <th>Total Defect (kg)</th>
              <th>Total Durian Unsold (kg)</th>
              <th>Durian Out (%)</th>
              <th>Defect (%)</th>
              <th>Unsold (%)</th>
            </tr>
          </thead>
          <tbody>
			<?php
			$production = $db->query("SELECT YEAR(date) as year, type, grade, SUM(qty_in) as total_in, SUM(qty_out) as total_out, SUM(defect) as total_defect, SUM(balance) as total_balance FROM durian GROUP BY YEAR(date), type, grade");
			$fetchProduction = $production->fetchAll(PDO::FETCH_OBJ);
			$previousYear = null;
			$previousType = null;

			foreach ($fetchProduction as $data) {
			  $durianOutPercentage = ($data->total_out / $data->total_in) * 100;
			  $defectPercentage = ($data->total_defect / $data->total_in) * 100;

			  // Assign CSS class based on year
			  $rowClass = ($previousYear == $data->year) ? 'same-year-row' : '';

			  ?>
			  <tr class="<?php echo $rowClass; ?>">
				<?php if ($previousYear == $data->year) { ?>
				  <td></td>
				  <?php if ($previousType == $data->type) { ?>
					<td></td>
				  <?php } else { ?>
					<td><?php echo $data->type; ?></td>
				  <?php } ?>
				<?php } else { ?>
				  <td><?php echo $data->year; ?></td>
				  <td><?php echo $data->type; ?></td>
				<?php } ?>

				<td><?php echo $data->grade; ?></td>
				<td><?php echo $data->total_in; ?></td>
				<td><?php echo $data->total_out; ?></td>
				<td><?php echo $data->total_defect; ?></td>
				<td><?php echo $data->total_balance; ?></td>
				<td><?php echo round($durianOutPercentage, 2); ?>%</td>
				<td><?php echo round($defectPercentage, 2); ?>%</td>
				<td><?php echo round(100 - $defectPercentage - $durianOutPercentage, 2); ?>%</td>
			  </tr>
			  <?php
			  $previousYear = $data->year;
			  $previousType = $data->type;
			}
			?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
              <td>
                <?php
                $totalDurianIn = $db->query("SELECT SUM(qty_in) as total_in FROM durian");
                $fetchTotalDurianIn = $totalDurianIn->fetch(PDO::FETCH_OBJ);
                echo $fetchTotalDurianIn->total_in;
                ?>
              </td>
              <td>
                <?php
                $totalDurianOut = $db->query("SELECT SUM(qty_out) as total_out FROM durian");
                $fetchTotalDurianOut = $totalDurianOut->fetch(PDO::FETCH_OBJ);
                echo $fetchTotalDurianOut->total_out;
                ?>
              </td>
              <td>
                <?php
                $totalDefect = $db->query("SELECT SUM(defect) as total_defect FROM durian");
                $fetchTotalDefect = $totalDefect->fetch(PDO::FETCH_OBJ);
                echo $fetchTotalDefect->total_defect;
                ?>
              </td>
              <td>
                <?php
                $totalBalance = $db->query("SELECT SUM(balance) as total_balance FROM durian");
                $fetchTotalBalance = $totalBalance->fetch(PDO::FETCH_OBJ);
                echo $fetchTotalBalance->total_balance;
                ?>
              </td>
              <td>
                <?php
                echo round(($fetchTotalDurianOut->total_out / $fetchTotalDurianIn->total_in) * 100, 2) . "%";
                ?>
              </td>
              <td>
                <?php
                echo round(($fetchTotalDefect->total_defect / $fetchTotalDurianIn->total_in) * 100, 2) . "%";
                ?>
              </td>
              <td>
                <?php
                echo round(($fetchTotalBalance->total_balance / $fetchTotalDurianIn->total_in) * 100, 2) . "%";
                ?>
              </td>
              <td colspan="2"></td>
            </tr>
          </tfoot>
        </table>
      </div>
	  <div class="report-actions">
		<a href="download-report-durian.php" class="btn btn-primary"><i class="fa fa-download"></i> Download Report</a>
		<a href="#" class="btn btn-secondary" onclick="printReport()"><i class="fa fa-print"></i> Print Report</a>
	  </div>
    </div>
  </div>

</div>

<?php include 'theme/foot.php'; ?>

<script>
    function printReport() {
        window.print();
    }
</script>
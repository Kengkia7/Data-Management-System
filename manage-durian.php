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
      <h2>Update Production</h2>
      <a href="add-durian.php" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add New Production</a>
      <a href="view-report-durian.php" class="btn btn-sm btn-success pull-right" style="margin-right: 10px;"><i class="fa fa-file"></i> View Report</a><br><br>
      <div class="table-responsive">
        <table class="table table-hover table-striped" id="table">
          <thead>
            <tr>
              <th>Batch No.</th>
              <th>Date</th>
              <th>Type of Durian</th>
              <th>Grade</th>
              <th>Durian In (kg)</th>
              <th>Durian Out (kg)</th>
              <th>Defect (kg)</th>
              <th>Durian Unsold (kg)</th>
              <th>Options</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $all_durian = $db->query("SELECT * FROM durian ORDER BY date");
            $fetch = $all_durian->fetchAll(PDO::FETCH_OBJ);
            $counter = 1;
            foreach ($fetch as $data) {
              ?>
              <tr>
                <td><?php echo $counter ?></td>
                <td><?php echo $data->date ?></td>
                <td><?php echo $data->type ?></td>
                <td><?php echo $data->grade ?></td>
                <td><?php echo $data->qty_in ?></td>
                <td><?php echo $data->qty_out ?></td>
                <td>
				  <span class="badge bg-danger"><?php echo $data->defect ?></span>
				</td>
				<td>
				  <?php if ($data->balance == 0): ?>
					<span class="btn btn-sm btn-success">All Sold Out</span>
				  <?php else: ?>
					<span class="badge bg-danger"><?php echo $data->balance ?></span>
				  <?php endif; ?>
				</td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cog"></i> Option
                      <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li><a href="edit-durian.php?id=<?php echo $data->id ?>"><i class="fa fa-edit"></i> Edit</a></li>
                      <li><a onclick="return confirm('Continue delete production ?')" href="delete-durian.php?id=<?php echo $data->id ?>"><i class="fa fa-trash"></i> Delete</a></li>
                    </ul>
                  </div>
                </td>
              </tr>
            <?php
              $counter++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?php include 'theme/foot.php'; ?>

<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left: 300px; margin-top: 43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top: 22px">
    <h5><b><i class="fa fa-dashboard"></i> Sales Management</b></h5>
  </header>


  <div class="w3-container" style="padding-top: 22px">
    <div class="w3-row">
      <h2>Update Sales</h2>
      <a href="add-sales.php" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add New Sales</a>
      <a href="view-report-sales.php" class="btn btn-sm btn-success pull-right" style="margin-right: 10px;"><i class="fa fa-file"></i> View Report</a><br><br>

      <div class="table-responsive">
        <table class="table table-hover table-striped" id="table">
          <thead>
            <tr>
              <th>No.</th>
              <th>Date</th>
              <th>Type of Durian</th>
              <th>Grade</th>
              <th>Price per kg (RM)</th>
              <th>Quantity of durian sold (kg)</th>
              <th>Total Sales (RM)</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $all_sales = $db->query("SELECT * FROM revenue ORDER BY date");
            $fetch = $all_sales->fetchAll(PDO::FETCH_OBJ);
            $number = count($fetch);
            foreach ($fetch as $data) {
              ?>
              <tr>
                <td><?php echo $number ?></td>
                <td><?php echo $data->date ?></td>
                <td><?php echo $data->type ?></td>
                <td><?php echo $data->grade ?></td>
                <td><?php echo $data->price ?></td>
                <td><?php echo $data->qty ?></td>
                <td><?php echo $data->total ?></td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cog"></i> Option
                      <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li><a href="edit-sales.php?id=<?php echo $data->id ?>"><i class="fa fa-edit"></i> Edit</a></li>
                      <li><a onclick="return confirm('Continue delete sales ?')" href="delete-sales.php?id=<?php echo $data->id ?>"><i class="fa fa-trash"></i> Delete</a></li>
                    </ul>
                  </div>
                </td>
              </tr>
            <?php
            $number--;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?php include 'theme/foot.php'; ?>

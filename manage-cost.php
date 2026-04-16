<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left: 300px; margin-top: 43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top: 22px">
    <h5><b><i class="fa fa-dashboard"></i> Cost Management</b></h5>
  </header>


  <div class="w3-container" style="padding-top: 22px">
    <div class="w3-row">
      <h2>Update Costs</h2>
      <a href="add-cost.php" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add New Cost</a>
      <a href="view-report-cost.php" class="btn btn-sm btn-success pull-right" style="margin-right: 10px;"><i class="fa fa-file"></i> View Report</a><br><br>
      <div class="table-responsive">
        <table class="table table-hover table-striped" id="table">
          <thead>
            <tr>
              <th>No.</th>
              <th>Date</th>
              <th>Type of Cost</th>
              <th>Description</th>
              <th>Price per unit (RM)</th>
              <th>Quantity</th>
              <th>Total Cost (RM)</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $all_cost = $db->query("SELECT * FROM cost ORDER BY date");
            $fetch = $all_cost->fetchAll(PDO::FETCH_OBJ);
            $number = count($fetch);
            foreach ($fetch as $data) {
              ?>
              <tr>
                <td><?php echo $number ?></td>
                <td><?php echo $data->date ?></td>
                <td><?php echo $data->type ?></td>
                <td><?php echo $data->desc ?></td>
                <td><?php echo $data->price ?></td>
                <td><?php echo $data->qty ?></td>
                <td><?php echo $data->total ?></td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cog"></i> Option
                      <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li><a href="edit-cost.php?id=<?php echo $data->id ?>"><i class="fa fa-edit"></i> Edit</a></li>
                      <li><a onclick="return confirm('Continue delete cost ?')" href="delete-cost.php?id=<?php echo $data->id ?>"><i class="fa fa-trash"></i> Delete</a></li>
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

<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i>Durian Trees Management</b></h5>
  </header>
 


 
 <div class="w3-container" style="padding-top:22px">
 <div class="w3-row">
 	<h2>Update Durian Trees</h2>
  <a href="add-bearerplant.php" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add New Durian Trees</a>
  <a href="view-report-bearerplant.php" class="btn btn-sm btn-success pull-right" style="margin-right: 10px;"><i class="fa fa-file"></i> View Report</a><br><br>
 <div class="table-responsive">
 	<table class="table table-hover table-striped" id="table">
 		<thead>
 			<tr>
                <th>No.</th>
 				<th>Date</th>
 				<th>Code</th>
 				<th>Type</th>
 				<th>Age</th>
 				<th>Condition</th>
 				<th></th>
 			</tr>
 		</thead>
 		<tbody>
 	<?php
            $all_bp = $db->query("SELECT * FROM bearerplant ORDER BY date");
            $fetch = $all_bp->fetchAll(PDO::FETCH_OBJ);
            $number = count($fetch);
            foreach ($fetch as $data) {
              ?>
          <tr>
            <td><?php echo $number ?></td>
            <td><?php echo $data->date ?></td>
            <td><?php echo $data->code ?></td>
            <td><?php echo $data->type ?></td>
            <td><?php echo $data->age ?></td>
            <td>
              <?php
              $conditionClass = '';
              switch ($data->conditionbp) {
                case 'Good':
                  $conditionClass = 'btn-success';
                  break;
                case 'Poor':
                  $conditionClass = 'btn-warning';
                  break;
                case 'Bad':
                  $conditionClass = 'btn-danger';
                  break;
                default:
                  $conditionClass = 'btn-default';
                  break;
              }
              ?>
              <button class="btn <?php echo $conditionClass ?>"><?php echo $data->conditionbp ?></button>
            </td>
            <td>
               <div class="dropdown">
                  <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cog"></i> Option
                  <span class="caret"></span></button>
                  <ul class="dropdown-menu">
                    <li><a href="edit-bearerplant.php?id=<?php echo $data->id ?>"><i class="fa fa-edit"></i> Edit</a></li>
                    <li><a onclick="return confirm('Continue delete bearerplant ?')" href="delete-bearerplant.php?id=<?php echo $data->id ?>"><i class="fa fa-trash"></i> Delete</a></li>
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

<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php 
 if(!$_GET['id'] OR empty($_GET['id']) OR $_GET['id'] == '')
 {
 	header('location: manage-bearerplant.php');

 }else{
 	
 	$date = $code = $type = $age = $condition = "";
 	$id = (int)$_GET['id'];
 	$query = $db->query("SELECT * FROM bearerplant WHERE id = '$id' ");
 	$fetchObj = $query->fetchAll(PDO::FETCH_OBJ);

 	foreach($fetchObj as $obj){
       $date = $obj->date;
       $code = $obj->code;
	   $type = $obj->type;
	   $age = $obj->age;
	   $condition = $obj->conditionbp;
 	}
 }

?>
<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
  </header>

 
<div class="w3-container" style="padding-top:22px">
 <div class="w3-row">
  
 	<div class="col-md-12">

     <?php
      if(isset($_POST['submit']))
      {
      	$date = $_POST['date'];
	    $code = $_POST['code'];
	    $type = $_POST['type'];
	    $age = $_POST['age'];
	    $condition = $_POST['condition'];

      	$n_id = $_GET['id'];

      	$update_query = $db->query("UPDATE bearerplant SET date = '$date', code = '$code', `type` = '$type', age = '$age', conditionbp = '$condition' WHERE id = '$n_id' ");

      	if($update_query){?>
      	<div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
             <strong>Bearer plant details successfully update <i class="fa fa-check"></i></strong>
        </div>
       <?php
      	}else{ ?>
          <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
             <strong>Error updating pig data. Please try again <i class="fa fa-times"></i></strong>
        </div>
      	<?php
      }

      }

     ?>



    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Edit Bearer Plant</h3>
      </div>
      <div class="panel-body">	
	 	<form method="post">
          <div class="row">
            <div class="col-md-6">
				<div class="form-group date">
					<label class="control-label">Date</label>
					<input type="text" id="datepicker" name="date" class="form-control" value="<?php echo $date; ?>">
				</div>
			</div>                 
            <div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Age</label>
					<input type="number" name="age" id="age" class="form-control" value="<?php echo $age; ?>">
				</div>
			</div> 
		  </div>
              
          <div class="row">
            <div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Code</label>
					<input type="text" name="code" class="form-control" value="<?php echo $code; ?>">
				</div>
			</div>
            <div class="col-md-6">
              <div class="form-group">
			    <label class="control-label">Condition</label>
				  <div>
					<label class="radio-inline">
					  <input type="radio" name="condition" value="Good" <?php echo ($condition == 'Good') ? 'checked' : ''; ?> required>Good
					</label>
					<label class="radio-inline">
					  <input type="radio" name="condition" value="Poor" <?php echo ($condition == 'Poor') ? 'checked' : ''; ?> required>Poor
					</label>
					<label class="radio-inline">
					  <input type="radio" name="condition" value="Bad" <?php echo ($condition == 'Bad') ? 'checked' : ''; ?> required>Bad
					</label>
				  </div>
              </div>
            </div>
		  </div>
              
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
			    <label class="control-label">Type of Durian</label>
				  <div>
					<label class="radio-inline">
					  <input type="radio" name="type" value="Musang King" <?php echo ($type == 'Musang King') ? 'checked' : ''; ?> required>Musang King
					</label>
					<label class="radio-inline">
					  <input type="radio" name="type" value="Black Thorn" <?php echo ($type == 'Black Thorn') ? 'checked' : ''; ?> required>Black Thorn
					</label>
					<label class="radio-inline">
					  <input type="radio" name="type" value="101" <?php echo ($type == '101') ? 'checked' : ''; ?> required>101
					</label>
				  </div>
              </div>
            </div>
		  </div>


		  <button name="submit" type="submit" name="submit" class="btn btn-sn btn-default">Update</button>
		  
	 	</form>
	</div>
 </div>
</div>
</div>
</div>


  <script>
    $(function() {
      // Initialize datepicker
      $('#datepicker').datepicker();

      // Close datepicker after date selection
      $('#datepicker').on('changeDate', function() {
        $(this).datepicker('hide');
      });
    });
  </script>


<?php include 'theme/foot.php'; ?>
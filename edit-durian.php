<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php 
 if(!$_GET['id'] OR empty($_GET['id']) OR $_GET['id'] == '')
 {
 	header('location: manage-durian.php');

 }else{
 	
 	$date = $type = $grade = $price = $qty = $total = "";
 	$id = (int)$_GET['id'];
 	$query = $db->query("SELECT * FROM durian WHERE id = '$id' ");
 	$fetchObj = $query->fetchAll(PDO::FETCH_OBJ);

 	foreach($fetchObj as $obj){
       $date = $obj->date;
       $type = $obj->type;
	   $grade = $obj->grade;
	   $in = $obj->qty_in;
	   $out = $obj->qty_out;
	   $defect = $obj->defect;
	   $balance = $obj->balance;
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
	    $type = $_POST['type'];
	    $grade = $_POST['grade'];
	    $in = $_POST['in'];
	    $out = $_POST['out'];
	    $defect = $_POST['defect'];
	    $balance = $_POST['balance'];

      	$n_id = $_GET['id'];

      	$update_query = $db->query("UPDATE durian SET date = '$date', type = '$type', `grade` = '$grade', qty_in = '$in', qty_out = '$out', defect = '$defect', balance = '$balance' WHERE id = '$n_id' ");

      	if($update_query){?>
      	<div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
             <strong>Production details successfully update <i class="fa fa-check"></i></strong>
        </div>
       <?php
      	}else{ ?>
          <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
             <strong>Error updating production data. Please try again <i class="fa fa-times"></i></strong>
        </div>
      	<?php
      }

      }

     ?>



    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Edit Production</h3>
      </div>
      <div class="panel-body">	
	 	<form method="post">
          <div class="form-group row">
            <div class="col-md-6">
					<label class="control-label">Date</label>
					<input type="text" id="datepicker" name="date" class="form-control" value="<?php echo $date; ?>" required>
			</div>
            <div class="col-md-6">
					<label class="control-label">Durian in (kg)</label>
					<input type="text" name="in" id="durian-in" class="form-control" value="<?php echo $in; ?>" required>
			</div>
		  </div>
		  
		  <div class="form-group row">
            <div class="col-md-6">
				<label class="control-label">Durian Grade</label>
				  <div>
					<label class="radio-inline">
					  <input type="radio" name="grade" value="A" <?php echo ($grade == 'A') ? 'checked' : ''; ?> required>A
					</label>
					<label class="radio-inline">
					  <input type="radio" name="grade" value="B" <?php echo ($grade == 'B') ? 'checked' : ''; ?> required>B
					</label>
					<label class="radio-inline">
					  <input type="radio" name="grade" value="C" <?php echo ($grade == 'C') ? 'checked' : ''; ?> required>C
					</label>
				  </div>
            </div>
            <div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Durian out (kg)</label>
					<input type="text" name="out" id="durian-out" class="form-control" value="<?php echo $out; ?>" required>
				</div>
			</div>
		  </div>
				
          <div class="form-group row">
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
            <div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Defect (kg)</label>
					<input type="text" name="defect" id="defect" class="form-control" value="<?php echo $defect; ?>" required>
				</div>
			</div>
		  </div>
				
          <div class="form-group row">
            <div class="col-md-6"></div>
			<div class="col-md-6">
					<label class="control-label">Durian Unsold (kg)</label>
					<input type="text" name="balance" id="balance" class="form-control" value="<?php echo $balance; ?>" readonly>
			</div>
		  </div>
			
	 		<button name="submit" type="submit" name="submit" class="btn btn-sn btn-default">Update</button>
	 	</form>
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
  
 <script>
  // Function to calculate the durian balance
  function calculateDurianBalance() {
    var durianIn = parseFloat(document.getElementById("durian-in").value) || 0;
    var durianOut = parseFloat(document.getElementById("durian-out").value) || 0;
    var defect = parseFloat(document.getElementById("defect").value) || 0;

    var durianBalance = durianIn - durianOut - defect;
    document.getElementById("balance").value = durianBalance.toFixed(0);
  }

  // Event listener to trigger the calculation when inputs change
  document.getElementById("durian-in").addEventListener("input", calculateDurianBalance);
  document.getElementById("durian-out").addEventListener("input", calculateDurianBalance);
  document.getElementById("defect").addEventListener("input", calculateDurianBalance);
</script>


<?php include 'theme/foot.php'; ?>
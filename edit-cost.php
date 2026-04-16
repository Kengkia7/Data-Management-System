<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php 
 if(!$_GET['id'] OR empty($_GET['id']) OR $_GET['id'] == '')
 {
 	header('location: manage-cost.php');

 }else{
 	
 	$date = $type = $desc = $price = $qty = $total = "";
 	$id = (int)$_GET['id'];
 	$query = $db->query("SELECT * FROM cost WHERE id = '$id' ");
 	$fetchObj = $query->fetchAll(PDO::FETCH_OBJ);

 	foreach($fetchObj as $obj){
       $date = $obj->date;
       $type = $obj->type;
	   $desc = $obj->desc;
	   $price = $obj->price;
	   $qty = $obj->qty;
	   $total = $obj->total;
 	}
 }

?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Cost Management > Add</b></h5>
  </header>


  <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
      <h2>Add New Cost</h2>

      <div class="col-md-12">

        <?php
        if (isset($_POST['submit'])) {
          $date = $_POST['date'];
          $type = $_POST['type'];
          $desc = $_POST['desc'];
          $price = $_POST['price'];
          $qty = $_POST['qty'];
          $total = $_POST['total'];

          $insert = $db->query("INSERT INTO cost(date, type, `desc`, price, qty, total) VALUES('$date', '$type', '$desc', '$price', '$qty', '$total')");

          if ($insert) {
        ?>
            <div class="alert alert-success alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Cost successfully created <i class="fa fa-check"></i></strong>
            </div>
          <?php
          } else {
          ?>
            <div class="alert alert-danger alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Error creating cost data. Please try again <i class="fa fa-times"></i></strong>
            </div>
        <?php
          }
        }
        ?>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">New Cost Form</h3>
          </div>
          <div class="panel-body">
            <form method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="form-group row">
                <div class="col-md-6">
                  <label class="control-label">Date</label>
                  <input type="text" id="datepicker" name="date" class="form-control" value="<?php echo $date; ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="control-label">Price per unit</label>
                  <input type="number" id="price" name="price" class="form-control" value="<?php echo $price; ?>" required>
                </div>
              </div>
			  
              <div class="form-group row">
                <div class="col-md-6">
                  <label class="control-label">Type of Cost</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="type" value="Startup Cost" <?php if (in_array($type, ["Land clearing","Soil ripping","Seedling plant","Irrigating system","Equipment"])) echo "checked"; ?> required> Startup Cost
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="type" value="Recurring Cost" <?php if (in_array($type, ["Fertilizer","Pesticide","Sundry","Water","Electricity","Tools"])) echo "checked"; ?> required> Recurring Cost
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="control-label">Quantity</label>
                  <input type="number" id="qty" name="qty" class="form-control" value="<?php echo $qty; ?>" required>
                </div>
              </div>

              <div class="form-group row" id="costGroup" style="display: none;">
                <div class="col-md-6" id="startupCostGroup" style="display: none;">
                  <label class="control-label">Startup Cost</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="cost" value="Land clearing" <?php if ($type == "Land clearing") echo "checked"; ?>> Land clearing
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="cost" value="Soil ripping" <?php if ($type == "Soil ripping") echo "checked"; ?>> Soil ripping
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="cost" value="Seedling plant" <?php if ($type == "Seedling plant") echo "checked"; ?>> Seedling plant
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="cost" value="Irrigating system" <?php if ($type == "Irrigating system") echo "checked"; ?>> Irrigating system
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="cost" value="Equipment" <?php if ($type == "Equipment") echo "checked"; ?>> Equipment
                    </label>
                  </div>
                </div>
                <div class="col-md-6" id="recurringCostGroup" style="display: none;">
                  <label class="control-label">Recurring Cost</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="cost" value="Fertilizer" <?php if ($type == "Fertilizer") echo "checked"; ?>> Fertilizer
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="cost" value="Pesticide" <?php if ($type == "Pesticide") echo "checked"; ?>> Pesticide
                    </label>
                  </div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Sundry" <?php if ($type == "Sundry") echo "checked"; ?>> Sundry
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Water" <?php if ($type == "Water") echo "checked"; ?>> Water
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Electricity" <?php if ($type == "Electricity") echo "checked"; ?>> Electricity
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Tools" <?php if ($cost == "Tools") echo "checked"; ?>> Tools
				  </label>
				</div>
			  </div>
                <div class="col-md-6">
                  <label class="control-label">Total Cost</label>
                  <input type="text" id="total" name="total" class="form-control" value="<?php echo $total; ?>" readonly>
                </div>
			</div>
              <div class="form-group row">
                <div class="col-md-6">
                  <label class="control-label">Description</label>
                  <input type="text" name="desc" class="form-control" value="<?php echo $desc; ?>">
                </div>
              </div>
				  
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button name="submit" type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
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

    // Calculate total cost
    $('#price, #qty').keyup(function() {
      var price = parseFloat($('#price').val());
      var qty = parseFloat($('#qty').val());
      var total = price * qty;
      $('#total').val(total.toFixed(2));
    });

    // Show/hide cost groups based on selected type
    $('input[name="type"]').on('change', function() {
      const selectedType = $(this).val();
      const costGroup = $('#costGroup');
      const startupCostGroup = $('#startupCostGroup');
      const recurringCostGroup = $('#recurringCostGroup');

      if (selectedType === 'Startup Cost') {
        costGroup.show();
        startupCostGroup.show();
        recurringCostGroup.hide();
      } else if (selectedType === 'Recurring Cost') {
        costGroup.show();
        startupCostGroup.hide();
        recurringCostGroup.show();
      } else {
        costGroup.hide();
        startupCostGroup.hide();
        recurringCostGroup.hide();
      }
    });

    // Trigger change event on page load
    $('input[name="type"]:checked').trigger('change');
  });
</script>


<?php include 'theme/foot.php'; ?>

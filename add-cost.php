<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

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
          $type = $_POST['cost'];
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
                  <input type="text" id="datepicker" name="date" class="form-control" value="<?php echo date('Y-m-d');?>" required>
                </div>
                <div class="col-md-6">
                  <label class="control-label">Price per unit</label>
                  <input type="number" id="price" name="price" class="form-control" required>
                </div>
			  </div>
			  
			<div class="form-group row">
			  <div class="col-md-6">
				<label class="control-label">Type of Cost</label>
				<div class="radio">
				  <label>
					<input type="radio" name="type" value="Startup Cost" required> Startup Cost
				  </label>
				</div>  
				<div class="radio">
				  <label>
					<input type="radio" name="type" value="Recurring Cost" required> Recurring Cost
				  </label>
				</div>
			  </div>
			  <div class="col-md-6">
				<label class="control-label">Quantity</label>
				<input type="number" id="qty" name="qty" class="form-control" required>
			  </div>
			</div>

			<div class="form-group row" id="costGroup" style="display: none;">
			  <div class="col-md-6" id="startupCostGroup" style="display: none;">
				<label class="control-label">Startup Cost</label>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Land clearing"> Land clearing
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Soil ripping"> Soil ripping
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Seedling plant"> Seedling plant
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Irrigating system"> Irrigating system
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Equipment"> Equipment
				  </label>
				</div>
			  </div>
			  <div class="col-md-6" id="recurringCostGroup" style="display: none;">
				<label class="control-label">Recurring Cost</label>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Fertilizer"> Fertilizer
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Pesticide"> Pesticide
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Sundry"> Sundry
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Water"> Water
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Electricity"> Electricity
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="cost" value="Tools"> Tools
				  </label>
				</div>
			  </div>
                <div class="col-md-6">
                  <label class="control-label">Total Cost</label>
                  <input type="text" id="total" name="total" class="form-control" readonly>
                </div>
			</div>
              <div class="form-group row">
                <div class="col-md-6">
                  <label class="control-label">Description</label>
                  <input type="text" name="desc" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <button name="submit" type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>

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

    // Calculate total revenue dynamically
    $('#price').on('input', calculateTotal);
    $('#qty').on('input', calculateTotal);

    function calculateTotal() {
      const price = parseFloat($('#price').val()) || 0;
      const qty = parseFloat($('#qty').val()) || 0;
      const total = price * qty;
      $('#total').val(total.toFixed(2));
    }

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
  });
</script>

<?php include 'theme/foot.php'; ?>

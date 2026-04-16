<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php 
if (!$_GET['id'] || empty($_GET['id']) || $_GET['id'] == '') {
  header('location: manage-sales.php');
} else {
  $date = $type = $grade = $price = $qty = $total = "";
  $id = (int)$_GET['id'];
  $query = $db->query("SELECT * FROM revenue WHERE id = '$id'");
  $fetchObj = $query->fetchAll(PDO::FETCH_OBJ);

  foreach ($fetchObj as $obj) {
    $date = $obj->date;
    $type = $obj->type;
    $grade = $obj->grade;
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
    <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
  </header>

  <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
      <div class="col-md-12">
        <?php
        if (isset($_POST['submit'])) {
          $date = $_POST['date'];
          $type = $_POST['type'];
          $grade = $_POST['grade'];
          $price = $_POST['price'];
          $qty = $_POST['qty'];
          $total = $price * $qty; // Calculate the total sales

          $n_id = $_GET['id'];

          $update_query = $db->query("UPDATE sales SET date = '$date', type = '$type', `grade` = '$grade', price = '$price', qty = '$qty', total = '$total' WHERE id = '$n_id' ");

          if ($update_query) {
            ?>
            <div class="alert alert-success alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Sales details successfully updated <i class="fa fa-check"></i></strong>
            </div>
            <?php
          } else {
            ?>
            <div class="alert alert-danger alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Error updating sales data. Please try again <i class="fa fa-times"></i></strong>
            </div>
            <?php
          }
        }
        ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Edit Sales</h3>
      </div>
      <div class="panel-body">	
        <form method="post">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Date</label>
                <input type="text" id="datepicker" name="date" class="form-control" value="<?php echo $date; ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Price per kg</label>
                <input type="text" name="price" id="price" class="form-control" value="<?php echo $price; ?>">
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
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Quantity Sold (kg)</label>
                <input type="text" name="qty" id="qty" class="form-control" value="<?php echo $qty; ?>">
              </div>
            </div>
		  </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
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
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Total sales</label>
                <input type="text" name="total" id="total" class="form-control" value="<?php echo $total; ?>" readonly>
              </div>
            </div>
          </div>

          <button name="submit" type="submit" class="btn btn-sn btn-default">Update</button>
        </form>
      </div>
     </div>
    </div>
   </div>
  </div>
</div>

<?php include 'theme/foot.php'; ?>

<script>
  $(function() {
    // Initialize datepicker
    $('#datepicker').datepicker();

    // Close datepicker after date selection
    $('#datepicker').on('changeDate', function() {
      $(this).datepicker('hide');
    });
  });

  $(document).ready(function() {
    $('#price, #qty').keyup(function() {
      var price = parseFloat($('#price').val());
      var qty = parseFloat($('#qty').val());
      var total = price * qty;
      $('#total').val(total.toFixed(2));
    });
  });
</script>

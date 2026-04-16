<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

$durianOut = isset($_GET['durian_out']) ? $_GET['durian_out'] : "";
$type = isset($_GET['type']) ? $_GET['type'] : ""; // Set default value if not defined
$grade = isset($_GET['grade']) ? $_GET['grade'] : ""; // Set default value if not defined

$date = date('Y-m-d'); // Format: YYYY-MM-DD
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Sales Management > Add</b></h5>
  </header>
  
  <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
      <h2>Add New Sales</h2>
      <div class="col-md-12">
        <?php
        if(isset($_POST['submit'])) {
          $date = $_POST['date'];
          $type = $_POST['type'];
          $grade = $_POST['grade'];
          $price = $_POST['price'];
          $qty = $_POST['qty'];
          $total = $price * $qty; // Calculate the total sales

          $insert = $db->query("INSERT INTO revenue(date, type, `grade`, price, qty, total) VALUES('$date', '$type', '$grade', '$price', '$qty', '$total')");

          if($insert) {
        ?>
            <div class="alert alert-success alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Sales successfully created <i class="fa fa-check"></i></strong>
            </div>
        <?php
          } else {
        ?>
            <div class="alert alert-danger alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Error creating sales data. Please try again <i class="fa fa-times"></i></strong>
            </div>
        <?php
          }
        }
        ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">New Sales Form</h3>
      </div>
      <div class="panel-body">		
        <form method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="form-group row">
            <div class="col-md-6">
              <label class="control-label">Date</label>
              <input type="text" id="datepicker" name="date" class="form-control" value="<?php echo $date;?>" required>
            </div>
            <div class="col-md-6">
              <label class="control-label">Price per kg</label>
              <input type="number" id="price" name="price" class="form-control" required>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-6">
              <label class="control-label">Type of Durian</label>
              <div>
                <label class="radio-inline">
                  <input type="radio" name="type" value="Musang King" <?php if ($type == "Musang King") echo "checked"; ?> required>Musang King
                </label>
                <label class="radio-inline">
                  <input type="radio" name="type" value="Black Thorn" <?php if ($type == "Black Thorn") echo "checked"; ?> required>Black Thorn
                </label>
                <label class="radio-inline">
                  <input type="radio" name="type" value="101" <?php if ($type == "101") echo "checked"; ?> required>101
                </label>
              </div>
            </div>
            <div class="col-md-6">
              <label class="control-label">Quantity Sold (kg)</label>
              <input type="number" id="qty" name="qty" class="form-control" value="<?php echo $durianOut; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-6">
              <label class="control-label">Durian Grade</label>
              <div>
                <label class="radio-inline">
                  <input type="radio" name="grade" value="A" <?php if ($grade == "A") echo "checked"; ?> required>A
                </label>
                <label class="radio-inline">
                  <input type="radio" name="grade" value="B" <?php if ($grade == "B") echo "checked"; ?> required>B
                </label>
                <label class="radio-inline">
                  <input type="radio" name="grade" value="C" <?php if ($grade == "C") echo "checked"; ?> required>C
                </label>
              </div>
            </div>
            <div class="col-md-6">
              <label class="control-label">Total Sales</label>
              <input type="number" name="total" id="total" class="form-control" readonly>
            </div>
          </div>
          <button name="submit" type="submit" class="btn btn-sn btn-default">Submit</button>
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

    // Calculate total sales dynamically
    $('#price').on('input', calculateTotal);
    $('#qty').on('input', calculateTotal);

    function calculateTotal() {
      const price = parseFloat($('#price').val()) || 0;
      const qty = parseFloat($('#qty').val()) || 0;
      const total = price * qty;
      $('#total').val(total.toFixed(2));
    }
  });
</script>

<?php include 'theme/foot.php'; ?>

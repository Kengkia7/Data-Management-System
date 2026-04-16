<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>
<?php 
$date = date('Y-m-d'); // Format: YYYY-MM-DD
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left: 300px; margin-top: 43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top: 22px">
    <h5><b><i class="fa fa-dashboard"></i> Task Management > Add</b></h5>
  </header>

  <div class="w3-container" style="padding-top: 22px">
    <div class="w3-row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h2 class="panel-title">Add New Task</h2>
          </div>
          <div class="panel-body">
            <?php
            if (isset($_POST['submit'])) {
              // Handle form submission
              $date = $_POST['date'];
			  if (isset($_POST['fert'])) {
				$fert = $_POST['fert'];
			  } else {
				$fert = 0;
			  }		
			  if (isset($_POST['pest'])) {
				$pest = $_POST['pest'];
			  } else {
				$pest = 0;
			  }				

              $insert = $db->query("INSERT INTO task(date, fertilizer, pesticide) VALUES('$date', '$fert', '$pest')");

              if ($insert) {
                ?>
                <div class="alert alert-success alert-dismissable">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Task successfully created <i class="fa fa-check"></i></strong>
                </div>
              <?php
              } else {
                ?>
                <div class="alert alert-danger alert-dismissable">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Error creating task data. Please try again <i class="fa fa-times"></i></strong>
                </div>
            <?php
              }
            }
            ?>

            <form method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Date</label>
                    <input type="text" id="datepicker" name="date" class="form-control" value="<?php echo $date;?>" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                  <label>Task Completed</label><br>
                    <input type="checkbox" id="fert" name="fert" value="1">
					<label for="fert">Fertilizer</label><br>
                    <input type="checkbox" id="pest" name="pest" value="1">
					<label for="pest">Pesticide</label><br>
                  </div>
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
  });

  // Function to calculate the durian balance
  function calculateDurianBalance() {
    var durianIn = parseFloat(document.getElementById("durian-in").value) || 0;
    var durianOut = parseFloat(document.getElementById("durian-out").value) || 0;
    var defect = parseFloat(document.getElementById("defect").value) || 0;

    var durianBalance = durianIn - durianOut - defect;
    document.getElementById("durian-balance").value = durianBalance.toFixed(0);
  }

  // Event listener to trigger the calculation when inputs change
  document.getElementById("durian-in").addEventListener("input", calculateDurianBalance);
  document.getElementById("durian-out").addEventListener("input", calculateDurianBalance);
  document.getElementById("defect").addEventListener("input", calculateDurianBalance);
</script>

<?php include 'theme/foot.php'; ?>

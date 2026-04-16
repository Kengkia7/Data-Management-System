<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left: 300px; margin-top: 43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top: 22px">
    <h5><b><i class="fa fa-dashboard"></i> Production Management > Add</b></h5>
  </header>

  <div class="w3-container" style="padding-top: 22px">
    <div class="w3-row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h2 class="panel-title">Add New Production</h2>
          </div>
          <div class="panel-body">
            <?php
            if (isset($_POST['submit'])) {
			  // Handle form submission
			  $date = $_POST['date'];
			  $type = $_POST['type'];
			  $grade = $_POST['grade'];
			  $in = $_POST['in'];
			  $out = $_POST['out'];
			  $defect = $_POST['defect'];
			  $balance = $_POST['balance'];

			  $insert = $db->prepare("INSERT INTO durian(date, type, grade, qty_in, qty_out, defect, balance) VALUES(:date, :type, :grade, :in, :out, :defect, :balance)");
			  $insert->bindParam(':date', $date);
			  $insert->bindParam(':type', $type);
			  $insert->bindParam(':grade', $grade);
			  $insert->bindParam(':in', $in);
			  $insert->bindParam(':out', $out);
			  $insert->bindParam(':defect', $defect);
			  $insert->bindParam(':balance', $balance);
			  $insert->execute();

			  if ($insert) {
				?>
				<div class="alert alert-success alert-dismissable">
				  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  <strong>Production successfully created <i class="fa fa-check"></i></strong>
				</div>
				<?php

				// Retrieve the last inserted ID
				$id = $db->lastInsertId();

				// Prepare the SELECT query to fetch the record
				$selectQuery = $db->prepare("SELECT * FROM durian WHERE id = :id");
				$selectQuery->bindParam(':id', $id);
				$selectQuery->execute();

				// Fetch the result
				$result = $selectQuery->fetch(PDO::FETCH_ASSOC);

				// Extract the required data
				$durianOut = $productionData['qty_out'];
				$durianType = $productionData['type'];
				$durianGrade = $productionData['grade'];

				// Show the alert box before redirecting
				echo '<script>alert("Production successfully created");</script>';

				// Redirect to the add-revenue form with auto-filled fields
				header("Location: add-sales.php?durian_out=$out&type=$type&grade=$grade");
				exit();
			  } else {
				?>
				<div class="alert alert-danger alert-dismissable">
				  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  <strong>Error creating production data. Please try again <i class="fa fa-times"></i></strong>
				</div>
				<?php
			  }
			}
			?>

            <form method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="form-group row">
                <div class="col-md-6">
                  <label class="control-label">Date</label>
                  <input type="text" id="datepicker" name="date" class="form-control" value="<?php echo date('Y-m-d');?>" required>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Durian in (kg)</label>
                    <input type="number" name="in" id="durian-in" class="form-control" required>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  <label class="control-label">Type of Durian</label>
                  <div>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="Musang King" required>Musang King
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="Black Thorn" required>Black Thorn
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="101" required>101
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Durian out (kg)</label>
                    <input type="number" name="out" id="durian-out" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  <label class="control-label">Durian Grade</label>
                  <div>
                    <label class="radio-inline">
                      <input type="radio" name="grade" value="A" required>A
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="grade" value="B" required>B
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="grade" value="C" required>C
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Defect (kg)</label>
                    <input type="number" name="defect" id="defect" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                  <label class="control-label">Durian Unsold (kg)</label>
                  <input type="text" name="balance" id="durian-balance" class="form-control" readonly>
                </div>
              </div>

              <button name="submit" type="submit" class="btn btn-sn btn-default" onclick="remind('Remember to add sales record for durian out.')">Submit</button>
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
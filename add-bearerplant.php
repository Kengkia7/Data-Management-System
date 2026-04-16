<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>
<?php 
$date = date('Y-m-d'); // Format: YYYY-MM-DD
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Bearer plant > Add</b></h5>
  </header>

  <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
      <h2>Add New Bearer Plant</h2>

      <div class="col-md-12">

        <?php
        if(isset($_POST['submit']))
        {
          $date = $_POST['date'];
          $code = $_POST['code'];
          $type = $_POST['type'];
          $age = $_POST['age'];
          $condition = $_POST['conditionbp'];

          $insert = $db->query("INSERT INTO bearerplant(date, code, type, age, conditionbp) VALUES('$date', '$code', '$type', '$age', '$condition')");

          if($insert){?>
            <div class="alert alert-success alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Bearer Plant successfully created <i class="fa fa-check"></i></strong>
            </div>
          <?php
          }else{ ?>
            <div class="alert alert-danger alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Error creating bearer plant data. Please try again <i class="fa fa-times"></i></strong>
            </div>
          <?php
          }
        }
        ?>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">New Bearer Plant Form</h3>
          </div>
          <div class="panel-body">
            <form method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="form-group row">
                <div class="col-md-6">
                  <label class="control-label">Date</label>
                  <input type="text" id="datepicker" name="date" class="form-control" value="<?php echo $date;?>" required>
                </div>    
                <div class="col-md-6">
                  <label class="control-label">Age</label>
                  <input type="number" name="age" class="form-control">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  <label class="control-label">Code</label>
                  <input type="text" id="code" name="code" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="control-label">Condition of Bearer Plant</label>
                  <div>
                    <label class="radio-inline">
                      <input type="radio" name="conditionbp" value="Good" required>Good
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="conditionbp" value="Poor" required>Poor
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="conditionbp" value="Bad" required>Bad
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  <label class="control-label">Type of Bearer Plant</label>
                  <div>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="Musang King" required onchange="updateCodeField('MK')">Musang King
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="Black Thorn" required onchange="updateCodeField('BT')">Black Thorn
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="101" required onchange="updateCodeField('IOI')">101
                    </label>
                  </div>
                </div>
              </div>    
         
              <button name="submit" type="submit" class="btn btn-primary">Add</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  function updateCodeField(code) {
    document.getElementById('code').value = code;
  }
</script>

<?php include 'theme/foot.php'; ?>

<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php 
 if(!$_GET['id'] OR empty($_GET['id']) OR $_GET['id'] == '')
 {
    header('location: manage-task.php');
 } else {
    $date = $fertilizer = $pesticide = "";
    $id = (int)$_GET['id'];
    $query = $db->query("SELECT * FROM task WHERE id = '$id' ");
    $fetchObj = $query->fetchAll(PDO::FETCH_OBJ);

    foreach($fetchObj as $obj) {
       $date = $obj->date;
       $fert = $obj->fertilizer;
       $pest = $obj->pesticide;
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
      if(isset($_POST['submit'])) {
          $date = $_POST['date'];
          $fert = isset($_POST['fert']) ? 1 : 0;
          $pest = isset($_POST['pest']) ? 1 : 0;

          $n_id = $_GET['id'];

          $update_query = $db->query("UPDATE task SET date = '$date', fertilizer = '$fert', `pesticide` = '$pest' WHERE id = '$n_id' ");

          if($update_query){?>
          <div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
             <strong>Task details successfully updated <i class="fa fa-check"></i></strong>
        </div>
         <?php
          }else{ ?>
          <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
             <strong>Error updating task data. Please try again <i class="fa fa-times"></i></strong>
        </div>
        <?php
      }

      }

     ?>



    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Edit Task</h3>
      </div>
      <div class="panel-body">    
        <form method="post">
          <div class="row">
            <div class="col-md-6">
                <div class="form-group date">
                    <label class="control-label">Date</label>
                    <input type="text" id="datepicker" name="date" class="form-control" value="<?php echo $date; ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                      <label>Task Completed</label><br>
                        <input type="checkbox" id="fert" name="fert" value="1" <?php echo $fert == '1' ? 'checked' : ''; ?>>
                        <label for="fert">Fertilizer</label><br>
						<input type="checkbox" id="pest" name="pest" value="1" <?php echo $pest == '1' ? 'checked' : ''; ?>>
                        <label for="pest">Pesticide</label><br>
                </div>
            </div>
          </div>
            
          <button name="submit" type="submit" class="btn btn-sn btn-default">Update</button>
          
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


<?php include 'theme/foot.php'; ?>

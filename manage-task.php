<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Delete Task
if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    // Delete the task from the database using appropriate SQL query
    // ...

    // Redirect to the current page after deleting the task
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Get the total number of tasks
$totalTasks = $db->query("SELECT COUNT(*) AS total FROM task")->fetchColumn();

// !PAGE CONTENT!

?>

<?php

$query6 = $db->query("SELECT MAX(date) AS max_date FROM task WHERE fertilizer=1");
$fert = $query6->fetch(PDO::FETCH_ASSOC);
$lastDate = $fert['max_date'];

if ($lastDate) {
    $date = date('Y-m-d', strtotime($lastDate . '+14 days'));
} else {
    // Handle the case when there is no data available
    $date = date('Y-m-d');
}

$query7 = $db->query("SELECT MAX(date) AS max_date FROM task WHERE pesticide=1");
$pest = $query7->fetch(PDO::FETCH_ASSOC);
$lastDate1 = $pest['max_date'];

if ($lastDate1) {
    $dates = date('Y-m-d', strtotime($lastDate1 . '+14 days'));
} else {
    // Handle the case when there is no data available
    $dates = date('Y-m-d');
}

function showAlert($message) {
    echo "<script>alert('$message');</script>";
}

showAlert("Please complete fertilizer task before: $date and pesticide task before: $dates");
?>

<div class="w3-main" style="margin-left: 300px; margin-top: 43px;">
    <!-- Header -->
    <header class="w3-container" style="padding-top: 22px">
        <h5><b><i class="fa fa-dashboard"></i> Task Management</b></h5>
    </header>


    <div class="w3-container" style="padding-top: 22px">
        <div class="w3-row">
            <h2>Update Task</h2>
            <a href="add-task.php" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add New Task</a>
            <a href="view-report-task.php" class="btn btn-sm btn-success pull-right" style="margin-right: 10px;"><i class="fa fa-file"></i> View Report</a><br><br>
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="table">
                    <thead>
                        <tr>
                            <th>Task No.</th>
                            <th>Date</th>
                            <th>Fertilizer</th>
                            <th>Pesticide</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $all_task = $db->query("SELECT * FROM task ORDER BY date");
                        $fetch = $all_task->fetchAll(PDO::FETCH_OBJ);
                        foreach ($fetch as $index => $data) {
                            $taskNo = $totalTasks - $index;
                            ?>
                            <tr>
                                <td><?php echo $taskNo ?></td>
                                <td><?php echo $data->date ?></td>
                                <td><?php echo $data->fertilizer == 1 ? '&#10004;' : '' ?></td>
                                <td><?php echo $data->pesticide == 1 ? '&#10004;' : '' ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cog"></i> Option
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="edit-task.php?id=<?php echo $data->id ?>"><i class="fa fa-edit"></i> Edit</a></li>
                                            <li><a onclick="return confirm('Continue delete task ?')" href="delete-task.php?id=<?php echo $data->id ?>"><i class="fa fa-trash"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>

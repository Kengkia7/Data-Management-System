<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

$connect = mysqli_connect("localhost", "root", "", "durian");

$salessql = "SELECT SUM(total) AS yearly_total, YEAR(date) AS year FROM revenue GROUP BY YEAR(date) ORDER BY YEAR(date)";
$revResult = mysqli_query($connect, $salessql);

// Fetch the revenue data and store it in an array
$revData = [];
while ($row = mysqli_fetch_assoc($revResult)) {
    $yearName = $row['year'];
    $revData[] = [$yearName, $row['yearly_total']];
}

// Fetch the cost data 
$costsql = "SELECT SUM(total) AS yearly_cost, YEAR(date) AS year FROM cost GROUP BY YEAR(date) ORDER BY YEAR(date)";
$costResult = mysqli_query($connect, $costsql);

// Fetch the cost data and store it in an array
$costData = [];
while ($row = mysqli_fetch_assoc($costResult)) {
    $yearName = $row['year'];
    $costData[] = [$yearName, $row['yearly_cost']];
}

// Calculate the profit
$profitData = [];
foreach ($revData as $key => $data) {
    $cost = isset($costData[$key][1]) ? $costData[$key][1] : 0;
    $profit = $data[1] - $cost;
    $profitData[] = [$data[0], $data[1], $cost, $profit];
}

// Fetch the condition data
$condsql = "SELECT conditionbp, COUNT(*) AS count FROM bearerplant GROUP BY conditionbp";
$condresult = mysqli_query($connect, $condsql);

$conddata = [];
while ($row = mysqli_fetch_assoc($condresult)) {
    $conddata[$row['conditionbp']] = (int) $row['count'];
}

// Retrieve revenue data from the database
$query = "SELECT type, grade, SUM(qty_in) as total_in FROM durian GROUP BY type, grade";
$result = mysqli_query($connect, $query);

// Store revenue data in an associative array
$revenueData = array();
while ($row = mysqli_fetch_assoc($result)) {
  $type = $row['type'];
  $grade = $row['grade'];
  $totalIn = (float)$row['total_in'];

  if (!isset($revenueData[$type])) {
    $revenueData[$type] = array();
  }

  $revenueData[$type][$grade] = $totalIn;
}

// Retrieve defect data from the database
$query = "SELECT type, SUM(defect) as total_defect FROM durian GROUP BY type";
$result = mysqli_query($connect, $query);

// Store defect data in an associative array
$defectData = array();
while ($row = mysqli_fetch_assoc($result)) {
  $type = $row['type'];
  $totalDefect = (float)$row['total_defect'];

  $defectData[$type] = $totalDefect;
}

$connect->close();
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
  </header>
 
 <?php include 'inc/data.php'; ?>

 
 <div class="w3-container" style="padding-top:22px">
 
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading"> <i class="fa fa-line-chart"></i>
					<h2>Gross Profit</h2>
				</div>
				<div class="panel-body" id="rev">
				</div>	
			</div>
		</div> 
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"> <i class="fa fa-pie-chart"></i>
					<h2>Tree Condition</h2>
				</div>
				<div class="panel-body" id="chart_div">
				</div>	
			</div>
		</div>
	</div> 
	
	<div class="row">	
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading"> <i class="fa fa-line-chart"></i>
					<h2>Durian Quality</h2>
				</div>
				<div class="panel-body" id="durian">
				</div>	
			</div>
		</div> 
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2>Tree Need Replacement</h2>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>No.</th>
									<th>Code</th>
									<th>Type</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$all_bp = $db->query("SELECT * FROM bearerplant WHERE conditionbp='bad'");
								$fetch = $all_bp->fetchAll(PDO::FETCH_OBJ);
								$number = count($fetch);
								foreach ($fetch as $data) {
								?>
							  <tr>
								<td><?php echo $number ?></td>
								<td><?php echo $data->code ?></td>
								<td><?php echo $data->type ?></td>
							  </tr>    
								<?php
								$number--;
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div> 
	</div> 
	
	<div class="row">	
	
	</div>
</div>
	
<script>
  function resetRadioButtons() {
    var radioButtons = document.getElementsByName("report_period");
    for (var i = 0; i < radioButtons.length; i++) {
      radioButtons[i].checked = false;
    }
  }
  
  function resetDateInputs() {
    document.getElementById("start_datepicker").value = "";
    document.getElementById("end_datepicker").value = "";
  }


	$(function() {
	  // Initialize datepickers
	  $('#start_datepicker').datepicker();
	  $('#end_datepicker').datepicker();

	  // Close datepicker after date selection
	  $('#start_datepicker, #end_datepicker').on('changeDate', function() {
		$(this).datepicker('hide');
	  });
	});
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Year');
        data.addColumn('number', 'Total Sales');
        data.addColumn('number', 'Total Cost');
        //data.addColumn('number', 'Gross Profit');
        <?php
        foreach ($profitData as $key => $data) {
            echo "data.addRow(['" . $data[0] . "', " . $data[1] . ", " . $data[2] . ",]);";
        }
        ?>

        var options = {
            title: '',
            chartArea: { width: '50%' },
            hAxis: {
                title: 'Year'
            },
            vAxis: {
                title: 'Amount'
            },
            pointSize: 10,
            dataOpacity: 1
        };

        var chart = new google.visualization.LineChart(document.getElementById('rev'));
        chart.draw(data, options);
    }

    google.charts.setOnLoadCallback(drawPieChart);

    function drawPieChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Conditionbp');
        data.addColumn('number', 'Count');
        data.addRows([
            ['Good', <?php echo isset($conddata['Good']) ? $conddata['Good'] : 0; ?>],
            ['Poor', <?php echo isset($conddata['Poor']) ? $conddata['Poor'] : 0; ?>],
            ['Bad', <?php echo isset($conddata['Bad']) ? $conddata['Bad'] : 0; ?>]
        ]);

        var options = {
        title: '',
        pieHole: 0.4,
        chartArea: {
            width: '80%',
            height: '80%',
            left: 'center',
            top: 'center'
        }, 
        legend: {
            textStyle: {
                fontSize: 14 // Adjust the font size as needed
            }
        }
    };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>
  
<script type="text/javascript">
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Type');
        data.addColumn('number', 'Grade A');
        data.addColumn('number', 'Grade B');
        data.addColumn('number', 'Grade C');
        data.addColumn('number', 'Defect %');

        <?php
        foreach ($revenueData as $type => $grades) {
            $gradeA = isset($grades['A']) ? (float) $grades['A'] : 0;
            $gradeB = isset($grades['B']) ? (float) $grades['B'] : 0;
            $gradeC = isset($grades['C']) ? (float) $grades['C'] : 0;
            $totalProduction = $gradeA + $gradeB + $gradeC;
            $defectPercentage = isset($defectData[$type]) ? ($defectData[$type] / $totalProduction) * 100 : 0;
            $gradeAPercentage = round(($gradeA / $totalProduction) * 100,2);
            $gradeBPercentage = round(($gradeB / $totalProduction) * 100,2);
            $gradeCPercentage = round(($gradeC / $totalProduction) * 100,2);
            echo "data.addRow(['$type', $gradeAPercentage, $gradeBPercentage, $gradeCPercentage, $defectPercentage]);";
            echo "data.setFormattedValue(data.getNumberOfRows() - 1, 1, '$gradeAPercentage%');";
            echo "data.setFormattedValue(data.getNumberOfRows() - 1, 2, '$gradeBPercentage%');";
            echo "data.setFormattedValue(data.getNumberOfRows() - 1, 3, '$gradeCPercentage%');";
        }
        ?>

        var options = {
            title: 'Durian Grade and Defect Percentage',
            vAxis: { title: 'Production Percentage' },
            hAxis: { title: 'Durian Type' },
            seriesType: 'bars',
            isStacked: true,
            series: {
                3: { type: 'line' }
            },
            legend: {
                textStyle: {
                    fontSize: 9
                }
            },
            vAxis: {
                format: '0\'%\'', // Format the vertical axis as a percentage
                minValue: 0, // Set the minimum value to 0
                maxValue: 100 // Set the maximum value to 100
            }
        };

        var chart = new google.visualization.ComboChart(document.getElementById('durian'));
        chart.draw(data, options);
    }
</script>

<?php include 'theme/foot.php'; ?>

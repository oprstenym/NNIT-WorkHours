<?php
Class Report {
	var $UserID = 0;
	var $Year = 2011;
	var $data = array();
	
	function __construct($userID) {
		$this->UserID = $userID;			
	}

	function LoadWeekReportData($weekno) {
		$weekno *= 1;
		$firstweek = mktime(0,0,0,1, $this->GetFirstMondayOfYear(), $this->Year);
		
		$startDay = $firstweek + ($weekno-1) * 7 * 24 * 60 * 60;
		$endDay = $firstweek + ($weekno) * 7 * 24 * 60 * 60 - 24 * 60 * 60;
		
		$start = date("Y-m-d", $startDay);
		$end = date("Y-m-d", $endDay);
		
		$query = "SELECT pr.id projectid, pw.timestamp, pw.timespent, pr.projectnumber, pr.crmname
                	FROM pwa pw
                	JOIN changes ch ON pw.task = ch.id
                	JOIN projects pr ON ch.projectid = pr.id
                	WHERE pw.timestamp BETWEEN '".$start." 00:00:00' AND '".$end." 23:59:59'
                	AND pw.UserID = ".$this->UserID."
                	ORDER BY pw.timestamp";

		$result = mysql_query($query);
		
		$this->data = array();
		
		while (@$row = mysql_fetch_array($result)) {
			array_push($this->data, $row);
		}
	}
	
	function GetFirstMondayOfYear() {
		$date = 1;
		for($date=1; $date<=7; $date ++){
			if(date("w", mktime(0,0,0,1,$date, $this->Year))==1){
				return $date;
			}
		}
	}
	
	function InitWeekTotals() {
		return array(0,0,0,0,0,0,0);
	}
	
	function ShowWeekReport($weekno){
		$this->LoadWeekReportData($weekno);
		$projects = array();
		$day_totals = $this->InitWeekTotals();
		$week_total = 0;
		
		foreach ($this->data as $row ) {
			if(!isset($projects[$row['projectid']])) {
				$projects[$row['projectid']] = $this->InitWeekTotals();				
				$projects[$row['projectid']]['name'] = $row['projectnumber']." - ".$row['crmname'];
			}
			
			$projects[$row['projectid']]['total'] += $row['timespent'];
			$day_index = date("N",strtotime($row['timestamp']))-1;
			$projects[$row['projectid']][$day_index] += $row['timespent'];
		}
			
		
		$firstweek = mktime(0,0,0,1, $this->GetFirstMondayOfYear(), $this->Year);
		$startdate = $firstweek + ($weekno-1) * 7 * 24 * 60 * 60;
		?>

		<h2>Week <?php echo $weekno;?></h2>
		<h3><?php echo date("Y-m-d",$startdate)." - ".date("Y-m-d", $startdate + 6 * 24 * 60 * 60);?> </h3>		
		<table border=1>
		<thead>
		<tr>
		<th>Project</th>
		<th>Total</th>
		<th>Mon<br/><?php echo date("d.m.",$startdate);?></th>
		            <th>Tue<br/><?php echo date("d.m.",$startdate+24*3600);?></th>
		            <th>Wed<br/><?php echo date("d.m.",$startdate+2*24*3600);?></th>
		            <th>Thu<br/><?php echo date("d.m.",$startdate+3*24*3600);?></th>
		            <th>Fri<br/><?php echo date("d.m.",$startdate+4*24*3600);?></th>
		            <th>Sat<br/><?php echo date("d.m.",$startdate+5*24*3600);?></th>
		            <th>Sun<br/><?php echo date("d.m.",$startdate+6*24*3600);?></th>
		        </tr>
		    </thead>
		 
	<?php
		foreach ($projects as $project){
			echo "<tr>";
			echo "<td>".$project['name']."</td>";
			echo "<td>".$project['total']."</td>";
		
			for($i=0; $i<7; $i++) {
				echo "<td>".$project[$i]."</td>";
				$totals[$i] += $project[$i];
				$total += $project[$i];
			}
			echo "</tr>";
		}
		echo "<tr>
		<td style=\"font-weight:bold;\">TOTAL</td>
		<td style=\"font-weight:bold;\">".$total."</td>";
		for($i=0; $i<7; $i++) {
			echo "<td style=\"font-weight:bold;\">".($totals[$i]==0 ? "0" : $totals[$i])."</td>";
		}
		echo "</tr>";
		?>
		</table>
	<?php 
	}
}
if(isset($_REQUEST['t'])) {
	global $app;
	$rep = new Report($app->user['id']);
	switch ($_REQUEST['t']) {
		case 'week':
			$rep->ShowWeekReport($_REQUEST['id']);
			break;
	}
} else {
    $t = new Template(); 
    echo $t->fetch("template/reports.t");
}
?>
<?php   
Class T_Report {	
	var $Year = 2011;
	var $data = array();
	var $UserID;	
	
	function __construct($userID) {
		$this->UserID = $userID;        
    }
    	
    function PrepareMonthData(){
    	for($i=1; $i<=12; $i++){
			$this->data["m"][$i]['billable'] = 0;
			$this->data["m"][$i]['nonbillable'] = 0;
		}
		
    	foreach($this->data as $row) {
			if($row['IsBillable'] == 'Y') {
				$this->data["m"][date("n", strtotime($row['TimeStamp']))]['billable'] += $row['TimeSpent'];
			} else {
				$this->data["m"][date("n", strtotime($row['TimeStamp']))]['nonbillable'] += $row['TimeSpent'];
			}
		}
    }	
    	
    function PrepareWeekData(){
		//reset data
		$numweeks = date("W", mktime(0,0,0,12,31,$this->Year));
		for($i=1; $i<=$numweeks; $i++){
			$this->data["w"][sprintf("%02d", $i)]['billable'] = 0;
			$this->data["w"][sprintf("%02d", $i)]['nonbillable'] = 0;
		}
		
		
		foreach($this->data as $row) {
			if($row['IsBillable'] == 'Y') {
				$this->data["w"][date("W", strtotime($row['TimeStamp']))]['billable'] += $row['TimeSpent'];
			} else {
				$this->data["w"][date("W", strtotime($row['TimeStamp']))]['nonbillable'] += $row['TimeSpent'];
			}
		}		
    }
    
	function LoadData(){
		
		$start_date = date("Y-m-d H:i:s", mktime(0, 0, 0, 1,1,$this->Year));
		$end_date = date("Y-m-d H:i:s", mktime(23, 59, 59, 31,12,$this->Year));
		$query = "SELECT pr.IsBillable, pw.TimeStamp, pw.TimeSpent 
					FROM pwa pw 
						JOIN changes ch ON pw.Task=ch.ID 
						JOIN projects pr ON pr.ID = ch.ProjectID 
					WHERE pw.UserID = ".$this->UserID. " 
				  	AND pw.`TimeStamp` BETWEEN '".$start_date."' AND '".$end_date."'";
			
		$result = mysql_query($query);   
						             
        while ($row = @mysql_fetch_array($result)) {
        	array_push($this->data, $row);
        }         	        		        						        							
									             				
	}
	
	function RenderCurrentData(){?>
		<h3>Current data</h3>
		<table border=1px cellspacing=0>
	    <tr>
		    <th>Data</td>
		    <th>BH (Charged)</td>
		    <th>NBH (Charged)</td>
		    <th>Sum</td>
		    <th>OT</td>
	    </tr>
	    <tr>
	    	<td>Current week</td>
	    	<td><?php echo $this->data['w'][date("W")]['billable'];?></td>
	    	<td><?php echo $this->data['w'][date("W")]['nonbillable'];?></td>
	    	<td><?php echo ($this->data['w'][date("W")]['billable'] + $this->data['w'][date("W")]['nonbillable']);?></td>
	    	<td>N/A</td>
	    </tr>
	    <tr>
	    	<td>Last week</td>
	    	<td><?php echo date("W")=="01" ? "N/A" : $this->data['w'][date("W")-1]['billable'];?></td>
	    	<td><?php echo date("W")=="01" ? "N/A" : $this->data['w'][date("W")-1]['nonbillable'];?></td>
	    	<td><?php echo date("W")=="01" ? "N/A" : ($this->data['w'][date("W")-1]['billable']+$this->data['w'][date("W")-1]['nonbillable']);?></td>
	    	<td>N/A</td>
	    </tr>
	    <tr>
	    	<td>Current month</td>
	    	<td><?php echo $this->data['m'][date("n")]['billable'];?></td>
	    	<td><?php echo $this->data['m'][date("n")]['nonbillable'];?></td>
	    	<td><?php echo ($this->data['m'][date("n")]['billable'] + $this->data['m'][date("n")]['nonbillable']);?></td>
	    	<td>N/A</td>
	    </tr>
	    <tr>
	    	<td>Last month</td>
	    	<td><?php echo date("n")=="1" ? "N/A" : $this->data['m'][date("n")-1]['billable'];?></td>
	    	<td><?php echo date("n")=="1" ? "N/A" : $this->data['m'][date("n")-1]['nonbillable'];?></td>
	    	<td><?php echo date("n")=="1" ? "N/A" : ($this->data['m'][date("n")-1]['billable']+$this->data['m'][date("n")-1]['nonbillable']);?></td>
	    	<td>N/A</td>
	    </tr>
	    </table>
	<?php
	}
	
	function RenderMonthlyOverView() {?>
		<h3>Monthly overview</h3>
		<table border=1px cellspacing=0>
	    <tr>
		    <th style="width:70px;">Month</td>
		    <th>BH (Charged)</td>
		    <th>NBH (Charged)</td>
		    <th>Sum</td>
		    <th>OT</td>
	    </tr>
	    <?php
	    for($i=1; $i<=12; $i++) {
	    	echo "<tr>
	    			<td>".date("F", mktime(0,0,0,$i,1,$this->Year))."</td>
	    			<td>".$this->data["m"][$i]['billable']."</td>
	    			<td>".$this->data["m"][$i]['nonbillable']."</td>
	    			<td>".($this->data["m"][$i]['billable'] + $this->data["m"][$i]['nonbillable'])."</td>
	    			<td>N/A</td>
    			</tr>";
	    }
	    ?>
	    </table>
	<?php
	}
	
	function RenderWeeklyOverView(){?>
		<h3>Weekly overview</h3>
		<table border=1px cellspacing=0>
	    <tr>
		    <th>Week #</td>
		    <th>BH (Charged)</td>
		    <th>NBH (Charged)</td>
		    <th>Sum</td>
		    <th>OT</td>
		    <th>&nbsp;</td>
	    </tr>  
	    <?php
	    	$curweek = date("W");
	    	$style = " style=\"background-color:#cccccc;\"";
	    	foreach ($this->data["w"] as $weekno => $weekdata){
	    		$hi_lite = $weekno == $curweek;	    		
	    		echo "<tr>
	    			<td".($hi_lite?$style:"").">".$weekno."</td>
	    			<td".($hi_lite?$style:"").">".$this->data["w"][$weekno]['billable']."</td>
	    			<td".($hi_lite?$style:"").">".$this->data["w"][$weekno]['nonbillable']."</td>
	    			<td".($hi_lite?$style:"").">".($this->data["w"][$weekno]['billable'] + $this->data["w"][$weekno]['nonbillable'])."</td>
	    			<td".($hi_lite?$style:"").">N/A</td>
	    			<td".($hi_lite?$style:"")."><a href=\"?a=reports&t=week&id=".$weekno."\">Details</a></td>
    			</tr>";
	    	}
	    ?> 
	    </table>
	<?php
	}
	
	function Render() {
		$this->LoadData();
		$this->PrepareMonthData();
		$this->PrepareWeekData();
	
		echo "<h2>Reports</h2>";
		
		$this->RenderCurrentData();
		$this->RenderMonthlyOverview();
		$this->RenderWeeklyOverView(); 		
	}	
	
	function SetYear($year){
		$this->Year = $year;
	}
}

global $app;
$rep = new T_Report($app->user['id']);
$rep->Render();
?>	

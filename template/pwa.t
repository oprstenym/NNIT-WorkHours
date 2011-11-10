<?php
global $app;
    
Class T_PWA {
	var $data = array();
	var $userID;
	
	function __construct($userID) {
		$this->userID = $userID;        
    }
     
    function Render(){
    	$this->PrintHeader();        
        $this->PrintTable();
    }
    
	function PrintHeader() {?>
	<h2>PWA Data</h2>
	<style>		
		a.menu {
    		text-decoration:none;
    		color: navy;
		}
		a.menu:hover {
    		background-color: #dddddd;
		}

	</style>
	<button dojoType="dijit.form.Button" type="button" onclick="document.location = 'index.php?a=pwa&t=edit';">ADD NEW</button>
	<?php	
	}
	
	function PrintTable() {		
		$this->PrintTableHeader();
		$this->ReadAllData();
		$this->PrintTableData();
		$this->PrintTableFooter();
	}
	
	function PrintTableFooter(){?>
	</table>
	</p>
	<?php		
	}
	
	function PrintTableHeader(){?>
<!-- List of PWAs -->
	<p>
    <table border=1px cellspacing=0 width="100%">
	    <tr>
		    <th>Date</td>
		    <th>Project</td>
		    <th>Task</td>
		    <th>TimeSpent</td>
		    <th>Description</td>
		    <th>AX</td>
	    </tr>
    <?php
	}
	
	function PrintTableData() {
		$date = date("Y-m-d H:i:s");
        $date_to_print = $date;

		foreach ($this->data as $row){
			if (substr($row["timestamp"],0,10) != $date) {
				$date = substr($row["timestamp"],0,10);
                $date_to_print = $date;
			} else {
            	$date_to_print  = "&nbsp;";
			}
                    
			$max_note_len = 60;
			$note = nl2br((strlen($row["description"]) > $max_note_len) ? (substr($row['description'],0, $max_note_len)."...") : $row['description']);
            
            $style_charged = $row["Charged"] == 'Y' ? " style=\"background-color:lightgreen;\"" : " style=\"background-color:pink;\"";
            
            echo "<tr>
            <td>$date_to_print</td>
            <td><a class=\"menu\" href=\"?a=project&t=edit&id=".$row['projectid']."\" id=\"project_".$row["projectid"]."_".$row['id']."\" onmousedown=\"if(event.button==2) {projectMenu({projectID : '".$row["projectid"]."', itemID : '".$row['id']."'});}\">".$row["projectnumber"]."</a></td>
            <td><a class=\"menu\" href=\"?a=tasks&t=edit&id=".$row['changeid']."\" id=\"task_".$row["changeid"]."_".$row['id']."\" onmousedown=\"if(event.button==2) {taskMenu({taskID : '".$row["changeid"]."', itemID : '".$row['id']."'});}\">".$row["changenumber"]." - ".$row['CHGDescr']."</a></td>
            <td><a class=\"menu\" href=\"?a=pwa&t=edit&id=".$row["id"]."\" id=\"pwa_".$row['id']."\" onmousedown=\"if(event.button==2) {pwaMenu({charged : '".$row["Charged"]."', pwaID : '".$row['id']."'});}\">".$row["timespent"]."</a></td>
            <td>".$note."</td>
            <td id=\"charged".$row['id']."\" $style_charged><a style=\"color:black;text-decoration:none;\" href=\"#\" onclick=\"switchCharged('".$row["Charged"]."', ".$row["id"]."); return false;\">".$row["Charged"]."</a></td>
            </tr>";
		}
	}
	
	function ReadAllData() {
		$query = "SELECT pr.id as projectid, pw.id, pw.timestamp, pr.projectnumber, ch.id as changeid, ch.changenumber, ch.description as CHGDescr, pw.timespent, pw.description, pw.Charged
                    FROM pwa pw, changes ch, projects pr
                    WHERE pw.task = ch.id 
                    AND ch.projectid = pr.id
                    AND pw.UserID = ".$this->userID."
                    AND (pw.TimeStamp > DATE_SUB(CURRENT_DATE(), INTERVAL 14 DAY) OR pw.Charged = 'N')
                    ORDER BY pw.timestamp DESC, ch.changenumber";        
		$result = mysql_query($query);                
        while ($row = @mysql_fetch_array($result)) {
        	array_push($this->data, $row);        	
		}
	}
}

$pwa = new T_PWA($app->user['id']);
$pwa->Render();
?>
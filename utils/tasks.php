<?php
class Task {
	var $ID = 0;
	var $UserID = 0;
	var $ChangeNumber = "CHG-";
	var $ProjectID = 0;
	var $Description = "";
	var $StatusID = 0;
	var $EstHours = 0;
	var $ReleaseID = 0;
	var $PhaseID = 0;
	var $CutOver = false;
	var $Functional = "";
	var $System = "";
	var $Notes = "";
	
	var $projectsData = array();
	var $statusData = array();
	var $releaseData = array();
	var $phaseData = array();
	var $systemData = array();
	
	function __construct($userID, $ID) {
		$this->UserID = $userID;
		$this->ID = $ID;	
	}
	
	function LoadData(){
		$query = "SELECT * FROM changes                    
            	  WHERE ID=".$this->ID." 
                  AND UserID = ".$this->UserID;
			
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
	
		$this->ChangeNumber = $row['ChangeNumber'];	
		$this->ProjectID = $row['ProjectID'];			
		$this->Description = $row["Description"];
		$this->StatusID = $row["StatusID"];
		$this->EstHours = $row["EstHours"];
		$this->ReleaseID = $row["ReleaseID"];
		$this->PhaseID = $row["PhaseID"];
		$this->CutOver = $row["Cutover"] == "Y"?true:false;
		$this->Functional = $row["Functional"];
		$this->System = $row["System"];
		$this->Notes = $row["Notes"];
	}
	
	function RenderForm() {
	$this->GetProjects();
	$this->GetStatuses();
	$this->GetRelease();
	$this->GetPhase();
	$this->GetSystem();
	$this->GetDocuments();
	?>
	<h2>Edit Task</h2>
	<p>
	<!-- Form to add new PWA -->
		<form method="post" action="index.php">
			<table>
				<tr>
            	<td>Project Number</td>
            	<td>
                	<select name="projectid" dojoType="dijit.form.Select" maxHeight="200" style="width:550px;">
                	<?php echo $this->RenderSelectProjects(); ?>
             		</select>                
            	</td>
        		</tr>
        		<tr>
            	<td>Change Number</td>
            	<td>
                	<input dojoType="dijit.form.TextBox" style="width:550px;" type="text" name="changenumber" value="<?php echo $this->ChangeNumber;?>"/>
            	</td>
        		</tr>
        		<tr>
            	<td>Description</td>
            	<td>
	                <input dojoType="dijit.form.TextBox" style="width:550px;" type="text" name="description" value="<?php echo $this->Description;?>">                
            	</td>
        		</tr>
        		<tr>
            	<td>Status</td>
            	<td>
	                <select dojoType="dijit.form.Select" maxHeight="200" name="statusid" style="width:100px;">
	                <?php echo $this->RenderSelectStatus(); ?>
	            	</select>	                        
            	</td>
        		</tr>
        		<tr>
            	<td>EST Hours</td>
            	<td>
	                <input dojoType="dijit.form.TextBox" style="width:5em;" type="text" name="esthours" value="<?php echo $this->EstHours;?>">                
            	</td>
        		</tr>
        		<tr>
            	<td>Release</td>
            	<td>
             		<select dojoType="dijit.form.Select" maxHeight="200" name="releaseid" style="width:100px;">
             		<?php echo $this->RenderSelectRelease(); ?>
             		</select>
             		&nbsp;&nbsp;&nbsp;
             		Phase
             		&nbsp;
             		<select dojoType="dijit.form.Select" maxHeight="200" name="phaseid" style="width:100px;">
             		<?php echo $this->RenderSelectPhase(); ?>
             		</select> 
             		&nbsp;&nbsp;&nbsp;
             		System
             		&nbsp;
             		<select dojoType="dijit.form.Select" maxHeight="200" name="system" style="width:100px;">
             		<?php echo $this->RenderSelectSystem(); ?>
             		</select>  
            	</td>
        		</tr>
        		<tr>
            	<td>CutOver</td>
            	<td>
	                <input dojoType="dijit.form.CheckBox" type="checkbox" name="cutover" <?php if($this->CutOver) echo "checked";?>>                
    	        </td>
		        </tr>
        		<tr>
            	<td>Functional owner</td>
            	<td>
	                <input dojoType="dijit.form.TextBox" style="width:5em;" type="text" name="functional" value="<?php echo $this->Functional;?>">                
            	</td>
        		</tr>
        		<tr>
            	<td>Notes</td>
            	<td>
	                <textarea dojoType="dijit.form.Textarea" data-dojo-props="rows: '5'" style="width:550px;" name="notes"><?php echo $this->Notes;?></textarea>
    	        </td>
		        </tr>
		        <tr>
            	<td>&nbsp;</td>
            	<td>
					<!-- <button dojoType="dijit.form.Button" type="button" onclick="addTDS('dialog');">Add TDS</button>  -->
	                <button dojoType="dijit.form.Button" type="submit">Save</button>
	                <button dojoType="dijit.form.Button" type="button" onclick="history.go(-1);">Back</button>            
	            </td>            
		        </tr>
			</table>
			<input type="hidden" name="a" value="tasks"/>
			<input type="hidden" name="t" value="save"/>
			<input type="hidden" name="id" value="<?php echo $this->ID;?>"/>
			<input type="hidden" name="userid" value="<?php echo $this->UserID; ?>">
		</form>
	</p>
	<?php 	
	}
	
	function GetProjects() {
		$query = "SELECT *
		            FROM projects
		            WHERE UserID = ".$this->UserID;

		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			array_push($this->projectsData, $row);
		}		
	}
	function RenderSelectProjects(){
		$data = "";
		foreach ($this->projectsData as $row) {		
			$data .= "<option value=\"".$row['ID']."\"";
			if($this->ProjectID == $row['ID']) {
				$data .= ' selected="selected"';
			}
			$data .= ">".$row['ProjectNumber']."-".$row['CRMName']."</option>";
		}
		return $data;
	}
	
	function GetStatuses(){
		$query = "SELECT * FROM settings WHERE attribute=\"CHG Status\" ORDER BY `Order`";
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			array_push($this->statusData, $row);
		}
	}
	function RenderSelectStatus(){
		$data = "";
		foreach ($this->statusData as $row) {		
			$data .= "<option value=\"".$row['ID']."\"";
			if($this->StatusID == $row['ID']) {
				$data .= ' selected="selected"';
			}
			$data .= ">".$row['Value']."</option>";
		}
		return $data;
	}
	
	function GetRelease() {
		$query = "SELECT * FROM settings WHERE attribute=\"Release\" ORDER BY `Order`";
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			array_push($this->releaseData, $row);
		}
	}
	function RenderSelectRelease(){
		$data = "";
		foreach ($this->releaseData as $row) {	
			$data .= "<option value=\"".$row['ID']."\"";
			if($this->ReleaseID == $row['ID']) {
				$data .= ' selected="selected"';
			}
			$data .= ">".$row['Value']."</option>";
		}	
		return $data;
	}
	
function GetPhase() {
		$query = "SELECT * FROM settings WHERE attribute=\"Release Phase\" ORDER BY `Order`";
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			array_push($this->phaseData, $row);
		}
	}
	function RenderSelectPhase(){
		$data = "";
		foreach ($this->phaseData as $row) {
			$data .= "<option value=\"".$row['ID']."\"";
			if($this->PhaseID == $row['ID']) {
				$data .= ' selected="selected"';
			}
			$data .= ">".$row['Value']."</option>";
		}
		return $data;
	}
	
	function GetSystem() {
		$query = "SELECT * FROM settings WHERE attribute=\"System\" ORDER BY `Order`";
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			array_push($this->systemData, $row);
		}
	}
	function RenderSelectSystem(){
		$data = "";
		foreach ($this->systemData as $row) {
			$data .= "<option value=\"".$row['ID']."\"";
			if($this->System == $row['ID']) {
				$data .= ' selected="selected"';
			}
			$data .= ">".$row['Value']."</option>";
		}
		return $data;
	}
	
	function GetDocuments(){
		$query = "SELECT * FROM documents WHERE ChangeID = ".$this->ID;
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			array_push($this->documentData, $row);
		}
	}
	
	function Save(){
		$query = "";
		$description = mysql_real_escape_string($this->Description);
		$notes = mysql_real_escape_string($this->Notes);
		
		if($this->ID != 0){
			$query = "UPDATE changes SET UserID = ".$this->UserID.",
					                     ChangeNumber = '".$this->ChangeNumber."', 
					                     ProjectID = ".$this->ProjectID.", 
					                     Description = '".$description."',
					                     StatusID = ".$this->StatusID.", 
										 EstHours = '".$this->EstHours."',
										 ReleaseID = ".$this->ReleaseID.",
										 PhaseID = ".$this->PhaseID.", 
					                     Cutover = '".($this->CutOver?"Y":"N")."',
					                     Functional = '".$this->Functional."',
					                     System = '".$this->System."',
					                     Notes = '".$notes."'
					                     WHERE ID = ".$this->ID;
		} else {
			$query = "INSERT INTO changes (UserID, ChangeNumber, ProjectID, Description, StatusID, EstHours, ReleaseID, PhaseID, Cutover, Functional, System, Notes)
								       VALUES (
								         ".$this->UserID.", 
					                     '".$this->ChangeNumber."', 
					                     ".$this->ProjectID.", 
					                     '".$this->Description."',
					                     ".$this->StatusID.",
					                     '".$this->EstHours."',
					                     ".$this->ReleaseID.",
					                     ".$this->PhaseID.",					                     
					                     '".($this->CutOver?"Y":"N")."',
					                     '".$this->Functional."',
					                     '".$this->System."',
					                     '".$notes."'
					)";
		}
		mysql_query($query);
	}
	
	function SetStatus($status) {
		$this->GetStatuses();
		
		foreach ($this->statusData as $row) {
			
			if($status == $row['Value']) {
				$this->StatusID = $row['ID'];
				return;
			}
		}		
	}
	
	function SetCutOver ($active) {	
		$this->CutOver = $active == "Y";
	}
}



if(isset($_REQUEST['t'])) {
	
	global $app;
	
	$task = new Task($app->user['id'], (isset($_REQUEST['id'])? $_REQUEST['id']: 0));
	switch ($_REQUEST['t']) {
		case 'save':
			$task->ChangeNumber = $_REQUEST['changenumber'];
			$task->ProjectID = $_REQUEST['projectid'];
			$task->Description = $_REQUEST['description'];
			$task->StatusID = $_REQUEST['statusid'];
			$task->EstHours = $_REQUEST['esthours'];
			$task->ReleaseID = $_REQUEST['releaseid'];
			$task->PhaseID = $_REQUEST['phaseid'];
			$task->CutOver = $_REQUEST['cutover'] == 'on';
			$task->Functional = $_REQUEST['functional'];
			$task->System = $_REQUEST['system'];
			$task->Notes = $_REQUEST['notes'];
			
			$task->Save();
				
			echo "<script language=\"JavaScript\">document.location = 'index.php?a=tasks';</script>";
			break;
		case 'edit':
			if($task->ID != 0) {
				$task->LoadData();
			}
			$task->RenderForm();
			break;
		case 'status':
			$task->LoadData();
			$task->SetStatus($_REQUEST['value']);			
			$task->Save();
			break;
		case 'cutover':
			$task->LoadData();
			$task->SetCutOver($_REQUEST['value']);
			$task->Save();
			break;
	}
} else {
	$t = new Template(); 
    echo $t->fetch("template/tasks.t");
}
?>
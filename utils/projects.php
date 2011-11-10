<?php
class Project {
	
	var $ID = 0;
	var $UserID = 0;
	var $ProjectNumber = "";
	var $CRMName = "";
	var $Name = "";
	var $Active = true;
	var $IsBillable = false;
	var $Order = 999;
	
	
	function __construct($userID, $projectID) {
		$this->UserID = $userID;
		$this->ID = $projectID;		
	}
	
	function LoadData(){
		$query = "SELECT *
			            FROM projects                   
			            WHERE id=".$this->ID." AND UserID = ".$this->UserID;   
			
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
			
		$this->ProjectNumber = $row["ProjectNumber"];
		$this->CRMName = $row["CRMName"];
		$this->Name = $row["Name"];
		$this->Active = $row["Active"] == "Y"?true:false;
		$this->IsBillable = $row["IsBillable"] == "Y"?true:false;
		$this->Order = $row["Order"];
	}
	
	function RenderForm(){?>
	<p>
	<!-- Form to add new PWA -->
	<form method="post" action="index.php">
	<table>
		<tr>
			<td>Project Number</td>
			<td>
				<input dojoType="dijit.form.TextBox" style="width:550px;" type="text" name="projectnumber" value="<?php echo $this->ProjectNumber;?>"/>
			</td>
		</tr>
		<tr>
			<td>CRM Name</td>
			<td>
				<input dojoType="dijit.form.TextBox" style="width:550px;" type="text" name="crmname" value="<?php echo $this->CRMName;?>">
			</td>
		</tr>
		<tr>
			<td>Name</td>
			<td>
				<input dojoType="dijit.form.TextBox" style="width:550px;" type="text" name="name" value="<?php echo $this->Name;?>">
			</td>
		</tr>
		<tr>
			<td>Is Active</td>
			<td>
				<input dojoType="dijit.form.CheckBox" type="checkbox" name="active" <?php if($this->Active) echo "checked";?>>
				&nbsp;&nbsp;&nbsp;
				Is Billable
				&nbsp;
				<input dojoType="dijit.form.CheckBox" type="checkbox" name="isbillable" <?php if($this->IsBillable) echo "checked";?>>
			</td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>
        		<button dojoType="dijit.form.Button" type="submit" value="Submit">Save</button>
        		<button dojoType="dijit.form.Button" type="button" onclick="history.go(-1);">Back</button>
        	</td>
        </tr>
	</table>
	<input type="hidden" name="order" value="999"/>
	<input type="hidden" name="a" value="projects"/>
	<input type="hidden" name="t" value="save"/>
	<input type="hidden" name="id" value="<?php echo $this->ID;?>"/>
	<input type="hidden" name="userid" value="<?php echo $this->UserID; ?>">
	</form>
	</p>
	<?php 
	}
	
	function Save() {
		$query = "";		
		$CRMName = mysql_real_escape_string($this->CRMName);
		$Name = mysql_real_escape_string($this->Name);
	
		if($this->ID != 0){
			$query = "UPDATE projects SET ProjectNumber = '".$this->ProjectNumber."', 
				                          CRMName = ".$CRMName.", 
				                          Name = '".$Name."', 
				                          Order = '".$this->Order."', 
				                          Active = '".($this->Active?"Y":"N")."'
				                     WHERE ID = ".$this->ID;
		} else {
			$query = "INSERT INTO pwa (UserID, ProjectNumber, CRMName, Name, Active, Order)
							       VALUES (
							         ".$this->UserID.", 
				                     '".$this->ProjectNumber."', 
				                     '".$this->CRMName."', 
				                     '".$this->Name."',
				                     '".($this->Active?"Y":"N")."',
				                     '".$this->Order."'				                     
				)";
		}
		mysql_query($query);	
	}
	
	function SetActive($active) {
		$this->Active = $active == "Y";
	}
	
	function Delete() {
		$query = "DELETE FROM projects WHERE ID = ".$this->ID;
		mysql_query($query);
	}
}

if(isset($_REQUEST['t'])) {
	global $app;
	$pr = new Project($app->user['id'], (isset($_REQUEST['id'])? $_REQUEST['id']: 0));
	switch ($_REQUEST['t']) {
		case 'save':
			$pr->ProjectNumber = $_REQUEST['projectnumber'];
			$pr->CRMName = $_REQUEST['crmname'];
			$pr->Name = $_REQUEST['name'];
			$pr->Active = $_REQUEST['active'] == 'on';
			$pr->IsBillable = $_REQUEST['isbillable'] == 'on';
			$pr->Order = $_REQUEST['order'];
			$pr->Save();
				
			echo "<script language=\"JavaScript\">document.location = '?a=projects';</script>";
			break;
		case 'delete':			
			$pr->Delete();
			break;
		case 'edit':
			if($pr->ID != 0) {
				$pr->LoadData();
			}
			$pr->RenderForm();
			break;
		case 'active':
			$pr->SetActive($_REQUEST['value']);
			$pr->Save();
			break;
	}
} else {
	$t = new Template();
	echo $t->fetch("template/projects.t");
}
?>
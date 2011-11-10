<?php
Class PWA {
	var $ID = 0;
	var $UserID = 0;
	var $TimeStamp = array();
	var $TaskID = 0;
	var $TimeSpent = 0;
	var $Description = "";
	var $Charged = false;

	var $taskData = array();

	function __construct($userID, $pwaID) {
		$this->UserID = $userID;
		$this->ID = $pwaID;
		$this->TimeStamp = array(date("Y"), date("m"), date("d"), date("H"), date("i"));		
	}

	function LoadData(){
		$query = "SELECT pw.*
		            FROM pwa pw                   
		            WHERE pw.id=".$this->ID." AND pw.UserID = ".$this->UserID;   
			
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);

		$this->TimeStamp = array(substr($row['TimeStamp'],0,4),
		substr($row['TimeStamp'],5,2),
		substr($row['TimeStamp'],8,2),
		substr($row['TimeStamp'],11,2),
		substr($row['TimeStamp'],14,2)
		);
		$this->TaskID = $row["Task"];
		$this->TimeSpent = $row["TimeSpent"];
		$this->Description = $row["Description"];
		$this->Charged = $row["Charged"] == "Y"?true:false;
	}

	function RenderForm() {
		$this->GetTasks();
		?>
	<p>
    <!-- Form to add new PWA -->
    <form method="post" action="index.php">    
        <table>
        
        <tr>
            <td>Task</td>
            <td>
                <select  dojoType="dijit.form.Select" maxHeight="200" name="task" style="width:550px;">
                <?php echo $this->RenderSelectTasks(); ?>
                 </select>
            </td>
        </tr>
        <tr>
            <td>Time spent (hours)</td>
            <td>
                <input dojoType="dijit.form.TextBox" style="width:5em;" type="text" name="timespent" value="<?php echo $this->TimeSpent ;?>"/>
            </td>
        </tr>
        <tr>
            <td>Date (dd.mm.yyyy)</td>
            <td>
                <input dojoType="dijit.form.TextBox" style="width:2em;" type="text" name="day" value="<?php echo $this->TimeStamp[2]; ?>"/ size="2" maxlength=2>
                <input dojoType="dijit.form.TextBox" style="width:2em;" type="text" name="month" value="<?php echo $this->TimeStamp[1]; ?>"/ size="2" maxlength=2>
                <input dojoType="dijit.form.TextBox" style="width:4em;" type="text" name="year" value="<?php echo $this->TimeStamp[0]; ?>"/ size="4" maxlength=4>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input dojoType="dijit.form.TextBox" style="width:2em;" type="text" name="hour" value="<?php echo $this->TimeStamp[3]; ?>"/ size="2" maxlength=2>
                <input dojoType="dijit.form.TextBox" style="width:2em;" type="text" name="minute" value="<?php echo $this->TimeStamp[4]; ?>"/ maxLength=2>
            </td>
        </tr>
        <tr>
            <td>Ax Charged</td>
            <td>
                <input dojoType="dijit.form.CheckBox" type="checkbox" name="charged" <?php if ($this->Charged) echo "checked";?>>
            </td>
        </tr>
        <tr>
            <td>Description</td>
            <td>
                <textarea dojoType="dijit.form.Textarea" data-dojo-props="rows: '5'" style="width:550px;" name="description"><?php echo $this->Description; ?></textarea>
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
	<input type="hidden" name="a" value="pwa"/>
	<input type="hidden" name="t" value="save"/>
	<input type="hidden" name="id" value="<?php echo $this->ID;?>"/>	
    <input type="hidden" name="userid" value="<?php echo $this->UserID; ?>">
    </form>        
	</p>	
	<?php
	}
	
	function RenderSelectTasks() {
		$data = "";
		foreach ($this->taskData as $row) {
			$data .= "<option value=\"".$row["ID"]."\"";
			if($this->ID != 0 && $this->TaskID == $row['ID']) {
				$data .= ' selected="selected"';
			}
			$data .= ">";
			if($row['Status'] == 'Closed') $data .= "[CLOSED] ";
			$data .= $row["ChangeNumber"]." - ".$row["Description"]."</option>";
		}	
		return $data;
	}
	
	function GetTasks() {
		$query = "SELECT ch.*, s.Value as Status
				  	FROM changes ch
		          		LEFT JOIN settings s ON s.id=ch.statusid
		                AND s.attribute LIKE 'CHG Status'
	              WHERE s.Value NOT LIKE 'Closed'
            	  AND ch.UserID = ".$this->UserID." 
	              ORDER BY ch.ChangeNumber";	
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			array_push($this->taskData, $row);
		}
	}
	
	function Save() {
		$query = "";
		$timestamp = date("Y-m-d H:i:s", mktime($this->TimeStamp[3], 
		                                        $this->TimeStamp[4], 
		                                        0, 
		                                        $this->TimeStamp[1],
		                                        $this->TimeStamp[2], 
		                                        $this->TimeStamp[0]));
		$description = mysql_real_escape_string($this->Description);
		
		if($this->ID != 0){
			$query = "UPDATE pwa SET UserID = ".$this->UserID.", 
			                         TimeStamp = '".$timestamp."', 
			                         Task = ".$this->TaskID.", 
			                         TimeSpent = '".$this->TimeSpent."', 
			                         Description = '".$description."', 
			                         Charged = '".($this->Charged?"Y":"N")."'
			                     WHERE ID = ".$this->ID;
		} else {
			$query = "INSERT INTO pwa (UserID, TimeStamp, Task, TimeSpent, Description, Charged)
						       VALUES (
						         ".$this->UserID.", 
			                     '".$timestamp."', 
			                     ".$this->TaskID.", 
			                     '".$this->TimeSpent."',
			                     '".$description."',
			                     '".($this->Charged?"Y":"N")."'
			)";
		}							
		mysql_query($query);
	
	}
	
	function Charge(){
		$query = "UPDATE pwa
			SET Charged = '".($this->Charged?"Y":"N")."'                        
			WHERE ID = ".$this->ID;
		mysql_query($query);		
	}
	
	function Delete() {
		$query = "DELETE FROM pwa WHERE ID = ".$this->ID;
		mysql_query($query);
	}
}

if(isset($_REQUEST['t'])) {
	global $app;
	$pwa = new PWA($app->user['id'], (isset($_REQUEST['id'])? $_REQUEST['id']: 0));
	switch ($_REQUEST['t']) {	
		case 'save':
			$pwa->TaskID = $_REQUEST['task'];
			$pwa->TimeSpent = $_REQUEST['timespent'];
			$pwa->Description = $_REQUEST['description'];
			$pwa->Charged = $_REQUEST['charged'] == 'on';
			$pwa->TimeStamp = array($_REQUEST['year'],$_REQUEST['month'], $_REQUEST['day'], $_REQUEST['hour'], $_REQUEST['minute']);	
			$pwa->Save();
			
			echo "<script language=\"JavaScript\">document.location = 'index.php?a=pwa';</script>";
			break;
		case 'charge':
			$pwa->Charged = !($_REQUEST['value']=='Y');		
			$pwa->Charge();				
			break;
		case 'edit':
			if($pwa->ID != 0) {
				$pwa->LoadData();
			}
			$pwa->RenderForm();
			break;
		case 'delete':
			$pwa->Delete();
			break;
	}
} else {
    $t = new Template(); 
    echo $t->fetch("template/pwa.t");
}
?>

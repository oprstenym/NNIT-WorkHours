<?php
    global $app;
?>
<h2>Assigned projects data</h2>
<button dojoType="dijit.form.Button" type="button" onclick="document.location = 'index.php?a=projects&t=edit';">ADD NEW</button>
<p>
<!-- List of PWAs -->
    <table border=1px cellspacing=0 width="100%">
    <tr>
    <th>Project Number</td>
    <th>CRM Name</td>
    <th>Name</td>
    <th>Is Active</td>
    </tr>
    <?php
        global $app;
                $query = "SELECT id, projectnumber, crmname, name, active
                    FROM projects                    
                    WHERE UserID = ".$app->user['id']."
                    ORDER BY projectnumber";                    
                $result = @mysql_query($query);
                 while ($row = @mysql_fetch_array($result)) {
                    $style = $row["active"]=='N' ? " style=\"background-color:pink;\"" : " style=\"background-color:lightgreen;\"";
                    echo "<tr>
                    <td><a href=\"?a=projects&t=edit&id=".$row["id"]."\" >".$row["projectnumber"]."</a></td>
                    <td>".$row["crmname"]."</td>
                    <td>".$row["name"]."</td>
                    <td".$style.">".$row["active"]."</td>
                    </tr>";
                } 
            ?>
    </table>
</p>
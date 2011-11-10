<?php
    global $app;
?>
<h2>Assigned Tasks</h2>
<button dojoType="dijit.form.Button" type="button" onclick="document.location = 'index.php?a=tasks&t=edit';">ADD NEW</button>
<p>
<!-- List of PWAs -->
    <table border=1px cellspacing=0 width="100%">
    <tr>
    <th>CHG</td>
    <th>Project</td>
    <th style="width:110px;">Status</td>
    <th style="width:50px;">EST</td>
    <th style="width:50px;">CUR</td>
    <th style="width:50px;">REL</td>
    <th style="width:20px;">C/O</td>
    <th>FUNC</td>
    </tr>
    <?php
   function hours_spent_percentage ($current, $estimated) {
    if($estimated == 0) {
        if ($current > 0) 
            return 1;
        else return 0;
    }
   return $current / $estimated;
    }   
    
    
                $query = "SELECT ch.*, pr.CRMName, s1.Value as `Status`, co.Color as StatusColor, s2.Value as `Release`, SUM(pw.timespent) as CUR
                    FROM changes ch
                    LEFT JOIN projects pr ON ch.projectid = pr.id
                    LEFT JOIN settings s1 ON s1.id = ch.statusid
                    LEFT JOIN colors co ON co.AttributeID = s1.ID
                    LEFT JOIN settings s2 ON s2.id = ch.releaseid
                    LEFT JOIN pwa pw ON pw.task = ch.id
                    WHERE ch.UserID = ".$app->user['id']."
                    GROUP BY ch.id
                    ORDER BY ch.changenumber";     
                            
                $result = mysql_query($query);                
                
                while ($row = @mysql_fetch_array($result)) {
                $linestyle ^= 1;
                $style =  "style=\"background-color:".$row['StatusColor'].";\"";
                $co_style = $row["Cutover"] == 'Y' ? "style=\"background-color: ".Constants::$Colors['CutOver'].";\"" : "class=\"linestyle".$linestyle."\"";

                $current_hours = isset($row["CUR"])?$row["CUR"]:0;

                $hours_percentage = hours_spent_percentage($current_hours,$row["EstHours"]);
                if($hours_percentage <= 0.4) {
                    $hours_style = "style=\"background-color: ".Constants::$Colors['TaskRemainingHours_OK'].";\"";
                } elseif ($hours_percentage > 0.4 && $hours_percentage <= 0.95) {
                    $hours_style = "style=\"background-color: ".Constants::$Colors['TaskRemainingHours_WARNING'].";\"";
                } else {
                    $hours_style = "style=\"background-color: ".Constants::$Colors['TaskRemainingHours_BAD'].";\"";
                }                             
                
                 $max_description_len = 40;
                 $description = "";
                 $description = strlen($row["Description"]) > $max_description_len ? substr($row["Description"],0,$max_description_len)."..." : $row["Description"]; 
                    echo "<tr>
                    <td class=\"linestyle".$linestyle."\"><a href=\"?a=tasks&t=edit&id=".$row['ID']."\">".$row["ChangeNumber"]." - ".$description."</a></td>
                    <td class=\"linestyle".$linestyle."\">".(isset($row["CRMName"]) ? $row["CRMName"]:"&nbsp;")."</td>
                    <td $style>".(isset($row["Status"])?$row["Status"]:"&nbsp;")."</td>
                    <td class=\"linestyle".$linestyle."\">".$row["EstHours"]."h</td>
                    <td $hours_style>".$current_hours."h</td>
                    <td class=\"linestyle".$linestyle."\">".(isset($row["Release"])?$row["Release"]:"&nbsp;") ."</td>
                    <td $co_style>".$row["Cutover"] ."</td>
                    <td class=\"linestyle".$linestyle."\">".(isset($row["Functional"]) && $row["Functional"]!=''?$row["Functional"]:"&nbsp;")."</td>
                    </tr>";
                } 
            ?>
    </table>
</p>
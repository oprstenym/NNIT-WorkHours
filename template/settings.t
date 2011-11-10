<html>
<head>
<title>Task registration</title>
</head>
<body>
<h1>Project Time Registration</h1>
<table cellspacing=0>
<tr>
    <td style="vertical-align:top; width:150px; background-color:#dddddd;border-left:1px solid black; border-top:1px solid black; border-bottom:1px solid black;padding:5px;">    
    <a href="projects.php">Projects</a><br/>
    <a href="tasks.php">Tasks</a><br/>
    <a href="index.php">PWA</a><br/>
    <a href="settings.php">Settings</a><br/>
    </td>
    <td style="vertical-align:top; width:150px; background-color:#aaaaaa;border:1px solid black;">
    <form method="post">    
        <table>
        <tr>
            <td>Project</td>
            <td>
                <select name="project">
                    <option value="1">SRM Development (E3D)</option>
                    <option value="2">SRM Development (E1D)</option>
                    <option value="3">SRM Internal Meetings</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Task</td>
            <td>
                <select name="task">
                    <option value="1">SRM Development (E3D)</option>
                    <option value="2">SRM Development (E1D)</option>
                    <option value="3">SRM Internal Meetings</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Time spent (hours)</td>
            <td>
                <input type="text" name="timespent" value="0"/>
            </td>
        </tr>
        <tr>
            <td>Description</td>
            <td>
                <textarea name="description" rows=5 cols=65></textarea>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <button type="submit">Save</button>
                <button type="reset">Reset</button>
            </td>            
        </tr>
        </table>
    </form>
    </td>
</tr>    
</table>    
</body>
</html>
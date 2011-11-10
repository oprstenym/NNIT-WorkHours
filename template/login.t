<html>
<head>
<title>
NNIT Project Time Registration 2011
</title>
</head>
<body>
<h1>NNIT Project Time Registration 2011</h1>
<br /><br /><br />
<div style="text-align:center;">
<form action="index.php" method="post">
<table style="border:0px none">
<?php
if(isset($error)) {
    echo "<tr><td colspan=2 style=\"color:red;\">Login credentials are not correct</td></tr>";
}
?>
<tr>
<td>Login name (Initials)</td>
<td><input type="text" name="username"/ value="">
</td>
</tr>
<tr>
<td>Password</td>
<td>
<input type="password" name="password" value=""/> 
</td>
</tr>
<tr>
<td colspan="2" style="text-align:center;"> 
<button type="submit">LOGIN</button>
</td>
</tr>
</table>
<input type="hidden" name="a" value="pwa">
</form>
</div>
</body>
</html>
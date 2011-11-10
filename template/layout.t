<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>NNIT Project Time Registration</title>
        <link rel="stylesheet" href="./css/style.css" media="screen">
        <link rel="stylesheet" href="./js/dijit/themes/claro/claro.css" media="screen">
        <!-- load dojo and provide config via data attribute -->
        <script src="js/tasks.js"></script>
        <script src="js/dojo/dojo.js" data-dojo-config="isDebug: true,parseOnLoad: true"></script>
        <script>
            dojo.require("dijit.layout.BorderContainer");
            dojo.require("dijit.layout.TabContainer");
            dojo.require("dijit.layout.ContentPane");
            dojo.require("dijit.Dialog");
            dojo.require("dijit.Menu");

            dojo.require("dijit.form.Textarea");
            dojo.require("dijit.form.CheckBox");
            dojo.require("dijit.form.TextBox");
            dojo.require("dijit.form.Select");
            dojo.require("dojox.grid.DataGrid");
            dojo.require("dojo.data.ItemFileReadStore");
            dojo.require("dijit.form.Form");
    </script>   
    </head>
    <body class="claro">                    
           <div id="appLayout" class="demoLayout" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props="design: 'headline'">
			<!-- <div class="centerPanel" data-dojo-type="dijit.layout.TabContainer" data-dojo-props="region: 'center', tabPosition: 'bottom'">-->
                    <div class="centerPanel" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="region:'center', title: 'PWA'">
            <?php
$t = new Template();            
 echo $t->fetch("./utils/".$action.".php");
?>
                    </div>
                <!--</div>-->
			<div class="edgePanel" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="region: 'top'"><h1><img src="./img/nnit_logo.jpg" style="height:45px; float:right;"/>Project Time Registration</h1></div>
			<div id="leftCol" class="edgePanel" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="region: 'left', splitter: true" style="width:120px;">
                    <button dojoType="dijit.form.Button" type="button" onclick="document.location='?a=pwa';">PWA</button><br/>
                    <button dojoType="dijit.form.Button" type="button" onclick="document.location='?a=tasks';">Tasks</button><br/> 
                    <button dojoType="dijit.form.Button" type="button" onclick="document.location='?a=projects';">Projects</button><br />                                       
                    <button dojoType="dijit.form.Button" type="button" onclick="document.location='?a=reports'">Reports</button><br />
                </div>
		</div>
    </body>
</html>
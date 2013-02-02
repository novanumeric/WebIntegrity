<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<link rel="stylesheet" type="text/css" href="novanumeric.css" />
<link rel="stylesheet" type="text/css" href="inc/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="inc/jquery.jqplot.css" />
<style type="text/css">

		#mouseon-examples div {
			background-color: #EEE;
			text-align: center;
			width: 400px;
			padding: 40px;
		}
	</style>
	<link rel="stylesheet" type="text/css" href="inc/plugins/jquery.powertip.css" />
<TITLE>API 579 General Metal Loss(GML) and Local Metal Loss (LTA)</TITLE>
</HEAD>

<BODY>
<? if(file_exists("../inc/header.php")) { require "../inc/header.php"; }?>
<? require "includes/header579.php"; ?>
<? require "includes/pipeSchedules.php"; ?>


<script language="javascript" type="text/javascript" src="inc/excanvas.js"></script>
<script language="javascript" type="text/javascript" src="inc/jquery-1.8.3.js"></script>
<script language="javascript" type="text/javascript" src="inc/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="inc/jquery-ui.js"></script>

<script type="text/javascript" src="inc/plugins/jquery.powertip.js"></script>
<script language="javascript" type="text/javascript" src="inc/plugins/jqplot.cursor.min.js"></script>
<script language="javascript" type="text/javascript" src="inc/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="inc/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />

<script src="lib/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>

<script src="includes/calculateCOV.js" type="text/javascript"></script>
<script src="includes/calculateCTPS.js" type="text/javascript"></script>
<script src="includes/calculateGMLCTPS.js" type="text/javascript"></script>
<script src="includes/calculateCodeCalcs.js" type="text/javascript"></script>
<script src="includes/calculateLTACTPS.js" type="text/javascript"></script>
<script src="includes/displayResults.js" type="text/javascript"></script>
<script src="includes/sharedJavascript.js" type="text/javascript"></script>
<script src="includes/updateDisplay.js" type="text/javascript"></script>
<script src="includes/load.js" type="text/javascript"></script>

<br><br><br><br><br>
<? require "includes/inputsSpan.php"; ?>
<br>
<div id="PTRInputDataSpan" class="ui-widget-content" style="position:absolute;border-style:solid;border-color:black;border-width:1px;">
Point Thickness Readings <span id="PTRInputDataSpanUnits"><br></span>
<span style="font-size:12px">(Recommended minimum 15 sample):<br></span>
<textarea rows="5" cols=25 size=140 id="PTRInputData"></textarea>
<span id="PTRCovResult">
<img id="PTRCovWork"  src=""><br>
</span>
<div id="PTRWarning" style="color:red"></div>
</div>
<br><br>
<? require "includes/GridDataSpan.php"; ?>
<br>
<div id="CTPInputSpan"  class="ui-widget-content" style="position:absolute;border-style:solid;left:510px;top:80px;border-color:black;border-width:1px;"><img id="CTPInputImg"  src=""></div>
<div id="CTPInputCSpan" class="ui-widget-content" style="position:absolute;border-style:solid;left:10px;border-color:black;border-width:1px;white-space: nowrap;"> 
<label for="CTPCDataC">CTP Circumferential Plane (C Projected): </label><br><textarea rows="3" cols=60 size=140 type="text" id="CTPInputC" ></textarea><br><img id="CTPInputCImg"  src="">
</div>

<div id="CTPInputMSpan" class="ui-widget-content" style="position:absolute;border-style:solid;left:500px;border-color:black;border-width:1px;white-space: nowrap;"> 
<label for="CTPMDataM">CTP Longitudinal Plane (M Projected): </label><br><textarea rows="3" cols=60 size=140 type="text" id="CTPInputM" ></textarea><br><img id="CTPInputMImg" src="">
</div>

<? require "includes/ResultsSpan.php"; ?>



</BODY>
</HTML>
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
<TITLE>ASME B31G Calculations</TITLE>
</HEAD>

<BODY>
<script language="javascript" type="text/javascript" src="inc/excanvas.js"></script>
<script language="javascript" type="text/javascript" src="inc/jquery-1.8.3.js"></script>
<script language="javascript" type="text/javascript" src="inc/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="inc/jquery-ui.js"></script>

<script type="text/javascript" src="inc/plugins/jquery.powertip.js"></script>
<script language="javascript" type="text/javascript" src="inc/plugins/jqplot.cursor.min.js"></script>
<script language="javascript" type="text/javascript" src="inc/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="inc/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>


<? if(file_exists("../inc/header.php")) { require "../inc/header.php"; }?>
<? require "includes/headerB31G.php"; ?>
<? require "includes/pipeSchedules.php"; ?>
<script src="includes/sharedJavascript.js" type="text/javascript"></script>


<div style="position:absolute;background-color:white;left:25px;top:100px;">
<span><b  style="color:blue">Level 1 B31G Calculations:<br>The following procedures calculates the maximum pressure for damaged piping per ASME B31G-2009.</b></span>

<table style="background-color:white;">
<tr><td><span id="HelpUnits" title="Select if units are english or metric">Units</span></td><td ><select id="selectUnits"><option>English<option selected>Metric-MPa<option>Metric-Bar</select></td></tr>
<tr><td><span id="HelpMethod"  title="Method to calculate magnification factor">Method</td><td><select  id="Method"><option value="Original">Original<option value="Modified" selected>Modified</select></td><td></td><td><span style="color:red" id="depthMessage"></span></td></tr>
<tr><td><span id="HelpDia" title="D = specified outside diameter of the pipe">Diameter (D)</span></td><td><input type="text" id="CalcsDiameter" size=6 value="609.6"></td><td> <span id="DIAUNITS">(in)</span></td></tr>
<tr><td><span id="Helpt" title="t = pipe wall thickness">Thickness (t)</span></td><td><input type="text" id="CalcsUniformThickness" size=6 value="11.3"></td><td> <span id="TUNITS">(in)</span></td></tr>
<tr><td><span id="Helpd" title="d = depth of metal loss">Depth (d)</span></td><td><input type="text" id="d"  size=6 value="4"></td><td> <span id="DUNITS">(in)</span> </td><td rowspan=2><img id="ShowPipeScheduleLookup" title="Click here to select geometry based upon pipe schedule" src="images/pipeSchedule.png" alt="Click here to lookup Pipe Schedule" width=50></td></tr>
<tr><td><span id="HelpL" title="L = length of metal loss">Length (L)</span></td><td><input type="text" id="l"  size=6 value="210"></td><td> <span id="LUNITS">(in)</td></tr>
<tr><td><span id="HelpSflow" title="Sflow =<br>
										   (b.1) min(1.1xSMYS,SMTS) for plain Carbon Steel less than 120°C (120°F)<br>
										   (b.2) = SMYS + 69 MPa (10 ksi) for plain carbon and low alloy steel having<br>
										         SMYS not in excess of 70 ksi (483 MPa)<br> 
											 and operating at temperatures below 120°C (250°F)<br>
											(b.3) = (S<sub>YT</sub>+S<sub>UT</sub>/2) ASME B31G 2009 1.7(b)">S<sub>flow</sub></span></td><td><input type="text" id="Sflow"  size=6 value="554"></td><td> <span id="SFLOWUNITS">(MPa)</span></td></tr>
</table>

	
<div style="position:absolute>
</div>


<span id="target" style="color:blue;">
  <input type="button" text="Calc" id="calc" value="Calc">
</span>
<br>

<span>Pf <span id="MAWP"></span></span>
<br>

<br>
<span >
Proof of Calculation:<br><br>
<img id="work"  src="">
</span>

</div>
<!-- <span style="position:absolute;left:50px;top:550px">Numbers matched (*except case 2) for <a target="_new" href="http://www.asme-ipti.org/attachments/files/646/Track%204%20Ben%20Verhagen.pdf">"VALIDATON OF THE ASME B31G AND RSTRENG METHODOLOGIES FOR THE DETERMINATION OF THE BURST PRESSURE OF CORRODED PIPES IN API 5L X70 / EN 10208-2 L485"</a></span>-->
<script>
function calculate(){
	
	if($("#selectUnits").val()=="English") {
		$("#DIAUNITS").html("(in)");
		$("#DUNITS").html("(in)");
		$("#TUNITS").html("(in)");
		$("#LUNITS").html("(in)");
		$("#SFLOWUNITS").html("(psi)");
		$("#MAWPUNITS").html("(psi)");
	} else { 
		$("#DIAUNITS").html("(mm)");
		$("#DUNITS").html("(mm)");
		$("#TUNITS").html("(mm)");
		$("#LUNITS").html("(mm)");
		$("#SFLOWUNITS").html("(MPa)");
	}


	if($("#d").val()/$("#CalcsUniformThickness").val()>0.8) {
		$("#depthMessage").html("Depth exceed 80%, see ASME B31.G 1.2(f)");
	} else {
		$("#depthMessage").html("");
	};
	$("#work").attr("src","");
	$("#MAWP").html("#####");
	$.getJSON('functions/B31G.py', { Method: $("#Method").val(),d: $("#d").val() ,dia: $("#CalcsDiameter").val() ,t:$("#CalcsUniformThickness").val(), l:$("#l").val(), Sflow:$("#Sflow").val() },
	  function(data) {
		$("#work").attr("src","functions/render.php?equation=" + encodeURIComponent(data.Equation));
		if($("#selectUnits").val()=="English") {
			$("#MAWP").html(Math.floor(data.MAWP) + " (psi)");
		}else if($("#selectUnits").val()=="Metric-MPa") {
			$("#MAWP").html(Math.floor(data.MAWP) + " (MPa)");
		} else {
			$("#MAWP").html(Math.floor(data.MAWP*10) + " (bar)");	
		}
	  });
	displayNPS();
};

$(function() {

	$.ajax(
	{
		type: 'POST', 
		url:'functions/pipeSchedules.php',
		async: false,
		dataType: "json",
		data:{RequestType:'NPS'}
	}).done(function displayNPS(data) {
		$("#selectNPS").html("");
		$.each(data, function(key, value) {
		$("#selectNPS").append("<option value=\'"+value+"\'>"+value);
		});
	});
	displayNPS();
	$('#HelpUnits').powerTip({placement: 'ne',	mouseOnToPopup: true});
	$('#HelpMethod').powerTip({placement: 'ne',	mouseOnToPopup: true});
	$('#HelpDia').powerTip({placement: 'ne',	mouseOnToPopup: true});
	$('#Helpt').powerTip({placement: 'ne',mouseOnToPopup: true});
	$('#Helpd').powerTip({placement: 'ne',mouseOnToPopup: true});
	$('#HelpL').powerTip({placement: 'ne',mouseOnToPopup: true});
	$('#HelpSflow').powerTip({placement: 'ne',mouseOnToPopup: true});
	$('#ShowPipeScheduleLookup').powerTip({placement: 'ne',mouseOnToPopup: true});
	$("#selectNPS").change(displayNPS);
	$("#selectSchedules").change(displayGeometry);
	$('#selectUnits').change(function test() {displayNPS(); calculate();});
	$('#Method').change(calculate);
	$('#selectUnits').val(getParameterByName('selectUnits'),'English');
	$('#CalcsDiameter').val(getParameterByName('CalcsDiameter',30));
	$('#CalcsUniformThickness').val(getParameterByName('CalcsUniformThickness',5));
	$('#d').change(calculate);
	$('#CalcsUniformThickness').change(calculate);
	$('#l').change(calculate);
	$('#Sflow').change(calculate);
	$('#calc').click(calculate);
	setupLinks();
	$(document).ready(calculate);
});



$("#ClosePipeScheduleLookup").click(function () {
	$("#PipeScheduleLookup").hide(); 
});

$("#btnSelect").click(function () {
	$("#CalcsDiameter").val($("#ValOD").html());
	$("#CalcsUniformThickness").val($("#ValThick").html());
	$("#PipeScheduleLookup").hide(); 
});


$("#ShowPipeScheduleLookup").click(function () {
	displayNPS();
	$("#PipeScheduleLookup").show();
	$("#PipeScheduleLookup").draggable();
});



</script>
</HTML>
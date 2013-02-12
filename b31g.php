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
<span><b  style="color:blue">B31G Calculations:<br>The following procedures calculate the maximum pressure for damaged piping per ASME B31G-2009.</b></span><br>
<span id="depthMessage" style="color:red"></span>
<table >

<tr><td valign="top">
<img src="images/pipe.png"><br>
<img src="images/flaw.png"><br>
  <input type="button" text="Calc" id="calc" value="Calc">
</td><td valign="top">
<table style="background-color:white;">
<tr><td><span class="WithHints" title="Select if units are english or metric">Units</span></td><td ><select class="Calculate" id="selectUnits"><option>English<option selected>Metric-MPa<option>Metric-Bar</select></td></tr>
<tr><td><span class="WithHints"  title="Level">Level</td><td><select class="Calculate" id="selectLevel"><option value="Level1" selected>1<option value="Level2" >2</select></td><td></td></tr>
<tr id="selectMethodRow"><td ><span class="WithHints"  title="Method to calculate magnification factor">Method</td><td><select class="Calculate" id="selectMethod"><option value="Original">Original<option value="Modified" selected>Modified</select></td><td></td><td><span style="color:red" id="depthMessage"></span></td></tr>
<tr><td><span class="WithHints" title="D = specified outside diameter of the pipe">Diameter (D)</span></td><td><input class="Calculate" type="text" id="CalcsDiameter" size=6 value="609.6"></td><td> <span class="LengthUnits">(in)</span></td><td rowspan=2><img id="ShowPipeScheduleLookup" title="Click here to select geometry based upon pipe schedule" src="images/pipeSchedule.png" alt="Click here to lookup Pipe Schedule" width=50></td></tr>
<tr><td><span class="WithHints" title="t = pipe wall thickness">Thickness (t)</span></td><td><input type="text" class="Calculate" id="CalcsUniformThickness" size=6 value="11.3"></td><td> <span class="LengthUnits">(in)</span></td></tr>
<tr id="PitDepthsLevel1"><td><span class="WithHints" title="d = Maximum Depth of Metal Loss">Depth (d)</span></td><td><input type="text" class="Calculate" id="CalcsDepth"  size=6 value="4"></td><td> <span class="LengthUnits">(in)</span> </td></tr>
<tr><td><span class="WithHints" title="L = length of metal loss">Length (L)</span></td><td><input type="text" class="Calculate" id="CalcsLength"  size=6 value="210"></td><td> <span class="LengthUnits">(in)</td></tr>
<tr><td><span class="WithHints" title="Sflow =<br>
										   (b.1) min(1.1xSMYS,SMTS) for plain Carbon Steel less than 120�C (120�F)<br>
										   (b.2) = SMYS + 69 MPa (10 ksi) for plain carbon and low alloy steel having<br>
										         SMYS not in excess of 70 ksi (483 MPa)<br> 
											 and operating at temperatures below 120�C (250�F)<br>
											(b.3) = (S<sub>YT</sub>+S<sub>UT</sub>/2) ASME B31G 2009 1.7(b)">S<sub>flow</sub></span></td><td><input type="text" id="Sflow"  size=6 value="554"></td><td> <span class="StressUnits">(MPa)</span></td></tr>
</table></td>
<td>
<table id="PitDepthsLevel2">
  <tr><td>Increment<br>S <span class="LengthUnits"></span></td><td>Pit Depth<br>d <span class="LengthUnits"></span></td></tr>
  <tr><td><textarea id="CTPInputMLengths" rows=20 cols=5></textarea></td><td><textarea id="CTPInputM" rows=20 cols=5></textarea></td></tr>
</table>
</td>
<td valign="top">
<img id="work"  src="">
</td>
</tr>
</table>

</div>
<!-- <span style="position:absolute;left:50px;top:550px">Numbers matched (*except case 2) for <a target="_new" href="http://www.asme-ipti.org/attachments/files/646/Track%204%20Ben%20Verhagen.pdf">"VALIDATON OF THE ASME B31G AND RSTRENG METHODOLOGIES FOR THE DETERMINATION OF THE BURST PRESSURE OF CORRODED PIPES IN API 5L X70 / EN 10208-2 L485"</a></span>-->
<script>
$StressUnits="";
$LengthUnits="";
$PressureUnits="";
function calculate(){
	
	if($("#selectUnits").val()=="English") {
		$LengthUnits="in";
		$PressureUnits="psi";
		$StressUnits="psi";
	} else if($("#selectUnits").val()=="Metric-MPa") {
		$LengthUnits="mm";
		$PressureUnits="MPa";
		$StressUnits="MPa";
	} else {
		$LengthUnits="mm";
		$PressureUnits="Bar";
		$StressUnits="MPa";
	}
	$(".LengthUnits").html("["+$LengthUnits+"]");	
	$(".StressUnits").html("["+$StressUnits+"]");	
	$(".PressureUnits").html("["+$PressureUnits+"]");
	if($("#selectLevel").val()=="Level2") {
		$("#selectMethodRow").hide();
	} else {
		$("#selectMethodRow").show();	
	}
	if($("#selectLevel").val()=="Level1") {
		$("#PitDepthsLevel1").show();
		$("#PitDepthsLevel2").hide();

	} else {
		$("#PitDepthsLevel1").hide();
		$("#PitDepthsLevel2").show();
	}
	

	$("#work").attr("src","");
	$("#MAWP").html("#####");
	$.getJSON('functions/calculateB31G.php', 
		{ 
		selectMethod: $("#selectMethod").val(),
		selectUnits: $("#selectUnits").val(),
		CalcsDepth: $("#CalcsDepth").val() ,
		CalcsDiameter: $("#CalcsDiameter").val() ,
		CalcsUniformThickness:$("#CalcsUniformThickness").val(), 
		selectLevel:$("#selectLevel").val(), 
		CalcsLength:$("#CalcsLength").val(), 
		Sflow:$("#Sflow").val(),
		CTPInputM:$("#CTPInputM").val(),
		CTPInputMLengths:$("#CTPInputMLengths").val(),
		PressureUnits:$PressureUnits,
		LengthUnits:$LengthUnits,
		StressUnits:$StressUnits
		},
	  function(data) {
		$("#work").attr("src","functions/render.php?equation=" + encodeURIComponent(data.Equation));

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
	
	$('.WithHints').powerTip({placement: 'ne',	mouseOnToPopup: true});
	$("#selectNPS").change(displayNPS);
	$("#selectSchedules").change(displayGeometry);
	$('#selectUnits').change(function test() {displayNPS(); calculate();});
	$('#selectUnits').val(getParameterByName('selectUnits','English'));	
	$('#CalcsDiameter').val(getParameterByName('CalcsDiameter',60));
	$('#CalcsDiameter').val(getParameterByName('CalcsUniformThickness',5));
	$('.Calculate').change(calculate);
	
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

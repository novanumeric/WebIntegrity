LengthUnits="Unset";
DisableEvents=false;
tamMinusFCA=0.1;
tamc=0;
tams=0;
tam=0;
MAWP=0;
MAWPL=0;
MAWPC=0;
tmin=0;
tminc=0;
tmm=0;
FCA=0;
RSFC=0;
RSFM=0;
RSF=0;
KEllipsoide=0;
Lkc=0;
M=0;

$(function() {
	$.ajax(
	{
		type: 'POST', 	url:'functions/pipeSchedules.php',
		async: false, 	dataType: "json",
		data:{RequestType:'NPS',NPS:"",selectUnits:""}
	}).done(function displayNPS(data) {
		$("#selectNPS").html("");
		$.each(data, function(key, value) {
		$("#selectNPS").append("<option value=\'"+value+"\'>"+value);
		});
	});
	$('#CalcsUniformThickness').val(getParameterByName('CalcsUniformThickness',5));
	$('#selectInputType').val(getParameterByName('selectInputType',"Thickness Grid(GRID)"));
	$('#selectCriteria').val(getParameterByName('selectCriteria',"MAWP"));
	$('#selectShellType').val(getParameterByName('selectShellType',"Sphere"));	
	$('#GeneralMetalLossThickMethodType').val(getParameterByName('GeneralMetalLossThickMethodType',"Custom"));	
	
	$('#selectAnalysisProcedure').val(getParameterByName('selectAnalysisProcedure',"Part 3: Brittle Fracture"));
	$('#GeneralMetalLossThickMethodType').val(getParameterByName('GeneralMetalLossThickMethodType',"Calculate"));
	$('#CalcsDiameter').val(getParameterByName('CalcsDiameter',30.0));
	$('#selectUnits').val(getParameterByName('selectUnits','English'));
	$('.updateDisplay').change(updateDisplay);
	
	
	
	$("#GeneralMetalLossThickMethodType").change(updateDisplay);
	$('#btnUpdate').click(updateDisplay);
//	$('#PTRInputData').keypress(calculateCOV);
//	$("#CTPInputM").keypress(updateDisplay);
//	$("#CTPInputC").keypress(updateDisplay);	
//	$("#GridInputData").keypress(updateDisplay);
	$("#ThicknessCalcsCorrosionAllowance").change(updateDisplay);
	$("#selectNPS").change(displayNPS);
	$("#selectSchedules").change(displayGeometry);
	$(".ShowPipeScheduleLookup").click(function () { 
	//	displayNPS();
		$("#PipeScheduleLookup").show();
		$("#PipeScheduleLookup").draggable();
	});
	$("#ClosePipeScheduleLookup").click(function () {
		$("#PipeScheduleLookup").hide(); 
	});
	
	$(".ui-widget-content").draggable();

	$("#btnSelect").click(function () {
		$("#CalcsDiameter").val($("#ValOD").html());
		$("#CalcsUniformThickness").val($("#ValThick").html());
		$("#PipeScheduleLookup").hide(); 
	});
	$("#showMaterialFilters").click(function () {
		if($("#showMaterialFilters").html()=="Filters") {
			$("#showMaterialFilters").html("Close");
			$(".MaterialFilters").show();
		} else {
			$("#showMaterialFilters").html("Filters");
			$(".MaterialFilters").hide();
		}

	});

	updateDisplay();
	setupLinks();
});
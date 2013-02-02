

function updateDisplay() {
	if(DisableEvents==true) {
		return;
	}
	
	if($("#selectAnalysisProcedure").val().indexOf("Local Thickness Area - LTA")>0) {
		$("#selectCriteria").val("MAWP");		
		$("#FFSCriteriaType").hide();
	} else {
		$("#FFSCriteriaType").show();
	}
	$temp=$("#selectDesignCode").val();
	$("#selectDesignCode").html("");

	if($("#selectShellType").val()=="Cylinder") {
		$("#selectDesignCode").append("<option>ASME SVIII-I");
	} else if( $("#selectShellType").val()=="Elbow" || $("#selectShellType").val()=="Pipe") {
		$("#selectDesignCode").append("<option>ASME B31.1");
		$("#selectDesignCode").append("<option>ASME B31.3");
	} else {
		$("#selectDesignCode").append("<option>ASME SVIII-I");
	}
	$("#selectDesignCode").val($temp);

	
	DisableEvents=true
	$('.WithHints').powerTip({placement: 'ne',	mouseOnToPopup: true});
	if($("#selectUnits").val()=="English") {
		window.LengthUnits="in";
		window.StressUnits="ksi";
		window.PressureUnits="psi";	  
		window.TemperatureUnits="F";	 
	} else if($("#selectUnits").val()=="Metric-Bar") { 
		window.LengthUnits="mm";
		window.StressUnits="MPa";
		window.PressureUnits="bar";	
		window.TemperatureUnits="C";	 
	} else { 
		window.LengthUnits="mm";
		window.StressUnits="MPa";
		window.PressureUnits="MPa";	
		window.TemperatureUnits="C";
	}
	
	$("#ShellTypeImage").attr("src","images/"+$("#selectShellType").val()+".png");
	
	if($("#selectShellType").val()=="Sphere") {
		$("#VesselID").show();
		$("#PipeOD").hide();
		$("#CalcsAngleRow").hide();
		$("#CalcsCrownRadiusRow").hide();
		$("#CalcsKnuckleRadiusRow").hide();
		$("#CalcsInputTminSpan").show();
		$("#CalcsInputTminCSpan").hide();
		$("#CalcsInputTminLSpan").hide();
		$("#CalcsLocationRow").hide();
		$("#CalcsRatioRow").hide();
		$("#ShowPipeScheduleLookup").hide();
		$(".CalcsPipeElbow").hide();
	}else if($("#selectShellType").val()=="Cylinder")  {
		$("#VesselID").show();
		$("#PipeOD").hide();
		$("#CalcsAngleRow").hide();
		$("#CalcsKnuckleRadiusRow").hide();
		$("#CalcsCrownRadiusRow").hide();			
		$("#CalcsInputTminSpan").hide();
		$("#CalcsInputTminCSpan").show();
		$("#CalcsInputTminLSpan").show();
		$("#CalcsRatioRow").hide();
		$("#CalcsLocationRow").hide();
		$("#ShowPipeScheduleLookup").hide()
		$(".CalcsPipeElbow").hide();
		displayNPS();
	}else if($("#selectShellType").val()=="Pipe")  {
		$("#VesselID").hide();
		$("#PipeOD").show();
		$("#CalcsAngleRow").hide();
		$("#CalcsKnuckleRadiusRow").hide();
		$("#CalcsCrownRadiusRow").hide();			
		$("#CalcsInputTminSpan").hide();
		$("#CalcsInputTminCSpan").show();
		$("#CalcsInputTminLSpan").show();
		$("#CalcsLocationRow").hide();
		$("#CalcsRatioRow").hide();
		$("#ShowPipeScheduleLookup").show();
		$(".CalcsPipeElbow").hide();
		displayNPS();
	}else if($("#selectShellType").val()=="Elbow")  {
		$("#VesselID").hide();
		$("#PipeOD").show();
		$("#CalcsAngleRow").hide();
		$("#CalcsKnuckleRadiusRow").hide();
		$("#CalcsCrownRadiusRow").hide();			
		$("#CalcsInputTminSpan").hide();
		$("#CalcsInputTminCSpan").show();
		$("#CalcsInputTminLSpan").show();
		$("#CalcsLocationRow").hide();
		$("#CalcsRatioRow").hide();
		$("#ShowPipeScheduleLookup").show();
		$(".CalcsPipeElbow").show();
		displayNPS();
	}  else if($("#selectShellType").val()=="Ellipsoid")  {
		$("#VesselID").show();
		$("#PipeOD").hide();
		$("#CalcsAngleRow").hide();
		$("#ShowPipeScheduleLookup").hide();
		$("#CalcsKnuckleRadiusRow").hide();
		$("#CalcsCrownRadiusRow").hide();
		$("#CalcsRatioRow").show();
		$("#CalcsInputTminSpan").hide();
		$("#CalcsInputTminCSpan").show();
		$("#CalcsInputTminLSpan").show();
		$("#CalcsLocationRow").show();
		$(".CalcsPipeElbow").hide();
	} else if($("#selectShellType").val()=="Toricone")  {
		$("#VesselID").show();
		$("#PipeOD").hide();
		$("#CalcsAngleRow").show();
		$("#CalcsKnuckleRadiusRow").show();
		$("#CalcsCrownRadiusRow").hide();
		$("#CalcsInputTminSpan").hide();
		$("#CalcsInputTminCSpan").show();
		$("#CalcsInputTminLSpan").show();
		$("#ShowPipeScheduleLookup").hide();
		$("#CalcsRatioRow").hide();
		$("#CalcsLocationRow").hide();
		$(".CalcsPipeElbow").hide();
	} else if($("#selectShellType").val()=="Cone")  {
		$("#VesselID").show();
		$("#PipeOD").hide();
		$("#CalcsAngleRow").show();
		$("#CalcsKnuckleRadiusRow").hide();
		$("#CalcsCrownRadiusRow").hide();
		$("#CalcsInputTminSpan").hide();
		$("#CalcsInputTminCSpan").show();
		$("#ShowPipeScheduleLookup").hide();
		$("#CalcsInputTminLSpan").show();
		$("#CalcsRatioRow").hide();
		$("#CalcsLocationRow").hide();
		$(".CalcsPipeElbow").hide();
	}	else if($("#selectShellType").val()=="Torisphere") {
		$("#VesselID").show();
		$("#PipeOD").hide();
		$("#CalcsAngleRow").hide();
		$("#CalcsCrownRadiusRow").show();
		$("#CalcsKnuckleRadiusRow").show();
		$("#CalcsInputTminSpan").show();
		$("#CalcsInputTminCSpan").hide();
		$("#ShowPipeScheduleLookup").hide();
		$("#CalcsInputTminLSpan").hide();
		$("#CalcsRatioRow").hide();
		$("#CalcsLocationRow").show();
		$(".CalcsPipeElbow").hide();
	}

	$(".LengthUnits").html( "[" + window.LengthUnits  + "]");
	$(".StressUnits").html( "[" + window.StressUnits  + "]");
	$(".PressureUnits").html( "[" + window.PressureUnits  + "]");
	$(".TemperatureUnits").html( "[&deg;" + window.TemperatureUnits  + "]");
	$(".AngleUnits").html( "[&deg;]");

	if($("#selectMaterial").val()!="0"&& $.trim($("#selectMaterial").val())!="") { //Not Manual
		$("#CalcsAllowableStress").attr("disabled", "disabled"); 	
		$.ajax({ type: 'POST', 	url:'functions/materialProperties.php',
			async: false, 	dataType: "json",
			data:{ 	RequestType:'AllowableStress',
				DesignCode:$("#selectDesignCode").val(),
				MaterialID:$("#selectMaterial").val(),
				StressUnits:window.StressUnits,
				TemperatureUnits:window.TemperatureUnits,
				Temperature:$("#CalcsTemperature").val()
				}}).done(function displaySchedules(data) {
			
				//$("#selectSpecNo").html("");
			//	$.each(data, function(key, value) {
				//	$("#selectSpecNo").append("<option value=\'"+value+"\'>"+value);
				//
				//}
				$("#CalcsAllowableStress").val(data.AllowableStress);
			})
		//}	

	} else { 
		$("#CalcsAllowableStress").removeAttr("disabled"); 
		
	}	
	
	$temp=$("#selectCodeYear").val();
	$("#selectCodeYear").html('');
	$.ajax({ type: 'POST', 	url:'functions/materialProperties.php',
		async: false, 	dataType: "json",
		data:{ 					DesignCode:$("#selectDesignCode").val(),RequestType:'CodeYear'}}).done(function displaySchedules(data) {
			$.each(data, function(key, value) {
				$("#selectCodeYear").append("<option value=\'"+value+"\'>"+value);
			}
		)}	
	);
	$("#selectCodeYear").val($temp);
		
	$temp=$("#selectMaterial").val();
	$("#selectMaterial").html('');
	$.ajax({ type: 'POST', 	url:'functions/materialProperties.php',
		async: false, 	dataType: "json",
		data:{ 	RequestType:'Material',
			DesignCode:$("#selectDesignCode").val(),
			CodeYear:$("#selectCodeYear").val(),
			ProductForm:$("#selectProductForm").val(),
			NominalComposition:$("#selectNominalComposition").val(),
			SpecNo:$("#selectSpecNo").val()
		}}).done(function displaySchedules(data) {
			
			$("#selectMaterial").html("");
			$count=0;
			$.each(data, function(key, value) {
				$count++;
				if($count%2!=1) {
					$("#selectMaterial").append("<option value=\'"+$MaterialID+"\'>"+value);
				} else {
					$MaterialID=value;
				}
			}
		)}	
	);
	$("#selectMaterial").val($temp);


		
	$temp=$("#selectNominalComposition").val();
	$("#selectNominalComposition").html('');
	$.ajax({ type: 'POST', 	url:'functions/materialProperties.php',
		async: false, 	dataType: "json",
		data:{ 	RequestType:'NominalComposition',
			DesignCode:$("#selectDesignCode").val(),
			CodeYear:$("#selectCodeYear").val(),
			ProductForm:$("#selectProductForm").val(),
			SpecNo:$("#selectSpecNo").val()
		}}).done(function displaySchedules(data) {
			
			$("#selectNominalComposition").html("");
			$.each(data, function(key, value) {
				$("#selectNominalComposition").append("<option value=\'"+value+"\'>"+value);
			}
		)}	
	);
	$("#selectNominalComposition").val($temp);

	
	$temp=$("#selectProductForm").val();
	$("#selectProductForm").html('');
	$.ajax({ type: 'POST', 	url:'functions/materialProperties.php',
		async: false, 	dataType: "json",
		data:{ 	RequestType:'ProductForm',
			DesignCode:$("#selectDesignCode").val(),
			CodeYear:$("#selectCodeYear").val(),
			SpecNo:$("#selectSpecNo").val(),
			NominalComposition:$("#selectNominalComposition").val()
		}}).done(function displaySchedules(data) {
			
			$("#selectProductForm").html("");
			$.each(data, function(key, value) {
				$("#selectProductForm").append("<option value=\'"+value+"\'>"+value);
			}
		)}	
	);
	$("#selectProductForm").val($temp);
	
	$temp=$("#selectSpecNo").val();
	$("#selectSpecNo").html('');
	$.ajax({ type: 'POST', 	url:'functions/materialProperties.php',
		async: false, 	dataType: "json",
		data:{ 	RequestType:'SpecNo',
			DesignCode:$("#selectDesignCode").val(),
			CodeYear:$("#selectCodeYear").val(),
			ProductForm:$("#selectProductForm").val(),
			NominalComposition:$("#selectNominalComposition").val()
		}}).done(function displaySchedules(data) {
			
			$("#selectSpecNo").html("");
			$.each(data, function(key, value) {
				$("#selectSpecNo").append("<option value=\'"+value+"\'>"+value);
			}
		)}	
	);
	$("#selectSpecNo").val($temp);
	


	
	if($("#selectInputType").val()=="Critical Thickness Profile(CTP)" || $("#selectInputType").val()=="Thickness Grid(GRID)") {
		$("#PTRInputDataSpan").hide();
		$("#SpanAnalysisProcedure").show();
		$("#selectAnalysisProcedure").show();
		$(".FlawDimensions").show();
		$("#CalcsUniformLoss").removeAttr("disabled");
		
		if($("#selectFlawDimensions").val()=="Spacing") {
			$(".FlawDimensionsSpacing").show();
			$(".FlawDimensionsLength").hide(); 
		} else {
			$(".FlawDimensionsSpacing").hide();		
			$(".FlawDimensionsLength").show();
		}
		if($("#selectAnalysisProcedure").val().indexOf("General Metal Loss - GML")>0) {
			if($("#selectInputType").val()=="Thickness Grid(GRID)") {
				$("#CTPInputM").attr("disabled", "disabled"); 
				$("#CTPInputC").attr("disabled", "disabled");
				$("#GridDataSpan").show();
				$("#CTPInputSpan").show();
				$("#CTPInputCSpan").show();
				$("#CTPInputMSpan").show();
				calculateCTPS();
				calculateGMLCTPS(); 
				calculateCodeCalcs();
				displayResults();
			} else {

				$("#CTPInputM").removeAttr("disabled"); 
				$("#CTPInputC").removeAttr("disabled");
				$("#GridDataSpan").hide();
				$("#CTPInputSpan").hide();
				$("#CTPInputCSpan").show();
				$("#CTPInputMSpan").show();
				calculateGMLCTPS();
				calculateCodeCalcs();
				displayResults();
			}		
		} else { //LTA
			if($("#selectInputType").val()=="Thickness Grid(GRID)") {
				$("#CTPInputM").attr("disabled", "disabled"); 
				$("#CTPInputC").attr("disabled", "disabled");
				$("#GridDataSpan").show();
				$("#CTPInputSpan").show();
				calculateCTPS();
				calculateLTACTPS();
				calculateCodeCalcs();
				displayResults();
			} else {
				$("#CTPInputM").removeAttr("disabled"); 
				$("#CTPInputC").removeAttr("disabled");
				$("#GridDataSpan").hide();
				$("#CTPInputSpan").show();
				$("#CTPInputCSpan").show();
				$("#CTPInputMSpan").show();
				calculateLTACTPS();
				calculateCodeCalcs();
				displayResults();

			}				
		}
		
	} else if($("#selectInputType").val()=="Point Thickness Readings(PTR)") {
		$("#CTPInputSpan").hide();
		$("#CTPInputMSpan").hide();
		$("#CTPInputCSpan").hide();
		$("#CalcsUniformLoss").attr("disabled", "disabled");
		
		$("#GridDataSpan").hide();
		$(".FlawDimensions").hide();
		$("#SpanAnalysisProcedure").hide();
		$("#CalcsSpacingRow").hide();
		$("#PTRInputDataSpan").show();
		$("#minThicknessSpan").show();
		calculateCOV();
		calculateCodeCalcs();
		displayResults();
	}
	
	
	DisableEvents=false;
}


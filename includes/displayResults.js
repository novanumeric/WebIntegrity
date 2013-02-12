function displayResults() {
	$("#GeneralMetalLossThickMethodTypeRow").show();

	if($("#selectCriteria").val()=="Thickness") {
		$Calc="TMIN";
	} else {
		$Calc="PRESS";
//		$("#GeneralMetalLossThickMethodTypeRow").hide();
	}
		
	$.ajax({type: 'POST', 
		url:'functions/calculateResults.php',
		async: false,
		dataType: "json",
		data:{
			Calc:$Calc,
			tmin:window.tmin, tminl:window.tminl,
			tminc:window.tminc, tam:window.tam,
			tams:window.tams, tamc:window.tamc,
			tmm:window.tmm,
			MAWP:window.MAWP,
			MAWPL:window.MAWPL,
			MAWPC:window.MAWPC,
			RSF:window.RSF,
			RSFM:window.RSFM,
			RSFC:window.RSFC,
			EllipsoidLocation:$("#selectEllipsoidLocation").val(),
			AnalysisProcedure:$('#selectAnalysisProcedure').val(),
			FFSLevel:$("#selectFFSLevel").val(),
			InputType:$("#selectInputType").val(),
			ShellType:$("#selectShellType").val(),
			LengthUnits:window.LengthUnits,
			FCA:$("#CalcsCorrosionAllowance").val(),
			tnom:$("#CalcsUniformThickness").val()
		}}).done(	function displayResultsFinal(data) {
			if(data.GMLThicknessCalcResult!="") {
				$("#GMLThicknessCalcResult").show();
				$("#GMLThicknessCalcResult").attr("src","functions/render.php?equation=" + encodeURIComponent(data.GMLThicknessCalcResult));			
			} else {
				$("#GMLThicknessCalcResult").hide();
			}
			
		});	
}

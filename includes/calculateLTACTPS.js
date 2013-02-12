function calculateLTACTPS() {

	$("#GMLSpan").show();	
	//$("#LTASpanMAWP").show();
	
	var url;
	url='functions/calculateLTACTPS.php';
	$.ajax(	{ type: 'POST', url:url, async: false, dataType: "json",
	data:{
		FFSLevel:$("#selectFFSLevel").val(),
		Diameter:$("#CalcsDiameter").val(),
		CTPInputM:$("#CTPInputM").val(), 
		CTPInputC:$("#CTPInputC").val(),
		Angle:$("#CalcsAngle").val(), 
		LengthUnits:window.LengthUnits,
		ShellType:$("#selectShellType").val(),
		UniformLoss:$("#CalcsUniformLoss").val(),
		FCA:$("#CalcsCorrosionAllowance").val(),
		tnom:$("#CalcsUniformThickness").val(),
		selectFlawDimensions:$("#selectFlawDimensions").val(),
		CircumferentialLength:$("#CircumferentialLength").val(),
		LongitudinalLength:$("#LongitudinalLength").val(),
		CircumferentialSpacing:$("#CircumferentialSpacing").val(),
		LongitudinalSpacing:$("#LongitudinalSpacing").val(),
		Location:$('#selectLocation').val(),
		Ratio:$("#CalcsRatio").val(),
		CrownRadius:$("#CalcsCrownRadius").val(), 
		KnuckleRadius:$("#CalcsKnuckleRadius").val()
	
	}}).done( 	
			function calculateLTACTPS(data) {
				//alert(encodeURIComponent(data.CTPInputImg));
				$("#CTPInputImg").attr("src","functions/render.php?equation="+encodeURIComponent(data.CTPInputImg));
				$("#CTPInputMImg").attr("src","functions/render.php?equation="+encodeURIComponent(data.CTPInputMImg));
				$("#CTPInputCImg").attr("src","functions/render.php?equation="+encodeURIComponent(data.CTPInputCImg));
				window.RSFC=data.RSFC;
				window.RSFM=data.RSFM;
				window.RSF=data.RSF;
				window.KEllipsoide=data.KEllipsoide;
				window.M=data.M;
				window.Lkc=data.Lkc;
				window.tmm=data.tmm;
			}	
	);	
	
}

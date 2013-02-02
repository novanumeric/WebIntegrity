function calculateGMLCTPS() {
	var url;
	url='functions/calculateGMLCTPS.php';
	$.ajax(	{ type: 'POST', url:url, async: false, dataType: "json",
	data:{
		Diameter:$("#CalcsDiameter").val(),
		CTPInputM:$("#CTPInputM").val(), 
		CTPInputC:$("#CTPInputC").val(),
		Angle:$("#CalcsAngle").val(), 
		LengthUnits:window.LengthUnits,
		FCA:$("#CalcsCorrosionAllowance").val(),
		tnom:$("#CalcsUniformThickness").val(),
		selectFlawDimensions:$("#selectFlawDimensions").val(),
		CircumferentialLength:$("#CircumferentialLength").val(),
		LongitudinalLength:$("#LongitudinalLength").val(),
		CircumferentialSpacing:$("#CircumferentialSpacing").val(),
		LongitudinalSpacing:$("#LongitudinalSpacing").val(),
		UniformLoss:$("#CalcsUniformLoss").val(),
		ShellType:$("#selectShellType").val(),
		Location:$('#selectLocation').val(),
		Ratio:$("#CalcsRatio").val(),
		CrownRadius:$("#CalcsCrownRadius").val(), 
		KnuckleRadius:$("#CalcsKnuckleRadius").val()
		}}).done( 	
			function calculateCTPMDisplayCOV(data) {
				window.tamc=data.tamc;
				window.tams=data.tams;
				window.tam=data.tam;
				window.tmm=data.tmm;
				window.KEllipsoide=data.KEllipsoide;
				window.M=data.M;
				window.Lkc=data.Lkc;
				$("#CTPInputImg").attr("src","functions/render.php?equation="+data.CTPInputImg);
				$("#CTPInputMImg").attr("src","functions/render.php?equation="+data.CTPInputMImg);
				$("#CTPInputCImg").attr("src","functions/render.php?equation="+data.CTPInputCImg);
			}	
	);	
}

function calculateCOV() {
	$.ajax(
	{
		type: 'POST', 
		url:'functions/calculateCOV.php',
		async: false,
		dataType: "json",
		data:{ 
			CalcsUniformLoss:$("#CalcsUniformLoss").val(),
			PTRInputData:$("#PTRInputData").val(),
			LengthUnits:LengthUnits,
			tnom:$("#CalcsUniformThickness").val()
	}}).done(function displayCOV(data) {
			$("#PTRCovWork").attr("src","functions/render.php?equation=" + encodeURIComponent(data.Equation));
			$("#PTRWarning").html(data.Warning);
			window.tam=data.tam;
			window.tmm=data.tmm;
			$("#CalcsUniformLoss").val(data.Loss);
		}	
	);
}

function calculateCTPS() {
	$.ajax({
		type: 'POST', url:'functions/calculateCTPS.php',
		async: false, dataType: "json",
		data:{ 	GridInputData:$("#GridInputData").val() }}).done(
		function DisplayCTPS(data) {

			$("#CTPInputC").val(data.CTPInputC);
			$("#CTPInputM").val(data.CTPInputM);
			$("#CTPInputMData").html(data.CTPInputM);
			$("#CTPInputCData").html(data.CTPInputC);
			$("#GridData").html(data.HTMLTable);
		}
	);	
}
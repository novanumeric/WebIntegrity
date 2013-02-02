<script>


function displayNPS() {
	var NPSSelected=$.trim($("#selectNPS").val());
	if(NPSSelected.indexOf(' [')!==-1) {
		NPSSelected=$.trim($("#selectNPS").val().split(' [')[0]);
	} else {
		NPSSelected="";
	};

	$("#selectSchedules").html('');
	$.ajax({ type: 'POST', 	url:'functions/pipeSchedules.php',
		async: false, 	dataType: "json",
		data:{ 	RequestType:'Schedule', NPS:NPSSelected,selectUnits:$("#selectUnits").val() }}).done(function displaySchedules(data) {
			$("#selectSchedules").html("");
			$.each(data, function(key, value) {
				$("#selectSchedules").append("<option value=\'"+value+"\'>"+value);
			}
		)}	
	);
	displayGeometry();

}


function displayGeometry() {
	var NPSSelected=$.trim($("#selectNPS").val());
	var Convert;
	if(NPSSelected.indexOf(' [')!==-1) {
		NPSSelected=$.trim($("#selectNPS").val().split(' [')[0]);
	};

	$.ajax(
	{
		type: 'POST', 
		url:'functions/pipeSchedules.php',
		async: false,
		dataType: "json",
		data:{
			RequestType:'Geometry',
			NPS:NPSSelected,
			selectUnits:$("#selectUnits").val(),
			Schedule:$("#selectSchedules").val() }
	}).done(function displayGeometry(data) {
			if($("#selectUnits").val()=="English") {
				$("#ThickUnits").html( "(in)");
				$("#ODUnits").html( "(in)");
			} else { 
				$("#ThickUnits").html( "(mm)");
				$("#ODUnits").html( "(mm)");
			}
			$("#ValOD").html(parseFloat(data.OD));
			$("#ValThick").html(parseFloat(data.Thickness));
		});
}

</script>


<div id="PipeScheduleLookup" class="ui-widget-content" style="display:none ;border-style:solid;border-color:black;border-width:1px;position:absolute;left:270px;top:140px;z-index:1000;width:200px;height:160px">
<img style="width:20px;height:20px" src="images/pipeSchedule-mini.png" >

<button id="ClosePipeScheduleLookup" style="position:absolute;top:0px;left:155px;color:blue;z-index:1001;">-x-</button>

<br>
&nbsp;&nbsp;&nbsp;NPS [DN]: <select id="selectNPS"></select><br>
&nbsp;&nbsp;&nbsp;SCHD: <select id="selectSchedules"></select><br>
&nbsp;&nbsp;&nbsp;OD: <span id="ValOD">#.##</span> <span id="ODUnits">(..)</span><br>
&nbsp;&nbsp;&nbsp;Thick: <span id="ValThick">#.##</span> <span id="ThickUnits">(..)</span><br>
<button id="btnSelect">Select</button>
</div>

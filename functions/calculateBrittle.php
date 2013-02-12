<?

	require "sharedCode.php";

	$RSFa=1;
	$FFSLevel=$_REQUEST["FFSLevel"];
	$InputType=$_REQUEST["InputType"];
	$WeldJointEfficiency=$_REQUEST["WeldJointEfficiency"];
	$ShellType=$_REQUEST["ShellType"];
	$LengthUnits=$_REQUEST["LengthUnits"];
	$AnalysisProcedure=$_REQUEST["AnalysisProcedure"];
	$FCA=$_REQUEST["FCA"];
	$tams=$_REQUEST["tams"];
	$tamc=$_REQUEST["tamc"];
	$tam=$_REQUEST["tam"];
	$tnom=$_REQUEST["tnom"];
	$LOSS=$_REQUEST["CalcsUniformLoss"];
	if(isset($_REQUEST["tmin"])) {
		$tmin=$_REQUEST["tmin"];
	} else {
		$tmin=0;
	}
	$GMLThicknessCalcResult="|n|";
	$Estar=max($WeldJointEfficiency,0.8);
	$GMLThicknessCalcResult.="|n|E^*=\\max\\left[E,0.8\\right]=".$Estar."|n|";
	$Rts=$tmin*$Estar/($tnom-$LOSS-$FCA);
	$GMLThicknessCalcResult.="|n|R_{ts}=\\frac{t_{min}E^*}{t_{nom}-LOSS-FCA}=".round($Rts,3)."|n|";
	
	$details=array('GMLThicknessCalcResult'=>$GMLThicknessCalcResult);

	echo json_encode($details);
	
?>
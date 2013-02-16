<?

	require "sharedCode.php";

	$RSFa=1;
	$FFSLevel=$_REQUEST["FFSLevel"];
		
	$InputType=$_REQUEST["InputType"];
	$WeldJointEfficiency=$_REQUEST["WeldJointEfficiency"];
	$ShellType=$_REQUEST["ShellType"];
	$LengthUnits=$_REQUEST["LengthUnits"];
	$StressUnits=$_REQUEST["StressUnits"];
	$PressureUnits=$_REQUEST["PressureUnits"];
	$TemperatureUnits=$_REQUEST["TemperatureUnits"];
	$AnalysisProcedure=$_REQUEST["AnalysisProcedure"];
	$ThresholdRTS=$_REQUEST["ThresholdRTS"];
	$SectionVIIICurve=$_REQUEST["SectionVIIICurve"];
	$FCA=$_REQUEST["FCA"];
	$tams=$_REQUEST["tams"];
	$tamc=$_REQUEST["tamc"];
	$tam=$_REQUEST["tam"];
	$CalcsUniformThickness=$_REQUEST["CalcsUniformThickness"];
	$tnom=$CalcsUniformThickness;	
	$LOSS=$_REQUEST["CalcsUniformLoss"];
	$ThresholdRTS=$_REQUEST["ThresholdRTS"];
	if(isset($_REQUEST["tmin"])) {
		$tmin=$_REQUEST["tmin"];
	} else {
		$tmin=0;
	}
	$GMLThicknessCalcResult="|n|";
	$Estar=max($WeldJointEfficiency,0.8);
	$YAxisLabel="Min Allow. Temperature, [°".$TemperatureUnits."]   ";
	$XAxisLabel="Governing Plate Thickness, ".$LengthUnits;	
	$YAxisLabel2="Stress Ratio";
	$XAxisLabel2="Temperature Reduction, [°".$TemperatureUnits."]";
	$index=0;
	$Temperatures="";
	$Lengths="";
	for($inch=0;$inch<=6.0;$inch+=0.1) {
		if($Lengths!="") {
			$Lengths.=",";
		}
		if($LengthUnits=="in") {
			$Lengths.=$inch;
		} else {
			$Lengths.=$inch*25.4;
		}
		if($Temperatures!="") {
			$Temperatures.=",";
		}
		$Temperatures.=calculateMAT($SectionVIIICurve,$inch,$TemperatureUnits);
	}
	if($LengthUnits=="mm") {
		$MAT=calculateMAT($SectionVIIICurve,$tnom/25.4,$TemperatureUnits);
	} else {
		$MAT=calculateMAT($SectionVIIICurve,$tnom,$TemperatureUnits);
	}
	if($FFSLevel=="Level 2") {
		$GMLThicknessCalcResult.="|n|E^*=\\max\\left[E,0.8\\right]=".$Estar."|n|";
		$Rts=$tmin*$Estar/($tnom-$LOSS-$FCA);
		$GMLThicknessCalcResult.="|n|R_{ts}=\\frac{t_{min}E^*}{t_{nom}-LOSS-FCA}=".round($Rts,3)."|n|";
		$Rts=min(max($tmin*$Estar/($tnom-$LOSS-$FCA),$ThresholdRTS),1);
		$GMLThicknessCalcResult.="|n|\\textup{Threshold } R_{ts}=".round($ThresholdRTS,3)."|n|";
		
		$PlotCount=2;
		$StressRatios="";
		$TemperatureReductions="";
		for($RtsInc=0.3;$RtsInc<=1;$RtsInc+=0.025){
			if($StressRatios!="") {
				$StressRatios.=",";
			}
			$StressRatios.=$RtsInc;
			
			if($TemperatureReductions!="") {
				$TemperatureReductions.=",";
			}
			$TemperatureReductions.=calculateTemperatureReduction($RtsInc,$TemperatureUnits);
		}
		$TemperatureReduction=calculateTemperatureReduction($Rts,$TemperatureUnits);
	} else {
		$TemperatureReduction="";
		$PlotCount=1;
		$StressRatios="";
		$TemperatureReductions="";
	}
	if($FFSLevel=="Level 2") {
		$GMLThicknessCalcResult.="|n|MAT_{nom}=".formatTemperatureResults($MAT,$TemperatureUnits)."|n|";

		$GMLThicknessCalcResult.="|n|T_R=".formatTemperatureResults($TemperatureReduction,$TemperatureUnits)."|n|";
		if($TemperatureUnits=="C") {
			$GMLThicknessCalcResult.="|n|MAT=\\max\\left[MAT_{nom}-T_R,-48\\right]=".formatTemperatureResults(max($MAT-$TemperatureReduction,-48),$TemperatureUnits)."|n|";
		} else {
			$GMLThicknessCalcResult.="|n|MAT=\\max\\left[MAT_{nom}-T_R,-55\\right]=".formatTemperatureResults(max($MAT-$TemperatureReduction,-55),$TemperatureUnits)."|n|";
		}
	} else {
	$GMLThicknessCalcResult.="|n|MAT=".formatTemperatureResults($MAT,$TemperatureUnits)."|n|";	
	}
	$GMLThicknessCalcResult.="|n|{\\color{blue} \\textup{1. MAT may be reduced a further";
	if($TemperatureUnits=="C") {
		$GMLThicknessCalcResult.=" 17 C ";
	} else {
		$GMLThicknessCalcResult.=" 30 F ";
	}
	$GMLThicknessCalcResult.="}} |n| {\\color{blue} \\textup{if PWHT Requirements are met.}}|n|";
	$GMLThicknessCalcResult.="{\\color{blue} \\textup{2. Alternatively to assessment curves,}} |n| {\\color{blue} \\textup{charpy impact tests can be performed}}|n|";
	$details=array('GMLThicknessCalcResult'=>$GMLThicknessCalcResult,'PlotCount'=>$PlotCount,'Temperatures'=>$Temperatures,'Lengths'=>$Lengths,'YAxisLabel'=>$YAxisLabel,'XAxisLabel'=>$XAxisLabel,'TemperatureReductions'=>$TemperatureReductions,'StressRatios'=>$StressRatios,'YAxisLabel2'=>$YAxisLabel2,'XAxisLabel2'=>$XAxisLabel2,'RTS'=>$Rts,'TR'=>$TemperatureReduction,'MAT'=>$MAT);

	echo json_encode($details);


function calculateMAT($SectionVIIICurve,$inch,$TemperatureUnits) 
{	
		//$Temperatures="";
		if($SectionVIIICurve=="A") {
			if($inch<=0.394) {
				$Temperatures=18;
			} else {
			
				$Temperatures=(-76.911+284.85*$inch-27.560*pow($inch,2))/(1.0+1.7971*$inch-0.17887*pow($inch,2));
			}
		} else if($SectionVIIICurve=="B") {
			if($inch<=0.394) {
				$Temperatures=-20;
			} else {
				$Temperatures=(-135.79+171.56*pow($inch,0.5)+103.63*pow($inch,1)-172.0*pow($inch,1.5)+73.737*pow($inch,2)-10.535*pow($inch,2.5));
			}
		} else if($SectionVIIICurve=="C") {
			if($inch<=0.394) {
				$Temperatures=-55;
			} else {
				$Temperatures=101.29-255.5/$inch+287.87/pow($inch,2)-196.42/pow($inch,3)+69.457/pow($inch,4)-9.8082/pow($inch,5);
			}
		} else if($SectionVIIICurve=="D") {
			if($inch<=0.5) {
				$Temperatures=-55;
			} else {
				$Temperatures=-92.965+94.065*$inch-39.812*pow($inch,2)+9.6838*pow($inch,3)-1.1698*pow($inch,4)+0.054687*pow($inch,5);
			}
		}
	if($TemperatureUnits=="C") {
		$Temperatures=($Temperatures-32)/1.8;		
	}
	return $Temperatures;
}	

function calculateTemperatureReduction($Rts,$TemperatureUnits) {
	//$TemperatureReductions="";
	
	if($Rts>=0.6) {
		$TemperatureReductions=100*(1-$Rts);
	} else {
		$TemperatureReductions=-9979.51-14125*pow($Rts,1.5)+9088.11*exp($Rts)-17.3883*log($Rts)/pow($Rts,2);
	}
	if($TemperatureUnits=="C") {
		return $TemperatureReductions/1.8;		
	} else {
		return $TemperatureReductions;
	}
}
?>
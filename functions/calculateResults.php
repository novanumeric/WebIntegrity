<?

	require "sharedCode.php";

	$RSFa=1;
	$FFSLevel=$_REQUEST["FFSLevel"];
	$InputType=$_REQUEST["InputType"];
	$ShellType=$_REQUEST["ShellType"];
	$LengthUnits=$_REQUEST["LengthUnits"];
	$AnalysisProcedure=$_REQUEST["AnalysisProcedure"];
	$FCA=$_REQUEST["FCA"];
	$tams=$_REQUEST["tams"];
	$tamc=$_REQUEST["tamc"];
	$tam=$_REQUEST["tam"];
	if(isset($_REQUEST["tmin"])) {
		$tmin=$_REQUEST["tmin"];
	} else {
		$tmin=0;
	}
	$tmm=$_REQUEST["tmm"];
	if(isset($_REQUEST["tminl"])) {
		$tminl=$_REQUEST["tminl"];
	} else {
		$tminl=0;
	}
	$tminc=$_REQUEST["tminc"];
	$RSF=$_REQUEST["RSF"];
	$RSFC=$_REQUEST["RSFC"];
	$RSFM=$_REQUEST["RSFM"];
	$MAWP=$_REQUEST["MAWP"];
	$MAWPC=$_REQUEST["MAWPC"];
	$MAWPL=$_REQUEST["MAWPL"];
	$Calc=$_REQUEST["Calc"];
	$tnom=$_REQUEST["tnom"];
	$GMLThicknessCalcResult="";

	$equation="";
	if($Calc=="PRESS"){
		$GMLThicknessCalcResult.="|n| {\\color{Blue}\\textup{Please compare reduced rating to original MAWP.}} |n|";
	} else if($InputType=="Point Thickness Readings(PTR)") {
		if($FFSLevel=="Level 2") {
			$RSFa=0.9;
			$GMLThicknessCalcResult.="|n| RSF_{a}=".$RSFa." |n| RSF_{a}\\times{t_{min}}=".formatLengthResults($RSFa*$tmin,$LengthUnits). "} |n|  ";
			$temp="RSF_a\\times";
		} else {
			$RSFa=1;
			$GMLThicknessCalcResult.="|n|";
			$temp="";
		}
		$GMLThicknessCalcResult.="{t_{am}}-FCA=" . formatLengthResults($tam-$FCA,$LengthUnits). "} |n|  ";
		if($tmin=="") {
			$GMLThicknessCalcResult.="|n|{t_{min}}\\ \\textup{ is not specified}";
		} else if($tam-$FCA>=$RSFa*$tmin) {
			$GMLThicknessCalcResult.="|n|{t_a_m}-FCA\\geq{".$temp."{t_{min}}}";
		} else {
			$GMLThicknessCalcResult.="|n|{\\color{Red}{t_{am}}-FCA\\leq{".$temp."{t_{min}}}}";
		}
		
	} else if($InputType=="Critical Thickness Profile(CTP)" || $InputType=="Thickness Grid(GRID)") {

		if($FFSLevel=="Level 2" && $ShellType=="Sphere") {
			$RSFa=0.9;
			$temp2="|n| RSF_{a}=0.9 |n| RSF_{a}\\times{t_{min}}=".round($RSFa*$tmin,2). "\\ \\textup{" . $LengthUnits. "} |n|  ";
			$temp="RSF_a\\times";
		} else if($FFSLevel=="Level 2") {
			$RSFa=0.9;
			$temp2="|n| RSF_{a}=0.9 |n| RSF_{a}\\times{t^C_{min}}=".round($RSFa*$tminc,2). "\\ \\textup{" . $LengthUnits. "} |n|  ";
			$temp2.="|n| RSF_{a}\\times{t^L_{min}}=".round($RSFa*$tminl,2). "\\ \\textup{" . $LengthUnits. "} |n|  ";
			$temp="RSF_a\\times";	
		} else {
			$RSFa=1;
			$temp2="|n| ";
			$temp="";
		}

		
		$GMLThicknessCalcResult="|n|";
		$GMLThicknessCalcResult.="{t^s_{am}}-FCA=" . round($tams-$FCA,3) . "\\ \\textup{" .  $LengthUnits ."} |n|";
		$GMLThicknessCalcResult.="{t^c_{am}}-FCA=" . round($tamc-$FCA,3) . "\\ \\textup{" .  $LengthUnits ."} |n|";

		if($ShellType=="Sphere") {
			if($tmin=="") {
				$GMLThicknessCalcResult.="|n| {t_{min}}\\ \\textup{ is not specified}";
			} else if(max($tams,$tamc)-$FCA>=$RSFa*$tmin) {
				$GMLThicknessCalcResult.=$temp2."\\max\\left[t^s_{am},t^c_{am}\\right]-FCA\\geq{".$temp."{t_{min}}} |n|";
			} else {
				$GMLThicknessCalcResult.=$temp2."{\\color{Red}\\max\\left[t^s_{am},t^c_{am}\\right]-FCA\\leq{".$temp."{t_{min}}}}  |n|";
			}
		} else {
			if($tminc=="") {
				$GMLThicknessCalcResult.="|n| {t^C_{min}}\\ \\textup{ is not specified}";
			}
			if($tminl=="") {
				$GMLThicknessCalcResult.="|n| {t^L_{min}}\\ \\textup{ is not specified}";
			} 
			if($tminl!="" && $tminc!="") {
				if($tams-$FCA>=$RSFa*$tminc) {
					$line1="|n| t^s_{am}-FCA\\geq{".$temp."{t^C_{min}}} |n| ";
				} else {
					$line1="|n| \\color{Red}t^s_{am}-FCA\\leq{".$temp."{t^C_{min}}}\\color{Black}|n| ";
				}
				if($tamc-$FCA>=$RSFa*$tminl) {
					$line2=" |n|  {t^c_{am}}-FCA\\geq{".$temp."{t^L_{min}}}\\ |n|";
				} else {
					$line2=" |n| {\\color{Red}t^c_{am}-FCA\\leq{".$temp."{t^L_{min}}}}} |n|";
				}
				$GMLThicknessCalcResult.=$temp2.$line1 . $line2;
			}				
		}
	}
	

	if(strpos($AnalysisProcedure,"Local Thickness Area - LTA") <=0){
		$GMLThicknessCalcResult.="|n| \\textup{Minimum Measured Thickness Criteria} ";
		if($LengthUnits=="mm") {
			$tlim=max(0.2*$tnom,2.5);
			$GMLThicknessCalcResult.="|n| t_{lim}=\\max\\left[0.2{t_{nom}},2.5\\ \\textup{mm}\\right]=".round($tlim,3)."\\ \\textup{mm}|n|";
		} else {
			$tlim=max(0.2*$tnom,0.1);	
			$GMLThicknessCalcResult.="|n| {t_{lim}}=\\max\\left[0.2{t_{nom}},0.1\\ \\textup{in}\\right]=".round($tlim,3)."\\ \\textup{in} |n|";
		}
		if(($InputType=="Point Thickness Readings(PTR)") && ($Calc=="PRESS")) {
			$tmin=$tam-$FCA;
			$GMLThicknessCalcResult.="{t_{min}}=t_{am}-FCA=".round($tmin,4)."\\ \\textup{" .  $LengthUnits ."}|n|";

		}
		$GMLThicknessCalcResult.="|n| t_{mm}-FCA=".formatLengthResults($tmm-$FCA,$LengthUnits)."|n|";
		if(($tmm-$FCA)>=max(0.5*$tmin,$tlim)) {

			$GMLThicknessCalcResult.="t_{mm}-FCA\\geq\\max\\left[0.5t_{min},t_{lim}\\right]=".round(max(0.5*$tmin,$tlim),3) . "\\ \\textup{" .  $LengthUnits ."}|n|";
		} else {
			$GMLThicknessCalcResult.=" {\\color{Red}t_{mm}-FCA<\\max\\left[0.5t_{min},t_{lim}\\right]=".round(max(0.5*$tmin,$tlim),3)."\\ \\textup{" .  $LengthUnits ."}} |n|";
		}
	}
	$details=array('GMLThicknessCalcResult'=>$GMLThicknessCalcResult);
	echo json_encode($details);
?>
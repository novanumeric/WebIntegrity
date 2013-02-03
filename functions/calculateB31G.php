<?

$CTPInput=trim($_REQUEST["CTPInputM"]);
$CTPInputLengths=trim($_REQUEST["CTPInputMLengths"]);

$CalcsDiameter=$_REQUEST["CalcsDiameter"];
$LengthUnits=$_REQUEST["LengthUnits"];
$PressureUnits=$_REQUEST["PressureUnits"];
$StressUnits=$_REQUEST["StressUnits"];
$CalcsUniformThickness=$_REQUEST["CalcsUniformThickness"];
$CalcsDepth=$_REQUEST["CalcsDepth"];
$selectLevel=$_REQUEST["selectLevel"];
$selectUnits=$_REQUEST["selectUnits"];
$CalcsLength=$_REQUEST["CalcsLength"];
$Sflow=$_REQUEST["Sflow"];
$selectMethod=$_REQUEST["selectMethod"];

$CalcsUniformThickness=$_REQUEST["CalcsUniformThickness"];
$ShellType="RSTRENG";
$Equation="";
require "sharedCode.php";
require "sharedIterative.php";
$Equation="|n|";
if($selectLevel=="Level1") {
	$RSF=1;
	$Equation.="\\textup{Level 1 Calculations}|n|";
	$z=pow($CalcsLength,2.0)/($CalcsDiameter*$CalcsUniformThickness);
	//$z=pow(210,2)/609/11.3;
	$Equation.="z=\\frac{L^2}{Dt}=".round($z,4)."|n|";
	if($selectMethod=='Original'){
		$M=sqrt(1.0 + 0.8*$z);
		$Equation.="M=\\sqrt{1+0.8z}=".round($M,4)." |n|";		
		
		if($z<=20.0) {
			$Equation.="z\\leq20 |n|";
			$RSF=(1.0 - (2.0/3.0)*($CalcsDepth/$CalcsUniformThickness))/(1 - (2.0/3.0)*($CalcsDepth/$CalcsUniformThickness)/$M);	
			$Equation.="RSF=\\frac{\\Big(1.0-\\frac{2.0}{3.0}\\times\\frac{d}{t}\\Big)}{(1.0-\\frac{2.0}{3.0}\\times\\frac{d}{t}/M)}=".round($RSF,4)."|n|";
		} else {
			$Equation.="z\\ge20 |n|";
			$RSF=(1.0 - $CalcsDepth/$CalcsUniformThickness);
			$Equation.="RSF=\\bigg(1-\\frac{d}{t}\\bigg)=".round($RSF,4)."|n|";
		}
	} else {	
		if($z<=50.0) {
			$Equation.="z\\leq50 |n|";
			$M=sqrt(1 + 0.6275*$z - 0.003375*pow($z,2));
			$Equation.="M=\\sqrt{1+0.6275z-0.003375z^2}=".round($M,4)."|n|";
		} else {
			$Equation.="z\\ge50 |n|";
			$M=0.032*$z + 3.3;
			$Equation.="M=0.032\\times z+3.3=".round($M,4)."|n|";
		}
		$temp=1-0.85*$CalcsDepth/($CalcsUniformThickness);
		$temp2=1-0.85*$CalcsDepth/($CalcsUniformThickness)/$M;
		$RSF=$temp/($temp2);
		$Equation.="RSF=\\frac{1-0.85\\times\\frac{d}{t}}{1-0.85\\times\\frac{d}{t}/M}=".round($RSF,4)."|n|";

	}
} else {
	$Equation.="\\textup{Level 2 Calculations}|n|"; 
	$CTPInput=preg_split("/[\s,]+/",$CTPInput);
	$CTPInputLengths=preg_split("/[\s,]+/",$CTPInputLengths);
	$EquationBuf="";
	for($i=0;$i<count($CTPInput);$i++) {
		$CTPInput[$i]=$CalcsUniformThickness-$CTPInput[$i];
	}
	$RSF=calculateRSFIterative($CTPInput,$CTPInputLengths,&$EquationBuf,$ShellType,$CalcsDiameter,$CalcsUniformThickness,$LengthUnits);
	$Equation.=$EquationBuf;
}
if(strtoupper($StressUnits)=="KSI") {
	$Sflow=$Sflow*1000;
} elseif(strtoupper($StressUnits)=="MPA") {
	$Sflow=$Sflow*1000000;
}
$Sf=$RSF*$Sflow;

$Equation.="S_f=RSF\\times S_{flow}=".formatStressResults($Sf,$StressUnits)."|n|";

$PCalc=$Sf*2*$CalcsUniformThickness/$CalcsDiameter;
$Equation.="P=\\frac{S_f\\times2t}{D}=".formatPressureResults($PCalc,$PressureUnits)."|n| ";

//print json.dumps({'Sf': Sf,'Equation':Equation,'MAWP':MAWP}, sort_keys=True, indent=4);
$details=array('Equation'=>$Equation,'RSF'=>$RSF);
echo json_encode($details);
?>
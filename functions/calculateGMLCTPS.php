<?
	
error_reporting(E_ALL);
$CTPInputM=$_REQUEST["CTPInputM"];
$CTPInputC=$_REQUEST["CTPInputC"];
$CTPInputM=preg_split("/[\s,]+/",$CTPInputM);
$CTPInputC=preg_split("/[\s,]+/",$CTPInputC);
$tnom=$_REQUEST["tnom"];
$FCA=$_REQUEST["FCA"];
$Diameter=$_REQUEST["Diameter"];
$Ratio=$_REQUEST["Ratio"];
$Location=$_REQUEST["Location"];
$LengthUnits=$_REQUEST["LengthUnits"];
$Location=$_REQUEST["Location"];
$selectFlawDimensions=$_REQUEST["selectFlawDimensions"];
$LongitudinalLength=$_REQUEST["LongitudinalLength"];
$LongitudinalSpacing=$_REQUEST["LongitudinalSpacing"];
$CircumferentialLength=$_REQUEST["CircumferentialLength"];
$CircumferentialSpacing=$_REQUEST["CircumferentialSpacing"];
$UniformLoss=$_REQUEST["UniformLoss"];
$CrownRadius=$_REQUEST["CrownRadius"];
$KnuckleRadius=$_REQUEST["KnuckleRadius"];
$Angle=$_REQUEST["Angle"];
$KEllipsoide="";
$M="";
$Lkc="";
require "sharedCode.php";

function performCTPIntegration($CTPInput,$tmm,$tmmposition,$Spacing,$L,&$A)
{
	$Count=1000;
	$left=$tmmposition-$L/2;
	$right=$tmmposition+$L/2;
	$delta=($L)/$Count;
	$t2=0;
	$t1=evaluateCTP($left,$CTPInput,$Spacing);
	$A=0;
	for($i=1;$i<=$Count;$i++) {
		$position=$left+$delta*$i;
		$t2=evaluateCTP($position,$CTPInput,$Spacing);
		$A=$A+($delta)*($t2+$t1)/2;
		$t1=$t2;
	}
	return($A/$L);
}
function evaluateCTP($position,$CTPInput,$Spacing) {
	$N=count($CTPInput);
	for($i=0;$i<=$N;$i++) {
		if(($position>=$i*$Spacing) && ($position<=($i+1)*$Spacing)) { 

			$temp=$CTPInput[$i]+($CTPInput[$i+1]-$CTPInput[$i])/($Spacing)*($position-$i*$Spacing);
		//	echo "Input $position output $temp\n";
			return($temp);
		}
	}
	
}
function calculateCTP($CTPInput,&$CTPInputImg,&$tam,$tmm,$L,$Spacing,$Type,$LengthUnits)
{
	if($Type=="Circumferential") {
		$delta="{\\Delta C}";
		$char="c";
	} else {
		$delta="{\\Delta S}";
		$char="s";		
	}
	$N2=count($CTPInput);
	$CTPInputImg="|n|t_{mm} \\textup{ is not found in dataset. Please confirm data}|n|";
	for($i=0;$i<$N2;$i++) {  
		if($CTPInput[$i]==$tmm) {
			if(($i==0)|| ($i>=$N2-1)) {
				$CTPInputImg="|n|{\\color{red}\\textup{Minimum thickness is on end}\\|n|}"; 	
				$tam=$tmm;
				break;	
			} else {
				if(($L/2)/$Spacing>1) {
					$tmmposition=$i*$Spacing;
					$tam=performCTPIntegration($CTPInput,$tmm,$tmmposition,$Spacing,$L,$A);

					$CTPInputImg="|n|\\frac{L}{2}>$delta\\ \\textup{ therefore a numerical integration technique is used.}|n|";
					$CTPInputImg.="A^".$char."=\int^{-L/2}_{L/2} t^".$char."\,d$char=".formatLengthResultsSquare($A,$LengthUnits)."\\ \\textup{0 is at }".formatLengthResults($tmmposition,$LengthUnits)." |n|";
					$CTPInputImg.="{t^".$char."_{am}}=	\\frac{A}{L}=".formatLengthResults($tam,$LengthUnits)."}";
				} else {
					$t1=$tmm+($CTPInput[$i-1]-$tmm)*(($L/2)/$Spacing);
					$t2=$tmm+($CTPInput[$i+1]-$tmm)*(($L/2)/$Spacing);
					$A1=(($t1+$tmm)/2)*(($L/2));
					$A2=($tmm+$t2)/2*(($L/2));			
					$tam=($A1+$A2)/$L;
					$CTPInputImg="|n|$delta=".formatLengthResults($Spacing,$LengthUnits)."|n|";
					$CTPInputImg.="{t_{1}}=t_{mm}%2B\\left(t_{i-1}%2B{t_{mm}}\\right)\\frac{L/2}{$delta}=".formatLengthResults($t1,$LengthUnits)."}|n|";
					$CTPInputImg.="{t_{2}}=t_{mm}%2B\\left(t_{i%2B1}%2B{t_{mm}}\\right)\\frac{L/2}{$delta}=". formatLengthResults($t2,$LengthUnits)."} |n|";
					$CTPInputImg.="{A_{1}}=\\frac{{t_{1}}%2Bt_{mm}}{2}({L/2})=".formatLengthResults($A1,$LengthUnits)."}^2 |n|";
					$CTPInputImg.="{A_{2}}=\\frac{{t_{mm}}%2Bt_{2}}{2}({L/2})=".formatLengthResults($A2,$LengthUnits)."}^2 |n|";
					$CTPInputImg.="{t^".$char."_{am}}=\\frac{A_{1}%2BA_{2}}{L}=".formatLengthResults($tam,$LengthUnits)."}";
				}
				break;
			}			
		}
	}
}



if($selectFlawDimensions=="Length") {
	$CircumferentialSpacing=$CircumferentialLength/(count($CTPInputC)-1);
	$LongitudinalSpacing=$LongitudinalLength/(count($CTPInputM)-1);
}
$ShellType=$_REQUEST["ShellType"];
$RSFa=0.9;
$Warning="";
$temp2="";
$tc=($tnom-$FCA-$UniformLoss);

$Diameter=$Diameter+2*$FCA+2*$UniformLoss;
$LengthCalc="|n| D=".formatLengthResults($Diameter,$LengthUnits)."|n|";
$LengthCalc.="t_{c}={t_{nom}-FCA-LOSS}=".formatLengthResults($tc,$LengthUnits)."|n|";
$tmm=min(min($CTPInputM),min($CTPInputC));
$LengthCalc.="{t_{mm}}=".$tmm."\\ \\textup{". $LengthUnits."}|n|";
$Rt=($tmm-$FCA)/$tc;

$LengthCalc.="R_{t}=\\left(\\frac{t_{mm}-FCA}{t_c}\\right)=".Round($Rt,4)."|n|";
$LengthCalc.=$temp2;
if($Rt>=$RSFa) { 
	$LengthCalc.=" R_{t}\\geq$RSFa |n|";
	$Q=50;
	$LengthCalc.=" Q = 50 |n|"; 
} else {
	$LengthCalc.=" R_{t}<$RSFa |n|";
	$Q=1.123*sqrt(pow((1-$Rt)/(1-$Rt/$RSFa),2)-1);
	$LengthCalc.=" Q=1.123\\left[\\left(\\frac{1-R_t}{1-R_t/RSF_a}\\right)^2-1\\right]^{0.5}=".Round($Q,4)." |n|";
}


if($ShellType=="Ellipsoid") {
	$KEllipsoide=calculateKEllipsoid($LengthCalc,$Location,$Ratio);
	$L=$Q*sqrt(2*$KEllipsoide*$Diameter*$tc);
	$LengthCalc.="|n|L=Q\\sqrt{2KD{t_c}}=".formatLengthResults(Round($L,4),$LengthUnits)."|n|";
} elseif($ShellType=="Toricone") {
	 calculateToriconeParameters($LengthCalc,$Lkc,$M,$Location,$Angle,$Diameter/2,$KnuckleRadius);
	$L=$Q*sqrt(($Diameter)*$tc);
	$LengthCalc.="|n|L=Q\\sqrt{D{t_c}}=".formatLengthResults(Round($L,4),$LengthUnits)."|n|";

} elseif($ShellType=="Torisphere") {
	calculateTorisphereParameters($LengthCalc,$M,$Location,$CrownRadius,$KnuckleRadius);
	$L=$Q*sqrt(($Diameter)*$tc);
	$LengthCalc.="|n|L=Q\\sqrt{D{t_c}}=".formatLengthResults(Round($L,4),$LengthUnits)."|n|";

}else {
	$L=$Q*sqrt(($Diameter)*$tc);
	$LengthCalc.="|n|L=Q\\sqrt{D{t_c}}=".formatLengthResults(Round($L,4),$LengthUnits)."|n|";
}

$CTPInputImg=$temp2.$LengthCalc;
calculateCTP($CTPInputC,$CTPInputCImg,$tamc,$tmm,$L,$CircumferentialSpacing,"Circumferential",$LengthUnits);
calculateCTP($CTPInputM,$CTPInputMImg,$tams,$tmm,$L,$LongitudinalSpacing,"Longitudinal",$LengthUnits);

$tam=min($tams,$tamc);
$details=array('tmm'=>$tmm,'Lkc'=>$Lkc,'M'=>$M,'KEllipsoide'=>$KEllipsoide,'CTPInputImg'=>$CTPInputImg,'CTPInputCImg'=>$CTPInputCImg,'CTPInputMImg'=>$CTPInputMImg,'tam'=>$tam,'tams'=>$tams,'tamc'=>$tamc,'tmm'=>$tmm);

echo json_encode($details);
?>
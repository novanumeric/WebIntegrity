<?
error_reporting(E_ALL);

$CTPInputM=trim($_REQUEST["CTPInputM"]);
$CTPInputC=trim($_REQUEST["CTPInputC"]);
$ShellType=$_REQUEST["ShellType"];
$tnom=$_REQUEST["tnom"];
$FCA=$_REQUEST["FCA"];
$Diameter=$_REQUEST["Diameter"];
$LengthUnits=$_REQUEST["LengthUnits"];
$selectFlawDimensions=$_REQUEST["selectFlawDimensions"];
$LongitudinalLength=$_REQUEST["LongitudinalLength"];
$LongitudinalSpacing=$_REQUEST["LongitudinalSpacing"];
$CircumferentialLength=$_REQUEST["CircumferentialLength"];
$CircumferentialSpacing=$_REQUEST["CircumferentialSpacing"];
$UniformLoss=$_REQUEST["UniformLoss"];
$CrownRadius=$_REQUEST["CrownRadius"];
$KnuckleRadius=$_REQUEST["KnuckleRadius"];
$Angle=$_REQUEST["Angle"];
$Location=$_REQUEST["Location"];
$FFSLevel=$_REQUEST["FFSLevel"];
$Ratio=$_REQUEST["Ratio"];
$CTPInputC=preg_split("/[\s,]+/",$CTPInputC);
$CTPInputM=preg_split("/[\s,]+/",$CTPInputM);
$RSFC=0;
$RSFM=0;
$RSF=0;
$KEllipsoide="";
$M="";
$Lkc="";
require "sharedCode.php";

function formatLengthResults($val,$LengthUnits) {
	$buf="";
	$buf.=round($val,4);
	$buf.="\\ \\textup{".$LengthUnits."}";
	return $buf;
}

function calculateRSF($Rt,$Mt,&$CTPInputImg) {

	$RSF=$Rt/(1-(1/$Mt)*(1-$Rt));
	$CTPInputImg.="RSF=\\frac{R_t}{1-\\frac{1}{M_t}\\left(1-R_t\\right)}=".round($RSF,4)."|n|";
	return $RSF;
}

function calculateMt($ShellType,$lambda,&$CTPInputImg) {
	if($ShellType=="Sphere") {
		$Mt=(1.0005  +0.49001*$lambda+0.32409*pow($lambda,2))/(1.0+0.50144*$lambda-0.011067*pow($lambda,2));
		$CTPInputImg.="M_t=\\frac{1.0005%2b0.49011\\lambda%2b0.32409\\lambda^2}{1.0%2b0.50144\\lambda-0.011067\\lambda^2}=".round($Mt,4)."|n|";
	} else {
		$Mt=1.0010 - 0.014195*$lambda+0.29090*pow($lambda,2)-0.096420*pow($lambda,3)+0.020890*pow($lambda,4)-0.0030540*pow($lambda,5)+2.9570/pow(10,4)*pow($lambda,6)-1.8462/pow(10,5)*pow($lambda,7)+7.1553/pow(10,7)*pow($lambda,8)-1.5631/pow(10,8)*pow($lambda,9)+1.4656/pow(10,10)*pow($lambda,10);
		$CTPInputImg.="M_t=1.0010-0.014195\\lambda%2b0.29090\\lambda^2-0.096420\\lambda^3%2b0.020890\\lambda^4|n|-0.0030540\\lambda^5%2b2.9570\\times10^4\\lambda^{-6}-1.8462\\times10^{-5}\\lambda^7|n|%2b7.1533\\times10^{-7}\\lambda^8-1.5631\\times10^{-8}\\lambda^9%2b1.4656\\times10^{-10}\\lambda^10|n|=".round($Mt,4)."|n|";
	}
	return $Mt;
}

if($selectFlawDimensions=="Length") {
	$CircumferentialSpacing=$CircumferentialLength/(count($CTPInputM)-1);
	$LongitudinalSpacing=$LongitudinalLength/(count($CTPInputC)-1);
} else {
	$LongitudinalLength=$LongitudinalSpacing*(count($CTPInputC)-1);
	$CircumferentialLength=$CircumferentialSpacing*(count($CTPInputM)-1);
}


$tc=($tnom-$FCA-$UniformLoss);
$tmm=min(min($CTPInputM),min($CTPInputC));
$N1=count($CTPInputC);
$N2=count($CTPInputM);


$CTPInputImg="|n|\\textup{Calculating LTA} |n|";

$CTPInputImg.="D=D%2b2FCA%2b2LOSS=".formatLengthResults($Diameter+2*$FCA+2*$UniformLoss,$LengthUnits)."|n|";
$CTPInputImg.="t_{c}={t_{nom}-FCA-LOSS}=".formatLengthResults($tc,$LengthUnits)."|n|";
#$CTPInputImg.="s=".$s."|n|";
#$CTPInputImg.="\\lambda=\\frac{1.285s}{\\sqrt{Dt_c}}=";
if($LengthUnits=="mm") {
	if($tmm-$FCA>2.5){
		$CTPInputImg.="|n|{\\color{Black}t_{mm}-FCA=".formatLengthResults($tmm-$FCA,$LengthUnits)."\\geq2.5\\ \\textup{mm}}|n|";
	} else {
		$CTPInputImg.="|n|{\\color{Red}t_{mm}-FCA=".formatLengthResults($tmm-$FCA,$LengthUnits)."<2.5\\ \\textup{mm}}|n|";
	}
} else {
	if($tmm-$FCA>0.1){
		$CTPInputImg.="|n|{\\color{Black}t_{mm}-FCA=".formatLengthResults($tmm-$FCA,$LengthUnits)."\\geq0.1\\ \\textup{in}}|n|";
	} else {
		$CTPInputImg.="|n|{\\color{Red}t_{mm}-FCA=".formatLengthResults($tmm-$FCA,$LengthUnits)."<0.1\\ \\textup{in}}|n|";
	}
}
$Rt=($tmm-$FCA)/$tc;
if($Rt<0.2) {
	$CTPInputImg.="{\\color{red}";
}else {
	$CTPInputImg.="{\\color{black}";
}
$CTPInputImg.="R_{t}=\\left(\\frac{t_{mm}-FCA}{t_c}\\right)=".Round($Rt,4);
if($Rt<0.2) {
	$CTPInputImg.="<0.2}|n|";
}else {
	$CTPInputImg.="\\geq0.2}|n|";
}
if($ShellType=="Ellipsoid") {
	$KEllipsoide=calculateKEllipsoid($CTPInputImg,$Location,$Ratio);
} elseif($ShellType=="Toricone") {
	calculateToriconeParameters($CTPInputImg,$Lkc,$M,$Location,$Angle,$Diameter/2,$KnuckleRadius);
} elseif($ShellType=="Torisphere") {
	calculateTorisphereParameters($CTPInputImg,$M,$Location,$CrownRadius,$KnuckleRadius);
}
$LOSS=$UniformLoss;
$Lmsd=1.8*sqrt(($Diameter+2*$FCA+2*$LOSS)*$tc);
$CTPInputImg.="\\textup{Distance to Disconuity}\\ L_{msd}\\overset{?}{\\geq}1.8\\sqrt{Dt_c}=".formatLengthResults($Lmsd,$LengthUnits)."|n|";
$CTPInputImg.="\\textup{Check Groove Radius}\\ gr\\overset{?}{\\geq}\\left(1-R_t\\right)t_c=".formatLengthResults((1-$Rt)*$tc,$LengthUnits)."|n||n|";

if($FFSLevel=="Level 1") {
	
	$lambdaS=1.285*$LongitudinalLength/sqrt(($Diameter+2*$FCA+2*$LOSS)*$tc);
	$Mt=calculateMt($ShellType,$lambdaS,$CTPInputImg);
	$RSF=calculateRSF($Rt,$Mt,$CTPInputImg);
	$CTPInputImg.="s=\\Delta S\\times ".($N1-1)."=".formatLengthResults($LongitudinalLength,$LengthUnits)."|n|";
	$CTPInputImg.="\\lambda_S=\\frac{1.285s}{\\sqrt{Dt_c}}=".round($lambdaS,4)."|n|";
//	$CTPInputImg.="M_t=".$Mt."|n|";
//	$CTPInputImg.="RSF=".round($RSF,4)."|n|";
	if($ShellType=="Cylinder"||$ShellType=="Cone") {
		$CTPInputImg.="|n|\\textup{Checking Circumferential Plane} |n|";
		$CTPInputImg.="c=".formatLengthResults($CircumferentialLength,$LengthUnits)."|n|";
		$lambdaC=1.285*$CircumferentialLength/sqrt(($Diameter+2*$FCA)*$tc);
		$CTPInputImg.="\\lambda_C=\\frac{1.285c}{\\sqrt{Dt_c}}=".round($lambdaC,4)."|n|";
		$EL=1;
		$EC=1;
		$TSF=$EC/(2*$RSF);
		$temp=sqrt(4-3*pow($EL,2));
		$TSF=$TSF*(1+$temp/$EL);
		$CTPInputImg.="TSF=\\frac{E_c}{2\\times\\ RSF}\\left(1%2b\\frac{\\sqrt{4-3E^2_L}}{E_L}\\right)=".round($TSF,4)."|n|";
		$Rtmin=calculateRTMinFrmTSF($TSF,$lambdaC);
		$CTPInputImg.="R_{tmin}=".round($Rtmin,4)."\\ \\textup{See Figure 5.8} |n|";
		if($Rt<$Rtmin) {
			$CTPInputImg.="{\\color{red}R_{t}< R_{tmin}} |n|";
		}else {
			$CTPInputImg.="R_{t}\\geq R_{tmin} |n|";
		}
	}
	$CTPInputCImg="\\textup{Only}\\ t_{mm}=$tmm\\ \\textup{is used for Level 1 Analysis}|n|";
	$CTPInputMImg="\\textup{Only}\\ t_{mm}=$tmm\\ \\textup{is used for Level 1 Analysis}|n|";	

} else {
	if ($tnom<$tmm) {
		$CTPInputImg.="\\textup{Specified thickness is less than measured data} |n|";
		$CTPInputCImg="...|n|";
		$CTPInputMImg="...|n|";
	} else {
		if($N1<=1) {
			$RSFC="-1";
			$CTPInputCImg="\\textup{Insufficient data points}";
		} else {
			$RSFC=calculateRSFIterative($CTPInputC,$CTPInputCImg,$ShellType,$N1,$Diameter,$tc,$CircumferentialSpacing,$LengthUnits,'C');
			$CTPInputCImg="|n|\\Delta C=".formatLengthResults($CircumferentialSpacing,$LengthUnits).$CTPInputCImg;

		}

		if($N2<=1) {
			$RSFM="-1";
			$CTPInputMImg="\\textup{Insufficient data points}";
		} else {
			$CTPInputMImg="";
			$RSFM=calculateRSFIterative($CTPInputM,$CTPInputMImg,$ShellType,$N2,$Diameter,$tc,$LongitudinalSpacing,$LengthUnits,'S');
			$CTPInputMImg="|n|\\Delta S=".formatLengthResults($LongitudinalSpacing,$LengthUnits).$CTPInputMImg;
		}
	}
	$RSF=min($RSFM,$RSFC);
}
$details=array('tmm'=>$tmm,'Lkc'=>$Lkc,'M'=>$M,'KEllipsoide'=>$KEllipsoide,'CTPInputImg'=>$CTPInputImg,'CTPInputCImg'=>$CTPInputCImg,'CTPInputMImg'=>$CTPInputMImg,'RSFC'=>$RSFC,'RSFM'=>$RSFM,'RSF'=>$RSF);
echo json_encode($details);

function calculateRTMinFrmTSF($TSF,$lambdaC){
	$coeffs[0][0]=0.7;
	$coeffs[0][1]=0.21;
	$coeffs[0][2]=9.9221e-1;
	$coeffs[0][3]=-1.1959e-1;
	$coeffs[0][4]=-5.733e-2;
	$coeffs[0][5]=1.6948e-2;
	$coeffs[0][6]=-1.7976e-3;
	$coeffs[0][7]=6.9114e-5;
	$coeffs[1][0]=0.75;
	$coeffs[1][1]=0.48;
	$coeffs[1][2]=9.6801E-01;
	$coeffs[1][3]=-2.3780E-01;
	$coeffs[1][4]=-3.2678E-01;
	$coeffs[1][5]=2.0684E-01;
	$coeffs[1][6]=-4.6537E-02;
	$coeffs[1][7]=3.9436E-03;
	$coeffs[2][0]=0.8;
	$coeffs[2][1]=0.67;
	$coeffs[2][2]=9.4413E-01;
	$coeffs[2][3]=-3.1256E-01;
	$coeffs[2][4]=-6.9968E-01;
	$coeffs[2][5]=6.5020E-01;
	$coeffs[2][6]=-2.2102E-01;
	$coeffs[2][7]=2.8799E-02;
	$coeffs[3][0]=0.9;
	$coeffs[3][1]=0.98;
	$coeffs[3][2]=8.9962E-01;
	$coeffs[3][3]=-3.8860E-01;
	$coeffs[3][4]=-1.6485E+00;
	$coeffs[3][5]=2.3445E+00;
	$coeffs[3][6]=-1.2534E+00;
	$coeffs[3][7]=2.5331E-01;
	$coeffs[4][0]=1.0;
	$coeffs[4][1]=1.23;
	$coeffs[4][2]=8.5947E-01;
	$coeffs[4][3]=-4.0012E-01;
	$coeffs[4][4]=-2.7979E+00;
	$coeffs[4][5]=5.0729E+00;
	$coeffs[4][6]=-3.5217E+00;
	$coeffs[4][7]=9.1877E-01;
	$coeffs[5][0]=1.2;
	$coeffs[5][1]=1.66;
	$coeffs[5][2]=7.8654E-01;
	$coeffs[5][3]=-2.5322E-01;
	$coeffs[5][4]=-5.7982E+00;
	$coeffs[5][5]=1.3858E+01;
	$coeffs[5][6]=-1.3118E+01;
	$coeffs[5][7]=4.6436E+00;
	$coeffs[6][0]=1.4;
	$coeffs[6][1]=2.03;
	$coeffs[6][2]=7.2335E-01;
	$coeffs[6][3]=1.1528E-02;
	$coeffs[6][4]=-9.3536E+00;
	$coeffs[6][5]=2.6031E+01;
	$coeffs[6][6]=-2.9372E+01;
	$coeffs[6][7]=1.2387E+01;
	$coeffs[7][0]=1.8;
	$coeffs[7][1]=2.66;
	$coeffs[7][2]=6.0737E-01;
	$coeffs[7][3]=9.3796E-01;
	$coeffs[7][4]=-1.9239E+01;
	$coeffs[7][5]=6.4267E+01;
	$coeffs[7][6]=-9.1307E+01;
	$coeffs[7][7]=4.8962E+01;
	$coeffs[8][0]=2.3;
	$coeffs[8][1]=3.35;
	$coeffs[8][2]=4.9304E-01;
	$coeffs[8][3]=2.1692E+00;
	$coeffs[8][4]=-3.2459E+01;
	$coeffs[8][5]=1.2245E+02;
	$coeffs[8][6]=-2.0243E2;
	$coeffs[8][7]=1.2727E2;
	if($TSF<=$coeffs[0][0]) {
		$row=0;
		$lambdac02=$coeffs[$row][1];
		$C1=$coeffs[$row][2];
		$C2=$coeffs[$row][3];
		$C3=$coeffs[$row][4];
		$C4=$coeffs[$row][5];
		$C5=$coeffs[$row][6];
		$C6=$coeffs[$row][7];
	}elseif($TSF>=$coeffs[8][0]) {
		$row=8;
		$lambdac02=$coeffs[$row][1];
		$C1=$coeffs[$row][2];
		$C2=$coeffs[$row][3];
		$C3=$coeffs[$row][4];
		$C4=$coeffs[$row][5];
		$C5=$coeffs[$row][6];
		$C6=$coeffs[$row][7];
	} else {
		for($row=0;$row<8;$row++){
			$TSFLow=$coeffs[$row][0];
			$TSFHigh=$coeffs[$row+1][0];
			if($TSF>=$TSFLOW&&TSF<=$TSFHigh)
			{
				$scale=($TSF-$TSFLow)/($TSFHigh-$TSFLow);
				$lambdac02=$coeffs[$row][1]+$scale*($coeffs[$row+1][1]-$coeffs[$row][1]);
				$C1=$coeffs[$row][2]+$scale*($coeffs[$row+1][2]-$coeffs[$row][2]);
				$C2=$coeffs[$row][3]+$scale*($coeffs[$row+1][3]-$coeffs[$row][3]);
				$C3=$coeffs[$row][4]+$scale*($coeffs[$row+1][4]-$coeffs[$row][4]);
				$C4=$coeffs[$row][5]+$scale*($coeffs[$row+1][5]-$coeffs[$row][5]);
				$C5=$coeffs[$row][6]+$scale*($coeffs[$row+1][6]-$coeffs[$row][6]);
				$C6=$coeffs[$row][7]+$scale*($coeffs[$row+1][7]-$coeffs[$row][7]);
			}
		}
	}
	if($lambdaC<$lambdac02) {
		return(0.2);
	} else {
		$Rt=$C1+$C2/$lambdaC+$C3/pow($lambdaC,2)+$C4/pow($lambdaC,3)+$C5/pow($lambdaC,4)+$C6/pow($lambdaC,5);
		return($Rt);
	}
}
function calculateRSFIterative($CTPInput,&$CTPInputImg,$ShellType,$N,$Diameter,$tc,$CalcsSpacing,$LengthUnits,$Abbr) {
	$CTPInputImg="";

	$RSF=1.0;
	$processed=Array();
	for($Se=0;$Se<$N;$Se++) { 		
		for($Ss=0;$Ss<$Se;$Ss++) {
			$Ai=0;
			$tempMt="";
			$spreadLength=($Se-$Ss)*$CalcsSpacing;
			$lambda=1.285*$spreadLength/sqrt($Diameter*$tc);
			$Ai0=$spreadLength*$tc;	
			$calcRSF=false;
			$Mt=calculateMt($ShellType,$lambda,$tempMt);
			$temp=($Ss) ." to ". ($Se);
			if(!array_key_exists($temp,$processed)) {
				$processed[$temp]='true';
				$Ai=0;
				for($Ss2=$Ss;$Ss2<$Se;$Ss2++) {
					$left=$Ss2;
					$right=$Ss2+1;
					$b=$right*$CalcsSpacing;;
					$a=$left*$CalcsSpacing;
					$AA=$tc-$CTPInput[$left];
					$BB=$tc-$CTPInput[$right];
					$deltaAi=($b-$a)*($AA+$BB)/2;
					$Ai+=$deltaAi;
					$calcRSF=true;
				}
				if($calcRSF==true) {
					$RSFCurrent=(1-($Ai)/($Ai0))/(1-(1/$Mt)*($Ai/$Ai0));
					$tempSs=($Ss)*$CalcsSpacing;
					$tempSe=($Se)*$CalcsSpacing;
					if($RSFCurrent<=$RSF) {
						$RSF=$RSFCurrent;
						$CTPInputImg="|n|S_s=".formatLengthResults($tempSs,$LengthUnits)."\\ S_e=".formatLengthResults($tempSe,$LengthUnits)."\\ \\|n|";
						$CTPInputImg.="{A_i}=$Ai|n|\\ {Ai_0}=\\left(S_e-S_S\\right){t_c}=$Ai0 |n|";
						$CTPInputImg.="\\lambda=\\frac{1.285{\\left(S_e-S_s\\right)}}{\\sqrt{Dt_c}}=".round($lambda,4)."|n|";
						$CTPInputImg.=$tempMt;
						$CTPInputImg.="\\textup{RSF}=".round($RSF,3)."|n|";
					}
				}
			}
		}
	}
	return $RSF;
}


?>
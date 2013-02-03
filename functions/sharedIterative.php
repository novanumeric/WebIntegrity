<?
function calculateRSFIterative($CTPInput,$CTPInputLengths,&$CTPInputImg,$ShellType,$Diameter,$tc,$LengthUnits) {
	$CTPInputImg="";
	$RSF=1.0;
	$processed=Array();
	$N=count($CTPInput);
	for($Se=0;$Se<$N;$Se++) { 		
		for($Ss=0;$Ss<$Se;$Ss++) {
			$Ai=0;
			$tempMt="";
			$spreadLength=$CTPInputLengths[$Se]-$CTPInputLengths[$Ss];
			$Ai0=$spreadLength*$tc;	
			if(strtoupper($ShellType)=="RSTRENG") {
				$z=pow($spreadLength,2)/($Diameter*$tc);
				$Mt=calculateM($ShellType,$z,$tempMt);
			} else {
				$lambda=1.285*$spreadLength/sqrt($Diameter*$tc);
				$Mt=calculateMt($ShellType,$lambda,$tempMt);
			}
			$calcRSF=false;
			//echo "SL: $spreadLength ||";
			
			$temp=($Ss) ." to ". ($Se);
			if(!array_key_exists($temp,$processed)) {
				$processed[$temp]='true';
				$Ai=0;
				
				for($Ss2=$Ss;$Ss2<$Se;$Ss2++) {
					$left=$Ss2;
					$right=$Ss2+1;
					$CalcsSpacing=$CTPInputLengths[$right]-$CTPInputLengths[$left];
					
					$AA=$tc-$CTPInput[$left];
					$BB=$tc-$CTPInput[$right];
					$deltaAi=($CTPInputLengths[$right]-$CTPInputLengths[$left])*($AA+$BB)/2;
					$Ai+=$deltaAi;
					//$calcRSF=true;
				}
				if($SS<$Se) {
					$RSFCurrent=(1-($Ai)/($Ai0))/(1-(1/$Mt)*($Ai/$Ai0));
					if($RSFCurrent<=$RSF) {
						$RSF=$RSFCurrent;
						$CTPInputImg="|n|S_s=".$Ss."\\ S_e=".$Se."\\ \\|n|";
						$CTPInputImg.="S_{sl}=".formatLengthResults($CTPInputLengths[$Ss],$LengthUnits)."\\ S_{el}=".formatLengthResults($CTPInputLengths[$Se],$LengthUnits)."\\ \\|n|";
						$CTPInputImg.="{A^i}=\int^{S_{el}}_{S_{sl}} LOSS\,ds=$Ai|n|\\ {A^i_0}=\\left(S_{el}-S_{sl}\\right)\\times{t_c}=".formatAreaResults($Ai0,$LengthUnits). "|n|";
						if(strtoupper($ShellType)=="RSTRENG") {
							$CTPInputImg.="z=\\frac{L^2}{Dt}=".round($z,4)."|n|";
						} else { 
							$CTPInputImg.="\\lambda=\\frac{1.285{\\left(S_{el}-S_{sl}\\right)}}{\\sqrt{Dt_c}}=".round($lambda,4)."|n|";
						}
						$CTPInputImg.=$tempMt;
						$CTPInputImg.="\\textup{RSF}=\\frac{1-\left(\\frac{A^i}{A_o^i}\\right)}{1-\\frac{1}{M_t^i}\\left(\\frac{A^i}{A_o^i}\\right)}=".round($RSF,3)."|n|";
					}
				}
			}
		}
	}
	return $RSF;
}
function calculateMt($ShellType,$lambda,&$CTPInputImg) {
	if($ShellType=="Sphere") {
		$Mt=(1.0005  +0.49001*$lambda+0.32409*pow($lambda,2))/(1.0+0.50144*$lambda-0.011067*pow($lambda,2));
		$CTPInputImg.="M_t=\\frac{1.0005+0.49011\\lambda+0.32409\\lambda^2}{1.0+0.50144\\lambda-0.011067\\lambda^2}=".round($Mt,4)."|n|";
	} else {
		$Mt=1.0010 - 0.014195*$lambda+0.29090*pow($lambda,2)-0.096420*pow($lambda,3)+0.020890*pow($lambda,4)-0.0030540*pow($lambda,5)+2.9570/pow(10,4)*pow($lambda,6)-1.8462/pow(10,5)*pow($lambda,7)+7.1553/pow(10,7)*pow($lambda,8)-1.5631/pow(10,8)*pow($lambda,9)+1.4656/pow(10,10)*pow($lambda,10);
		$CTPInputImg.="M_t=1.0010-0.014195\\lambda+0.29090\\lambda^2-0.096420\\lambda^3+0.020890\\lambda^4|n|-0.0030540\\lambda^5+2.9570\\times10^4\\lambda^{-6}-1.8462\\times10^{-5}\\lambda^7|n|+7.1533\\times10^{-7}\\lambda^8-1.5631\\times10^{-8}\\lambda^9+1.4656\\times10^{-10}\\lambda^10|n|=".round($Mt,4)."|n|";
	}
	return $Mt;
}
//Front ASME B31G
function calculateM($ShellType,$z,&$CTPInputImg) {
	if($z<=50.0) {
		$CTPInputImg.="z\\leq50 |n|";
		$M=sqrt(1 + 0.6275*$z - 0.003375*pow($z,2));
		$CTPInputImg.="M=\\sqrt{1+0.6275z-0.003375z^2}=".round($M,4)." |n|";
	} else {
		$CTPInputImg.="z\\ge50 |n|";
		$M=0.032*z + 3.3;
		$CTPInputImg.="M=0.032\\times z+3.3=".round($M)."|n|";
	}
	return $M;
}
?>
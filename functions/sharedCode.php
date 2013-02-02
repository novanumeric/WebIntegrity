<?php 
	function calculateKEllipsoid(&$LengthCalc,$Location,$Ratio)
	{
		$LengthCalc.="|n|{R_{ell}}=".$Ratio."|n|";
		if($Location=="Other") {
			$KEllipsoide=(1/6)*(2+$Ratio);
			$LengthCalc.="|n|K=\\frac{1}{6}\\left(2%2BR_{ell}^2\\right)=\\ $KEllipsoide |n|";
		} else {
			$KEllipsoide=0.25346+0.13995*$Ratio+0.12238*pow($Ratio,2)-0.015297*pow($Ratio,3);
			$LengthCalc.="|n|K=0.25346%2B0.13955{R_{ell}}%2B0.12238{R_{ell}}^2|n|-0.015297{R_{ell}}^3=\\ $KEllipsoide |n|";
		}
		return $KEllipsoide;
	}
	
	function calculateToriconeParameters(&$LengthCalc,&$Lkc,&$M,$Location,$Angle,$Radius,$KnuckleRadius)
	{	
		
		$tempAngle=$Angle*pi()/180;
		$Lkc=($Radius-$KnuckleRadius*(1-cos($tempAngle)))/cos($tempAngle);
	//	$LengthCalc.="|n|R=$Radius, r_k=$KnuckleRadius, angle=$Angle|n|";
		$LengthCalc.="|n|L_{kc}=\\frac{R-r_k\\left(1-\\cos\\left[\\alpha\\right]\\right)}{\\cos\\left[\\alpha\\right]}=".round($Lkc,4)."|n|";
		$M=0.25*(3.0+sqrt($Lkc/$KnuckleRadius));
		$LengthCalc.="|n|M=\\frac{1}{4}\\left(3.0%2b\\sqrt{\\frac{L_{kc}}{r_k}}\\right)=".round($M,4);
	}
	
	function calculateTorisphereParameters(&$LengthCalc,&$M,$Location,$InsideCrownRadius,$KnuckleRadius)
	{
		if($Location=="Center") {
			$M=1;
			$LengthCalc.="|n|M=$M\\ \\textup{Center of Torispherical Head}|n|";
		} else {
			$M=0.25*(3.0+sqrt($InsideCrownRadius/$KnuckleRadius));
			$LengthCalc.="|n|M=\\frac{1}{4}\\left(3.0%2b\\sqrt{\\frac{C_r}{r_k}}\\right)=$M";			
		}

	}
	
?>
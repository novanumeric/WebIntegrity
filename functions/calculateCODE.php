<?

require "sharedCode.php";

$CodeCalculation= new CodeCalculationsClass();
$CodeCalculation->LoadVariables();
echo json_encode($CodeCalculation->processEquations());

class CodeCalculationsClass
{

	public $ShellType, $Radius, $Diameter, $Ratio, $Angle, $DesignCode, $InsideCrownRadius, $InputType, $KnuckleRadius;
	public $FFSLevel, $Pressure, $StressUnits, $Stress, $PressureUnits, $LengthUnits, $AnalysisProcedure, $trd;
	public $tams, $tamc, $tam;
	public $FCA, $Calc, $WeldJointEfficiency, $PressureMoniker, $LengthMoniker;
	public $Loss;
	public $RSFC,$RSFM,$RSF;
	public $KEllipsoide,$M,$Lkc;


	
	function LoadVariables(){
	
		$this->ShellType=$_REQUEST["ShellType"];
	//	$Radius=$_REQUEST["Radius"];
		$this->Diameter=$_REQUEST["Diameter"];

		$this->Location=$_REQUEST["Location"];
		$this->Ratio=$_REQUEST["Ratio"];
		$this->Angle=$_REQUEST["Angle"];
		$this->DesignCode=$_REQUEST["DesignCode"]; 

		$this->InputType=$_REQUEST["InputType"];
		$this->CrownRadius=$_REQUEST["CrownRadius"];
		$this->KnuckleRadius=$_REQUEST["KnuckleRadius"];
		$this->FFSLevel=$_REQUEST["FFSLevel"];
		$this->Pressure=$_REQUEST["Pressure"];
		$this->StressUnits=$_REQUEST["StressUnits"]; 
		$this->Stress=$_REQUEST["Stress"];
		$this->PressureUnits=$_REQUEST["PressureUnits"];
		$this->KEllipsoide=$_REQUEST["KEllipsoide"];
		$this->M=$_REQUEST["M"];
		$this->Lkc=$_REQUEST["Lkc"];
		
		$this->LengthUnits=$_REQUEST["LengthUnits"];
		$this->AnalysisProcedure=$_REQUEST["AnalysisProcedure"];
		$this->tnom=$_REQUEST["CalcsUniformThickness"];
		$this->tams=$_REQUEST["tams"];
		$this->tamc=$_REQUEST["tamc"];
		$this->tam=$_REQUEST["tam"];
		$this->FCA=$_REQUEST["FCA"];
		$this->Calc=$_REQUEST["Calc"];
		$this->Loss=$_REQUEST["CalcsUniformLoss"];
		$this->WeldJointEfficiency=$_REQUEST["WeldJointEfficiency"];
		$this->Radius=$this->Diameter/2+$this->FCA+$this->Loss;		
		$this->RSFC=$_REQUEST["RSFC"];
		$this->RSFM=$_REQUEST["RSFM"];
		$this->RSF=$_REQUEST["RSF"];
		$this->CalcsTheta=$_REQUEST["CalcsTheta"];
		$this->CalcsBeta=$_REQUEST["CalcsBeta"];
		$this->CalcsRb=$_REQUEST["CalcsRb"];
		
		if($this->StressUnits=="ksi") {
			$this->Stress=$this->Stress*1000;
		}
		if($this->StressUnits=="MPa") {
			$this->Stress=$this->Stress*1000000;
		}
		
		if(strtoupper($this->PressureUnits)=="BAR") {
			$this->Pressure=$this->Pressure*100000;
		}
		if(strtoupper($this->PressureUnits)=="MPA") {
			$this->Pressure=$this->Pressure*1000000;
		}
		$this->LengthMoniker= "\\ \\textup{" . $this->LengthUnits ."}";
		$this->PressureMoniker= "\\ \\textup{" . $this->PressureUnits ."}";

	}

	function MAWP_calculateSVIIIDivICylinderOrCone() {
		$Equation="";
		if($this->AnalysisProcedure!="Local Thickness Area - LTA" && $this->FFSLevel=="Level 2") {
			$RSFa=0.9;
			$Equation.="|n| RSF_{a}=".round($RSFa,4)." |n|  ";
			$temp2="/RSF_{a}";
		} else {
			$RSFa=1;
			$temp2="";
		}
		
		if($this->InputType=="Point Thickness Readings(PTR)") {
			$tc=($this->tam-$this->FCA)/$RSFa;
			$Equation.="|n|t_{c}=\\left({t_{am}-FCA}\\right)$temp2=".formatLengthResults($tc,$this->LengthUnits)."}|n|";
			$tcs=$tc;
			$tcc=$tc;
			$temps="";
			$tempc="";
		} elseif(strpos($this->AnalysisProcedure,"Local Thickness Area - LTA") >0){
			$tc=($this->tnom-$this->FCA-$this->Loss);
			$Equation.="|n|t_{c}=\\left({t_{nom}-FCA-LOSS}\\right)=".formatLengthResults($tc,$this->LengthUnits)."}|n|";
			$tcs=$tc;
			$tcc=$tc;
			$temps="";
			$tempc="";
		} else {
			$tcs=($this->tams-$this->FCA)/$RSFa;
			$Equation.="|n|t^s_{c}=\\left({t^s_{am}-FCA}\\right)$temp2=".formatLengthResults($tcs,$this->LengthUnits)."}|n|";
			$temps="^s";
			$tcc=($this->tamc-$this->FCA)/$RSFa;
			$Equation.="|n|t^c_{c}=\\left({t^c_{am}-FCA}\\right)$temp2=".formatLengthResults($tcc,$this->LengthUnits)."}|n|";
			$tempc="^c";
			$tc=min($tcs,$tcc);
		}
		if($this->ShellType=="Cylinder") {
			if($tc<=0.5*$this->Radius) {
				$Equation.="|n|t_{c}<\\frac{1}{2}R|n|";
				$MAWPC=$this->Stress*$this->WeldJointEfficiency*$tcs/($this->Radius+0.6*$tcs);
				$Equation.="|n|MAWP^C=\\frac{SEt".$temps."_{c}}{R+0.6t".$temps."c}=".formatPressureResults($MAWPC,$this->PressureUnits)."|n|";
				$MAWPL=2*$this->Stress*$this->WeldJointEfficiency*$tcc/($this->Radius-0.4*$tcc);
				$Equation.="|n|MAWP^L=\\frac{2SE(t".$tempc."_c)}{R-0.4t".$tempc."c}=".formatPressureResults($MAWPL,$this->PressureUnits)."|n|";
			} else {
				$Equation.="|n|t_{c}\\geq\\frac{1}{2}R|n|";
				$temp1=pow(($this->Radius+$tcs)/$this->Radius,2)-1;
				$temp2=pow(($this->Radius+$tcs)/$this->Radius,2)+1;
				$MAWPC=$this->Stress*$this->WeldJointEfficiency*($temp1)/$temp2;
				$Equation.="|n|MAWP^C=SE\\left[\\left(\\frac{R+t".$temps."_c}{R}\\right)^2-1\\right]\\left[\\left(\\frac{R+t_c}{R}\\right)^2+1\\right]^{-1}=".formatPressureResults($MAWPC,$this->PressureUnits);
				$temp=pow(($this->Radius+$tcc)/$this->Radius,2);
				$MAWPL=2*$this->Stress*$this->WeldJointEfficiency*($temp-1);
				$Equation.="|n|MAWP^L=SE\\left[\\left(\\frac{R+t".$tempc."_c}{R}\\right)^2-1\\right]=".formatPressureResults($MAWPL,$this->PressureUnits);
			}

			
			if(strpos($this->AnalysisProcedure,"Local Thickness Area - LTA")>0&&$this->InputType!="Point Thickness Readings(PTR)") {
				$MAWP=min($MAWPC,$MAWPL);
				$Equation.="|n|MAWP=\\min\\left[MAWP^C,MAWP^L\\right]=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
				$RSFa=0.9;
				$MAWP=min($this->RSF,$RSFa)/$RSFa*$MAWP;				
				$Equation.="|n|MAWP_r=MAWP\\frac{\\max\\left[RSF,RSA_a\\right]}{RSF_a}=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
			} else {
				$MAWP=min($MAWPC,$MAWPL);
				$Equation.="|n|MAWP_r=\\min\\left[MAWP^C,MAWP^L\\right]=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
			}
		}elseif($this->ShellType=="Cone"||$this->ShellType=="Toricone") {
			$tempAngle=cos($this->Angle*pi()/180);
	
			$Equation.="|n|t_{c}<\\frac{1}{2}R|n|";
	
			$MAWPC=2*$this->Stress*$this->WeldJointEfficiency*$tcs*$tempAngle/($this->Diameter+1.2*$tcs*$tempAngle);
			$Equation.="|n|MAWP^C=\\frac{2SEt".$temps."_{c}\\cos\\left[\\alpha\\right]}{D+1.2t".$temps."_c\\cos\\left[\\alpha\\right]}=".formatPressureResults($MAWPC,$this->PressureUnits)."|n|";
			$MAWPL=4*$this->Stress*$this->WeldJointEfficiency*$tcc*$tempAngle/($this->Diameter-0.8*$tcc*$tempAngle);
			$Equation.="|n|MAWP^L=\\frac{4SE(t".$tempc."_c)\\cos\\left[\\alpha\\right]}{D-0.8t".$tempc."_c\\cos\\left[\\alpha\\right]}=".formatPressureResults($MAWPL,$this->PressureUnits)."|n|";
	
			if($this->ShellType=="Toricone") {
				$MAWPK=2*$this->Stress*$this->WeldJointEfficiency*$tcs/($this->Lkc*$this->M+0.2*$tcs);
				$Equation.="|n|MAWP^k=\\frac{2SEt}{L_{kc}M+0.2{t}}=".formatPressureResults($MAWPK,$this->PressureUnits)."|n|";	
			}
			if(strpos($this->AnalysisProcedure,"Local Thickness Area - LTA")>0&&$this->InputType!="Point Thickness Readings(PTR)") {
				if($this->ShellType=="Toricone") {
					$MAWP=min($MAWPC,$MAWPL,$MAWPK);
					$Equation.="|n|MAWP=\\min\\left[MAWP^C,MAWP^L,MAWP^K\\right]=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
					
				} else {
					$MAWP=min($MAWPC,$MAWPL);
					$Equation.="|n|MAWP=\\min\\left[MAWP^C,MAWP^L\\right]=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
					
				}	
				$RSFa=0.9;
				
				$MAWP=min($this->RSFM,$RSFa)/$RSFa*$MAWP;
				$Equation.="|n|MAWP_r=MAWP\\frac{RSF}{RSF_a}=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
			} else {
				if($this->ShellType=="Toricone") {
					$MAWP=min($MAWPC,$MAWPL,$MAWPK);
					$Equation.="|n|MAWP=\\min\\left[MAWP^C,MAWP^L,MAWP^K\\right]=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
					
			 	} else {
					$MAWP=min($MAWPC,$MAWPL);
					$Equation.="|n|MAWP_r=\\min\\left[MAWP^C,MAWP^L\\right]=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
				}
			}
		}
		$details=array('MAWP'=>$MAWP,'MAWPL'=>$MAWPL,'MAWPC'=>$MAWP,'tmin'=> $tc, 'Equation' => $Equation);
		return $details;
	}
	
	
	function MAWP_calculateSVIIIDivISphereFormed() {
		$Equation="";
		$Equation.="|n|R=\\frac{D}{2}+FCA+LOSS=".formatLengthResults($this->Radius,$this->LengthUnits)."|n|";

		if(strpos($this->AnalysisProcedure,"Local Thickness Area - LTA")>0)
		{
			$RSFa=0.9;
			$Equation.="|n| RSF_{a}=".round($RSFa,4).", RSF=".round($this->RSF,4)."|n|  ";
			$temp2="/RSF_{a}";
			
		} else {
			if($this->FFSLevel=="Level 2") {
				$RSFa=0.9;
				$Equation.="|n| RSF_{a}=".round($RSFa,4)." |n|  ";
				$temp2="/RSF_{a}";
			} else {
				$RSFa=1;
				$temp2="";
			}		
		}

		if($this->InputType=="Point Thickness Readings(PTR)") {
			$tc=($this->tam-$this->FCA)/$RSFa;
			$Equation.="|n|t_{c}=({t_{am}}-FCA)$temp2=".formatLengthResults($tc,$this->LengthUnits)."|n|";
		} elseif(strpos($this->AnalysisProcedure,"Local Thickness Area - LTA")>0) {
			$tc=($this->tnom-$this->FCA-$this->Loss);
			$Equation.="|n|t_{c}=\\left({t_{nom}-FCA-LOSS}\\right)=".formatLengthResults($tc,$this->LengthUnits)."}|n|";
		} else {
			$tc=(min($this->tams,$this->tamc)-$this->FCA)/$RSFa;
			$Equation.="|n|t_{c}=(\\min\\left[{t^s_{am}},{t^c_{am}}\\right]-FCA)$temp2=".formatLengthResults($tc,$this->LengthUnits)."|n|";
		}
		if(strpos($this->AnalysisProcedure,"Local Thickness Area - LTA")>0||$this->InputType=="Point Thickness Readings(PTR)") {
			$temp="_r";
		} else {
			$temp="";
		}
		
		if($this->ShellType=="Sphere") {
			if($tc<=0.365*$this->Radius) {
				$Equation.="|n|t_{c}\\leq0.365R|n|";
				$MAWP=$this->Stress*$this->WeldJointEfficiency*$tc/($this->Radius+0.6*$tc);
				$Equation.="|n|MAWP$temp=\\frac{2SEt_c}{R+0.2t_c}=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
			} else {
				$Equation.="|n|t_{c}>0.365R|n|";
				$temp1=pow(($this->Radius+$tc)/$this->Radius,3)-1;
				$temp2=pow(($this->Radius+$tc)/$this->Radius,3)+2;
				$MAWP=$this->Stress*$this->WeldJointEfficiency*($temp1)/$temp2;
				$Equation.="MAWP$temp=SE\\left[\\left(\\frac{R+t_c}{R}\\right)^3-1\\right]\\left[\\left(\\frac{R+t_c}{R}\\right)^3-2\\right]^{-1}=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
			}
		} elseif($this->ShellType=="Ellipsoid") {
			$MAWP=(2*$this->Stress*$this->WeldJointEfficiency*$tc)/($this->KEllipsoide*$this->Diameter+0.2*$tc);
			$Equation.="MAWP=\\frac{2SEt_{c}}{KD+0.2t_{c}}=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
				
		} elseif($this->ShellType=="Torisphere") {
			$MAWP=(2*$this->Stress*$this->WeldJointEfficiency*$tc)/($this->M*$this->CrownRadius+0.2*$tc);
			$Equation.="MAWP=\\frac{2SEt_c}{C_rM+0.2}=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
		}
		if(strrpos($this->AnalysisProcedure,"Local Thickness Area - LTA")>0&&$this->InputType!="Point Thickness Readings(PTR)") {
			$RSFa=0.9;
			$MAWP=min($this->RSF,$RSFa)/$RSFa*$MAWP;
			$Equation.="MAWP_r=MAWP\\frac{RSF}{RSF_a}=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
		}
		
		$MAWPL=-1;
		$MAWPC=-1;
		$details=array('MAWP'=>$MAWP,'MAWPL'=>$MAWPL,'MAWPC'=>$MAWP,'tmin'=> $tc,'Equation' => $Equation);
		return $details;
	}
	function TMIN_calculateSVIIIDivICylinderOrCone() {
		$Equation="";
		$Equation.="|n|R=\\frac{D}{2}+FCA+LOSS=".formatLengthResults($this->Radius,$this->LengthUnits)."|n|";
		if($this->ShellType=="Cylinder")
		{
			if($this->Pressure<=0.385*$this->Stress*$this->WeldJointEfficiency) {
				$Equation.="|n|P=".$this->Pressure."\\leq0.385SE=".(0.385*$this->Stress*$this->WeldJointEfficiency)."|n|";
								
				$tminc=$this->Pressure*$this->Radius/($this->Stress*$this->WeldJointEfficiency-0.6*$this->Pressure);
				$Equation.="t^c_{min}=\\frac{PR}{SE-0.6P}=".formatLengthResults($tminc,$this->LengthMoniker)."|n|";
				$tminl=$this->Pressure*$this->Radius/(2*$this->Stress*$this->WeldJointEfficiency+0.4*$this->Pressure);
				$Equation.="t^l_{min}=\\frac{PR}{2SE+0.4P}=".formatLengthResults($tminl,$this->LengthMoniker)."|n|";
										
			} else {
				$Equation.="|n|P=".$this->Pressure.">0.385SE=".(0.385*$this->Stress*$this->WeldJointEfficiency)."|n|";
						
				$temp=($this->Stress*$this->WeldJointEfficiency+$this->Pressure)/($this->Stress*$this->WeldJointEfficiency-$this->Pressure);
				$tminc=$this->Radius*(sqrt($temp)-1);
				$Equation.="t^c_{min}=R\\left[\\sqrt{\\frac{SE+P}{SE-P}}-1\\right]=".formatLengthResults($tminc,$this->LengthMoniker)."|n|";
								
				$tminl=$this->Radius*(sqrt($this->Pressure/($this->Stress*$this->WeldJointEfficiency)+1)-1);
				$Equation.="t^l_{min}=R\\left[\\sqrt{\\frac{P}{SE}+1}-1\\right]=".formatLengthResults($tminl,$this->LengthMoniker)."|n|";
			}
		}
		else {
			$tempAngle=cos($this->Angle*pi()/180);
			$Equation="|n|";
			$tminc=$this->Pressure*$this->Diameter/(2*cos($tempAngle)*($this->Stress*$this->WeldJointEfficiency-0.6*$this->Pressure));
			$Equation.="t^c_{min}=\\frac{PD}{2\\cos\\left[\\alpha\\right]\\left(SE-0.6P\\right)}=".formatLengthResults($tminc,$this->LengthMoniker)."|n|";
			$tminl=$this->Pressure*$this->Radius/(2*$this->Stress*$this->WeldJointEfficiency+0.4*$this->Pressure);
			$Equation.="t^l_{min}=\\frac{PD}{2\\cos\\left[\\alpha\\right]\\left(SE+0.4P\\right)}=".formatLengthResults($tminl,$this->LengthMoniker)."|n|";
			$tmin=max($tminl,$tminc);
			$Equation.="t_{min}=\\max\\left[t^c_{min},t^l_{min}\\right]=".formatLengthResults($tmin,$this->LengthMoniker)."|n|";
			$details=array('tmin'=>$tmin,'tminc'=>$tminc,'tminl'=>$tminl,'Equation' => $Equation);
			return $details;
		}
		$tmin=max($tminl,$tminc);
		$Equation.="t_{min}=\\max\\left[t^c_{min},t^l_{min}\\right]=".round($tmin,4).$LengthMoniker."|n|";
		$details=array('MAWP'=>$MAWP,'MAWPL'=>$MAWPL,'MAWPC'=>$MAWP,'tmin'=>$tmin,'tminc'=>$tminc,'tminl'=>$tminl,'Equation' => $Equation);		
		return $details;
	}
	function TMIN_calculateSVIIIDivISphereFormed() {	
		if($this->ShellType=="Sphere") {
			$Equation="";
			$Equation.="|n|R=\\frac{D}{2}+FCA+LOSS=".formatLengthResults($this->Radius,$this->LengthUnits)."|n|";
			if($this->Pressure<=0.665*$this->Stress*$this->WeldJointEfficiency) {
				$Equation.="P=".$this->Pressure."\\leq0.665SE=".(0.665*$this->Stress*$this->WeldJointEfficiency)."|n|";
				$Equation.="R=".$this->Radius.", S=".$this->Stress."|n|";
				$tminc=$this->Pressure*$this->Radius/($this->Stress*$this->WeldJointEfficiency-0.2*$this->Pressure);
				$Equation.="t_{min}=\\frac{PR}{SE-0.2P}=".formatLengthResults($tminc,$this->LengthMoniker);
			} else {
				$Equation.="P=".$this->Pressure.">0.665SE=".(0.665*$this->Stress*$this->WeldJointEfficiency)."|n|";
				$temp=2*($this->Stress*$this->WeldJointEfficiency+$this->Pressure)/(2*$Stress*$WeldJointEfficiency-$Pressure);
				$tminc=$this->Radius*(pow($temp,1/3)-1);
				$Equation.="t_{min}=R\\left(\\left[\\frac{2(SE+P)}{2SE-P}\\right]^\\frac{1}{3}-1\\right)=".round($tminc,4).$LengthMoniker;
			}
		} elseif($this->ShellType="Ellipsoid") {
			$Equation="";
			$Equation.="|n|R=\\frac{D}{2}+FCA+LOSS=".formatLengthResults($this->Radius,$this->LengthUnits)."|n|";
			$Equation.="|n|{R_{ell}}=".$this->Ratio."|n|";
			
			$tminc=$this->Pressure*$this->Diameter*$this->KEllipsoide/(2*$this->Stress*$this->WeldJointEfficiency-0.2*$this->Pressure);
			$Equation.="t_{min}=\\frac{PDK}{2SE-0.2P}=".formatLengthResults($tminc,$this->LengthMoniker);
			
		}
		$tminl=$tminc;
		$tmin=max($tminl,$tminc);
		$details=array('tmin'=>$tmin,'tminc'=>$tminc,'tminl'=>$tminl,'Equation' => $Equation);
		return $details;
	}
	function calculateLF(&$Equation,&$Lf,&$tc) {
			$Rm=($this->Diameter)/2-($this->tnom-$this->FCA-$this->Loss)/2;
			$Equation.="|n|R_m=\\frac{D_o}{2}-\\left(\\frac{t_{nom}-FCA-LOSS}{2}\\right)=".formatLengthResults($Rm,$this->LengthUnits)."|n|";
			if($this->CalcsTheta==0) {
				$Lf=1;
				$Equation.="L_{f}=1.0|n|";
			} else {
			$num=($this->CalcsRb/$Rm+sin($this->CalcsTheta*pi()/180)/2);
			$den=($this->CalcsRb/$Rm+sin($this->CalcsTheta*pi()/180));
			if($den==0)  {
				$Lf=1;
			} else {
				$Lf=$num/$den;
			}
			if($this->CalcsTheta==-90 ||$this->CalcsTheta==270) {
				$Equation.="L_{f}=\\left(\\frac{\\frac{R_b}{R_m}-0.5}{\\frac{R_b}{R_m}-1.0}\\right)=".round($Lf,4)."|n|";
			} elseif($this->CalcsTheta==90||$this->CalcsTheta==180) {
				$Equation.="L_{f}=\\left(\\frac{\\frac{R_b}{R_m}+0.5}{\\frac{R_b}{R_m}+1.0}\\right)=".round($Lf,4)."|n|";
			} else {
				$Equation.="L_{f}=\\left(\\frac{\\frac{R_b}{R_m}+\\frac{\\sin{\\theta}}{2}}{\\frac{R_b}{R_m}+\\sin{\\theta}}\\right)=".round($Lf,4)."|n|";
			}
			}
			$tc=($this->tnom-$this->FCA-$this->Loss);
			$Betaa=(121.5*$Rm)/($this->CalcsRb-$Rm)*sqrt($tc/$Rm);
			$Equation.="t_c=t_{nom}-FCA-LOSS=".formatLengthResults($tc,$this->LengthUnits)."|n|";
			$Equation.="{\\beta}_a=\\left(\\frac{121.5R_m}{R_b-R_m}\\right)\\sqrt{\\frac{t_c}{R_m}}=".formatAngleResults($Betaa)."|n|";
			if($this->CalcsBeta>2*$Betaa) {
				$Equation.="{\\beta>2\\beta_a}|n|";
			} else {
				$Equation.="{\\color{Red}\\beta\\leq\\beta_a}|n|";
			}
			//$this->Pressure*$this->Diameter*$this->KEllipsoide/(2*$this->Stress*$this->WeldJointEfficiency-0.2*$this->Pressure);
	}
	function TMIN_calculateB31CylinderOrElbow()	{	

		$MAWP="";
		$MAWPL="";
		$Equation="";
		if($this->ShellType=="Pipe") {
			$MA=0;
			$YB31=0.6;
			$den=2*($this->Stress*$this->WeldJointEfficiency+$this->Pressure*$YB31);
			$tminc=$this->Pressure*$this->Diameter/$den+$MA;
			$Equation.="|n|t_{min}^C=\\frac{PD_o}{2{SE+PY_{B31}}}+MA=".formatLengthResults($tminc,$this->LengthUnits)."|n|";
			$den=4*($this->Stress*$this->WeldJointEfficiency+$this->Pressure*$YB31);
			$tminl=$this->Pressure*$this->Diameter/$den+$MA;
			$Equation.="t_{min}^L=\\frac{PD_o}{4\\left(SE+PY_{B31}\\right)}+MA=".formatLengthResults($tminl,$this->LengthUnits)."|n|";
		} elseif($this->ShellType=="Elbow") {
			$Equation="";
			$tc=($this->tnom-$this->FCA-$this->Loss);
			$Equation.="|n|t_c=t_{nom}-FCA-LOSS=".formatLengthResults($tc,$this->LengthUnits)."|n|";
			$this->calculateLf($Equation,$Lf,$tc);
			$MA=0;
			$YB31=0.6;
			$den=2*($this->Stress*$this->WeldJointEfficiency/$Lf+$this->Pressure*$YB31);
			$tminc=$this->Pressure*$this->Diameter/$den+$MA;
			$Equation.="t_{min}^C=\\frac{PD_o}{2{\\frac{SE}{L_f}+PY_{B31}}}+MA=".formatLengthResults($tminc,$this->LengthUnits)."|n|";
			$den=4*($this->Stress*$this->WeldJointEfficiency+$this->Pressure*$YB31);
			$tminl=$this->Pressure*$this->Diameter/$den+$MA;
			$Equation.="t_{min}^L=\\frac{PD_o}{4\\left(SE+PY_{B31}\\right)}+MA=".formatLengthResults($tminl,$this->LengthUnits)."|n|";
		}
		$tmin=max($tminl,$tminc);
		$details=array('MAWP'=>$MAWP,'MAWPL'=>$MAWPL,'MAWPC'=>$MAWP,'tmin'=>$tmin,'tminc'=>$tminc,'tminl'=>$tminl,'Equation' => $Equation);
		return $details;
	}
	function MAWP_calculateB31CylinderOrElbow()	{		
		$Equation="";
		//$Equation.="|n|R=\\frac{D}{2}+FCA+LOSS=".formatLengthResults($this->Radius,$this->LengthUnits)."|n|";
		if($this->AnalysisProcedure!="Local Thickness Area - LTA" && $this->FFSLevel=="Level 2") {
			$RSFa=0.9;
			$Equation.="|n| RSF_{a}=".round($RSFa,4)." |n|  ";
			$temp2="/RSF_{a}";
		} else {
			$RSFa=1;
			$temp2="";
		}
		
		if($this->InputType=="Point Thickness Readings(PTR)") {
			$tc=($this->tam-$this->FCA)/$RSFa;
			$Equation.="|n|t_{c}=\\left({t_{am}-FCA}\\right)$temp2=".formatLengthResults($tc,$this->LengthUnits)."}|n|";
			$tcs=$tc;
			$tcc=$tc;
			$temps="";
			$tempc="";
		} elseif(strpos($this->AnalysisProcedure,"Local Thickness Area - LTA") >0){
			$tc=($this->tnom-$this->FCA-$this->Loss);
			$Equation.="|n|t_{c}=\\left({t_{nom}-FCA-LOSS}\\right)=".formatLengthResults($tc,$this->LengthUnits)."}|n|";
			$tcs=$tc;
			$tcc=$tc;
			$temps="";
			$tempc="";
		} else {
			$tcs=($this->tams-$this->FCA)/$RSFa;
			$Equation.="|n|t^s_{c}=\\left({t^s_{am}-FCA}\\right)$temp2=".formatLengthResults($tcs,$this->LengthUnits)."}|n|";
			$temps="^s";
			$tcc=($this->tamc-$this->FCA)/$RSFa;
			$Equation.="|n|t^c_{c}=\\left({t^c_{am}-FCA}\\right)$temp2=".formatLengthResults($tcc,$this->LengthUnits)."}|n|";
			$tempc="^c";
			$tc=min($tcs,$tcc);
		}

		if($this->ShellType=="Pipe") {
			$MA=0;
			$YB31=0.6;
			//$den=2*($this->Stress*$this->WeldJointEfficiency+$this->Pressure*$YB31);
			//$MAWPC=$this->Pressure*$this->Diameter/$den+$MA;
			$den=($this->Diameter-2*$YB31*($tcs-$MA));
			$MAWPC=2*($this->Stress*$this->WeldJointEfficiency)*($tcs-$MA)/$den;
			$Equation.="|n|MAWP^C=\\frac{2SE\\left(t".$temps."_{c}-MA\\right)}{D_o-2Y_{B31}\\left(".$temps."_{c}-MA\\right)}=".formatLengthResults($MAWPC,$this->PressureUnits)."|n|";
			$den=($this->Diameter-4*$YB31*($tc-$MA));
			$MAWPL=4*($this->Stress*$this->WeldJointEfficiency)*($tcc-$MA)/$den;
			$Equation.="MAWP^L=\\frac{4SE\\left(t".$tempc."_c-MA\\right)}{D_o-4Y_{B31}\\left(t_c-MA\\right)}=".formatLengthResults($MAWPL,$this->PressureUnits)."|n|";
		} elseif($this->ShellType=="Elbow") {
			$this->calculateLf($Equation,$Lf,$tc);
			$MA=0;
			$YB31=0.6;
			$den=($this->Diameter-2*$YB31*($tcs-$MA));
			$MAWPC=2*($this->Stress/$Lf*$this->WeldJointEfficiency)*($tcs-$MA)/$den;
			$Equation.="|n|MAWP^C=\\frac{2\\frac{SE}{L_f}\\left(t".$temps."_{c}-MA\\right)}{D_o-2Y_{B31}\\left(".$temps."_{c}-MA\\right)}=".formatLengthResults($MAWPC,$this->PressureUnits)."|n|";

			$den=($this->Diameter-4*$YB31*($tc-$MA));
			$MAWPL=4*($this->Stress*$this->WeldJointEfficiency)*($tcc-$MA)/$den;
			$Equation.="MAWP^L=\\frac{4SE\\left(t".$tempc."_c-MA\\right)}{D_o-4Y_{B31}\\left(t_c-MA\\right)}=".formatLengthResults($MAWPL,$this->PressureUnits)."|n|";
		}
		if(strpos($this->AnalysisProcedure,"Local Thickness Area - LTA")>0&&$this->InputType!="Point Thickness Readings(PTR)") {
			$MAWP=min($MAWPC,$MAWPL);
			$Equation.="|n|MAWP=\\min\\left[MAWP^C,MAWP^L\\right]=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
			$RSFa=0.9;
			$MAWP=min($this->RSF,$RSFa)/$RSFa*$MAWP;
			$Equation.="RSF_a=$RSFa|n|";
			$Equation.="|n|MAWP_r=MAWP\\frac{\\max\\left[RSF,RSA_a\\right]}{RSF_a}=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
		} else {
			$MAWP=min($MAWPC,$MAWPL);
			$Equation.="|n|MAWP_r=\\min\\left[MAWP^C,MAWP^L\\right]=".formatPressureResults($MAWP,$this->PressureUnits)."|n|";
		}

		$MAWP="";
		$MAWPL=-1;
		$MAWPC=-1;
		$tc="";
		$details=array('MAWP'=>$MAWP,'MAWPL'=>$MAWPL,'MAWPC'=>$MAWP,'tmin'=> $tc,'Equation' => $Equation);
		return $details;
	}
	function generateDetails($text) {
		$details=array('MAWP'=>'0','MAWPC'=>'0','MAWPL'=>'0','tmin'=>'0','tminc'=>'0','tminl'=>'0','Equation' => "\\textup{".$text."}");
		return $details;
	}
	function processEquations() {
		//$details=array('MAWP'=>'0','MAWPC'=>'0','MAWPL'=>'0','tmin'=>'0','tminc'=>'0','tminl'=>'0','Equation' => "\\textup{Not Implemented}");
		$details=$this->generateDetails("Not implemented");
		if($this->ShellType=="Cone"||$this->ShellType=="Toricone"||$this->ShellType=="Cylinder") {	
			if(strtoupper($this->Calc)=="TMIN") {
				$details=$this->TMIN_calculateSVIIIDivICylinderOrCone();
			} else {
				$details=$this->MAWP_calculateSVIIIDivICylinderOrCone();
			}
		} elseif($this->ShellType=="Ellipsoid"||$this->ShellType=="Torisphere"||$this->ShellType=="Sphere") {
			if(strtoupper($this->Calc)=="TMIN") {
				$details=$this->TMIN_calculateSVIIIDivISphereFormed();
			} else {
				$details=$this->MAWP_calculateSVIIIDivISphereFormed();
			}		
		} elseif($this->ShellType=="Pipe"||$this->ShellType=="Elbow") {		
			if(strtoupper($this->Calc)=="TMIN") {
				$details=$this->TMIN_calculateB31CylinderOrElbow();
			} else {
				$details=$this->MAWP_calculateB31CylinderOrElbow();
			}
		}	
			
		return $details;
	}
	

}

?>

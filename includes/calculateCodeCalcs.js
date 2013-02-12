function calculateCodeCalcs() {
	$("#ResultsSpan").show();
		
		if($("#selectCriteria").val()=="Thickness"||$("#selectAnalysisProcedure").val().indexOf("Brittle Fracture")>0) {
			$Calc="TMIN";
		} else {
			$Calc="PRESS";
		}


		$.ajax(	{
			type: 'POST', 
			url:'functions/calculateCODE.php',
			async: false,
			dataType: "json",
			data:{
				DesignCode:$("#selectDesignCode").val(), ShellType:$("#selectShellType").val(),
				Pressure:$("#CalcsPressure").val(),	Diameter:$("#CalcsDiameter").val(),
				Stress:$("#CalcsAllowableStress").val(), Ratio:$("#CalcsRatio").val(),			
				Calc:$Calc,
				FFSLevel:$("#selectFFSLevel").val(),
				Angle:$("#CalcsAngle").val(), 
				AnalysisProcedure:$('#selectAnalysisProcedure').val(), InputType:$("#selectInputType").val(),
				CrownRadius:$("#CalcsCrownRadius").val(), KnuckleRadius:$("#CalcsKnuckleRadius").val(),
				WeldJointEfficiency:$("#CalcsWeldJointEfficiency").val(), LengthUnits:window.LengthUnits,
				PressureUnits:window.PressureUnits, StressUnits:window.StressUnits,
				FCA:$("#CalcsCorrosionAllowance").val(),
				CalcsUniformThickness:$("#CalcsUniformThickness").val(),
				CalcsUniformLoss:$("#CalcsUniformLoss").val(),
				CalcsRb:$("#CalcsRb").val(),
				CalcsBeta:$("#CalcsBeta").val(),
				CalcsTheta:$("#CalcsTheta").val(),
				tam:window.tam,
				tams:window.tams,
				tamc:window.tamc,
				RSFC:window.RSFC,
				RSFM:window.RSFM,
				RSF:window.RSF,
				M:window.M,
				Lkc:window.Lkc,
				KEllipsoide:window.KEllipsoide,
				Location:$('#selectLocation').val()				
			}
		}).done(	function displayGMLThicknessCalc(data) {
				$("#GMLThicknessCalcWork").show();
				$("#GMLThicknessCalcWork").attr("src","functions/render.php?equation=" + encodeURIComponent(data.Equation));
				window.tmin=data.tmin;
				window.tminl=data.tminl;
				window.tminc=data.tminc;
				window.MAWP=data.MAWP;
				window.MAWPL=data.MAWPL;
				window.MAWPC=data.MAWPC;
			}	
		);				
//	}
	
}

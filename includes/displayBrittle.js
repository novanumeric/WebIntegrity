function displayBrittle() {
	$.ajax({type: 'POST', 
		url:'functions/calculateBrittle.php',
		async: false,
		dataType: "json",
		data:{
			Calc:$Calc,
			tmin:window.tmin, tminl:window.tminl,
			tminc:window.tminc, tam:window.tam,
			tams:window.tams, tamc:window.tamc,
			tmm:window.tmm,
			MAWP:window.MAWP,
			MAWPL:window.MAWPL,
			MAWPC:window.MAWPC,
			RSF:window.RSF,
			RSFM:window.RSFM,
			RSFC:window.RSFC,
			WeldJointEfficiency:$("#CalcsWeldJointEfficiency").val(),
			EllipsoidLocation:$("#selectEllipsoidLocation").val(),
			AnalysisProcedure:$('#selectAnalysisProcedure').val(),
			FFSLevel:$("#selectFFSLevel").val(),
			InputType:$("#selectInputType").val(),
			ShellType:$("#selectShellType").val(),
			SectionVIIICurve:$("#SectionVIIICurve").val(),
			LengthUnits:window.LengthUnits,
			ThresholdRTS:$("#ThresholdRTS").val(),
			StressUnits:window.StressUnits,
			PressureUnits:window.PressureUnits, 
			TemperatureUnits:window.TemperatureUnits,
			LengthUnits:window.LengthUnits,
			FCA:$("#CalcsCorrosionAllowance").val(),
			CalcsUniformLoss:$("#CalcsUniformLoss").val(),
			CalcsUniformThickness:$("#CalcsUniformThickness").val(),
			SectionVIIICurve:$("#SectionVIIICurve").val()
		}}).done(	function displayResultsFinal(data) {
			if(data.GMLThicknessCalcResult!="") {
				$("#GMLThicknessCalcResult").show();
				$("#GMLThicknessCalcResult").attr("src","functions/render.php?equation=" + encodeURIComponent(data.GMLThicknessCalcResult));			
			} else {
				$("#GMLThicknessCalcResult").hide();
			}
			$("#TempReduction").html("");			
			$("#GovThickness").html("");
			var myData = new Array();
			dataSplitT=data.Temperatures.split(",");
			dataSplitL=data.Lengths.split(",");
			for (i=0;i<dataSplitT.length;i++) {
				var b=new Array(2);
				b[0]=parseFloat(dataSplitL[i]);
				b[1]=parseFloat(dataSplitT[i]);
				myData.push(b);
			}
		
			var plot2 = $.jqplot ('GovThickness', [myData,[[$("#CalcsUniformThickness").val(),data.MAT]]], {
				series:[
					{showMarker:false},
					{showMarker:true,showLine:false,markerOptions:{ size: 5, style:"x" }}
				],
				  // Give the plot a title.
				 title: '',
				  // You can specify options for all axes on the plot at once with
				  // the axesDefaults object.  Here, we're using a canvas renderer
				  // to draw the axis label which allows rotated text.
				  axesDefaults: {
					labelRenderer: $.jqplot.CanvasAxisLabelRenderer
				  },
				  
				  // An axes object holds options for all axes.
				  // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
				  // Up to 9 y axes are supported.
				 // {yaxis:'yaxis',showMarker:true,showLine:false,markerOptions:{ size: 3, style:markerStyle }}
				  axes: {
					// options for each axis are specified in seperate option objects.
					xaxis: {
					  label: data.XAxisLabel,
					  // Turn off "padding".  This will allow data point to lie on the
					  // edges of the grid.  Default padding is 1.2 and will keep all
					  // points inside the bounds of the grid.
					  pad: 0
					},
					yaxis: {
						
					  label: data.YAxisLabel
					}
				  }
				});
				if(data.PlotCount>1) {
					$("#TempReduction").show();
					var myData = new Array();
					dataSplitL=data.TemperatureReductions.split(",");
					dataSplitT=data.StressRatios.split(",");
					for (i=0;i<dataSplitT.length;i++) {
						var b=new Array(2);
						b[0]=parseFloat(dataSplitL[i]);
						b[1]=parseFloat(dataSplitT[i]);
						myData.push(b);
					}


					var plot2 = $.jqplot ('TempReduction', [myData,[[data.TR,data.RTS]]], {
					series:[{showMarker:false},
					{showMarker:true,showLine:false,markerOptions:{ size: 5, style:"x" }}],
					  // Give the plot a title.
					 // title: 'Temperature Reduction',
					  // You can specify options for all axes on the plot at once with
					  // the axesDefaults object.  Here, we're using a canvas renderer
					  // to draw the axis label which allows rotated text.
					  axesDefaults: {
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer
					  },
					  // An axes object holds options for all axes.
					  // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
					  // Up to 9 y axes are supported.
					  axes: {
						// options for each axis are specified in seperate option objects.
						xaxis: {
						  label: data.XAxisLabel2,
						  // Turn off "padding".  This will allow data point to lie on the
						  // edges of the grid.  Default padding is 1.2 and will keep all
						  // points inside the bounds of the grid.
						  pad: 0
						},
						yaxis: {
						  label: data.YAxisLabel2
						}
					  }
					});
				} else {
					$("#TempReduction").hide();

				}
		});	
}
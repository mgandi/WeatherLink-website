<?php
/* @var $this SiteController */
/* @var $stations Stations[] */

$this->pageTitle=Yii::app()->name . ' - Data';

// Register client script
Yii::app()->clientScript->registerScript('dataScript', "

var rootUrl = '" . Yii::app()->createAbsoluteUrl('site/getData') . "';
var currentStation = '" . $stations[0]->name . "';
var now = new Date();

$( '.stations' ).change(function(e) {
	setCurrentStation(e.target.options[e.target.selectedIndex].text);
});

function drawWindRose(data)
{
	var d = [];
	
	// Re-format points
	data.forEach(function(entry) {
		d.push([entry[0], entry[1], Math.pow(2, (parseFloat(entry[2] & 0xFFFF)) / 200)]);
	});
	
	// Parse the data from an inline table using the Highcharts Data plugin
	$('#chartwindrose').highcharts({	    
		chart: {
			polar: true,
			type: 'bubble',
		},
		
		title: {
			text: 'Rose des vents'
		},
        
        tooltip: {
            formatter: function() {
				var date = new Date(this.point.x * 1000);
                return this.point.y +'km/h - ' + this.point.x + '°';
            }
        },
		
		pane: {
			startAngle: 0,
			endAngle: 360,
		},
		
		xAxis: {
			min: 0,
			max: 360,
			tickInterval: 22.5,
			labels: {
                enabled: true,
				formatter: function() {
					switch (this.value) {
						case 0:
							return 'N';
//						case 22.5:
//							return 'NNE';
//						case 45:
//							return 'NE';
//						case 67.5:
//							return 'ENE';
						case 90:
							return 'E';
//						case 112.5:
//							return 'ESE';
//						case 135:
//							return 'SE';
//						case 157.5:
//							return 'SSE';
						case 180:
							return 'S';
//						case 202.5:
//							return 'SSW';
//						case 225:
//							return 'SW';
//						case 247.5:
//							return 'WSW';
						case 270:
							return 'W';
//						case 292.5:
//							return 'WNW';
//						case 315:
//							return 'NW';
//						case 337.5:
//							return 'NNW';
						default:
							return '';
					}
				},
            },
		},
		
		yAxis: {
			min: 0,
			tickInterval: 5,
            alternateGridColor: '#EEEEEE',
		},
		
		series: [{
			name: 'Plus un point est gros plus il est récent',
			data: d,
			marker: {
				fillColor: '#5A5A5A',
				lineWidth: 0,
				lineColor: null // inherit from series
			}
		}],
		
		legend: {
			enabled: true,
		},
		
		plotOptions: {
			bubble: {
				minSize: 0.1,
				maxSize: 15,
				color: '#5A5A5A',
			},
		},
        
        credits: {
            position: {
                align: 'center',
                x: 10
            }
        },
	});
}

function updateWindRose(data)
{
	// Re-format and add points
	data.forEach(function(entry) {
		var p = [entry[0], entry[1], Math.pow(2, (parseFloat(entry[2] & 0xFFFF)) / 200)];
		$('#chartwindrose').highcharts().series[0].addPoint(p, true, true);
	});
}

function drawClassic(data)
{
	var d = [];
	
	// Re-format points
	data.forEach(function(entry) {
		d.push([parseInt(entry[2] / 60) * 60, entry[1], entry[0]]);
	});
	
	// Parse the data from an inline table using the Highcharts Data plugin
	$('#chartclassic').highcharts({
		
		chart: {
			polar: false,
			type: 'wind',
		},
		
		title: {
			text: 'Le classique'
		},
        
        tooltip: {
            formatter: function() {
				var date = new Date(this.point.x * 1000);
                return (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':' + (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()) + ' - '+ this.point.y +'km/h - ' + this.point.z + '°';
            }
        },
		
		xAxis: {
			tickInterval: 900,
			labels: {
                enabled: true,
				formatter: function() {
					var date = new Date(this.value * 1000);
					return (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':' + (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes());
				},
            },
            gridLineDashStyle: 'longdash',
			gridLineWidth: 1,
		},
		
		yAxis: {
			min: 0,
			tickInterval: 5,
			title: {
				text: 'km/h',
			},
		},
		
		series: [{
			name: 'data',
			data: d,
			marker: {
				symbol: 'flag',
				fillColor: '#5A5A5A',
				lineWidth: 1,
				lineColor: '#5A5A5A',
				radius: 4,
			}
		}],
		
		legend: {
			enabled: false,
		},
		
		plotOptions: {
			bubble: {
				minSize: 5,
				maxSize: 5,
				color: '#5A5A5A',
			},
			line: {
				lineWidth: 0,
			},
		},
        
        credits: {
            position: {
                align: 'center',
                x: 10
            }
        },
	});
}

function updateClassic(data)
{
	// Re-format and add points
	data.forEach(function(entry) {
		var p = [entry[2], entry[1], entry[0]];
		$('#chartclassic').highcharts().series[0].addPoint(p, true, true);
	});
}

function updateSummary(latest)
{
	$('<table class=\"table\"><thead><tr><td>R&eacute;capitulatif</td><td>Courant</td><td>Maximum du jour</td><td>Minimum du jour</td></tr></thead><tbody><tr><td>Temp&eacute;rature ext&eacute;rieure</td><td>'+latest.currentOutsideTemperature+'&deg;</td><td>'+latest.maxOutsideTemperature+'&deg;</td><td>'+latest.minOutsideTemperature+'&deg;</td></tr></tbody></table>').appendTo('#summary');
}

function loadData(success, stationName, deepnes)
{
	// Set deepnes to default value
	deepnes = typeof deepnes !== 'undefined' ? deepnes : 7200;
	
	// Compose url var
	var url = rootUrl + '&stationName=' + stationName + '&deepness=' + deepnes;
	
	// Send ajax request
	$.ajax({
		type: 'POST',
		url: url,
		dataType: 'json',
		success: success,
		error: function (data) {
		},
	});
}

function setCurrentStation(stationName)
{
	// Save current station's name
	currentStation = stationName;
	
	// Load data and display it
	loadData(function(data) {
		drawWindRose(data.data);
		drawClassic(data.data);
		updateSummary(data.latest);
	}, currentStation);
}

function captureNextGraphUpdate()
{
	now = new Date();
	now.setMinutes(now.getMinutes() + 1);
}

// Initial display
setCurrentStation(currentStation);

// Setup timer to trigger data update every minutes
captureNextGraphUpdate();
window.setInterval(function() {
	loadData(function(data) {
		captureNextGraphUpdate();
		updateWindRose(data.data);
		updateClassic(data.data);
	}, currentStation, 60)
}, 60 * 1000);

window.setInterval(function () {
	var tmp = new Date(),
	secondsLeft = Math.round((now.getTime() - tmp.getTime()) / 1000);
	$('#counter').html('<h4 style=\"float: right;\">Prochaine mise &agrave; jour dans ' + secondsLeft + ' secondes</h4>');
}, 1 * 1000);

");
?>

<h1>Data</h1>

<select class="stations">
<?php
for ($i = 0; $i < count($stations); $i++) {
	echo '<option>' . $stations[$i]->name . '</option>';
}
?>
</select>

<div class="row-fluid">
  <h2>R&eacute;capitulatif</h2>
</div>
<div class="row-fluid">
    <div id="summary"></div>
</div>
<div class="row-fluid">
  <h2>Graphics</h2>
</div>
<div class="row-fluid">
  <div class="span4">
    <div id="chartwindrose" style="height:400px;"></div>
  </div>
  <div class="span8">
    <div id="chartclassic" style="height:400px;"></div>
  </div>
</div>
<div class="row-fluid">
  <div id="counter"></div>
</div>

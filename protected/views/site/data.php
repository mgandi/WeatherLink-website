<?php
/* @var $this SiteController */
/* @var $stations Stations[] */

$this->pageTitle=Yii::app()->name . ' - Data';

Yii::app()->clientScript->registerScriptFile('http://code.highcharts.com/highcharts.js');
Yii::app()->clientScript->registerScriptFile('http://code.highcharts.com/highcharts-more.js');

Yii::app()->clientScript->registerScript('dataScript', "

/*
$( '.stations' ).change(function () {
	alert( 'handler for change called' );
});
*/

function drawWindRose(data)
{
	// Parse the data from an inline table using the Highcharts Data plugin
	$('#chartradar').highcharts({	    
		chart: {
			polar: true,
			type: 'bubble',
		},
		
		title: {
			text: 'Wind rose for South Shore Met Station, Oregon'
		},
		
		pane: {
			startAngle: 0,
			endAngle: 360,
		},
		
		xAxis: {
			min: 0,
			max: 360,
			tickInterval: 45,
		},
		
		yAxis: {
			min: 0,
			max: 20,
			tickInterval: 5,
		},
		
		series: [{
			name: 'data',
			data: data,
		}],
		
		plotOptions: {
			bubble: {
				minSize: 1,
				maxSize: 20,
				color: '#5A5A5A',
			},
		},
	});
}

function loadData()
{
	$.ajax({
		type: 'POST',
		url: '" . Yii::app()->createAbsoluteUrl('site/getData', array('stationName'=>$stations[0]->name)) . "',
		dataType: 'json',
		success: function (data) {
			drawWindRose(data);
		},
		error: function (data) {
		},
	});
}

loadData();

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
  <div class="span6">
    <div id="chartradar" style="height:400px;"></div>
  </div>
  <div class="span6">
    <div id="chartgauge" style="height:400px;"></div>
  </div>
</div>

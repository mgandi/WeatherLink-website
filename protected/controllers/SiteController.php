<?php

class SiteController extends Controller
{
	/**
	 * Access rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				  'actions'=>array('index', 'error', 'data', 'info'),
				  'users'=>array('*'),
				 ),
			array('deny',
				  'users'=>array('*'),
				 ),
		);
	}
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionData()
	{
		// Get the list of stations
		$stations = Stations::model()->findAll();
		
		$this->render('data', array('stations'=>$stations));
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionInfo()
	{
		$this->render('info');
	}
	

	/**
	 * JSON methods
	 */

	/**
	 * JSON action to get data of a station
	 */
	public function actionGetData($stationName, $deepness = 7200)
	{
		Yii::log('Request for data from station ' . $stationName . ' with a deepness of ' . $deepness);
		
		// Check that the station exists
		$station = Stations::model()->findByAttributes(array('name'=>$stationName));
		if ($station == NULL) {
			Yii::log('The station does not exist');
			Yii::app()->end();
		}
		
		// Get timestamp now
		$timestamp = time();
		$limit = $timestamp - $deepness;
		
		// Retrieve data
		$data = MeteoStation::model($stationName)->findAll('timeStamp>:timeStamp', array(':timeStamp'=>$limit));
		
		// Reformat data
		$subdata = array_map(create_function('$m','return array($m->currentWindDirection, $m->currentWindSpeed, $m->timeStamp);'),$data);
		
		// Create array for latest
		$latest = array('id'=>$data[0]->id,
		'timeStamp'=>$data[0]->timeStamp,
		'currentOutsideTemperature'=>$data[0]->currentOutsideTemperature,
		'maxOutsideTemperature'=>$data[0]->maxOutsideTemperature,
		'minOutsideTemperature'=>$data[0]->minOutsideTemperature,
		'currentOutsideHumidity'=>$data[0]->currentOutsideHumidity,
		'maxOutsideHumidity'=>$data[0]->maxOutsideHumidity,
		'minOutsideHumidity'=>$data[0]->minOutsideHumidity,
		'currentInsideTemperature'=>$data[0]->currentInsideTemperature,
		'maxInsideTemperature'=>$data[0]->maxInsideTemperature,
		'minInsideTemperature'=>$data[0]->minInsideTemperature,
		'currentInsideHumidity'=>$data[0]->currentInsideHumidity,
		'maxInsideHumidity'=>$data[0]->maxInsideHumidity,
		'minInsideHumidity'=>$data[0]->minInsideHumidity,
		'currentHeatIndex'=>$data[0]->currentHeatIndex,
		'maxHeatIndex'=>$data[0]->maxHeatIndex,
		'currentWindChill'=>$data[0]->currentWindChill,
		'minWindChill'=>$data[0]->minWindChill,
		'currentDewPoint'=>$data[0]->currentDewPoint,
		'maxDewPoint'=>$data[0]->maxDewPoint,
		'minDewPoint'=>$data[0]->minDewPoint,
		'currentPressure'=>$data[0]->currentPressure,
		'maxPressure'=>$data[0]->maxPressure,
		'minPressure'=>$data[0]->minPressure,
		'currentWindSpeed'=>$data[0]->currentWindSpeed,
		'maxWindSpeed'=>$data[0]->maxWindSpeed,
		'currentWindDirection'=>$data[0]->currentWindDirection,
		'averageWindSpeed2Minutes'=>$data[0]->averageWindSpeed2Minutes,
		'averageWindSpeed10Minutes'=>$data[0]->averageWindSpeed10Minutes,
		'windGust'=>$data[0]->windGust);
		
		// Create table of data to send
		$allData = array('latest'=>$latest, 'data'=>$subdata);
		
		echo json_encode($allData, JSON_NUMERIC_CHECK);
		Yii::app()->end();
	}

	/**
	 * JSON acton to get the list of station names
	 */
	public function actionGetStations()
	{
		Yii::log('Request for station names');
		
		// Get the list of stations
		$stations = Stations::model()->findAll();
		
		// Build an array of station names
		$names = array();
		for ($i = 0; $i < count($stations); $i++) {
			$station = $stations[$i];
			array_push($names, $station->name);
		}
		
		echo CJSON::encode($names);
	}
}
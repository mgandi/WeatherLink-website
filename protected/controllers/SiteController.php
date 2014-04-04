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
				  'actions'=>array('index', 'error', 'data'),
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
		
		$subdata = array_map(create_function('$m','return array($m->currentWindDirection, $m->currentWindSpeed, $m->timeStamp);'),$data);
		
		echo json_encode($subdata, JSON_NUMERIC_CHECK);
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
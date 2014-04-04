<?php

/**
 * This is the model class for meteo station table.
 *
 * The followings are the available columns in the table:
 * @property integer $id
 * @property string $timeStamp
 * @property double $currentOutsideTemperature
 * @property double $maxOutsideTemperature
 * @property double $minOutsideTemperature
 * @property integer $currentOutsideHumidity
 * @property integer $maxOutsideHumidity
 * @property integer $minOutsideHumidity
 * @property double $currentInsideTemperature
 * @property double $maxInsideTemperature
 * @property double $minInsideTemperature
 * @property integer $currentInsideHumidity
 * @property integer $maxInsideHumidity
 * @property integer $minInsideHumidity
 * @property double $currentHeatIndex
 * @property double $maxHeatIndex
 * @property double $currentWindChill
 * @property double $minWindChill
 * @property double $currentDewPoint
 * @property double $maxDewPoint
 * @property double $minDewPoint
 * @property double $currentPressure
 * @property double $maxPressure
 * @property double $minPressure
 * @property double $currentWindSpeed
 * @property double $maxWindSpeed
 * @property integer $currentWindDirection
 * @property double $averageWindSpeed2Minutes
 * @property double $averageWindSpeed10Minutes
 * @property double $windGust
 */
class MeteoStation extends CActiveRecord
{
	var $tname = "";
	
	function __construct($tableName) {
		$tname = $tableName;
        parent::__construct('insert', $tableName);
    }
	
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return $tname;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('currentOutsideHumidity, maxOutsideHumidity, minOutsideHumidity, currentInsideHumidity, maxInsideHumidity, minInsideHumidity, currentWindDirection', 'numerical', 'integerOnly'=>true),
            array('currentOutsideTemperature, maxOutsideTemperature, minOutsideTemperature, currentInsideTemperature, maxInsideTemperature, minInsideTemperature, currentHeatIndex, maxHeatIndex, currentWindChill, minWindChill, currentDewPoint, maxDewPoint, minDewPoint, currentPressure, maxPressure, minPressure, currentWindSpeed, maxWindSpeed, averageWindSpeed2Minutes, averageWindSpeed10Minutes, windGust', 'numerical'),
            array('timeStamp', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, timeStamp, currentOutsideTemperature, maxOutsideTemperature, minOutsideTemperature, currentOutsideHumidity, maxOutsideHumidity, minOutsideHumidity, currentInsideTemperature, maxInsideTemperature, minInsideTemperature, currentInsideHumidity, maxInsideHumidity, minInsideHumidity, currentHeatIndex, maxHeatIndex, currentWindChill, minWindChill, currentDewPoint, maxDewPoint, minDewPoint, currentPressure, maxPressure, minPressure, currentWindSpeed, maxWindSpeed, currentWindDirection, averageWindSpeed2Minutes, averageWindSpeed10Minutes, windGust', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'timeStamp' => 'Time Stamp',
            'currentOutsideTemperature' => 'Current Outside Temperature',
            'maxOutsideTemperature' => 'Max Outside Temperature',
            'minOutsideTemperature' => 'Min Outside Temperature',
            'currentOutsideHumidity' => 'Current Outside Humidity',
            'maxOutsideHumidity' => 'Max Outside Humidity',
            'minOutsideHumidity' => 'Min Outside Humidity',
            'currentInsideTemperature' => 'Current Inside Temperature',
            'maxInsideTemperature' => 'Max Inside Temperature',
            'minInsideTemperature' => 'Min Inside Temperature',
            'currentInsideHumidity' => 'Current Inside Humidity',
            'maxInsideHumidity' => 'Max Inside Humidity',
            'minInsideHumidity' => 'Min Inside Humidity',
            'currentHeatIndex' => 'Current Heat Index',
            'maxHeatIndex' => 'Max Heat Index',
            'currentWindChill' => 'Current Wind Chill',
            'minWindChill' => 'Min Wind Chill',
            'currentDewPoint' => 'Current Dew Point',
            'maxDewPoint' => 'Max Dew Point',
            'minDewPoint' => 'Min Dew Point',
            'currentPressure' => 'Current Pressure',
            'maxPressure' => 'Max Pressure',
            'minPressure' => 'Min Pressure',
            'currentWindSpeed' => 'Current Wind Speed',
            'maxWindSpeed' => 'Max Wind Speed',
            'currentWindDirection' => 'Current Wind Direction',
            'averageWindSpeed2Minutes' => 'Average Wind Speed2 Minutes',
            'averageWindSpeed10Minutes' => 'Average Wind Speed10 Minutes',
            'windGust' => 'Wind Gust',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('timeStamp',$this->timeStamp,true);
        $criteria->compare('currentOutsideTemperature',$this->currentOutsideTemperature);
        $criteria->compare('maxOutsideTemperature',$this->maxOutsideTemperature);
        $criteria->compare('minOutsideTemperature',$this->minOutsideTemperature);
        $criteria->compare('currentOutsideHumidity',$this->currentOutsideHumidity);
        $criteria->compare('maxOutsideHumidity',$this->maxOutsideHumidity);
        $criteria->compare('minOutsideHumidity',$this->minOutsideHumidity);
        $criteria->compare('currentInsideTemperature',$this->currentInsideTemperature);
        $criteria->compare('maxInsideTemperature',$this->maxInsideTemperature);
        $criteria->compare('minInsideTemperature',$this->minInsideTemperature);
        $criteria->compare('currentInsideHumidity',$this->currentInsideHumidity);
        $criteria->compare('maxInsideHumidity',$this->maxInsideHumidity);
        $criteria->compare('minInsideHumidity',$this->minInsideHumidity);
        $criteria->compare('currentHeatIndex',$this->currentHeatIndex);
        $criteria->compare('maxHeatIndex',$this->maxHeatIndex);
        $criteria->compare('currentWindChill',$this->currentWindChill);
        $criteria->compare('minWindChill',$this->minWindChill);
        $criteria->compare('currentDewPoint',$this->currentDewPoint);
        $criteria->compare('maxDewPoint',$this->maxDewPoint);
        $criteria->compare('minDewPoint',$this->minDewPoint);
        $criteria->compare('currentPressure',$this->currentPressure);
        $criteria->compare('maxPressure',$this->maxPressure);
        $criteria->compare('minPressure',$this->minPressure);
        $criteria->compare('currentWindSpeed',$this->currentWindSpeed);
        $criteria->compare('maxWindSpeed',$this->maxWindSpeed);
        $criteria->compare('currentWindDirection',$this->currentWindDirection);
        $criteria->compare('averageWindSpeed2Minutes',$this->averageWindSpeed2Minutes);
        $criteria->compare('averageWindSpeed10Minutes',$this->averageWindSpeed10Minutes);
        $criteria->compare('windGust',$this->windGust);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return the static model class
     */
    public static function model($tableName, $className=__CLASS__)
    {
        return parent::model($className, $tableName);
    }
}
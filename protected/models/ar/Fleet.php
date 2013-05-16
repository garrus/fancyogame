<?php

/**
 * This is the model class for table "fleet".
 *
 * The followings are the available columns in table 'fleet':
 * @property string $id
 * @property string $owner_id
 * @property string $departure_planet_id
 * @property string $destination_planet_id
 * @property integer $task_type
 * @property string $departure_time
 * @property string $arrival_time
 * @property integer $is_finished
 * @property integer $is_aborted
 * @property string $abort_time
 * @property string $ships
 * @property integer $is_departure_moon
 *
 * The followings are the available model relations:
 * @property Planet $departurePlanet
 * @property Planet $destinationPlanet
 * @property Player $owner
 * @property RelayAccessHistory[] $relayAccessHistories
 */
class Fleet extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Fleet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fleet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_id, destination_planet_id, ships', 'required'),
			array('task_type, is_finished, is_aborted, is_departure_moon', 'numerical', 'integerOnly'=>true),
			array('owner_id, departure_planet_id, destination_planet_id', 'length', 'max'=>10),
			array('departure_time, arrival_time, abort_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, owner_id, departure_planet_id, destination_planet_id, task_type, departure_time, arrival_time, is_finished, is_aborted, abort_time, ships, is_departure_moon', 'safe', 'on'=>'search'),
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
			'departurePlanet' => array(self::BELONGS_TO, 'Planet', 'departure_planet_id'),
			'destinationPlanet' => array(self::BELONGS_TO, 'Planet', 'destination_planet_id'),
			'owner' => array(self::BELONGS_TO, 'Player', 'owner_id'),
			'relayAccessHistories' => array(self::HAS_MANY, 'RelayAccessHistory', 'fleet_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner_id' => 'Owner',
			'departure_planet_id' => 'Departure Planet',
			'destination_planet_id' => 'Destination Planet',
			'task_type' => 'Task Type',
			'departure_time' => 'Departure Time',
			'arrival_time' => 'Arrival Time',
			'is_finished' => 'Is Finished',
			'is_aborted' => 'Is Aborted',
			'abort_time' => 'Abort Time',
			'ships' => 'Ships',
			'is_departure_moon' => 'Is Departure Moon',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('owner_id',$this->owner_id,true);
		$criteria->compare('departure_planet_id',$this->departure_planet_id,true);
		$criteria->compare('destination_planet_id',$this->destination_planet_id,true);
		$criteria->compare('task_type',$this->task_type);
		$criteria->compare('departure_time',$this->departure_time,true);
		$criteria->compare('arrival_time',$this->arrival_time,true);
		$criteria->compare('is_finished',$this->is_finished);
		$criteria->compare('is_aborted',$this->is_aborted);
		$criteria->compare('abort_time',$this->abort_time,true);
		$criteria->compare('ships',$this->ships,true);
		$criteria->compare('is_departure_moon',$this->is_departure_moon);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
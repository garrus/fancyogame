<?php

/**
 * This is the model class for table "relay".
 *
 * The followings are the available columns in table 'relay':
 * @property string $id
 * @property string $discoverer_id
 * @property integer $galaxy
 * @property integer $system
 * @property string $discover_time
 *
 * The followings are the available model relations:
 * @property Player $discoverer
 * @property RelayAccessHistory[] $relayAccessHistories
 */
class Relay extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Relay the static model class
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
		return 'relay';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('galaxy, system, discover_time', 'required'),
			array('galaxy, system', 'numerical', 'integerOnly'=>true),
			array('discoverer_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, discoverer_id, galaxy, system, discover_time', 'safe', 'on'=>'search'),
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
			'discoverer' => array(self::BELONGS_TO, 'Player', 'discoverer_id'),
			'relayAccessHistories' => array(self::HAS_MANY, 'RelayAccessHistory', 'relay_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'discoverer_id' => 'Discoverer',
			'galaxy' => 'Galaxy',
			'system' => 'System',
			'discover_time' => 'Discover Time',
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
		$criteria->compare('discoverer_id',$this->discoverer_id,true);
		$criteria->compare('galaxy',$this->galaxy);
		$criteria->compare('system',$this->system);
		$criteria->compare('discover_time',$this->discover_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
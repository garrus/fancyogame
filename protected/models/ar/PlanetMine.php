<?php

/**
 * This is the model class for table "planet_mine".
 *
 * The followings are the available columns in table 'planet_mine':
 * @property string $id
 * @property string $owner_id
 * @property string $mine_blueprint_id
 * @property string $planet_id
 * @property string $trans_planet_id
 * @property string $launch_time
 *
 * The followings are the available model relations:
 * @property Player $owner
 * @property Planet $planet
 * @property Planet $transPlanet
 * @property MineBlueprint $mineBlueprint
 */
class PlanetMine extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PlanetMine the static model class
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
		return 'planet_mine';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_id, mine_blueprint_id, planet_id, trans_planet_id, launch_time', 'required'),
			array('owner_id, mine_blueprint_id, planet_id, trans_planet_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, owner_id, mine_blueprint_id, planet_id, trans_planet_id, launch_time', 'safe', 'on'=>'search'),
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
			'owner' => array(self::BELONGS_TO, 'Player', 'owner_id'),
			'planet' => array(self::BELONGS_TO, 'Planet', 'planet_id'),
			'transPlanet' => array(self::BELONGS_TO, 'Planet', 'trans_planet_id'),
			'mineBlueprint' => array(self::BELONGS_TO, 'MineBlueprint', 'mine_blueprint_id'),
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
			'mine_blueprint_id' => 'Mine Blueprint',
			'planet_id' => 'Planet',
			'trans_planet_id' => 'Trans Planet',
			'launch_time' => 'Launch Time',
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
		$criteria->compare('mine_blueprint_id',$this->mine_blueprint_id,true);
		$criteria->compare('planet_id',$this->planet_id,true);
		$criteria->compare('trans_planet_id',$this->trans_planet_id,true);
		$criteria->compare('launch_time',$this->launch_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
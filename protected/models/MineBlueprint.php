<?php

/**
 * This is the model class for table "mine_blueprint".
 *
 * The followings are the available columns in table 'mine_blueprint':
 * @property string $id
 * @property string $designer_id
 * @property string $name
 * @property string $resource_cost
 * @property string $production_rate
 * @property integer $designed_life
 * @property string $requirement
 *
 * The followings are the available model relations:
 * @property Player $designer
 * @property Player[] $players
 * @property PlanetMine[] $planetMines
 */
class MineBlueprint extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MineBlueprint the static model class
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
		return 'mine_blueprint';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, resource_cost, production_rate, designed_life, requirement', 'required'),
			array('designed_life', 'numerical', 'integerOnly'=>true),
			array('designer_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>45),
			array('resource_cost, production_rate, requirement', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, designer_id, name, resource_cost, production_rate, designed_life, requirement', 'safe', 'on'=>'search'),
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
			'designer' => array(self::BELONGS_TO, 'Player', 'designer_id'),
			'players' => array(self::MANY_MANY, 'Player', 'mine_permit(mine_blueprint_id, player_id)'),
			'planetMines' => array(self::HAS_MANY, 'PlanetMine', 'mine_blueprint_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'designer_id' => 'Designer',
			'name' => 'Name',
			'resource_cost' => 'Resource Cost',
			'production_rate' => 'Production Rate',
			'designed_life' => 'Designed Life',
			'requirement' => 'Requirement',
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
		$criteria->compare('designer_id',$this->designer_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('resource_cost',$this->resource_cost,true);
		$criteria->compare('production_rate',$this->production_rate,true);
		$criteria->compare('designed_life',$this->designed_life);
		$criteria->compare('requirement',$this->requirement,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
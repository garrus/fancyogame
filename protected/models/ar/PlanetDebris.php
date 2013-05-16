<?php

/**
 * This is the model class for table "planet_debris".
 *
 * The followings are the available columns in table 'planet_debris':
 * @property string $planet_id
 * @property string $metal
 * @property string $crystal
 *
 * The followings are the available model relations:
 * @property Planet $planet
 */
class PlanetDebris extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PlanetDebris the static model class
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
		return 'planet_debris';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('planet_id', 'required'),
			array('planet_id, metal, crystal', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('planet_id, metal, crystal', 'safe', 'on'=>'search'),
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
			'planet' => array(self::BELONGS_TO, 'Planet', 'planet_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'planet_id' => 'Planet',
			'metal' => 'Metal',
			'crystal' => 'Crystal',
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

		$criteria->compare('planet_id',$this->planet_id,true);
		$criteria->compare('metal',$this->metal,true);
		$criteria->compare('crystal',$this->crystal,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
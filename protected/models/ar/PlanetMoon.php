<?php

/**
 * This is the model class for table "planet_moon".
 *
 * The followings are the available columns in table 'planet_moon':
 * @property string $planet_id
 * @property string $resources
 * @property string $buildings
 * @property string $ships
 * @property integer $area
 *
 * The followings are the available model relations:
 * @property Planet $planet
 *
 * Behavior CollectionAttributeBehavior offers these methods:
 * @method Collection getCollection(string $name)
 * @method void setCollection(string $name, Collection $col)
 */
class PlanetMoon extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PlanetMoon the static model class
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
		return 'planet_moon';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('planet_id, resources, buildings, ships, building_queue', 'required'),
			array('area', 'numerical', 'integerOnly'=>true),
			array('planet_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('planet_id, resources, buildings, ships, building_queue, area', 'safe', 'on'=>'search'),
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
			'resources' => 'Resources',
			'buildings' => 'Buildings',
			'ships' => 'Ships',
			'building_queue' => 'Building Queue',
			'area' => 'Area',
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
		$criteria->compare('resources',$this->resources,true);
		$criteria->compare('buildings',$this->buildings,true);
		$criteria->compare('ships',$this->ships,true);
		$criteria->compare('building_queue',$this->building_queue,true);
		$criteria->compare('area',$this->area);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * @return array
     */
    public function behaviors(){
        return array(
            'colAttr' => array(
                'class' => 'application.components.CollectionAttributeBehavior',
                'collections' => array(
                    'res'   => array('attr' => 'resources', 'class' => 'Resources'),
                    'bd'    => array('attr' => 'buildings', 'class' => 'Buildings'),
                    'ship'  => array('attr' => 'ships',     'class' => 'Ships'),
                ),
            )
        );
    }
}
<?php

/**
 * This is the model class for table "planet".
 *
 * The followings are the available columns in table 'planet':
 * @property string $id
 * @property string $owner_id
 * @property integer $galaxy
 * @property integer $system
 * @property integer $position
 * @property string $name
 * @property integer $temperature
 * @property integer $is_colonized
 * @property integer $has_active_mine
 * @property integer $has_moon
 * @property integer $gas_production_rate
 * @property integer $mine_limit
 *
 * The followings are the available model relations:
 * @property Auction[] $auctions
 * @property Task[] $tasks
 * @property AuctionItem[] $auctionItems
 * @property Fleet[] $fleets
 * @property Fleet[] $fleets1
 * @property Player $owner
 * @property PlanetData $planetData
 * @property PlanetDebris $planetDebris
 * @property PlanetMine[] $planetMines
 * @property PlanetMine[] $planetMines1
 * @property PlanetMoon $planetMoon
 */
class Planet extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Planet the static model class
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
		return 'planet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('galaxy, system, position, name, temperature', 'required'),
			array('galaxy, system, position, temperature, is_colonized, has_active_mine, has_moon, gas_production_rate, mine_limit', 'numerical', 'integerOnly'=>true),
			array('owner_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, owner_id, galaxy, system, position, name, temperature, is_colonized, has_active_mine, has_moon, gas_production_rate, mine_limit', 'safe', 'on'=>'search'),
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
		    'tasks' => array(self::HAS_MANY, 'Task', 'planet_id', 'order' => 'create_time'),
			'auctions' => array(self::HAS_MANY, 'Auction', 'recipient_planet_id'),
			'auctionItems' => array(self::HAS_MANY, 'AuctionItem', 'depart_planet_id'),
			'fleets' => array(self::HAS_MANY, 'Fleet', 'departure_planet_id'),
			'fleets1' => array(self::HAS_MANY, 'Fleet', 'destination_planet_id'),
			'owner' => array(self::BELONGS_TO, 'Player', 'owner_id'),
			'planetData' => array(self::HAS_ONE, 'PlanetData', 'planet_id'),
			'planetDebris' => array(self::HAS_ONE, 'PlanetDebris', 'planet_id'),
			'planetMines' => array(self::HAS_MANY, 'PlanetMine', 'planet_id'),
			'planetMines1' => array(self::HAS_MANY, 'PlanetMine', 'trans_planet_id'),
			'planetMoon' => array(self::HAS_ONE, 'PlanetMoon', 'planet_id'),
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
			'galaxy' => 'Galaxy',
			'system' => 'System',
			'position' => 'Position',
			'name' => 'Name',
			'temperature' => 'Temperature',
			'is_colonized' => 'Is Colonized',
			'has_active_mine' => 'Has Active Mine',
			'has_moon' => 'Has Moon',
			'gas_production_rate' => 'Gas Production Rate',
			'mine_limit' => 'Mine Limit',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('galaxy',$this->galaxy);
		$criteria->compare('system',$this->system);
		$criteria->compare('position',$this->position);
		$criteria->compare('name',$this->name);
		$criteria->compare('temperature',$this->temperature);
		$criteria->compare('is_colonized',$this->is_colonized);
		$criteria->compare('has_active_mine',$this->has_active_mine);
		$criteria->compare('has_moon',$this->has_moon);
		$criteria->compare('gas_production_rate',$this->gas_production_rate);
		$criteria->compare('mine_limit',$this->mine_limit);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function ofSystem($galaxy, $system){

	    $this->getDbCriteria()->mergeWith(array(
	        'condition' => 'galaxy=:galaxy AND system=:system',
	        'params' => array(
        	        'galaxy' => $galaxy,
        	        'system' => $system,
    	        ),
	        ));
	    return $this;
	}

    /**
     * @param int|Location $galaxy
     * @param int|null $system
     * @param int|null $position
     * @return Planet|null
     */
    public function findByLocation($galaxy, $system=null, $position=null){

        if (is_object($galaxy) && $galaxy instanceof Location) {
            $system = $galaxy->gal;
            $position = $galaxy->pos;
            $galaxy = $galaxy->gal;
        }

        return $this->findByAttributes(array(
            'galaxy' => $galaxy,
            'system' => $system,
            'position' => $position,
        ));
    }

	/**
	 * Return location in format of [galaxy, system, position]
	 *
	 * @return string
	 */
	public function formatLocation(){

	    return sprintf('[%d, %d, %d]', $this->galaxy, $this->system, $this->position);
	}

    /**
     * @return int
     */
    public function getMineLimit(){

        return $this->mine_limit * 10000;
    }

    /**
     * @return int
     */
    public function getGasLimit(){

        return $this->gas_production_rate * 10000;
    }
}
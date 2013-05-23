<?php

/**
 * This is the model class for table "planet_data".
 *
 * The followings are the available columns in table 'planet_data':
 * @property string $planet_id
 * @property string $last_update_time
 * @property string $resources
 * @property string $buildings
 * @property string $ships
 * @property string $mines
 * @property string $building_queue
 * @property string $shipyard_queue
 *
 * The followings are the available model relations:
 * @property Planet $planet
 */
class PlanetData extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PlanetData the static model class
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
		return 'planet_data';
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
			array('planet_id', 'length', 'max'=>10),
			array('last_update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('planet_id, last_update_time, resources, buildings, ships, mines, building_queue, shipyard_queue', 'safe', 'on'=>'search'),
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
			'last_update_time' => 'Last Update Time',
			'resources' => 'Resources',
			'buildings' => 'Buildings',
			'ships' => 'Ships',
			'mines' => 'Mines',
			'building_queue' => 'Building Queue',
			'shipyard_queue' => 'Shipyard Queue',
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
		$criteria->compare('last_update_time',$this->last_update_time,true);
		$criteria->compare('resources',$this->resources,true);
		$criteria->compare('buildings',$this->buildings,true);
		$criteria->compare('ships',$this->ships,true);
		$criteria->compare('mines',$this->mines,true);
		$criteria->compare('building_queue',$this->building_queue,true);
		$criteria->compare('shipyard_queue',$this->shipyard_queue,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeSave(){

	    if ($this->isNewRecord) {
	        $this->resources || $this->resources = '{}';
	        $this->buildings || $this->buildings = '{}';
	        $this->ships || $this->ships = '{}';
	        $this->building_queue || $this->building_queue = '{}';
	        $this->shipyard_queue || $this->shipyard_queue = '{}';
	        $this->mines || $this->mines = '{}';
	    }

	    return parent::beforeSave();
	}

	private $_resources=null;
	private $_buildings=null;
	private $_ships=null;
	private $_defences=null;
	private $_mines=null;


	/**
	 * @param Collection $name
	 */
	public function getCollection($name){

	    $_cache = '_'. $name;
	    if (!$this->$_cache) {
	        $classname = ucfirst($name);

	        if ($this->$name) {
	            $obj = $this->$_cache = $classname::fromJson($this->$name);
	        } else {
	            $obj = $this->$_cache = new $classname;
	            $this->$name = json_encode($obj);
	        }
	        $obj->attachEventHandler('onchange', array($this, 'onCollectionChange'));
	    }
	    return $this->$_cache;
	}

	/**
	 *
	 * @param Collection $res
	 */
	public function setCollection($obj){

	    $name = strtolower(get_class($obj));
	    $this->{'_'. $name} = $obj;
	    $this->$name = json_encode($obj);
	    if (!$this->isNewRecord) {
	        if (!$this->save(true, array($name, 'last_update_time'))) {
	            throw new ModelError($this);
	        }
	    }
	}

	/**
	 *
	 * @param CEvent $event
	 */
	public function onCollectionChange($event){

	    $this->setCollection($event->sender);
	}

	/**
	 *
	 * @return Mines
	 */
	public function getMines(){

	    return new Mines($this->mines);
	}



}
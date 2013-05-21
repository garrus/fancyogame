<?php

/**
 * This is the model class for table "task".
 *
 * The followings are the available columns in table 'task':
 * @property string $id
 * @property string $planet_id
 * @property integer $is_running
 * @property integer $type
 * @property string $target
 * @property integer $amount
 * @property string $create_time
 * @property string $activate_time
 * @property string $end_time
 *
 * The followings are the available model relations:
 * @property Planet $planet
 */
class Task extends CActiveRecord implements ITask
{

    const TYPE_RESEARCH = 1;
    const TYPE_CONSTRUCT = 2;
    const TYPE_DECONSTRUCT = 3;
    const TYPE_BUILD_SHIPS = 4;
    const TYPE_BUILD_DEFENCES = 5;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Task the static model class
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
		return '{{task}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, target, create_time', 'required'),
			array('is_running, type, amount', 'numerical', 'integerOnly'=>true),
			array('planet_id', 'length', 'max'=>10),
			array('target', 'length', 'max'=>22),
			array('activate_time, end_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, planet_id, is_running, type, target, amount, create_time, activate_time, end_time', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'planet_id' => 'Planet',
			'is_running' => 'Is Running',
			'type' => 'Type',
			'target' => 'Target',
			'amount' => 'Amount',
			'create_time' => 'Create Time',
			'activate_time' => 'Activate Time',
			'end_time' => 'End Time',
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
		$criteria->compare('planet_id',$this->planet_id,true);
		$criteria->compare('is_running',$this->is_running);
		$criteria->compare('type',$this->type);
		$criteria->compare('target',$this->target,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('activate_time',$this->activate_time,true);
		$criteria->compare('end_time',$this->end_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
	 * Create a new task
	 *
	 * @param ZPlanet $planet
	 * @param int $type
	 * @param string $target
	 * @param int $amount
	 * @throws InvalidArgumentException
	 * @throws ModelError
	 * @return Task
	 */
	public static function createNew(ZPlanet $planet, $type, $target, $amount=1) {

	    $model = new self;
	    $model->planet_id = $planet->id;
	    $model->type = $type;
	    $model->target = $target;
	    switch ($type) {
	        case self::TYPE_RESEARCH:
	        case self::TYPE_CONSTRUCT:
	        case self::TYPE_DECONSTRUCT:
	            $amount = 1;
	            break;
	        case self::TYPE_BUILD_SHIPS:
	        case self::TYPE_BUILD_DEFENCES:
	            $amount = is_numeric($amount) && $amount > 1 ? ceil($amount) : 1;
	            break;
	        default:
	            throw new InvalidArgumentException('Unexpected value for task type: '. $type);
	    }
	    $model->amount = $amount;
	    if (!$model->save()) {
	        throw new ModelError($model);
	    }

	    return $model;
	}


	public function isActivated(){

	    return $this->is_running == 1;
	}


	/**
	 * Return if this task has conflict with given task.
	 * If two tasks have conflict, they cannot be running in a workflow at the same time.
	 *
	 * @param Task $task
	 * @return boolean
	 */
	public function hasConflictWith(Task $task){

	    if ($this->target != $task->target) {
	        return false;
	    }

	    // tasks on different planet won't have conflict
	    if ($this->planet_id != $task->planet_id) {
	        return false;
	    }

	    // research on the same tech or build the same building is not allowed
	    if ($this->type == $task->type) {
	        return $this->type != self::TYPE_BUILD_DEFENCES && $this->type != self::TYPE_BUILD_SHIPS;
	    }

	    // at last, you cannot construct and deconstruct the same building
	    return ($this->type == self::TYPE_CONSTRUCT && $task->type == self::TYPE_DECONSTRUCT)
	        || ($this->type == self::TYPE_DECONSTRUCT && $task->type == self::TYPE_CONSTRUCT);
	}


	/**
	 * (non-PHPdoc)
	 * 
	 * @see ITask::getType()
	 */
	public function getType(){
		
		return (int)$this->type;
	}


	/**
	 * (non-PHPdoc)
	 * 
	 * @see ITask::getObject()
	 */
	public function getObject(){
		
		return $this->target;
	}


	/**
	 * (non-PHPdoc)
	 * 
	 * @see ITask::getAmount()
	 */
	public function getAmount(){
		
		return round($this->amount);
	}


}
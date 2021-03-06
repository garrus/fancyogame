<?php

/**
 * This is the model class for table "player_data".
 *
 * The followings are the available columns in table 'player_data':
 * @property string $player_id
 * @property string $techs
 *
 * The followings are the available model relations:
 * @property Player $player
 *
 * Behavior CollectionAttributeBehavior offers these methods:
 * @method Collection getCollection(string $name)
 * @method void setCollection(string $name, Collection $col)
 */
class PlayerData extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PlayerData the static model class
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
		return 'player_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_id', 'required'),
			array('player_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('player_id, techs', 'safe', 'on'=>'search'),
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
			'player' => array(self::BELONGS_TO, 'Player', 'player_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'player_id' => 'Player',
			'techs' => 'Techs',
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

		$criteria->compare('player_id',$this->player_id,true);
		$criteria->compare('techs',$this->techs,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	protected function beforeSave(){
    	if ($this->isNewRecord) {
    	    $this->techs || $this->techs = '{}';
    	}

    	return parent::beforeSave();
	}

    public function regenerate(){
        $this->techs = '{}';
        $this->save(false);
    }

    /**
     * @return array
     */
    public function behaviors(){
        return array(
            'colAttr' => array(
                'class' => 'application.components.CollectionAttributeBehavior',
                'collections' => array(
                    'tech'   => array('attr' => 'techs', 'class' => 'Techs'),
                ),
            )
        );
    }

}
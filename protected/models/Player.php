<?php

/**
 * This is the model class for table "player".
 *
 * The followings are the available columns in table 'player':
 * @property string $id
 * @property string $account_id
 * @property string $name
 * @property integer $is_protected
 * @property integer $active_state
 * @property integer $vacation_mode_enabled
 * @property string $vacation_start_time
 * @property string $vacation_end_time
 * @property integer $can_use_relay
 * @property string $galaxy_credit
 *
 * The followings are the available model relations:
 * @property Auction[] $auctions
 * @property Auction[] $auctions1
 * @property Fleet[] $fleets
 * @property MineBlueprint[] $mineBlueprints
 * @property Planet[] $planets
 * @property PlanetMine[] $planetMines
 * @property Account $account
 * @property Relay[] $relays
 * @property RelayAccessHistory[] $relayAccessHistories
 */
class Player extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Player the static model class
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
		return 'player';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, name', 'required'),
			array('is_protected, active_state, vacation_mode_enabled, can_use_relay', 'numerical', 'integerOnly'=>true),
			array('account_id, galaxy_credit', 'length', 'max'=>10),
			array('name', 'length', 'max'=>45),
			array('vacation_start_time, vacation_end_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_id, name, is_protected, active_state, vacation_mode_enabled, vacation_start_time, vacation_end_time, can_use_relay, galaxy_credit', 'safe', 'on'=>'search'),
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
			'auctions' => array(self::HAS_MANY, 'Auction', 'seller_id'),
			'auctions1' => array(self::HAS_MANY, 'Auction', 'last_bidding_buyer_id'),
			'fleets' => array(self::HAS_MANY, 'Fleet', 'owner_id'),
			'mineBlueprints' => array(self::MANY_MANY, 'MineBlueprint', 'mine_permit(player_id, mine_blueprint_id)'),
			'planets' => array(self::HAS_MANY, 'Planet', 'owner_id'),
			'planetMines' => array(self::HAS_MANY, 'PlanetMine', 'owner_id'),
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
			'relays' => array(self::HAS_MANY, 'Relay', 'discoverer_id'),
			'relayAccessHistories' => array(self::HAS_MANY, 'RelayAccessHistory', 'player_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'account_id' => 'Account',
			'name' => 'Name',
			'is_protected' => 'Is Protected',
			'active_state' => 'Active State',
			'vacation_mode_enabled' => 'Vacation Mode Enabled',
			'vacation_start_time' => 'Vacation Start Time',
			'vacation_end_time' => 'Vacation End Time',
			'can_use_relay' => 'Can Use Relay',
			'galaxy_credit' => 'Galaxy Credit',
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
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('is_protected',$this->is_protected);
		$criteria->compare('active_state',$this->active_state);
		$criteria->compare('vacation_mode_enabled',$this->vacation_mode_enabled);
		$criteria->compare('vacation_start_time',$this->vacation_start_time,true);
		$criteria->compare('vacation_end_time',$this->vacation_end_time,true);
		$criteria->compare('can_use_relay',$this->can_use_relay);
		$criteria->compare('galaxy_credit',$this->galaxy_credit,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
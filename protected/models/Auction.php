<?php

/**
 * This is the model class for table "auction".
 *
 * The followings are the available columns in table 'auction':
 * @property string $id
 * @property string $seller_id
 * @property string $last_bidding_buyer_id
 * @property string $recipient_planet_id
 * @property string $category
 * @property integer $is_active
 * @property string $start_time
 * @property string $end_time
 * @property string $start_price
 * @property string $buy_it_now_price
 * @property string $last_bid
 * @property string $last_bid_time
 * @property string $create_time
 *
 * The followings are the available model relations:
 * @property Player $seller
 * @property Player $lastBiddingBuyer
 * @property Planet $recipientPlanet
 */
class Auction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Auction the static model class
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
		return 'auction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('seller_id, category, create_time', 'required'),
			array('is_active', 'numerical', 'integerOnly'=>true),
			array('seller_id, last_bidding_buyer_id, recipient_planet_id, start_price, buy_it_now_price, last_bid', 'length', 'max'=>10),
			array('category', 'length', 'max'=>14),
			array('start_time, end_time, last_bid_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, seller_id, last_bidding_buyer_id, recipient_planet_id, category, is_active, start_time, end_time, start_price, buy_it_now_price, last_bid, last_bid_time, create_time', 'safe', 'on'=>'search'),
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
			'seller' => array(self::BELONGS_TO, 'Player', 'seller_id'),
			'lastBiddingBuyer' => array(self::BELONGS_TO, 'Player', 'last_bidding_buyer_id'),
			'recipientPlanet' => array(self::BELONGS_TO, 'Planet', 'recipient_planet_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'seller_id' => 'Seller',
			'last_bidding_buyer_id' => 'Last Bidding Buyer',
			'recipient_planet_id' => 'Recipient Planet',
			'category' => 'Category',
			'is_active' => 'Is Active',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'start_price' => 'Start Price',
			'buy_it_now_price' => 'Buy It Now Price',
			'last_bid' => 'Last Bid',
			'last_bid_time' => 'Last Bid Time',
			'create_time' => 'Create Time',
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
		$criteria->compare('seller_id',$this->seller_id,true);
		$criteria->compare('last_bidding_buyer_id',$this->last_bidding_buyer_id,true);
		$criteria->compare('recipient_planet_id',$this->recipient_planet_id,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('start_price',$this->start_price,true);
		$criteria->compare('buy_it_now_price',$this->buy_it_now_price,true);
		$criteria->compare('last_bid',$this->last_bid,true);
		$criteria->compare('last_bid_time',$this->last_bid_time,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
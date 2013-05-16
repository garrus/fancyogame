<?php

/**
 * This is the model class for table "auction_item".
 *
 * The followings are the available columns in table 'auction_item':
 * @property string $auction_id
 * @property string $depart_planet_id
 * @property string $item_id
 * @property integer $count
 *
 * The followings are the available model relations:
 * @property Planet $departPlanet
 */
class AuctionItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AuctionItem the static model class
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
		return 'auction_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('auction_id, depart_planet_id, item_id, count', 'required'),
			array('count', 'numerical', 'integerOnly'=>true),
			array('auction_id, depart_planet_id, item_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('auction_id, depart_planet_id, item_id, count', 'safe', 'on'=>'search'),
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
			'departPlanet' => array(self::BELONGS_TO, 'Planet', 'depart_planet_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'auction_id' => 'Auction',
			'depart_planet_id' => 'Depart Planet',
			'item_id' => 'Item',
			'count' => 'Count',
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

		$criteria->compare('auction_id',$this->auction_id,true);
		$criteria->compare('depart_planet_id',$this->depart_planet_id,true);
		$criteria->compare('item_id',$this->item_id,true);
		$criteria->compare('count',$this->count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
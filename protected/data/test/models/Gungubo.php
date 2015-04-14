<?php

/**
 * This is the model class for table "gungubo".
 *
 * The followings are the available columns in table 'gungubo':
 * @property integer $id
 * @property integer $event_id
 * @property integer $owner_id
 * @property integer $attacker_id
 * @property string $side
 * @property integer $health
 * @property string $location
 * @property string $trait
 * @property integer $trait_value
 * @property string $condition_status
 * @property integer $condition_value
 * @property string $birthdate
 */
class Gungubo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Gungubo the static model class
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
		return 'gungubo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, owner_id, health, birthdate', 'required'),
			array('event_id, owner_id, attacker_id, health, trait_value, condition_value', 'numerical', 'integerOnly'=>true),
			array('side', 'length', 'max'=>10),
			array('location', 'length', 'max'=>20),
			array('trait, condition_status', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, owner_id, attacker_id, side, health, location, trait, trait_value, condition_status, condition_value, birthdate', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'event_id' => 'Event',
			'owner_id' => 'Owner',
			'attacker_id' => 'Attacker',
			'side' => 'Side',
			'health' => 'Health',
			'location' => 'Location',
			'trait' => 'Trait',
			'trait_value' => 'Trait Value',
			'condition_status' => 'Condition Status',
			'condition_value' => 'Condition Value',
			'birthdate' => 'Birthdate',
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
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('attacker_id',$this->attacker_id);
		$criteria->compare('side',$this->side,true);
		$criteria->compare('health',$this->health);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('trait',$this->trait,true);
		$criteria->compare('trait_value',$this->trait_value);
		$criteria->compare('condition_status',$this->condition_status,true);
		$criteria->compare('condition_value',$this->condition_value);
		$criteria->compare('birthdate',$this->birthdate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
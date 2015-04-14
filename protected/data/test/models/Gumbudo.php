<?php

/**
 * This is the model class for table "gumbudo".
 *
 * The followings are the available columns in table 'gumbudo':
 * @property integer $id
 * @property integer $event_id
 * @property integer $owner_id
 * @property string $side
 * @property string $class
 * @property integer $actions
 * @property string $trait
 * @property integer $trait_value
 * @property string $weapon
 * @property string $ripdate
 */
class Gumbudo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Gumbudo the static model class
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
		return 'gumbudo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, owner_id, class, ripdate', 'required'),
			array('event_id, owner_id, actions, trait_value', 'numerical', 'integerOnly'=>true),
			array('side', 'length', 'max'=>10),
			array('class', 'length', 'max'=>20),
			array('trait, weapon', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, owner_id, side, class, actions, trait, trait_value, weapon, ripdate', 'safe', 'on'=>'search'),
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
			'side' => 'Side',
			'class' => 'Class',
			'actions' => 'Actions',
			'trait' => 'Trait',
			'trait_value' => 'Trait Value',
			'weapon' => 'Weapon',
			'ripdate' => 'Ripdate',
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
		$criteria->compare('side',$this->side,true);
		$criteria->compare('class',$this->class,true);
		$criteria->compare('actions',$this->actions);
		$criteria->compare('trait',$this->trait,true);
		$criteria->compare('trait_value',$this->trait_value);
		$criteria->compare('weapon',$this->weapon,true);
		$criteria->compare('ripdate',$this->ripdate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * This is the model class for table "enrollment".
 *
 * The followings are the available columns in table 'enrollment':
 * @property integer $id
 * @property integer $user_id
 * @property integer $event_id
 * @property integer $meal_id
 * @property integer $drink_id
 * @property integer $ito
 * @property string $timestamp
 */
class Enrollment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Enrollment the static model class
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
		return 'enrollment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, event_id, timestamp', 'required'),
			array('user_id, event_id, meal_id, drink_id, ito', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, event_id, meal_id, drink_id, ito, timestamp', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'event_id' => 'Event',
			'meal_id' => 'Meal',
			'drink_id' => 'Drink',
			'ito' => 'Ito',
			'timestamp' => 'Timestamp',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('meal_id',$this->meal_id);
		$criteria->compare('drink_id',$this->drink_id);
		$criteria->compare('ito',$this->ito);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
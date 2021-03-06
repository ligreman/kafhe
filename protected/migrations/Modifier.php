<?php

/**
 * This is the model class for table "modifier".
 *
 * The followings are the available columns in table 'modifier':
 * @property integer $id
 * @property integer $event_id
 * @property integer $caster_id
 * @property string $target_final
 * @property integer $skill_id
 * @property integer $item_id
 * @property string $keyword
 * @property string $value
 * @property integer $duration
 * @property string $duration_type
 * @property integer $hidden
 * @property string $timestamp
 */
class Modifier extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Modifier the static model class
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
		return 'modifier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, caster_id, target_final, keyword', 'required'),
			array('event_id, caster_id, skill_id, item_id, duration, hidden', 'numerical', 'integerOnly'=>true),
			array('target_final, keyword', 'length', 'max'=>50),
			array('value', 'length', 'max'=>15),
			array('duration_type', 'length', 'max'=>6),
			array('timestamp', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, caster_id, target_final, skill_id, item_id, keyword, value, duration, duration_type, hidden, timestamp', 'safe', 'on'=>'search'),
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
			'caster_id' => 'Caster',
			'target_final' => 'Target Final',
			'skill_id' => 'Skill',
			'item_id' => 'Item',
			'keyword' => 'Keyword',
			'value' => 'Value',
			'duration' => 'Duration',
			'duration_type' => 'Duration Type',
			'hidden' => 'Hidden',
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
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('caster_id',$this->caster_id);
		$criteria->compare('target_final',$this->target_final,true);
		$criteria->compare('skill_id',$this->skill_id);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('duration_type',$this->duration_type,true);
		$criteria->compare('hidden',$this->hidden);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
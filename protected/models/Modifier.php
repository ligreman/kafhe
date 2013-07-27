<?php

/**
 * This is the model class for table "modifier".
 *
 * The followings are the available columns in table 'modifier':
 * @property integer $id
 * @property integer $caster_id
 * @property integer $target_original_id
 * @property integer $target_final_id
 * @property integer $skill_id
 * @property integer $item_id
 * @property string $keyword
 * @property integer $value
 * @property integer $duration
 * @property string $duration_type
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
			array('caster_id, target_original_id, target_final_id, keyword', 'required'),
			array('caster_id, target_original_id, target_final_id, skill_id, item_id, value, duration', 'numerical', 'integerOnly'=>true),
			array('keyword', 'length', 'max'=>50),
			array('duration_type', 'length', 'max'=>6),
			array('timestamp', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, caster_id, target_original_id, target_final_id, skill_id, item_id, keyword, value, duration, duration_type, timestamp', 'safe', 'on'=>'search'),
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
			'caster_id' => 'Caster',
			'target_original_id' => 'Target Original',
			'target_final_id' => 'Target Final',
			'skill_id' => 'Skill',
			'item_id' => 'Item',
			'keyword' => 'Keyword',
			'value' => 'Value',
			'duration' => 'Duration',
			'duration_type' => 'Duration Type',
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
		$criteria->compare('caster_id',$this->caster_id);
		$criteria->compare('target_original_id',$this->target_original_id);
		$criteria->compare('target_final_id',$this->target_final_id);
		$criteria->compare('skill_id',$this->skill_id);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('value',$this->value);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('duration_type',$this->duration_type,true);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
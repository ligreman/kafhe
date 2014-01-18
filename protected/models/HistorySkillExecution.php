<?php

/**
 * This is the model class for table "history_skill_execution".
 *
 * The followings are the available columns in table 'history_skill_execution':
 * @property integer $id
 * @property integer $event_id
 * @property integer $skill_id
 * @property integer $caster_id
 * @property string $target_final
 * @property string $result
 * @property string $timestamp
 */
class HistorySkillExecution extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return HistorySkillExecution the static model class
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
		return 'history_skill_execution';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, skill_id, caster_id, target_final, result, timestamp', 'required'),
			array('event_id, skill_id, caster_id', 'numerical', 'integerOnly'=>true),
			array('target_final', 'length', 'max'=>50),
			array('result', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, skill_id, caster_id, target_final, result, timestamp', 'safe', 'on'=>'search'),
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
			'skill_id' => 'Skill',
			'caster_id' => 'Caster',
			'target_final' => 'Target Final',
			'result' => 'Result',
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
		$criteria->compare('skill_id',$this->skill_id);
		$criteria->compare('caster_id',$this->caster_id);
		$criteria->compare('target_final',$this->target_final,true);
		$criteria->compare('result',$this->result,true);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
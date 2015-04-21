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
			'name' => 'Name',
			'type' => 'Type',
			'ito' => 'Ito',
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
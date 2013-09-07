<?php

/**
 * This is the model class for table "Event".
 *
 * The followings are the available columns in table 'Event':
 * @property integer $id
 * @property integer $group_id
 * @property integer $caller_id
 * @property string $caller_side
 * @property integer $relauncher_id
 * @property integer $status
 * @property integer $gungubos_population
 * @property integer $gungubos_kafhe
 * @property integer $gungubos_achikhoria
 * @property string $last_gungubos_timestamp
 * @property integer $stored_tueste_kafhe
 * @property integer $stored_tueste_achikhoria
 * @property string $type
 * @property string $date
 */
class Event extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Event the static model class
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
		return 'Event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_id, date', 'required'),
			array('group_id, caller_id, relauncher_id, status, gungubos_population, gungubos_kafhe, gungubos_achikhoria, stored_tueste_kafhe, stored_tueste_achikhoria', 'numerical', 'integerOnly'=>true),
			array('caller_side', 'length', 'max'=>10),
			array('type', 'length', 'max'=>8),
			array('last_gungubos_timestamp', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, group_id, caller_id, caller_side, relauncher_id, status, gungubos_population, gungubos_kafhe, gungubos_achikhoria, last_gungubos_timestamp, stored_tueste_kafhe, stored_tueste_achikhoria, type, date', 'safe', 'on'=>'search'),
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
			'group_id' => 'Group',
			'caller_id' => 'Caller',
			'caller_side' => 'Caller Side',
			'relauncher_id' => 'Relauncher',
			'status' => 'Status',
            'gungubos_population' => 'Población de Gungubos',
			'gungubos_kafhe' => 'Gungubos Kafhe',
			'gungubos_achikhoria' => 'Gungubos Achikhoria',
			'last_gungubos_timestamp' => 'Last Gungubos Timestamp',
			'stored_tueste_kafhe' => 'Stored Tueste Kafhe',
			'stored_tueste_achikhoria' => 'Stored Tueste Achikhoria',
			'type' => 'Type',
			'date' => 'Date',
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
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('caller_id',$this->caller_id);
		$criteria->compare('caller_side',$this->caller_side,true);
		$criteria->compare('relauncher_id',$this->relauncher_id);
		$criteria->compare('status',$this->status);
        $criteria->compare('gungubos_population',$this->gungubos_population);
		$criteria->compare('gungubos_kafhe',$this->gungubos_kafhe);
		$criteria->compare('gungubos_achikhoria',$this->gungubos_achikhoria);
		$criteria->compare('last_gungubos_timestamp',$this->last_gungubos_timestamp,true);
		$criteria->compare('stored_tueste_kafhe',$this->stored_tueste_kafhe);
		$criteria->compare('stored_tueste_achikhoria',$this->stored_tueste_achikhoria);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
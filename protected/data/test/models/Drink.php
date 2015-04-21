<?php

/**
 * This is the model class for table "drink".
 *
 * The followings are the available columns in table 'drink':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property integer $ito
 */
class Drink extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Drink the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

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
			array('event_id, skill_id, caster_id, target_final, result', 'required'),
			array('event_id, skill_id, caster_id', 'numerical', 'integerOnly'=>true),
			array('target_final', 'length', 'max'=>50),
			array('result', 'length', 'max'=>6),
			array('timestamp', 'safe'),
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'drink';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('ito', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('type', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, type, ito', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'message' => 'Message',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('ito',$this->ito);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property string $id
 * @property integer $sender
 * @property integer $recipient_original
 * @property integer $recipient_final
 * @property string $message
 * @property string $timestamp
 * @property string $type
 * @property integer $read
 */
class Notification extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Notification the static model class
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
		return 'notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sender, recipient_original, recipient_final, message, timestamp, type', 'required'),
			array('sender, recipient_original, recipient_final, read', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sender, recipient_original, recipient_final, message, timestamp, type, read', 'safe', 'on'=>'search'),
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
			'sender' => 'Sender',
			'recipient_original' => 'Recipient Original',
			'recipient_final' => 'Recipient Final',
			'message' => 'Message',
			'timestamp' => 'Timestamp',
			'type' => 'Type',
			'read' => 'Read',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('sender',$this->sender);
		$criteria->compare('recipient_original',$this->recipient_original);
		$criteria->compare('recipient_final',$this->recipient_final);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('read',$this->read);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
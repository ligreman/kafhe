<?php

/**
 * This is the model class for table "skill".
 *
 * The followings are the available columns in table 'skill':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $category
 * @property string $type
 * @property string $keyword
 * @property string $modifier_keyword
 * @property integer $modifier_hidden
 * @property integer $duration
 * @property string $duration_type
 * @property integer $gunbudo_action_duration
 * @property integer $gunbudo_action_rate
 * @property integer $critic
 * @property integer $fail
 * @property string $extra_param
 * @property integer $cost_tueste
 * @property integer $cost_retueste
 * @property integer $cost_relanzamiento
 * @property integer $cost_tostolares
 * @property integer $cost_gungubos
 * @property integer $is_cooperative
 * @property integer $cost_tueste_cooperate
 * @property integer $cost_tostolares_cooperate
 * @property integer $cooperate_benefit
 * @property integer $require_target_user
 * @property string $require_target_side
 * @property integer $require_caller
 * @property string $require_user_side
 * @property integer $require_user_min_rank
 * @property integer $require_user_max_rank
 * @property string $require_user_status
 * @property string $require_event_status
 * @property integer $require_talent_id
 * @property integer $overload
 * @property integer $generates_notification
 */
class Skill extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Skill the static model class
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
		return 'skill';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, category, type, keyword, modifier_keyword', 'required'),
			array('modifier_hidden, duration, gunbudo_action_duration, gunbudo_action_rate, critic, fail, cost_tueste, cost_retueste, cost_relanzamiento, cost_tostolares, cost_gungubos, is_cooperative, cost_tueste_cooperate, cost_tostolares_cooperate, cooperate_benefit, require_target_user, require_caller, require_user_min_rank, require_user_max_rank, require_talent_id, overload, generates_notification', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('description', 'length', 'max'=>255),
			array('category', 'length', 'max'=>13),
			array('type', 'length', 'max'=>8),
			array('keyword, modifier_keyword, extra_param, require_user_status, require_event_status', 'length', 'max'=>50),
			array('duration_type', 'length', 'max'=>6),
			array('require_target_side, require_user_side', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, category, type, keyword, modifier_keyword, modifier_hidden, duration, duration_type, gunbudo_action_duration, gunbudo_action_rate, critic, fail, extra_param, cost_tueste, cost_retueste, cost_relanzamiento, cost_tostolares, cost_gungubos, is_cooperative, cost_tueste_cooperate, cost_tostolares_cooperate, cooperate_benefit, require_target_user, require_target_side, require_caller, require_user_side, require_user_min_rank, require_user_max_rank, require_user_status, require_event_status, require_talent_id, overload, generates_notification', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'description' => 'Description',
			'category' => 'Category',
			'type' => 'Type',
			'keyword' => 'Keyword',
			'modifier_keyword' => 'Modifier Keyword',
			'modifier_hidden' => 'Modifier Hidden',
			'duration' => 'Duration',
			'duration_type' => 'Duration Type',
			'gunbudo_action_duration' => 'Gunbudo Action Duration',
			'gunbudo_action_rate' => 'Gunbudo Action Rate',
			'critic' => 'Critic',
			'fail' => 'Fail',
			'extra_param' => 'Extra Param',
			'cost_tueste' => 'Cost Tueste',
			'cost_retueste' => 'Cost Retueste',
			'cost_relanzamiento' => 'Cost Relanzamiento',
			'cost_tostolares' => 'Cost Tostolares',
			'cost_gungubos' => 'Cost Gungubos',
			'is_cooperative' => 'Is Cooperative',
			'cost_tueste_cooperate' => 'Cost Tueste Cooperate',
			'cost_tostolares_cooperate' => 'Cost Tostolares Cooperate',
			'cooperate_benefit' => 'Cooperate Benefit',
			'require_target_user' => 'Require Target User',
			'require_target_side' => 'Require Target Side',
			'require_caller' => 'Require Caller',
			'require_user_side' => 'Require User Side',
			'require_user_min_rank' => 'Require User Min Rank',
			'require_user_max_rank' => 'Require User Max Rank',
			'require_user_status' => 'Require User Status',
			'require_event_status' => 'Require Event Status',
			'require_talent_id' => 'Require Talent',
			'overload' => 'Overload',
			'generates_notification' => 'Generates Notification',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('modifier_keyword',$this->modifier_keyword,true);
		$criteria->compare('modifier_hidden',$this->modifier_hidden);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('duration_type',$this->duration_type,true);
		$criteria->compare('gunbudo_action_duration',$this->gunbudo_action_duration);
		$criteria->compare('gunbudo_action_rate',$this->gunbudo_action_rate);
		$criteria->compare('critic',$this->critic);
		$criteria->compare('fail',$this->fail);
		$criteria->compare('extra_param',$this->extra_param,true);
		$criteria->compare('cost_tueste',$this->cost_tueste);
		$criteria->compare('cost_retueste',$this->cost_retueste);
		$criteria->compare('cost_relanzamiento',$this->cost_relanzamiento);
		$criteria->compare('cost_tostolares',$this->cost_tostolares);
		$criteria->compare('cost_gungubos',$this->cost_gungubos);
		$criteria->compare('is_cooperative',$this->is_cooperative);
		$criteria->compare('cost_tueste_cooperate',$this->cost_tueste_cooperate);
		$criteria->compare('cost_tostolares_cooperate',$this->cost_tostolares_cooperate);
		$criteria->compare('cooperate_benefit',$this->cooperate_benefit);
		$criteria->compare('require_target_user',$this->require_target_user);
		$criteria->compare('require_target_side',$this->require_target_side,true);
		$criteria->compare('require_caller',$this->require_caller);
		$criteria->compare('require_user_side',$this->require_user_side,true);
		$criteria->compare('require_user_min_rank',$this->require_user_min_rank);
		$criteria->compare('require_user_max_rank',$this->require_user_max_rank);
		$criteria->compare('require_user_status',$this->require_user_status,true);
		$criteria->compare('require_event_status',$this->require_event_status,true);
		$criteria->compare('require_talent_id',$this->require_talent_id);
		$criteria->compare('overload',$this->overload);
		$criteria->compare('generates_notification',$this->generates_notification);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
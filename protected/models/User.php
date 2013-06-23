<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $birthdate
 * @property string $role
 * @property string $group_id
 * @property string $side
 * @property integer $rank
 * @property integer $ptos_tueste
 * @property integer $ptos_retueste
 * @property integer $ptos_relanzamiento
 * @property integer $ptos_talentos
 * @property integer $tostolares
 * @property integer $azucarillos
 * @property integer $dominio_tueste
 * @property integer $dominio_habilidades
 * @property integer $dominio_bandos
 * @property integer $times
 * @property integer $calls
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email', 'required'),
			array('rank, ptos_tueste, ptos_retueste, ptos_relanzamiento, ptos_talentos, tostolares, azucarillos, dominio_tueste, dominio_habilidades, dominio_bandos, times, calls', 'numerical', 'integerOnly'=>true),
			array('username, password, email', 'length', 'max'=>128),
			array('role', 'length', 'max'=>9),
			array('group_id, side', 'length', 'max'=>10),
			array('birthdate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, email, birthdate, role, group_id, side, rank, ptos_tueste, ptos_retueste, ptos_relanzamiento, ptos_talentos, tostolares, azucarillos, dominio_tueste, dominio_habilidades, dominio_bandos, times, calls', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'birthdate' => 'Birthdate',
			'role' => 'Role',
			'group_id' => 'Group',
			'side' => 'Side',
			'rank' => 'Rank',
			'ptos_tueste' => 'Ptos Tueste',
			'ptos_retueste' => 'Ptos Retueste',
			'ptos_relanzamiento' => 'Ptos Relanzamiento',
			'ptos_talentos' => 'Ptos Talentos',
			'tostolares' => 'Tostolares',
			'azucarillos' => 'Azucarillos',
			'dominio_tueste' => 'Dominio Tueste',
			'dominio_habilidades' => 'Dominio Habilidades',
			'dominio_bandos' => 'Dominio Bandos',
			'times' => 'Times',
			'calls' => 'Calls',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('birthdate',$this->birthdate,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('side',$this->side,true);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('ptos_tueste',$this->ptos_tueste);
		$criteria->compare('ptos_retueste',$this->ptos_retueste);
		$criteria->compare('ptos_relanzamiento',$this->ptos_relanzamiento);
		$criteria->compare('ptos_talentos',$this->ptos_talentos);
		$criteria->compare('tostolares',$this->tostolares);
		$criteria->compare('azucarillos',$this->azucarillos);
		$criteria->compare('dominio_tueste',$this->dominio_tueste);
		$criteria->compare('dominio_habilidades',$this->dominio_habilidades);
		$criteria->compare('dominio_bandos',$this->dominio_bandos);
		$criteria->compare('times',$this->times);
		$criteria->compare('calls',$this->calls);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
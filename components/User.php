<?php

namespace frenzelgmbh\appcommon\components;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

use dektrium\user\models\User as BaseUser;

class User extends BaseUser
{

	//defining the roles available for users
	const ROLE_ADMIN    = 1;
	const ROLE_ADVANCED = 2;
	const ROLE_USER 	  = 3;

	public static $roles = [
			'admins'   => self::ROLE_ADMIN,
			'advanced' => self::ROLE_ADVANCED,
			'users'    => self::ROLE_USER
	];

	public static function getRoleOptions()
  {
    return self::$roles;
  }

  /**
   * Returns a string representation of the model's role
   *
   * @return string The role of this model as a string
   */
  public function getRoleAsString()
  {
  	$options = self::getRoleOptions();
  	return isset($options[$this->role]) ? $options[$this->role] : '';
  }
	
	/**
	 * [getisAdmin description]
	 * @return boolean [description]
	 */
	public function getIsAdmin()
	{
		if(!\Yii::$app->user->isGuest)
		{
			if(\Yii::$app->user->identity->role < 2)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * [getisAdvanced description]
	 * @return boolean [description]
	 */
	public function getIsAdvanced()
	{
		if(!\Yii::$app->user->isGuest)
		{
			if(\Yii::$app->user->identity->role < 3)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * [getisUser description]
	 * @return boolean [description]
	 */
	public function getIsUser()
	{
		if(!\Yii::$app->user->isGuest)
		{
			if(\Yii::$app->user->identity->role < 4)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * [getCurrentEntityId description]
	 * @return integer the primary party key of the user that will log in
	 */
	public function getCurrentEntityId()
	{
		$query = new Query;
		$query->select('tbl_party.id AS id')
			->from('tbl_user')
      ->innerJoin('tbl_contact', 'tbl_contact.email = tbl_user.email')
      ->innerJoin('tbl_party','tbl_party.id = tbl_contact.party_id')
			->where('tbl_user.id = '.\Yii::$app->user->identity->id)
			->one();
		
		$command = $query->createCommand();
		$row = $command->queryOne();

		if(!is_null($row['id']))
		{
			return $row['id'];
		}
		return NULL;
	}

	public function getUserByEmailId($email)
	{
		$query = new Query;
		$query->select('id')
			->from('tbl_user')
      ->where(['email' => $email])
			->one();
		
		$command = $query->createCommand();
		$row = $command->queryOne();

		if(!is_null($row['id']))
		{
			return $row['id'];
		}
		return NULL;
	}

	/**
	 * [getCurrentContactId description]
	 * @return integer the primary contact key of the user that will log in
	 */
	public function getCurrentContactId()
	{
		$query = new Query;
		$query->select('tbl_contact.id AS id')
			->from('tbl_user')
      ->innerJoin('tbl_contact', 'tbl_contact.email = tbl_user.email')
      ->where('tbl_user.id = '.\Yii::$app->user->identity->id)
			->one();
		
		$command = $query->createCommand();
		$row = $command->queryOne();

		if(!is_null($row['id']))
		{
			return $row['id'];
		}
		return NULL;
	}

	/**
	 * [checkAccess description]
	 * @param  integer $role see constants above to see possible values
	 * @return boolean if access is granted or not
	 */
	public function checkAccess($role){
		if(!\Yii::$app->user->isGuest)
		{
			if(Yii::$app->user->identity->role == self::$roles[$role])
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * returns all users as an array for a pull down element in html
	 * @return array of registered users
	 */
	public static function pdUsers()
	{
		return ArrayHelper::map(self::find()->orderBy('username')->asArray()->all(), 'id', 'username');
	}

	/**
   * @inheritdoc
   */
  public static function findIdentityByAccessToken($token,$type = NULL)
  {
      foreach (self::findAll() as $user) {
          if ($user['accessToken'] === $token) {
              return new static($user);
          }
      }

      return null;
  }
}

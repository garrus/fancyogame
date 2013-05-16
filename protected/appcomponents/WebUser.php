<?php
class WebUser extends \CWebUser {
	
	protected function afterLogin($fromCookie){
		if (!$fromCookie) {
			Account::updateLoginRecord($this->getId(), Yii::app()->request->userHostAddress);
		}
	}
}

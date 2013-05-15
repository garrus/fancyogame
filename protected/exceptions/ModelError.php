<?php

class ModelError extends \CException {

	public function __construct(CModel $model) {
		$errors = $model->getErrors();
		if ($errors!==array()) {
			list(, $error) = each($errors);
			list(, $error) = each($error);
		} else {
			$error = 'Unknown error.';
		}
		
		parent::__construct('Operation blocked by model validation error: ['. get_class($model). '] '. $error);
	}
}

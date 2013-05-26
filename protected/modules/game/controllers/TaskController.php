<?php

class TaskController extends \GameBaseController {

    /**
     * (non-PHPdoc)
     * @see GameBaseController::filters()
     */
    public function filters(){

        return array_merge(parent::filters(), array(
            'planetContext',
        ));
    }

    public function actionAdd($type, $name, $amount=1){

        $this->planet->addNewTask($type, $name, $amount);
        $this->forwardHome();
    }

    public function actionCancel($id){

        $this->planet->cancelTask($id);
        $this->forwardHome();
    }

}

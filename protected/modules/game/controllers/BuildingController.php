<?php
/**
 *
 * @author user
 *
 */
class BuildingController extends \GameBaseController {


    /**
     * (non-PHPdoc)
     * @see GameBaseController::filters()
     */
    public function filters(){

        return array_merge(parent::filters(), array(
            'planetContext',
        ));
    }




    public function actionConst($name){

        ob_start();

        $this->planet->addNewTask(Task::TYPE_CONSTRUCT, $name);
        ob_end_clean();
        $this->redirect(array('planet/home'));
    }

    public function actionDeconst($name){

        ob_start();
        $this->planet->addNewTask(Task::TYPE_DECONSTRUCT, $name);
        ob_end_clean();
        $this->redirect(array('planet/home'));
    }

}

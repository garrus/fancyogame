<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 13-6-22
 * Time: 下午8:43
 * To change this template use File | Settings | File Templates.
 */

class FleetController extends GameBaseController {

    /**
     * (non-PHPdoc)
     * @see GameBaseController::filters()
     */
    public function filters(){

        return array_merge(parent::filters(), array(
            'planetContext + listCurrent',
        ));
    }




}
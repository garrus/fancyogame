<?php
/**
 *
 * @author user
 *
 */
class PlanetController extends GameBaseController {


    public $defaultAction = 'home';

    /**
     * (non-PHPdoc)
     * @see GameBaseController::filters()
     */
    public function filters(){

        return array_merge(parent::filters(), array(
            'planetContext - list,enter,view',
        ));
    }

    /**
     *
     * @param string $id
     * @throws CHttpException
     * @return Planet
     */
    protected function loadModel($id) {

        $model = ZPlanet::model()->with('planetData', 'tasks')->findByPk($id);
        if ($model) {
            return $model;
        } else {
            throw new CHttpException(404, 'Unable to find the planet.');
        }
    }

    /**
     * The list of player's planets
     */
    public function actionList() {

        $model = new ZPlanet('search');
        $model->owner_id = $this->player->id;

        $this->render('list', array(
            'dataProvider' => new CActiveDataProvider('Planet', array(
                'criteria' => array(
                    'condition' => 'owner_id=:owner_id',
                    'params' => array('owner_id' => $this->player->id),
                    ),
                'pagination' => false,
                )),
        ));
    }

    /**
     * View the details of a planet
     *
     * @param string $id
     */
    public function actionView($id) {

        $planet = $this->loadModel($id);

        $this->render('view', array(
            'model' => $planet,
        ));
    }

    /**
     * Enter a planet
     * Planet context switching happens here
     *
     * @param string $id
     */
    public function actionEnter($id) {

        Yii::app()->actx->switchPlanet($this->loadModel($id));
        $this->redirect(array('home'));
    }


    public function actionHome() {

        $this->render('home', array(
            'model' => $this->planet,
        ));
    }


    public function actionCreate(){

        Gen_Location:
        $hex = md5(microtime());
        $i = 0;
        do {
            $location = new Location(
                hexdec($hex[$i++]) + 1,
                hexdec($hex[$i++]. $hex[$i++]) + 1,
                mt_rand(4, 13)
            );

            $planet = $location->findPlanet();
            if (!$planet || self::isPlanetTouched($planet)) {
                break;
            } else {
                $location = null;
            }
        } while ($i < 30);

        if (!$location) {
            goto Gen_Location;
        }
        $planet = PlanetHelper::createPlanetAtLocation($location);
        PlanetHelper::setPlanetOwner($planet, $this->player);
        $this->redirect(array('list'));
    }

}
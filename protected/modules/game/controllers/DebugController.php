<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 13-6-11
 * Time: 下午6:29
 * To change this template use File | Settings | File Templates.
 */

class DebugController extends GameBaseController {


    /**
     * @return array
     */
    public function filters(){

        return array_merge(parent::filters(), array(
            'planetContext - InitMotherPlanet',
        ));
    }

    public function actionResetPlayer(){

        /** @var Player $player */
        $player = Yii::app()->actx->getPlayer();
        foreach ($player->planets as $planet) {
            $planet->delete();
        }
        if ($player->playerData) {
            $player->playerData->regenerate();
        }
        $planet = PlanetHelper::createMotherPlanet($player);
        Yii::app()->actx->switchPlanet($planet);
        $this->planet = $planet;
        $this->redirect(array('planet/home'));
    }

    public function actionFillResource(){
        $planet = $this->planet;
        $res = $planet->resources;

        $maxStorage = Calculator::resource_capacity($planet->buildings->warehouse);
        $res->add(Resources::c(array(
            'metal' => $maxStorage / 2,
            'crystal' => $maxStorage / 3,
            'gas' => $maxStorage / 6
        )));

        $this->redirect(array('planet/home'));
    }

    public function actionCreatePlanet(){

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
            if (!$planet || PlanetHelper::isPlanetTouched($planet)) {
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
        $this->redirect(array('planet/list'));
    }
}
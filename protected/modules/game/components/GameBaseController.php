<?php

class GameBaseController extends \CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='//layouts/game';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
    */
    public $breadcrumbs=array();

    /**
     *
     * @var Player
     */
    protected $player;

    /**
     *
     * @var ZPlanet
     */
    protected $planet;

    /**
     * (non-PHPdoc)
     * @see CController::filters()
     */
    public function filters(){

        return array(
            'playerContext',
        );
    }

    /**
     * Filter that there should be current player in session
     *
     * @param CFilterChain $filterChain
     */
    public function filterPlayerContext($filterChain){

        if (null !== ($this->player = Yii::app()->actx->getPlayer())) {
            $filterChain->run();
        } else {
            $this->redirect(array('/site/selectPlayer'));
        }
    }


    /**
     * Filter that there should be current planet in session
     *
     * @param CFilterChain $filterChain
     */
    public function filterPlanetContext($filterChain){

        if (null !== ($this->planet = Yii::app()->actx->getPlanet())) {
            $planet = $this->planet;
            $planet();
            $filterChain->run();
        } else {
            $this->redirect(array('planet/list'));
        }
    }


    public function forwardHome(){

        $this->redirect(array('planet/home'));

        //$this->forward('planet/home', true);
    }

}

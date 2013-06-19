<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 13-6-19
 * Time: 下午10:22
 * To change this template use File | Settings | File Templates.
 */
/**
 * Class CollectionAttributeBehavior
 *
 * @property CActiveRecord $owner
 *
 */
class CollectionAttributeBehavior extends CBehavior {

    /**
     * @var array
     */
    public $collections = array();
    /**
     * @var array name => Collection
     */
    private $_cache;
    /**
     * @var SplObjectStorage Collection => name
     */
    private $_objCache;


    /**
     * @param string $name
     * @param string $attribute
     * @param Collection $col
     */
    private function cache($name, $attribute, $col) {
        $this->_cache[$name] = $col;
        $this->_objCache->attach($col, $attribute);
        $col->attachEventHandler('onchange', array($this, 'onCollectionChange'));
    }

    /**
     * @param string $name
     * @return array (attribute, class)
     * @throws InvalidArgumentException if $name does not exists
     */
    private function getConfig($name) {
        if (isset($this->collections[$name])) {
            $config = $this->collections[$name];
            return array($config['attr'], $config['class']);
        } else {
            throw new InvalidArgumentException('There is no collection named ' . $name . ' in ' . get_class($this->owner));
        }
    }

    /**
     * @param $attribute
     * @param $col
     * @throws ModelError
     */
    private function updateColAttribute($attribute, $col) {

        $owner = $this->owner;
        $owner->setAttribute($attribute, json_encode($col));
        if (!$owner->isNewRecord) {
            if (!$owner->save(true, array($attribute, 'last_update_time'))) {
                throw new ModelError($owner);
            }
        }
    }

    /**
     * @param CActiveRecord $owner
     * @throws InvalidArgumentException
     */
    public function attach($owner) {

        foreach ($this->collections as $name => $config) {
            $c = new CMap($config);
            if (!$owner->hasAttribute($c['attr'])) {
                throw new InvalidArgumentException('Invalid config for collection ' . $name . ': ' .
                get_class($owner) . ' does not have attribute ' . $c['attr'] . '.');
            }
            if (!is_subclass_of($c['class'], 'Collection')) {
                throw new InvalidArgumentException('Invalid config for collection ' . $name . ': ' .
                $c['class'] . ' is not a sub class of Collection.');
            }
        }

        $this->_cache = array();
        $this->_objCache = new SplObjectStorage();

        parent::attach($owner);
    }

    /**
     * @param CComponent $owner
     */
    public function detach($owner) {

        if ($owner == $this->owner && !empty($this->_cache)) {
            $this->_cache = array();
            $this->_objCache = new SplObjectStorage();
        }
        parent::detach($owner);
    }


    /**
     *
     * @param CEvent $event
     */
    public function onCollectionChange($event) {

        $col = $event->sender;
        if ($this->_objCache->contains($col)) {
            $this->updateColAttribute($this->_objCache->offsetGet($col), $col);
        }
    }

    /**
     * @param string $name
     * @throws InvalidArgumentException
     * @return Collection
     */
    public function getCollection($name) {

        if (isset($this->_cache[$name])) {
            return $this->_cache[$name];
        }

        $owner = $this->owner;
        list($attribute, $class) = $this->getConfig($name);
        /**
         * @var $col Collection
         * @var $class Collection class name
         **/
        $val = $owner->getAttribute($attribute);
        $col = $class::fromJson($val);

        $this->cache($name, $attribute, $col);
        return $col;
    }

    /**
     * @param string $name
     * @param Collection $col
     * @throws InvalidArgumentException
     */
    public function setCollection($name, $col) {

        if ($this->_objCache->contains($col)) {
            $this->updateColAttribute($this->_objCache->offsetGet($col), $col);
            return;
        }

        if (isset($this->_cache[$name])) { // we should clean the old object first
            /** @var Collection $_col */
            $_col = $this->_cache[$name];
            $attribute = $this->_objCache->offsetGet($_col);
            $_col->detachEventHandler('onchange', array($this, 'onCollectionChange'));
            $this->_objCache->detach($_col);
            unset($_col);
        } else { // set a new object in
            list($attribute, $class) = $this->getConfig($name);
            if (!$col instanceof $class) {
                throw new InvalidArgumentException('Collection ' . $name . ' should be an instance of ' . $class);
            }
        }

        $this->cache($name, $attribute, $col);
        $this->updateColAttribute($attribute, $col);
        $col->onChange();
    }


}
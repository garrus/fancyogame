<?php
class Utils {

    /**
     * Return the first validation error of the model
     *
     * @param CModel $model
     * @return string
     */
    public static function modelError(CModel $model) {
        $errors = $model->hasErrors();
        if (!empty($errors)) {
            foreach ($errors as $errs) {
                foreach ($errs as $err) {
                    return $err;
                }
            }
        }
        return 'No error message';
    }


    /**
     * Cleanly clone a component.
     * It will detach all behaviors and event listeners of the cloned object.
     *
     * @param CComponent $component
     * @return CComponent
     */
    public static function cleanClone(CComponent $component) {
        static $property=null;
        if (!$property) {
            $reflection = new ReflectionClass('CComponent');
            $property = $reflection->getProperty('_e');
            $property->setAccessible(true);
        }

        $_clone = clone $component;
        $_clone->detachBehaviors();
        $property->setValue($_clone, null);

        return $_clone;
    }

}

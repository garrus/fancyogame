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
    
    /**
     * Built a Datetime object from given parameter
     *
     * @param mixed $dateTime  can be a string(a datetime format), a int(second), a long(microsecond) or a DateTime object
     * @param string $format [=null] This argument is used when you would like to create DateTime with <code>DateTime::createFromFormat()</code> instead of constructor
     * @throws InvalidArgumentException
     * @see DateTime::createFromFormat()
     * @return DateTime
     */
    public static function ensureDateTime($dateTime, $format=null) {
    
        if ( is_object($dateTime) ) {
            if ($dateTime instanceof DateTime) {
                return clone $dateTime;
            }
            if ( isset($dateTime->time) && is_numeric($dateTime->time) ) {
                $dateTime = $dateTime->time; // this will be handled on next branch
            } else {
                throw new InvalidArgumentException('Unable to convert a '. get_class($dateTime). ' into a DateTime.');
            }
        }
    
        if (is_numeric($dateTime)) {
            if ($dateTime > 2000000000) {
                $dateTime = round($dateTime / 1000);
            }
            $dateTime = new DateTime('@'.$dateTime); // this will ignore timezone
            $dateTime->setTimezone(new DateTimeZone(DEFAULT_TIMEZONE));
            return $dateTime;
        }
    
        if ( empty($dateTime) ) {
            // instead of throw exception, let's return a 0000 datetime
            return new DateTime('@0');
        }
    
        if (is_string($dateTime)) {
            if ( is_string($format) ) {
                $ret = DateTime::createFromFormat($format, $dateTime);
                if ( !$ret ) {
                    throw new InvalidArgumentException('Unable to create DateTime from format "'. $format. '" with "'. $dateTime. '".');
                }
                return $ret;
            }
            return new DateTime($dateTime);
        } else {
            return new DateTime('@0');
        }
    }

}

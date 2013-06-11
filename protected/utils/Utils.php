<?php
class Utils {

    /**
     * Return the first validation error of the model
     *
     * @param CModel $model
     * @return string
     */
    public static function modelError(CModel $model) {
        $errors = $model->getErrors();
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

    public static function formatDiff($datetime, DateTime $rel_datetime=null) {
        $datetime = self::ensureDateTime($datetime);
        if ($rel_datetime) {
            $_datetime = clone $rel_datetime;
        } else {
            $_datetime = new DateTime;
        }
        $hours = self::getHours($datetime->diff($_datetime));
        return self::formatHours($hours);
    }

    public static function formatHours($hours, $toSecond=true){
        $seconds = intval(($hours - floor($hours)) * 3600);
        $hours = floor($hours);
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        if ($hours) {
            if (!$toSecond) {
                return sprintf('%dh %dm', $hours, $minutes);
            }
            return sprintf('%dh %dm %ds', $hours, $minutes, $seconds);
        } elseif ($minutes) {
            if (!$toSecond) {
                return sprintf('%dm', $minutes);
            }
            return sprintf('%dm %ds', $minutes, $seconds);
        } else {
            return sprintf('%ds', $seconds);
        }
    }


    public static function getHours(DateInterval $diff){

        $hours = $diff->h + $diff->i / 60 + $diff->s / 3600;
        $hours += $diff->days * 24;
        return $hours;
    }


    public static function displayFlash($key, $msg){

        $htmlOptions = array();
        if (preg_match('/^(notice|error|success|info)_(.+)$/', $key, $matches)) {
            $htmlOptions['class'] = "fade in alert-$matches[1] alert";
            $htmlOptions['data-key'] = $matches[2];
        } else {
            $htmlOptions['class'] = "fade in alert";
            $htmlOptions['data-key'] = $key;
        }

        echo CHtml::openTag('div', $htmlOptions);
        echo CHtml::openTag('button', array(
            'type' => 'button',
            'class' => 'close',
            'data-dismiss' => 'alert',
            ));
        echo '&times;';
        echo CHtml::closeTag('button');
        echo CHtml::encode($msg);
        echo CHtml::closeTag('div');

        Yii::app()->clientScript->registerScript('bt-alert-box', '$(".alert").alert();', CClientScript::POS_END);
    }


    public static function timelinePercentage($time1, $time2, $now=null){

        if (!is_int($time1)) {
            $time1 = self::ensureDateTime($time1)->getTimestamp();
        }
        if (!is_int($time2)) {
            $time2 = self::ensureDateTime($time2)->getTimestamp();
        }
        if (!$now) {
            $now = time();
        } elseif (!is_int($now)) {
            $now = self::ensureDateTime($now)->getTimestamp();
        }

        if ($time2 == $time1) {
            return 100;
        }

        return round(100 * ($now - $time1) / ($time2 - $time1));

    }

}

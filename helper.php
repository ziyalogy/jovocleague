<?php
/**
 * ------------------------------------------------------------------------
 * jovocleague_tpl
 * ------------------------------------------------------------------------
 * Copyright (C) 2022 Buildal Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: Ziyal Amanya
 * Websites:  http://www.buildal.ug
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

class JATemplateHelper
{
    public static function getCustomFields($id, $context)
    {
        if ($context == 'article') {
            $context = 'com_content.article';
        } elseif ($context == 'contact') {
            $context = 'com_contact.contact';
        } elseif ($context == 'user') {
            $context = 'com_users.user';
        }
        $currentLanguage = JFactory::getLanguage();
        $currentTag = $currentLanguage->getTag();

        $sql =
            'SELECT fv.value, fg.title AS gtitle, f.title AS ftitle, f.name
				FROM #__fields_values fv
				LEFT JOIN #__fields f ON fv.field_id = f.id
				LEFT JOIN #__fields_groups fg ON fg.id = f.group_id
				WHERE fv.item_id = ' .
            $id .
            '
				AND f.context = "' .
            $context .
            '"
				AND f.language IN ("*", "' .
            $currentTag .
            '")
				AND f.access = 1
				';
        // echo $sql;
        $db = JFactory::getDbo();
        $db->setQuery($sql);
        $result = $db->loadObjectList();
        $arr = [];
        foreach ($result as $r) {
            if (
                self::isJson($r->value) &&
                version_compare(JVERSION, '4', 'ge')
            ) {
                $imgArr = json_decode($r->value, true);
                $img = $imgArr['imagefile'];
            } else {
                $img = $r->value;
            }
            $arr[$r->name][] = $img;
        }

        return $arr;
    }

    public static function isJson($string)
    {
        return is_string($string) &&
            (is_object(json_decode($string)) || is_array(json_decode($string)))
            ? true
            : false;
    }
    public static function OpenClosedTime($startTime, $endTime)
    {
        $config = JFactory::getConfig();
        date_default_timezone_set($config->get('offset', 'UTC'));
        if (empty($startTime) && empty($endTime)) {
            return Jtext::_('TPL_CLOSED');
        }
        $current_time = time();
        // default status
        $status = Jtext::_('TPL_CLOSED');
        // get current time object
        $currentTime = (new DateTime())->setTimestamp($current_time);

        // create time objects from start/end times
        $startTime = DateTime::createFromFormat('h:i A', $startTime);
        $endTime = DateTime::createFromFormat('h:i A', $endTime);
        // check if current time is within a range
        if ($startTime < $currentTime && $currentTime < $endTime) {
            $status = Jtext::_('TPL_OPEN_NOW');
        } elseif (
            $startTime > $endTime &&
            ($startTime < $currentTime || $currentTime < $endTime)
        ) {
            $status = Jtext::_('TPL_OPEN_NOW');
        }
        return $status;
    }
}

?>

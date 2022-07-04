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

defined('_JEXEC') or die('Restricted access');
$jinput = JFactory::getApplication()->input;
$view = $jinput->get('view', '', 'CMD'); //JRequest::getCmd('view');
$option = $jinput->get('option', '', 'CMD'); //JRequest::getCmd('option');

$mh_background = new stdClass();
$mh_background->url = '';
$mh_background->type = '';
$mh_bg_check = '';

if (
    isset($masthead['params']['background']) &&
    !empty($masthead['params']['background'])
) {
    $mh_background->url = $masthead['params']['background'];
    $frags = explode('#', $mh_background->url);
    $mh_background->url = array_shift($frags);
    $mh_bg_check = 'has-bg';

    if (preg_match('/^.*\.(mp4|ogg|webm)$/', $mh_background->url)) {
        $mh_background->type = 'video';
    } else {
        $mh_background->type = 'image';
    }

    if ($option == 'com_content' && $view == 'article') {
        $mh_bg_check = 'article-bg';
    }
}
?>
    
<div class="jv_breadcrumbs">
        <div class="container">
                        <?php echo JHtml::_(
                'content.prepare',
                '{loadposition breadcrumbs}'
            ); ?>
            </div>
</div>

<div class="ja-masthead <?php echo $mh_bg_check .
    ' ' .
    $params->get('moduleclass_sfx', ''); ?>" <?php if (
    $mh_background &&
    $mh_background->type == 'image'
): ?>style="background-image: url('<?php echo $mh_background->url; ?>')"<?php endif; ?>>
	<?php 
if ($mh_background && $mh_background->type == 'video'):
     preg_match_all('/^.*\.(mp4|ogg|webm)$/', $mh_background->url, $mathes); ?>
        <div class="ja-masthead-bg">
            <video id="ja_masthead_bg_video" loop="true" autoplay="true" playsinline muted>
                <source src="<?php echo $mh_background->url; ?>" />
            </video>
        </div>
    <?php endif; ?>

    <div class="container">
        
        <div class="ja-masthead-detail">

    		<h1 class="ja-masthead-title"><?php echo $masthead['title']; ?></h1>
            <?php if ($masthead['description'] != ''): ?>
    		  <div class="ja-masthead-description"><?php echo $masthead[
            'description'
        ]; ?></div>
            <?php endif; ?>
    	</div>
    </div>
</div>

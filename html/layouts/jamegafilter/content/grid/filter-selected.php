<?php
/**
 * ------------------------------------------------------------------------
 * JA Filter Plugin - Content
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2016 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: Ziyal Amanya
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die(); ?>
<div class="filter-selected filter-current filter-values hide">
	<h3 class="block-subtitle filter-current-subtitle" role="heading" aria-level="2" data-count="1"><?php echo JText::_(
     'COM_JAMEGAFILTER_SELECTED_FILTERS'
 ); ?></h3>
	
	<ol class="items">
	{@iter:values}
		{#value}<li class="item">
			<label data-lnprop="{prop}" class="clear-filter action remove">
	            <span class="filter-label">{name}</span>
	            <span class="filter-value">{value|s}</span>
		   </label>
        </li>{/value}     
    {/iter}        
	</ol>
	
</div>
<div class="block-actions filter-actions">
    <div class="btn btn-primary clear-all-filter action filter-clear"><?php echo JText::_(
        'COM_JAMEGAFILTER_CLEAR_ALL'
    ); ?></div>
</div> 
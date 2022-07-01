<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_categories
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div id="owl-categories-<?php echo $module->id ;?>" class="owl-categories">
	<div class="owl-carousel">
		<?php require JModuleHelper::getLayoutPath('mod_articles_categories', $params->get('layout', 'default') . '_items'); ?>
	</div>
</div>


<script>
// Carousel - Articles Slide
jQuery(document).ready(function($) {
	$('#owl-categories-<?php echo $module->id ;?> .owl-carousel').owlCarousel({
    item: 4,
    loop: true,
    margin: 24,
    nav: true,
    navText: [
      '<span aria-label="' + 'Previous' + '" class= "fa fa-angle-left" aria-hidden="true"></span>',
      '<span aria-label="' + 'Next' + '" class= "fa fa-angle-right" aria-hidden="true"></span>'
    ],
    dots: true,
    responsive:{
      0:{
        items: 1
      },
      480:{
        items: 2
      },
      768:{
        items: 2
      },
      1200:{
        items: 2
      },
      1440:{
        items: 4
      }
    },
    lazyLoad: true
	})
});
</script>
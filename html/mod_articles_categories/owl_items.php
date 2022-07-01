<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_categories
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$input  = JFactory::getApplication()->input;
$option = $input->getCmd('option');
$view   = $input->getCmd('view');
$id     = $input->getInt('id');
$filterID = $params->get('menu-filter');

foreach ($list as $item) : ?>
	<div class="category-inner">
		<?php 
			$catImage = "";
			if(json_decode($item->params)->image) {
				$catImage = json_decode($item->params)->image;
				$catBg = 'style="background-image: url('.JUri::root().''.$catImage.');"';
			};

			$catLink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->id));
			if($filterID) {
				$catLink = JRoute::_("index.php?Itemid={$filterID}#attr.cat.value={$item->id}");
			}
		?>

		<a href="<?php echo $catLink; ?>" <?php echo $catBg ;?>>
			<span class="info">
				<span class="title"><?php echo $item->title; ?></span>
				<?php if ($params->get('numitems')) : ?>
					<span class="numitems fs-xs"><?php echo $item->numitems.' '.JText::_('TPL_LISTING'); ?></span>
				<?php endif; ?>

				<?php if ($params->get('show_description', 0)) : ?>
					<span class="desc">
						<?php echo JHtml::_('content.prepare', $item->description, $item->getParams(), 'mod_articles_categories.content'); ?>
					</span>
				<?php endif; ?>
			</span>
		</a>

		
	</div>
<?php endforeach; ?>

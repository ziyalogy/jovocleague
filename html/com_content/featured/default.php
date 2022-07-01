<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

if(!class_exists('ContentHelperRoute')){
	if(version_compare(JVERSION, '4', 'ge')){
		abstract class ContentHelperRoute extends \Joomla\Component\content\Site\Helper\RouteHelper{};
	}else{
		JLoader::register('ContentHelperRoute', $com_path . '/helpers/route.php');
	}
}
//compatible params on joomla 4
$this->columns = !empty($this->columns) ? $this->columns : $this->params->get('num_columns',1);
if(!$this->columns) $this->columns = 1;
$this->blog_class_leading = $this->params->get('blog_class_leading','');
$this->blog_class = $this->params->get('blog_class','');
?>
<div class="blog-featured" itemscope itemtype="https://schema.org/Blog">
	<?php if ($this->params->get('show_page_heading') != 0) : ?>
	<div class="page-header">
		<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>
	<?php if ($this->params->get('page_subheading')) : ?>
		<h2>
			<?php echo $this->escape($this->params->get('page_subheading')); ?>
		</h2>
	<?php endif; ?>

	<?php $leadingcount = 0; ?>
	<?php if (!empty($this->lead_items)) : ?>
		<div class="blog-items items-leading <?php echo $this->params->get('blog_class_leading'); ?>">
			<?php foreach ($this->lead_items as &$item) : ?>
				<div class="blog-item type-horizontal"
					itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
					<div class="blog-item-content"><!-- Double divs required for IE11 grid fallback -->
						<?php
						$this->item = & $item;
						echo $this->loadTemplate('item');
						?>
					</div>
				</div>
				<?php $leadingcount++; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if (!empty($this->intro_items)) : ?>
		<?php 
			$blogClass = $this->blog_class;
			$introcount = count($this->intro_items);
			$counter = 0;

			$row = $counter / $this->columns;
		?>
		<?php if ((int) $this->columns  > 1) : ?>
			<?php $blogClass .= (int) $this->params->get('multi_column_order', 0) === 0 ? ' masonry-' : ' columns-'; ?>
			<?php $blogClass .= (int) $this->columns ; ?>
		<?php endif; ?>
		<div class="blog-items <?php echo $blogClass; ?>">
			<div class="items-intro row-cols-1 <?php echo $this->columns == 1 ? '' : 'row-cols-sm-2 row-cols-lg-3'; ?> row-cols-xl-<?php echo $this->columns ;?> row">
		<?php foreach ($this->intro_items as $key => &$item) : ?>
			<?php
				$key = ($key - $leadingcount) + 1;
				$rowcount = (((int) $key - 1) % (int) $this->columns) + 1;
			?>
			<div class="item-wrap col">
				<div class="item <?php echo $this->columns == 1 ? 'type-horizontal' : 'type-vertical'; ?>"
					itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
				<?php
						$this->item = &$item;
						echo $this->loadTemplate('item');
				?>
				</div>
				<?php $counter++; ?>
			</div>
		<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if (!empty($this->link_items)) : ?>
		<div class="items-more">
			<h4><?php echo Text::_('TPL_MORE_ITEMS'); ?></h4>
			<?php echo $this->loadTemplate('links'); ?>
		</div>
	<?php endif; ?>

	<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->pagesTotal > 1)) : ?>
		<div class="com-content-category-blog__navigation w-100 mb-4">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter float-right float-end pt-3 pr-2 pe-2">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>

</div>

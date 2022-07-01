<?php
/**
T4 Overide
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
//use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use T4\Helper\J3J4;
if(!class_exists('ContentHelperRoute')){
	if(version_compare(JVERSION, '4', 'ge')){
		abstract class ContentHelperRoute extends \Joomla\Component\content\Site\Helper\RouteHelper{};
	}else{
		JLoader::register('ContentHelperRoute', $com_path . '/helpers/route.php');
	}
}

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = Factory::getUser();
$info    = $params->get('info_block_position', 0);
$og_iamge = $images->image_intro ?: $images->image_fulltext;
// Check if associations are implemented. If they are, define the parameter.
$assocParam = (Associations::isEnabled() && $params->get('show_associations'));

?>
<div class="com-content-article item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Article">
	<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? Factory::getApplication()->get('language') : $this->item->language; ?>">

	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>

	<?php if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative) {
		echo $this->item->pagination;
	}	?>

	<?php // Todo Not that elegant would be nice to group the params ?>
	<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam); ?>

	<?php if (!$useDefList && $this->print) : ?>
		<div id="pop-print" class="btn hidden-print clearfix">
			<?php echo HTMLHelper::_('icon.print_screen', $this->item, $params); ?>
		</div>
	<?php endif; ?>

	<?php if ($params->get('show_title') || $params->get('show_author')) : ?>
	<div class="page-header">
		<?php if ($params->get('show_title')) : ?>
			<h2 itemprop="headline">
				<?php echo $this->escape($this->item->title); ?>
			</h2>
		<?php endif; ?>

		<?php // Content is generated by content plugin event "onContentAfterTitle" ?>
		<?php echo $this->item->event->afterDisplayTitle; ?>
	
		<?php if (J3J4::checkUnpublishedContent($this->item)) : ?>
			<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
		<?php endif; ?>

		<?php if (strtotime($this->item->publish_up) > strtotime(Factory::getDate())) : ?>
			<span class="badge badge-warning"><?php echo Text::_('JNOTPUBLISHEDYET'); ?></span>
		<?php endif; ?>

		<?php if ($this->item->publish_down && (strtotime($this->item->publish_down) < strtotime(Factory::getDate())) && $this->item->publish_down != Factory::getDbo()->getNullDate()) : ?>
			<span class="badge badge-warning"><?php echo Text::_('JEXPIRED'); ?></span>
		<?php endif; ?>

	</div>
	<?php endif; ?>

	<div class="article-aside">

	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
			<?php echo LayoutHelper::render('joomla.content.info_block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
		<?php endif; ?>

	<?php if (!$this->print) : ?>
			<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
				<?php echo LayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
			<?php endif; ?>
		<?php else : ?>
			<?php if ($useDefList) : ?>
				<div id="pop-print" class="btn hidden-print">
					<?php echo HTMLHelper::_('icon.print_screen', $this->item, $params); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

	</div>

	<div class="share-social">
		<div class="social-inner">
			<span class="label"><i class="fas fa-share-alt"></i><?php echo Text::_('TPL_SHARE');?></span>
			<div class="addthis_inline_share_toolbox"></div>
		</div>
	</div>

	<?php if ($info == 0 && $params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
		<?php $this->item->tagLayout = new FileLayout('joomla.content.tags'); ?>
		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
	<?php endif; ?>

	<?php // Content is generated by content plugin event "onContentBeforeDisplay" ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>

	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position)))
		|| (empty($urls->urls_position) && (!$params->get('urls_position')))) : ?>
		<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>

	<?php if ($params->get('access-view')) : ?>
		<div class="full-box">
			<?php echo LayoutHelper::render('joomla.content.full_image', $this->item); ?>
		</div>

		<?php if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative) :
			echo $this->item->pagination;
		endif; ?>

	<?php if (isset ($this->item->toc)) : echo $this->item->toc; endif; ?>

	<div itemprop="articleBody" class="article-body">
		<?php echo $this->item->text; ?>
	</div>

	<?php if ($info == 1 || $info == 2) : ?>
		<?php if ($useDefList) : ?>
			<?php echo LayoutHelper::render('joomla.content.info_block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
		<?php endif; ?>

		<?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
			<?php $this->item->tagLayout = new FileLayout('joomla.content.tags'); ?>
			<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php
		if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative) :
			echo $this->item->pagination;
		endif;
	?>

	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) : ?>
		<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>

	<?php // Optional teaser intro text for guests ?>
	<?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
		<?php echo LayoutHelper::render('joomla.content.intro_image', $this->item); ?>
		<?php echo HTMLHelper::_('content.prepare', $this->item->introtext); ?>
		<?php // Optional link to let them register to see the whole article. ?>
	
		<?php if ($params->get('show_readmore') && $this->item->fulltext != null) : ?>
		<?php $menu = Factory::getApplication()->getMenu(); ?>
		<?php $active = $menu->getActive(); ?>
		<?php $itemId = $active->id; ?>
		<?php $link = new Uri(Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false)); ?>
		<?php $link->setVar('return', base64_encode(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language))); ?>

		<p class="com-content-article__readmore readmore">
			<a href="<?php echo $link; ?>" class="register">
			<?php $attribs = json_decode($this->item->attribs); ?>
			<?php
			if ($attribs->alternative_readmore == null) :
				echo Text::_('COM_CONTENT_REGISTER_TO_READ_MORE');
			elseif ($readmore = $attribs->alternative_readmore) :
				echo $readmore;
				if ($params->get('show_readmore_title', 0) != 0) :
					echo HTMLHelper::_('string.truncate', $this->item->title, $params->get('readmore_limit'));
				endif;
			elseif ($params->get('show_readmore_title', 0) == 0) :
				echo Text::sprintf('COM_CONTENT_READ_MORE_TITLE');
			else :
				echo Text::_('COM_CONTENT_READ_MORE');
				echo HTMLHelper::_('string.truncate', $this->item->title, $params->get('readmore_limit'));
			endif; ?>
			</a>
		</p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
		if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative) :
			echo $this->item->pagination;
		endif;
	?>

	<?php // Content is generated by content plugin event "onContentAfterDisplay" ?>
	<?php echo $this->item->event->afterDisplayContent; ?>
</div>

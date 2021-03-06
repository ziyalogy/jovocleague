<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\AssociationHelper;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\CMS\Layout\LayoutHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_content.articles-list');

			// Custom field
			$extrafields = new JRegistry($item->attribs);
			$position_dct = $extrafields->get('position-doctor');

// Create some shortcuts.
$n          = count($this->items);
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$langFilter = false;

// Tags filtering based on language filter
if (($this->params->get('filter_field') === 'tag') && (Multilanguage::isEnabled()))
{
	$tagfilter = ComponentHelper::getParams('com_tags')->get('tag_list_language_filter');

	switch ($tagfilter)
	{
		case 'current_language':
			$langFilter = Factory::getApplication()->getLanguage()->getTag();
			break;

		case 'all':
			$langFilter = false;
			break;

		default:
			$langFilter = $tagfilter;
	}
}

// Check for at least one editable article
$isEditable = false;

if (!empty($this->items))
{
	foreach ($this->items as $article)
	{
		if ($article->params->get('access-edit'))
		{
			$isEditable = true;
			break;
		}
	}
}

$currentDate = Factory::getDate()->format('Y-m-d H:i:s');
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm"
	id="adminForm" class="com-content-category__articles">
	<?php if ($this->params->get('filter_field') !== 'hide') : ?>
	<div class="com-content__filter btn-group">
		<?php if ($this->params->get('filter_field') === 'tag') : ?>
		<span class="visually-hidden">
			<label class="filter-search-lbl" for="filter-search">
				<?php echo Text::_('JOPTION_SELECT_TAG'); ?>
			</label>
		</span>
		<select name="filter_tag" id="filter-search" class="form-select" onchange="document.adminForm.submit();">
			<option value=""><?php echo Text::_('JOPTION_SELECT_TAG'); ?></option>
			<?php echo HTMLHelper::_('select.options', HTMLHelper::_('tag.options', array('filter.published' => array(1), 'filter.language' => $langFilter), true), 'value', 'text', $this->state->get('filter.tag')); ?>
		</select>
		<?php elseif ($this->params->get('filter_field') === 'month') : ?>
		<span class="visually-hidden">
			<label class="filter-search-lbl" for="filter-search">
				<?php echo Text::_('JOPTION_SELECT_MONTH'); ?>
			</label>
		</span>
		<select name="filter-search" id="filter-search" class="form-select" onchange="document.adminForm.submit();">
			<option value=""><?php echo Text::_('JOPTION_SELECT_MONTH'); ?></option>
			<?php echo HTMLHelper::_('select.options', HTMLHelper::_('content.months', $this->state), 'value', 'text', $this->state->get('list.filter')); ?>
		</select>
		<?php else : ?>
		<label class="filter-search-lbl visually-hidden" for="filter-search">
			<?php echo Text::_('COM_CONTENT_' . $this->params->get('filter_field') . '_FILTER_LABEL'); ?>
		</label>
		<input type="text" name="filter-search" id="filter-search"
			value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox"
			onchange="document.adminForm.submit();"
			placeholder="<?php echo Text::_('COM_CONTENT_' . $this->params->get('filter_field') . '_FILTER_LABEL'); ?>">
		<?php endif; ?>

		<?php if ($this->params->get('filter_field') !== 'tag' && $this->params->get('filter_field') !== 'month') : ?>
		<button type="submit" name="filter_submit"
			class="btn btn-primary"><?php echo Text::_('JGLOBAL_FILTER_BUTTON'); ?></button>
		<?php endif; ?>
		<button type="reset" name="filter-clear-button"
			class="btn btn-secondary"><?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?></button>
	</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_pagination_limit')) : ?>
	<div class="com-content-category__pagination btn-group float-end">
		<label for="limit" class="visually-hidden">
			<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>
		</label>
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>
	<?php endif; ?>

	<?php if (empty($this->items)) : ?>
	<div class="alert alert-info">
		<span class="icon-info-circle" aria-hidden="true"></span><span
			class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
		<?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?>
	</div>
	<?php else : ?>
	<table class="com-content-category__table category table table-striped table-hover">
		<caption class="visually-hidden">
			<?php echo Text::_('COM_CONTENT_ARTICLES_TABLE_CAPTION'); ?>
		</caption>
		<?php if ($this->params->get('show_headings')) : ?>
		<thead>
			<tr>

				<th scope="col" id="categorylist_header_title">
					<?php echo HTMLHelper::_('grid.sort', 'PLAYER_NAME', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?>
				</th>

				<th scope="col" id="categorylist_header_author">
					<?php echo HTMLHelper::_('grid.sort', 'PLAYER_TEAM', 'author', $listDirn, $listOrder); ?>
				</th>

				<th scope="col" id="categorylist_header_author">
					<?php echo HTMLHelper::_('grid.sort', 'PLAYS_POSITION', 'author', $listDirn, $listOrder); ?>
				</th>


			</tr>
		</thead>
		<?php endif; ?>
		<tbody>
			<?php foreach ($this->items as $i => $article) : ?>
			<?php if ($this->items[$i]->state == ContentComponent::CONDITION_UNPUBLISHED) : ?>
			<tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
				<?php else : ?>
			<tr class="cat-list-row<?php echo $i % 2; ?>">
				<?php endif; ?>
				<th class="list-title" scope="row">
					<?php if (in_array($article->access, $this->user->getAuthorisedViewLevels())) : ?>
					<a title="Click to see <?php echo $this->escape($article->title); ?>'s profile"
						href="<?php echo Route::_(RouteHelper::getArticleRoute($article->slug, $article->catid, $article->language)); ?>">
						<?php echo $this->escape($article->title); ?>
					</a>
					<?php if (Associations::isEnabled() && $this->params->get('show_associations')) : ?>
					<div class="cat-list-association">
						<?php $associations = AssociationHelper::displayAssociations($article->id); ?>
						<?php foreach ($associations as $association) : ?>
						<?php if ($this->params->get('flags', 1) && $association['language']->image) : ?>
						<?php $flag = HTMLHelper::_('image', 'mod_languages/' . $association['language']->image . '.gif', $association['language']->title_native, array('title' => $association['language']->title_native), true); ?>
						<a href="<?php echo Route::_($association['item']); ?>"><?php echo $flag; ?></a>
						<?php else : ?>
						<?php $class = 'btn btn-secondary btn-sm btn-' . strtolower($association['language']->lang_code); ?>
						<a class="<?php echo $class; ?>" title="<?php echo $association['language']->title_native; ?>"
							href="<?php echo Route::_($association['item']); ?>"><?php echo $association['language']->lang_code; ?>
							<span class="visually-hidden"><?php echo $association['language']->title_native; ?></span>
						</a>
						<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					<?php else : ?>
					<?php
						echo $this->escape($article->title) . ' : ';
						$itemId = Factory::getApplication()->getMenu()->getActive()->id;
						$link   = new Uri(Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));
						$link->setVar('return', base64_encode(RouteHelper::getArticleRoute($article->slug, $article->catid, $article->language)));
						?>
					<a href="<?php echo $link; ?>" class="register">
						<?php echo Text::_('COM_CONTENT_REGISTER_TO_READ_MORE'); ?>
					</a>
					<?php if (Associations::isEnabled() && $this->params->get('show_associations')) : ?>
					<div class="cat-list-association">
						<?php $associations = AssociationHelper::displayAssociations($article->id); ?>
						<?php foreach ($associations as $association) : ?>
						<?php if ($this->params->get('flags', 1)) : ?>
						<?php $flag = HTMLHelper::_('image', 'mod_languages/' . $association['language']->image . '.gif', $association['language']->title_native, array('title' => $association['language']->title_native), true); ?>
						<a href="<?php echo Route::_($association['item']); ?>"><?php echo $flag; ?></a>
						<?php else : ?>
						<?php $class = 'btn btn-secondary btn-sm btn-' . strtolower($association['language']->lang_code); ?>
						<a class="<?php echo $class; ?>" title="<?php echo $association['language']->title_native; ?>"
							href="<?php echo Route::_($association['item']); ?>"><?php echo $association['language']->lang_code; ?>
							<span class="visually-hidden"><?php echo $association['language']->title_native; ?></span>
						</a>
						<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					<?php endif; ?>
					<?php if ($article->state == ContentComponent::CONDITION_UNPUBLISHED) : ?>
					<div>
						<span class="list-published badge bg-warning text-light">
							<?php echo Text::_('JUNPUBLISHED'); ?>
						</span>
					</div>
					<?php endif; ?>
					<?php if ($article->publish_up > $currentDate) : ?>
					<div>
						<span class="list-published badge bg-warning text-light">
							<?php echo Text::_('JNOTPUBLISHEDYET'); ?>
						</span>
					</div>
					<?php endif; ?>
					<?php if (!is_null($article->publish_down) && $article->publish_down < $currentDate) : ?>
					<div>
						<span class="list-published badge bg-warning text-light">
							<?php echo Text::_('JEXPIRED'); ?>
						</span>
					</div>
					<?php endif; ?>
				</th>

				<td class="list-author">

					<span class="leader_position">
						<?php echo $article->jcfields['6']->value; ?>
					</span>
				</td>
				<td class="list-author">
					<div class="leader_position">
						<?php echo $article->jcfields['1']->value; ?>
					</div>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>

	<?php // Code to add a link to submit an article. ?>
	<?php if ($this->category->getParams()->get('access-create')) : ?>
	<?php echo HTMLHelper::_('contenticon.create', $this->category, $this->category->params); ?>
	<?php endif; ?>

	<?php // Add pagination links ?>
	<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
	<div class="com-content-category__navigation w-100">
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="com-content-category__counter counter float-end pt-3 pe-2">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
		<?php endif; ?>
		<div class="com-content-category__pagination">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	</div>
	<?php endif; ?>
	<?php endif; ?>
	<div>
		<input type="hidden" name="filter_order" value="">
		<input type="hidden" name="filter_order_Dir" value="">
		<input type="hidden" name="limitstart" value="">
		<input type="hidden" name="task" value="">
	</div>
</form>
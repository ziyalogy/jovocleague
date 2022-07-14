<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use T4\Helper\J3J4;

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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
if(version_compare(JVERSION, '4','lt')){
JHtml::_('behavior.framework');
}

require_once(__DIR__.'/../../../helper.php');

// Create a shortcut for params.
$params = $this->item->params;
$images  = json_decode($this->item->images);

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_content.articles-list');

			// Custom field
			$extrafields = new JRegistry($item->attribs);
			//$team_position = $extrafields->get('team-position');
			$team_position = empty($customs['team-position']) ? '' : implode(",", $customs['team-position']);
			$team_logo = $extrafields->get('team-logo');
			$team_class = $extrafields->get('team');
			$games_played = $extrafields->get('games-played');
			$games_won = $extrafields->get('games-won');
			$games_drawn = $extrafields->get('games-drawn');
			$goals_for_team = $extrafields->get('goals-for-team');
			$goals_against_team = $extrafields->get('goals-against-team');
			$goal_difference = $extrafields->get('goal-difference');
			$games_lost = $extrafields->get('games-lost');
			$number_of_points = $extrafields->get('number-of-points');

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

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="com-content-category__articles">


	<?php if (empty($this->items)) : ?>
		<div class="alert alert-info">
			<span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
				<?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?>
		</div>
	<?php else : ?>
	<div class="table-responsive">
		<table class="com-content-category__table category table table-striped table-hover table-borderless border-primary">
			<caption class="visually-hidden">
				<?php echo Text::_('COM_CONTENT_ARTICLES_TABLE_CAPTION'); ?>
			</caption>
			<?php if ($this->params->get('show_headings')) : ?>
				<thead>
					<tr>
						<th scope="col" id="position"><?php echo HTMLHelper::_('grid.sort', '#', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?></th>
						<th scope="col" id="team"><?php echo HTMLHelper::_('grid.sort', 'Team', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?></th>
						<th scope="col" id="games_played"><?php echo HTMLHelper::_('grid.sort', 'Played', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?></th>
						<th scope="col" id="games-won"><?php echo HTMLHelper::_('grid.sort', 'Won', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?></th>
						<th scope="col" id="games_drawn"><?php echo HTMLHelper::_('grid.sort', 'Drawn', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?></th>
						<th scope="col" id="games_lost"><?php echo HTMLHelper::_('grid.sort', 'Lost', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?></th>
						<th scope="col" id="games_for"><?php echo HTMLHelper::_('grid.sort', 'GF', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?></th>
						<th scope="col" id="games_against"><?php echo HTMLHelper::_('grid.sort', 'GA', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?></th>
						<th scope="col" id="game_diff"><?php echo HTMLHelper::_('grid.sort', 'GD', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?></th>
						<th scope="col" id="total_points"><?php echo HTMLHelper::_('grid.sort', 'Points', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?></th>						
					</tr>
				</thead>
			<?php endif; ?>
			<tbody>
				
			<?php foreach ($this->items as $i => $article) : ?>
				<?php if ($this->items[$i]->state == ContentComponent::CONDITION_UNPUBLISHED) : ?>
					<tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
				<?php else : ?>
				<tr class="cat-list-row<?php echo $i % 2; ?>" >
					<?php endif; ?>
					<th class="list-title" scope="row">
					        <?php echo $article->jcfields['22']->value; ?>
				</th>
				
								
				
				<td>
					
				<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>

				<a href="<?php echo Route::_(RouteHelper::getArticleRoute($article->slug, $article->catid, $article->language)); ?>" title="<?php echo $this->escape($article->title); ?>" class="standings_team_logo"><?php echo $article->jcfields['23']->value; ?><span class="image-title"></span>
				<?php if (in_array($article->access, $this->user->getAuthorisedViewLevels())) : ?>
						<a href="<?php echo Route::_(RouteHelper::getArticleRoute($article->slug, $article->catid, $article->language)); ?>">
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
									<a class="<?php echo $class; ?>" title="<?php echo $association['language']->title_native; ?>" href="<?php echo Route::_($association['item']); ?>"><?php echo $association['language']->lang_code; ?>
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
									<a class="<?php echo $class; ?>" title="<?php echo $association['language']->title_native; ?>" href="<?php echo Route::_($association['item']); ?>"><?php echo $association['language']->lang_code; ?>
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
					<?php endif; ?></td>				
				<td><?php echo $article->jcfields['24']->value; ?></td>
				<td><?php echo $article->jcfields['25']->value; ?></td>
				<td><?php echo $article->jcfields['26']->value; ?></td>
				<td><?php echo $article->jcfields['27']->value; ?></td>
				<td><?php echo $article->jcfields['28']->value; ?></td>
				<td><?php echo $article->jcfields['29']->value; ?></td>
				<td><?php echo $article->jcfields['30']->value; ?></td>
				<td><?php echo $article->jcfields['31']->value; ?></td>
				
						<?php  $introImage = json_decode($item->images)->image_intro;?>
					<a href="<?php echo $item->link; ?>"><img src="<?php echo $introImage ;?>" alt="<?php echo $item->title; ?>" /></a>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		</div>
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

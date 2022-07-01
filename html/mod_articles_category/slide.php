<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;
	$modShow = $params->get('show-link');
	$modCat = $params->get('title-category');
	$modMenu = $params->get('link-category');
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
?>
<div id="mod-articles-<?php echo $module->id; ?>" class="owl-article-list <?php echo $moduleclass_sfx; ?>" >


	<div class="owl-carousel">
		<?php if ($grouped) : ?>
			<div class="alert alert-warning">
				<?php echo text::_('TPL_NO_SUPPORT') ;?>
			</div>
		<?php else : ?>
			<?php foreach ($list as $item) : ?>
					<div class="item-inner">
						<?php
							// Intro Image
							$introImage = json_decode($item->images)->image_intro;
						?>
						<!-- Intro Image -->
						<div class="intro-image">
							<?php if($introImage) : ?>
								<img src="<?php echo $introImage ;?>" alt="Intro Image" />
							<?php else : ?>
								<img src="images/joomlart/default.jpg" alt="No Image" />
							<?php endif ;?>
						</div>

						<div class="article-content">
							<!-- Title -->
							<div class="title">
								<?php if ($params->get('link_titles') == 1) : ?>
									<h5>
										<a class="mod-articles-category-title <?php echo $item->active; ?> link-dark" href="<?php echo $item->link; ?>">
											<?php echo $item->title; ?>
										</a>
									</h5>
								<?php else : ?>
									<h5>
										<?php echo $item->title; ?>
									</h5>
								<?php endif; ?>
							</div>

							<div class="article-aside">
								<?php if($item->displayCategoryTitle || $item->displayDate) :?>
								<div class="article-info">
									<span class="article-info-term d-none">Details</span>
									<?php if ($item->displayCategoryTitle) : ?>
										<span class="category">
											<?php echo $item->displayCategoryTitle; ?>
										</span>
									<?php endif; ?>

									<?php if ($item->displayDate) : ?>
										<span class="articles-date">
											<?php echo $item->displayDate; ?>
										</span>
									<?php endif; ?>

									<?php if ($params->get('show_author')) : ?>
										<span class="articles-writtenby">
											<?php echo '<span>'.$item->displayAuthorName.'</span>'; ?>
										</span>
									<?php endif; ?>

									<?php if ($item->displayHits) : ?>
										<span class="articles-hits">
											<i class="far fa-eye"></i> <?php echo $item->displayHits; ?>
										</span>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</div>

							<?php if ($params->get('show_introtext')) : ?>
								<div class="articles-introtext">
									<?php echo $item->displayIntrotext; ?>
								</div>
							<?php endif; ?>

							<?php if ($params->get('show_tags', 0) && $item->tags->itemTags) : ?>
								<div class="mod-articles-category-tags">
									<?php echo JLayoutHelper::render('joomla.content.tags', $item->tags->itemTags); ?>
								</div>
							<?php endif; ?>

							<?php if ($params->get('show_readmore')) : ?>
								<p class="articles-readmore">
									<a class="articles-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
										<?php if ($item->params->get('access-view') == false) : ?>
											<?php echo Text::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?>
										<?php elseif ($readmore = $item->alternative_readmore) : ?>
											<?php echo $readmore; ?>
											<?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
										<?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
											<span class="d-none"><?php echo Text::_('TPL_READ_MORE'); ?></span>
											<i class="fas fa-arrow-right"></i>
										<?php else : ?>
											<?php echo Text::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
											<?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
										<?php endif; ?>
									</a>
								</p>
							<?php endif; ?>
						</div>
					</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<?php if($modShow && $modCat) :?>
		<div class="category-action text-center">
			<a class="btn btn-primary" href="<?php  echo JRoute::_("index.php?Itemid={$modMenu}"); ?>" title="<?php echo $modCat ;?>">
					<?php echo $modCat ;?>
			</a>
		</div>
	<?php endif ;?>
</div>

<script>
(function($){
  jQuery(document).ready(function($) {
    $("#mod-articles-<?php echo $module->id; ?> .owl-carousel").owlCarousel({
      addClassActive: true,
      items: 4,
      singleItem : true,
      itemsScaleUp : true,
      nav : false,
      navText : ["<i class='fas fa-arrow-left'></i>", "<i class='fas fa-arrow-right'></i>"],
      dots: true,
      merge: false,
      mergeFit: true,
      margin: 32,
      dotsEach: true,
      autoHeight: false,
      animateOut: 'fadeOut',
      autoplaySpeed: 1200,
      smartSpeed: 1400,
      loop: false,
      autoplay: false,
      responsive : {
				0 : {
			    items: 1,
			    autoHeight: true
				},
				767 : {
			    items: 2,
			    autoHeight: true
				},
				1200 : {
			    items: 4,
			    autoHeight: false
				}
			}
    });
  });
})(jQuery);
</script>
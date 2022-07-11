<?php
/**
T4 Overide
 */

defined("_JEXEC") or die();

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
if (!class_exists("ContentHelperRoute")) {
    if (version_compare(JVERSION, "4", "ge")) {
        abstract class ContentHelperRoute extends
            \Joomla\Component\content\Site\Helper\RouteHelper
        {
        }
    } else {
        JLoader::register(
            "ContentHelperRoute",
            $com_path . "/helpers/route.php"
        );
    }
}

JHtml::addIncludePath(JPATH_COMPONENT . "/helpers");
require_once __DIR__ . "/../../../helper.php";

// Create shortcuts to some parameters.
$params = $this->item->params;
$images = json_decode($this->item->images);
$urls = json_decode($this->item->urls);
$canEdit = $params->get("access-edit");
$user = Factory::getUser();
$info = $params->get("info_block_position", 0);
$og_iamge = $images->image_intro ?: $images->image_fulltext;
// Check if associations are implemented. If they are, define the parameter.
$assocParam = Associations::isEnabled() && $params->get("show_associations");

if ($params->get("show_intro")) {
    $separator = md5(time());
    $this->item->text =
        $this->item->introtext . $separator . $this->item->fulltext;
    $offset = $this->state->get("list.offset");
    $app = JFactory::getApplication();
    $app->triggerEvent("onContentPrepare", [
        "com_content.article",
        &$this->item,
        &$this->item->params,
        $offset,
    ]);
    $app->triggerEvent("onContentAfterTitle", [
        "com_content.article",
        &$this->item,
        &$this->item->params,
        $offset,
    ]);
    $app->triggerEvent("onContentBeforeDisplay", [
        "com_content.article",
        &$this->item,
        &$this->item->params,
        $offset,
    ]);
    $app->triggerEvent("onContentAfterDisplay", [
        "com_content.article",
        &$this->item,
        &$this->item->params,
        $offset,
    ]);
    $explode = explode($separator, $this->item->text);
    $this->item->introtext = array_shift($explode);
    $this->item->text = implode("", $explode);
}

// Get Extrafields
$customs = JATemplateHelper::getCustomFields($this->item->id, "article");
$extrafields = new JRegistry($this->item->attribs);

$ltGallery = $extrafields->get("listing-gallery");
$playerGoals = empty($customs["goals"])
    ? ""
    : implode(",", $customs["goals"]);

$playerCurrentTeam = empty($customs["current-team"])
    ? ""
    : implode(",", $customs["current-team"]);

	$playerShirtNumber = empty($customs["player-number"])
    ? ""
    : implode(",", $customs["player-number"]);

$playerAppearances = empty($customs["appearances"])
    ? ""
    : explode("-", implode(", ", $customs["appearances"]));

$playerAppeared = empty($playerAppearances) ? "" : trim($playerAppearances[0]);

$ltClose = empty($playerAppearances) ? "" : trim($playerAppearances[1]);
$ltStatus = JATemplateHelper::OpenClosedTime($playerAppeared, $ltClose);
$playerAssists = $extrafields->get("assists");
$ltMail = $extrafields->get("mail");
$ltWebsite = $extrafields->get("website");
$ltContact = $extrafields->get("link-contact");
//$playerTeam = $extrafields->get("current-team");

$ltSocial = $extrafields->get("listing-social");
$ltMenudesc = $extrafields->get("menu-desc");
$ltMenu = $extrafields->get("listing-menu");

$ltFaqTitle = $extrafields->get("faq-title");
$leagueTable = $extrafields->get("league-table");

$app = JFactory::getApplication();
$path = JURI::base(true) . "/templates/" . $app->getTemplate() . "/";

// Rating
if (isset($this->item->rating_sum) && $this->item->rating_count > 0) {
    $this->item->rating = round(
        $this->item->rating_sum / $this->item->rating_count,
        1
    );
    $this->item->rating_percentage =
        ($this->item->rating_sum / $this->item->rating_count) * 20;
} else {
    if (!isset($this->item->rating)) {
        $this->item->rating = 0;
    }
    if (!isset($this->item->rating_count)) {
        $this->item->rating_count = 0;
    }
    $this->item->rating_percentage = $this->item->rating * 20;
}
$uri = JUri::getInstance();
?>
<?php //echo JHtml::_("content.prepare", "{loadposition breadcrumbs}"); ?>
<div class="player">
<div class="jv_breadcrumbs">
        <div class="container">
                        <?php echo JHtml::_(
                'content.prepare',
                '{loadposition breadcrumbs}'
            ); ?>
            </div>
</div>

					<div class="player view-listing-detail style-2 item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Article">
	<meta itemprop="inLanguage" content="<?php echo $this->item->language === "*"
     ? Factory::getApplication()->get("language")
     : $this->item->language; ?>">

	

	<div class="container">
		<div class="listing-info-detail-wrap">
			<div class="row">
				

				<div class="col-lg-12 order-lg-2">
					

					<?php if ($this->params->get("show_page_heading")): ?>
					<div class="page-header">
						<h1> <?php echo $this->escape($this->params->get("page_heading")); ?> </h1>
					</div>
					<?php endif; ?>

					<?php if (
         !empty($this->item->pagination) &&
         $this->item->pagination &&
         !$this->item->paginationposition &&
         $this->item->paginationrelative
     ) {
         echo $this->item->pagination;
     } ?>

					<?php // Todo Not that elegant would be nice to group the params ?>
					<?php $useDefList =
         $params->get("show_modify_date") ||
         $params->get("show_publish_date") ||
         $params->get("show_create_date") ||
         $params->get("show_hits") ||
         $params->get("show_category") ||
         $params->get("show_parent_category") ||
         $params->get("show_author") ||
         $assocParam; ?>

					<?php if (!$useDefList && $this->print): ?>
						<div id="pop-print" class="btn hidden-print clearfix">
							<?php echo HTMLHelper::_("icon.print_screen", $this->item, $params); ?>
						</div>
					<?php endif; ?>

					<!--PLAYER INFO BOX-->
					

					<!--PLAYER INFO BOX-->

					<div class="listing-detail">
						<!--<h4><?php echo Text::_("TPL_OVERVIEW"); ?></h4>-->
						<?php
// Content is generated by content plugin event "onContentBeforeDisplay"
?>
						<?php echo $this->item->event->beforeDisplayContent; ?>

						<?php if (
          (isset($urls) &&
              ((!empty($urls->urls_position) && $urls->urls_position == "0") ||
                  ($params->get("urls_position") == "0" &&
                      empty($urls->urls_position)))) ||
          (empty($urls->urls_position) && !$params->get("urls_position"))
      ): ?>
							<?php echo $this->loadTemplate("links"); ?>
						<?php endif; ?>

						<?php if ($params->get("access-view")): ?>
							<?php
          // echo LayoutHelper::render('joomla.content.full_image', $this->item);
          ?>

							<?php if (
           !empty($this->item->pagination) &&
           $this->item->pagination &&
           !$this->item->paginationposition &&
           !$this->item->paginationrelative
       ):
           echo $this->item->pagination;
       endif; ?>

						<?php if (isset($this->item->toc)):
          echo $this->item->toc;
      endif; ?>

						<div itemprop="articleBody" class="article-body">
							<?php //echo $this->item->text; ?>
						</div>

						<?php if ($info == 1 || $info == 2): ?>
							<?php if ($useDefList): ?>
								<?php echo LayoutHelper::render("joomla.content.info_block", [
            "item" => $this->item,
            "params" => $params,
            "position" => "below",
        ]); ?>
							<?php endif; ?>

							<?php if (
           $params->get("show_tags", 1) &&
           !empty($this->item->tags->itemTags)
       ): ?>
								<?php $this->item->tagLayout = new FileLayout("joomla.content.tags"); ?>
								<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
							<?php endif; ?>
						<?php endif; ?>

						

						<?php if (
          isset($urls) &&
          ((!empty($urls->urls_position) && $urls->urls_position == "1") ||
              $params->get("urls_position") == "1")
      ): ?>
							<?php echo $this->loadTemplate("links"); ?>
						<?php endif; ?>

						<?php
          // Optional teaser intro text for guests
          ?>
		  
         <?php //Optional link to let them register to see the whole article.
          // Optional link to let them register to see the whole article.?>
		  
						<?php //else if ( $params->get('show_noauth') == true && $user->get('guest')): ?>
							
							<?php echo HTMLHelper::_("content.prepare", $this->item->introtext); ?>
							<?php
          // Optional link to let them register to see the whole article.
          ?>
						
							<?php if ($params->get("show_readmore") && $this->item->fulltext != null): ?>
							<?php $menu = Factory::getApplication()->getMenu(); ?>
							<?php $active = $menu->getActive(); ?>
							<?php $itemId = $active->id; ?>
							<?php $link = new Uri(
           Route::_(
               "index.php?option=com_users&view=login&Itemid=" . $itemId,
               false
           )
       ); ?>
							<?php $link->setVar(
           "return",
           base64_encode(
               ContentHelperRoute::getArticleRoute(
                   $this->item->slug,
                   $this->item->catid,
                   $this->item->language
               )
           )
       ); ?>

							<p class="com-content-article__readmore readmore">
								<a href="<?php echo $link; ?>" class="register">
								<?php $attribs = json_decode($this->item->attribs); ?>
								<?php if ($attribs->alternative_readmore == null):
            echo Text::_("COM_CONTENT_REGISTER_TO_READ_MORE");
        elseif ($readmore = $attribs->alternative_readmore):
            echo $readmore;
            if ($params->get("show_readmore_title", 0) != 0):
                echo HTMLHelper::_(
                    "string.truncate",
                    $this->item->title,
                    $params->get("readmore_limit")
                );
            endif;
        elseif ($params->get("show_readmore_title", 0) == 0):
            echo Text::sprintf("COM_CONTENT_READ_MORE_TITLE");
        else:
            echo Text::_("COM_CONTENT_READ_MORE");
            echo HTMLHelper::_(
                "string.truncate",
                $this->item->title,
                $params->get("readmore_limit")
            );
        endif; ?>
								</a>
							</p>
							<?php endif; ?>
						<?php endif; ?>

						<?php if (
          !empty($this->item->pagination) &&
          $this->item->pagination &&
          $this->item->paginationposition &&
          $this->item->paginationrelative
      ):
          echo $this->item->pagination;
      endif; ?>
					</div>

					<div class="table-responsive">
  <table width="100%" class="table table-striped table-hover table-borderless border-primary ">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Team</th>
        <th scope="col">Played</th>
        <th scope="col">Won</th>
        <th scope="col">Drawn</th>
        <th scope="col">Lost</th>
        <th scope="col">GF</th>
        <th scope="col">GA</th>
        <th scope="col">GD</th>
        <th scope="col">Points</th>
        <th scope="col">Next</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>Ababombo</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
      </tr>

    </tbody>

    <caption>
      <div class="badge rounded-pill bg-info text-light">Stats are updated every game week.</div>
    </caption>

  </table>
</div>



					<?php if (!empty($leagueTable)): ?>
					<!-- FAQ -->
					<div class="section-listing-info league-table">
						<?php if ($ltFaqTitle): ?>
							<?php echo $ltFaqTitle; ?>
						<?php endif; ?>

						<div class="accordion" id="accordionFAQ">
							<?php
       $i = 0;
       foreach ($leagueTable as $key => $value):
           $i++; ?>
						  <div class="accordion-item">
						    <h2 class="accordion-header" id="heading-<?php echo $i; ?>">
						      <button class="accordion-button <?php if ($i != 1) {
                echo "collapsed";
            } ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $i; ?>">
						        <?php echo $value->faq_title; ?>
						      </button>
						    </h2>
						    <div id="collapse-<?php echo $i; ?>" class="accordion-collapse collapse <?php if (
    $i == 1
) {
    echo "show";
} ?>" aria-labelledby="heading-<?php echo $i; ?>" data-bs-parent="#accordionFAQ">
						      <div class="accordion-body">
						       <?php echo $value->faq_content; ?>
						      </div>
						    </div>
						  </div>
						  <?php
       endforeach;
       ?>
						</div>
					</div>
					<!-- // FAQ -->
					<?php endif; ?>

					<?php if (
         $info == 0 &&
         $params->get("show_tags", 1) &&
         !empty($this->item->tags->itemTags)
     ): ?>
						<div class="section-listing-info listing-tags">
							<h3><?php echo Text::_("TPL_TAGS"); ?></h3>
							<?php $this->item->tagLayout = new FileLayout("joomla.content.tags"); ?>
							<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
						</div>
					<?php endif; ?>

					<?php
// Content is generated by content plugin event "onContentAfterDisplay"
?>
						<?php echo $this->item->event->afterDisplayContent; ?>

					<?php if (
         !empty($this->item->pagination) &&
         $this->item->pagination &&
         $this->item->paginationposition &&
         !$this->item->paginationrelative
     ):
         echo $this->item->pagination;
     endif; ?>
	 
	
	 
	 
				</div>
	
				
				
				
			</div>	
		</div>
	</div>
	
	
</div>
					

</div>

<script>
	(function($){
		$(document).ready(function(){
			// Remove container
	  	$('.t4-wrapper').addClass('view-listing-detail-2');
	  	$('#t4-main-body > .t4-section-inner').removeClass('container');
	  	
			<?php if (!empty($ltGallery)): ?>
					$(".listing-gallery .owl-carousel").owlCarousel({
		      addClassActive: true,
		      items: 3,
		      singleItem:true,
		      nav : true,
		      navText : ["<i class='fas fa-arrow-left'></i>", "<i class='fas fa-arrow-right'></i>"],
		      dots: true,
		      margin: 24,
		      dotsEach: true,
		      animateOut: 'fadeOut',
		      autoplaySpeed: 1200,
		      smartSpeed: 1400,
		      loop: true,
		      autoWidth: false,
		      autoplay: true,
		      responsive : {
						0 : {
					    items: 2,
					    margin: 0
						},
						991 : {
					    items: 3
						},
						1200 : {
					    items: 3
						}
					}
		    });

				$(".listing-gallery .html5lightbox").html5lightbox({
					autoslide: true,
					showplaybutton: false,
					jsfolder: "<?php echo $path . "js/html5lightbox/"; ?>"
				});

			<?php endif; ?>
		});
	})(jQuery);
</script>

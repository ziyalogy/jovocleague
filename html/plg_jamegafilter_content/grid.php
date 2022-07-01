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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$input = JFactory::getApplication()->input;
$direction = !empty($this->config->Moduledirection)
    ? $this->config->Moduledirection
    : $this->config->direction;
if ($direction == 'vertical') {
    $direction = '';
}
$supportClass = [];
if (
    (!empty($this->config->isComponent) && empty($this->config->isModule)) ||
    (empty($this->config->isComponent) && !empty($this->config->isModule))
) {
    $user = JFactory::getUser();
    $userID = $user->id;
    $groups = $user->getAuthorisedGroups();
}
// support joomla custom field render-label class
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('f.id, f.params')->from('#__fields as f');
$db->setQuery($query);
$fields = $db->loadObjectList();
foreach ($fields as $f) {
    $supportClass['ct' . $f->id] = [];
    // echo '<pre>: '. print_r( $fields, true ) .'</pre>';die;
    $p = json_decode($f->params);
    $supportClass['ct' . $f->id]['params'] = [
        'render_class' => $p->render_class,
        'label_render_class' => $p->label_render_class,
    ];
}
?>

<?php if (
    !empty($this->config->isComponent) &&
    empty($this->config->isModule)
): ?>
<?php
$hasModule = JaMegafilterHelper::hasMegafilterModule();
if ($hasModule) {
    $this->config->sticky = 0;
}
?>
<?php if (
    isset($this->item['mparams']) &&
    $this->item['mparams']->get('show_page_heading')
): ?>
<div class="page-header">
	<h1> <?php echo $this->item['mparams']->get('page_heading'); ?> </h1>
</div>
<?php endif; ?>
<div class="jarow <?php echo $this->item[
    'type'
]; ?> <?php echo $direction; ?> ja-megafilter-wrap clearfix">
	<?php if (!empty($this->config->fullpage) && !$hasModule): ?>
		<div data-mgfilter="content" class="<?php echo $direction; ?> ja-mg-sidebar sidebar-main">
			<a href="javascript:void(0)" class="sidebar-toggle">
				<span class="filter-open">
					<i class="fa fa-filter"></i><?php echo JText::_(
         'COM_JAMEGAFILTER_OPEN_FILTER'
     ); ?>
				</span>
				<span class="filter-close">
					<i class="fa fa-close"></i><?php echo JText::_(
         'COM_JAMEGAFILTER_CLOSE_FILTER'
     ); ?>
				</span>
			</a>
			<div class="block ub-layered-navigation-sidebar sidebar-content"></div>
		</div>
	<?php endif; ?>
	<?php if ($hasModule || (empty($this->config->fullpage) && !$hasModule)) {
     $full_width = 'full-width';
 } else {
     $full_width = '';
 } ?>
	<div class="main-content <?php echo $full_width; ?>"></div>
</div>
<?php else: ?>
	<div data-mgfilter="content" class="<?php echo $direction; ?> ja-mg-sidebar sidebar-main">
		<div class="block ub-layered-navigation-sidebar sidebar-content"></div>
		<?php if (empty($this->config->isComponent)): ?>
			<a id="jamegafilter-search-btn" class="btn btn-default " href="javascript:void(0)"><?php echo JText::_(
       'COM_JAMEGAFILTER_SEARCH'
   ); ?></a>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if (
    (!empty($this->config->isComponent) && empty($this->config->isModule)) ||
    (empty($this->config->isComponent) && !empty($this->config->isModule))
): ?>

<script type="text/javascript">

<?php if (!empty($this->config->url)): ?>
var filter_url = '<?php echo $this->config->url; ?>';
<?php endif; ?>

var JABaseUrl = '<?php echo JUri::base(true); ?>';
var ja_default_sort="<?php echo $this->config->default_sort; ?>";
var ja_sort_by="<?php echo $this->config->sort_by; ?>";
var ja_layout_addition="<?php echo $this->config->layout_addition; ?>";
var ja_layout_columns=<?php echo json_encode($this->config->jacolumn); ?>;
var ja_userGroup = <?php echo json_encode($groups); ?>;
var ja_customfield_class_render = <?php echo json_encode($supportClass); ?>;
var p = <?php echo json_encode($this->jstemplate); ?>;
var ja_supportFieldgetValue = ["listing_type","price_level"];
if(typeof dust != undefined){
	dust.helpers.info = function (chunk, context, bodies) {
		var item = context.current();
		var obj = item.attr;
		if (typeof ja_layout_addition === 'string') {
			$ja_layout_add = ja_layout_addition.split(',');
			for (var $i = 0; $i < $ja_layout_add.length; $i++) {
				// for the key "key" in ctx variable. include if you want 2 columns in layout.
				var $jakey = $ja_layout_add[$i].replace('attr.', '').replace('.value', '');
				var render_class = "";
				var label_render_class = "";

				// this line support for custom field class
				// must override then render in template first to use.
				if (typeof ja_customfield_class_render !== "undefined") {
					if (typeof ja_customfield_class_render[$jakey] !== "undefined") {
						if (typeof ja_customfield_class_render[$jakey]['params'] !== "undefined") {
							if (typeof ja_customfield_class_render[$jakey]['params']['render_class'] !== "undefined")
								render_class = ja_customfield_class_render[$jakey]['params']['render_class'];
							if (typeof ja_customfield_class_render[$jakey]['params']['label_render_class'] !== "undefined")
								label_render_class = ja_customfield_class_render[$jakey]['params']['label_render_class'];
						}
					}
				}
				// custom layout input for thumb|desc. put something to "value" if all layout template same markup html.
				if ($ja_layout_add[$i] == 'thumb') {
					var ctx = { "_class": $jakey, "value": item.desc == undefined ? '' : item.desc, "render_class": render_class, "label_render_class": label_render_class };
					if (typeof ja_layout_columns[$ja_layout_add[$i]] != 'undefined' && ja_layout_columns[$ja_layout_add[$i]] == 1)
						ctx.key = jamegafilter_thumb;
					chunk = chunk.render(bodies.block, context.push(ctx));
					continue;
				}
				if ($ja_layout_add[$i] == 'desc') {
					var ctx = { "_class": $jakey, "value": item.desc == undefined ? '' : item.desc, "render_class": render_class, "label_render_class": label_render_class };
					if (typeof ja_layout_columns[$ja_layout_add[$i]] != 'undefined' && ja_layout_columns[$ja_layout_add[$i]] == 1)
						ctx.key = jamegafilter_desc;
					chunk = chunk.render(bodies.block, context.push(ctx));
					continue;
				}
				if ($ja_layout_add[$i] == 'baseprice') {
					var ctx = { "_class": $jakey, "value": item.baseprice == undefined ? '' : item.baseprice, "render_class": render_class, "label_render_class": label_render_class };
					if (typeof ja_layout_columns[$ja_layout_add[$i]] != 'undefined' && ja_layout_columns[$ja_layout_add[$i]] == 1)
						ctx.key = jamegafilter_baseprice;
					chunk = chunk.render(bodies.block, context.push(ctx));
					continue;
				}

				for (var key in obj) {
					var value = obj[key];
					if (key !== $jakey) continue;
					if (typeof value.type === 'undefined') continue;
					var _value;
					var _val_class = "";
					var _val_business_time = "";
					// custom layout input for name. put something to "value" if all layout template same markup html.
					if ($ja_layout_add[$i] == 'name') {
						var ctx = { "_class": $jakey, "value": value.frontend_value, "render_class": render_class, "label_render_class": label_render_class };
						if (typeof ja_layout_columns[$ja_layout_add[$i]] != 'undefined' && ja_layout_columns[$ja_layout_add[$i]] == 1) ctx.key = value.title;
						chunk = chunk.render(bodies.block, context.push(ctx));
						continue;
					}
					if (dust.isArray(value.frontend_value)) {
						if (value.type == 'date') {
							_date = new Date(value.frontend_value[0] * 1000);
							_value = _date.getFullYear() + '-' + (_date.getMonth() + 1) + '-' + _date.getDate();
						} else if (value.type == 'color') {
							_value = [];
							for (var i = 0; i < value.frontend_value.length; i++) {
								var color = value.frontend_value[i].toLowerCase();
								if (JAnameColor.hasOwnProperty(color)) {
									color = JAnameColor[color];
								}
								var span = '<span class="color-item-bg" style="background-color: ' + color + '; width: 24px; height: 20px; display: inline-block; box-shadow: 0 0 5px rgba(0, 0, 0, 0.65)"></span></span>';
								_value.push(span);
							}
							_value = _value.join(' ');
						} else if (value.type == 'media') {
							_value = [];
							for (var i = 0; i < value.frontend_value.length; i++) {
								if (/^(http|https):\/\//.test(value.frontend_value[i])) {
									_value.push('<img src="'+ value.frontend_value[i] + '" />');
								} else {
									_value.push('<img src="' + JABaseUrl + '/' + value.frontend_value[i] + '" />');
								}
							}
							_value = _value.join(' ');
						} else {
							// make sure the replace do not replace other value.
							// only apply for category. we need to clear the category tree.
							_value = [];
							for (var i = 0; i < value.frontend_value.length; i++) {
									_value.push(value.frontend_value[i].replace(/(.*?)\&raquo\; /g, '').replace(/(.*?)» /g, ''));
								}
							_value = _value.join(', ');
							if (value['value'] && value['value'].length === 1) {
								_val_class = value['value'].join(" "); // support for something like badge icon.
							}
							if (key == 'ct1' || key == 'ct6'){
								_val_class = window.decodeURIComponent(_val_class);
							}else{
								_val_class = _val_class.replace(/[0-9]+/, "");
							}
						}
						
					} else {
						if (key == 'rating') {
							_value = [
								'<div class="rating-summary">',
                                '<div title= "'+value.rating+' out of 5" class="rating-result">',
								'<span style="width:' + value.frontend_value + '%"></span>',
								'</div>',
								'</div>'
							].join('');
						} else {
							_value = value.frontend_value;
						}
						if(key == 'ct14'){
							_val_business_time = false;
							var timeArr = value.value.split("-");
							var opening = timeArr[0].trim();
							var closing = timeArr[1].trim();
							if(dust.helpers.convertBusinessTime(opening, closing)){
								_val_business_time = true;
							}
						}
					}
					// include "key" if 2 columns.
					var ctx = {
						"_class": $jakey,
						"value": _value,
						"val_class": _val_class,
						"val_business_time": _val_business_time,
						"render_class": render_class,
						"label_render_class": label_render_class
					};

					if (typeof ja_layout_columns[$ja_layout_add[$i]] !== 'undefined' && ja_layout_columns[$ja_layout_add[$i]] == 1) {
						ctx.key = value.title;
					}
					chunk = chunk.render(bodies.block, context.push(ctx));
				}
			}
		} else {

		}

		return chunk;
	};
	dust.helpers.convertBusinessTime = function(opening, closing){
		var checked = false;
		var currentTime = new Date().toLocaleTimeString('en-GB', { hour12:true,hour: "numeric", minute: "numeric"});
		var currentHours = new Date().getHours();
		var currentHoursInt = parseInt(currentHours);
		var currentMinus = new Date().getMinutes();
		var currentMinusInt = parseInt(currentMinus);
		var openAMPM = (opening.indexOf("AM") > -1) ? true : false;
		var closeAMPM = (closing.indexOf("PM") > -1) ? true : false;
		var openingHours = opening.split(":")[0].trim();
		var openingHoursInt = openAMPM ? parseInt(openingHours) : parseInt(openingHours)+12;
		var openingMinus = opening.split(":")[1].replace("AM","").replace("PM","").trim();
		var openingMinusInt = parseInt(openingMinus);
		var closingHours = closing.split(":")[0].trim();
		var closingHoursInt = closeAMPM ? parseInt(closingHours)+12 : parseInt(closingHours);
		var closingMinus = closing.split(":")[1].replace("AM","").replace("PM","").trim();
		var closingMinusInt = parseInt(closingMinus);
		if(openAMPM && closeAMPM){
			if(openingHoursInt < currentHoursInt < closingHoursInt){
				checked = true;
			}else if((openingHoursInt == currentHoursInt && currentMinusInt >= openingMinusInt) || ((closingHoursInt+12) == currentHoursInt && closingMinusInt >= currentMinusInt)){
				checked = true;
			}else{
				checked = false;
			}
		}else{
			if((openingHoursInt < closingHoursInt) && (openingHoursInt < currentHoursInt &&  currentHoursInt < closingHoursInt)){
				checked = true;
			}else if((openingHoursInt == currentHoursInt && currentMinusInt >= openingMinusInt) || (closingHoursInt == currentHoursInt && closingMinusInt >= currentMinusInt)){
				checked = true;
			}else if ((openingHoursInt > closingHoursInt) && (currentHoursInt > openingHoursInt || currentHoursInt < closingHoursInt)){
				checked = true;
			}else{
				checked = false;
			}
		}

		return checked;
	}
}
for (var key in p) {
  if (p.hasOwnProperty(key)) {
    var compiled = dust.compile(p[key], key);
    dust.loadSource(compiled);
  }
}

function bindCallback() {
	setTimeout(function(){
		if (jQuery('.jamegafilter-wrapper').find('.pagination-wrap').length) {
			jQuery('.jamegafilter-wrapper').removeClass('no-pagination');
		} else {
			jQuery('.jamegafilter-wrapper').addClass('no-pagination');
		}

		if (isMobile.apple.tablet && jQuery('#t3-off-canvas-sidebar').length) {
			jQuery('select').unbind().off().on('touchstart', function() {
    			formTouch=true;
    			fixedElement.css('position', 'absolute');
    			fixedElement.css('top', jQuery(document).scrollTop());
			});
			jQuery('html').unbind().off().on('touchmove', function() {
				if (formTouch==true) {
					fixedElement.css('position', 'fixed');
					fixedElement.css('top', '0');
					formTouch=false;
				}
			});
		}
		initScript();
	  }, 100);
	if (jQuery('.items.product-items').find('.item').length == 0) {
		jQuery('.toolbar-amount').each(function(){
			jQuery(this).find('.toolbar-number').first().text(0);
		});
	}
}

function scrolltop() {
	if (!isMobile.phone) jQuery("html, body").stop().animate({ scrollTop: jQuery('div.ja-megafilter-wrap').offset().top }, 400);
}

function MegaFilterCallback() {
	bindCallback();
	<?php echo $input->getCmd('scrolltop') ? 'scrolltop();' : ''; ?>
}


function afterGetData(item) {
	if (typeof(item.thumbnail) != 'undefined' && item.thumbnail != '') {
		thumbnail = item.thumbnail;
		if (!thumbnail.match(/^http|https:/)) {
			item.thumbnail = '<?php echo JUri::root(true) . '/'; ?>'+item.thumbnail;
		}
	}
	
	// owner
  if (item.created_by == '<?php echo $userID; ?>')
    return false;
  if (typeof item['access'] !== 'undefined' && item['access'] !== undefined && item['access'] !== null) {
    let itemAccess = item['access'].split(',');
    for (let i = 0; i < itemAccess.length; i++) {
      for (let x in ja_userGroup) {
        // super admin could see it all, public or guest.
        if (itemAccess[i] == ja_userGroup[x] || ja_userGroup[x] == 8) {
          return false;
        }
      }
    }
  }
	return true;

}

jQuery(document).ready(function() {
  var UBLNConfig = {};
  UBLNConfig.dataUrl = "<?php echo JUri::base(true) . $this->config->json; ?>";
  UBLNConfig.fields = <?php echo json_encode($this->config->fields); ?>;
  UBLNConfig.sortByOptions = <?php echo str_replace(
      '.value',
      '.frontend_value',
      json_encode($this->config->sorts)
  ); ?>;
  UBLNConfig.defaultSortBy = "<?php echo $this->config->default_sort; ?>";
  UBLNConfig.productsPerPageAllowed = [<?php echo implode(
      ',',
      $this->config->paginate
  ); ?>];
  UBLNConfig.autopage = <?php echo $this->config->autopage
      ? 'true'
      : 'false'; ?>;
  UBLNConfig.sticky = <?php echo $this->config->sticky ? 'true' : 'false'; ?>;
  UBLN.main(UBLNConfig);
});
</script>

<?php endif;

<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!$list)
{
	return;
}

?>
<ul class="mod-articleslatest latestnews mod-list">
<?php foreach ($list as $item) : ?>
	<li>
		<?php
			// Intro Image
			$introImage = json_decode($item->images)->image_intro;
		?>
		<?php if($introImage) : ?>
		<div class="intro-image">
			<a href="<?php echo $item->link; ?>">
				<img src="<?php echo $introImage ;?>" alt="<?php echo $item->title; ?>" />
			</a>
		</div>
		<?php endif ;?>

		<a href="<?php echo $item->link; ?>">
			<span>
				<?php echo $item->title; ?>
			</span>
		</a>
	</li>
<?php endforeach; ?>
</ul>

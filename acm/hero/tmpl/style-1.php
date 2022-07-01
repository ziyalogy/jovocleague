<?php
/**
 * ------------------------------------------------------------------------
 * jovocleague_tpl
 * ------------------------------------------------------------------------
 * Copyright (C) 2022 Buildal Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: Ziyal Amanya
 * Websites:  http://www.buildal.ug
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */
defined('_JEXEC') or die();
$count = $helper->count('btn-title');

$bgHero = '';

if ($helper->get('ft-bg')) {
    $bgHero =
        'style="background-image: url(' .
        JUri::root() .
        '' .
        $helper->get('ft-bg') .
        ');"';
}
?>

<div class="acm-hero style-1 align-<?php echo $helper->get(
    'ft-align'
); ?>" <?php echo $bgHero; ?>>
	<div class="mask" style="opacity: <?php echo $helper->get('ft-mask'); ?>"></div>
	<div class="container">
		<div class="hero-item">
			<?php if ($helper->get('title')): ?>
				<h1 data-aos="fade-up"><?php echo $helper->get('title'); ?></h1>
			<?php endif; ?>
			
			<?php if ($helper->get('description')): ?>
				<p class="lead" data-aos="fade-up" data-aos-delay="200"><?php echo $helper->get(
        'description'
    ); ?></p>
			<?php endif; ?>
			
			<div class="btn-action" data-aos="fade-up" data-aos-delay="400">
				<?php for ($i = 0; $i < $count; $i++): ?>
					<?php if ($helper->get('btn-title', $i)): ?>
						<a class="btn btn-<?php echo $helper->get(
          'btn-type',
          $i
      ); ?>" href="<?php echo $helper->get('btn-link', $i); ?>">
							<?php echo $helper->get('btn-title', $i); ?>
							<i class="far fa-arrow-alt-circle-right"></i>
					<?php endif; ?>
				</a>
				<?php endfor; ?>
			</div>
		</div>
	</div>
</div>
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
$alignMedia = $helper->get('align-media');
?>

<div class="acm-features style-1">
	<div class="row align-<?php echo $alignMedia; ?>">
		<?php if ($helper->get('intro-img')): ?>
			<div class="col-12 col-lg-6 features-media">
				<div class="intro-img">
					<img src="<?php echo $helper->get('intro-img'); ?>" alt="" />
				</div>				
			</div>
		<?php endif; ?>

		<div class="col-12 col-lg-6 features-desc">
			<div class="info-wrap">
				<div class="inner">
					<?php if ($helper->get('info-title')): ?>
						<div class="h1 feature-title">
							<?php echo $helper->get('info-title'); ?>
						</div>
					<?php endif; ?>

					<?php if ($helper->get('info-desc')): ?>
						<div class="info-desc fs-lg">
							<?php echo $helper->get('info-desc'); ?>
						</div>
					<?php endif; ?>

					<?php if ($helper->get('button1') || $helper->get('button2')): ?>
            <div class="feature-action">
              <?php if ($helper->get('button1')): ?>
                <a href="<?php echo $helper->get(
                    'btn-link1'
                ); ?>" class="btn btn-<?php echo $helper->get(
    'btn-type1'
); ?>"><?php echo $helper->get(
    'button1'
); ?><i class="fas ms-1 fa-arrow-right"></i></a>
              <?php endif; ?>
              <?php if ($helper->get('button2')): ?>
                <a href="<?php echo $helper->get(
                    'btn-link2'
                ); ?>" class="btn btn-<?php echo $helper->get(
    'btn-type2'
); ?>"><?php echo $helper->get(
    'button2'
); ?><i class="fas ms-1 fa-arrow-right"></i></a>
              <?php endif; ?>
            </div>
            <?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

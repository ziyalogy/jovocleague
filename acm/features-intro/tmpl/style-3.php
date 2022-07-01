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
$app = JFactory::getApplication();
$path = JURI::base(true) . '/templates/' . $app->getTemplate() . '/';
$alignMedia = $helper->get('align-media');
?>

<div class="acm-features style-3">
	<div class="container">
		<div class="row align-<?php echo $alignMedia; ?>">
			<?php if ($helper->get('intro-img')): ?>
				<div class="col-12 d-none d-xl-flex col-xl-7 features-media">
					<div class="intro-img">
						<img src="<?php echo $helper->get('intro-img'); ?>" alt="" />
					</div>				
				</div>
			<?php endif; ?>

			<div class="col-12 col-lg-12 col-xl-5 features-desc">
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

						<?php if (
          $helper->get('btn-appstore') ||
          $helper->get('btn-chplay') ||
          $helper->get('btn-qr')
      ): ?>
	            <div class="feature-action">
	              <?php if ($helper->get('btn-appstore')): ?>
	                <a href="<?php echo $helper->get(
                     'btn-appstore'
                 ); ?>" class="btn btn-dark"><?php echo $helper->get(
    'button1'
); ?>
	                	<img src="<?php echo $path .
                      'images/btn-appstore.svg'; ?>" alt="" />
	                </a>
	              <?php endif; ?>

	              <?php if ($helper->get('btn-chplay')): ?>
	                <a href="<?php echo $helper->get(
                     'btn-chplay'
                 ); ?>" class="btn btn-dark"><?php echo $helper->get(
    'button1'
); ?>
	                	<img src="<?php echo $path .
                      'images/btn-chplay.svg'; ?>" alt="" />
	                </a>
	              <?php endif; ?>

	              <?php if ($helper->get('btn-qr')): ?>
	                <a href="<?php echo $helper->get(
                     'btn-qr'
                 ); ?>" class="btn btn-dark"><?php echo $helper->get(
    'button1'
); ?>
	                	<img src="<?php echo $path .
                      'images/btn-qrcode.svg'; ?>" alt="" />
	                </a>
	              <?php endif; ?>

	            </div>
	            <?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

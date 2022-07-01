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
$count = $helper->getRows('title');
$column = $helper->get('columns');
?>

<div class="acm-features style-2">
	<?php for ($i = 0; $i < $count; $i++): ?>
		<?php if ($i % $column == 0): ?>
			<div class="row cols-<?php echo $column; ?> d-flex justify-content-center v-gutters">
		<?php endif; ?>

			<div class="col-12 col-sm-6 col-lg-<?php echo 12 / $column; ?>">
				<div class="features-item">
					<div class="item-inner">
						<?php if ($helper->get('img-icon', $i)): ?>
							<div class="img-icon">
								<img src="<?php echo $helper->get('img-icon', $i); ?>" alt="" />
							</div>
						<?php endif; ?>
						
						<?php if ($helper->get('title', $i)): ?>
							<div class="features-title">
								<h5>
									<?php echo $helper->get('title', $i); ?>
								</h5>
							</div>
						<?php endif; ?>
						
						<?php if ($helper->get('description', $i)): ?>
							<div class="features-descriptions"><?php echo $helper->get(
           'description',
           $i
       ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>

		<?php if ($i % $column == $column - 1 || $i == $count - 1) {
      echo '</div>';
  } ?>
	<?php endfor; ?>
</div>
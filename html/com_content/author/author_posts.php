<?php
$introcount = count($this->article_items);
$counter = 0;
$colClass = floor(12/$this->columns);
?>
<div class="blog-items">
<?php if (!empty($this->article_items)) : ?>
	<div class="items-intro row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-<?php echo $this->columns ;?> row">

	<?php foreach ($this->article_items as $key => &$item) : ?>
		<?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
	<?php if ($rowcount === 1) : ?>
		<?php $row = $counter / $this->columns; ?>
	<?php endif; ?>

		<div class="item-wrap col">
			<div class="item <?php echo $this->columns == 2 ? 'type-horizontal' : 'type-vertical'; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
			<div class="box-inner">	
				<?php
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
				$this->item = &$item;
				echo $this->loadTemplate('post');
			?>
		</div>
			</div><!-- end item -->
			<?php $counter++; ?>
		</div><!-- end col -->

	<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
	<div id="left_nav" class="span3">
	<?php
		$this->widget('bootstrap.widgets.TbMenu', array(
			'htmlOptions' => array('class' => 'nav-pills'),
			'items'=>$this->menu,
		));
	?>
	</div><!-- content -->
	<div id="content" class="span9">
		<?php echo $content;?>
	</div>
</div>

	

<?php $this->endContent(); ?>
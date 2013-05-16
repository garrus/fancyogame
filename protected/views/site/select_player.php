<h1>Your players</h1>

<div class="form-actions">
    <?php echo CHtml::link('Create a new player', array('site/newplayer'), array('class' => 'btn btn-primary'));?>
    <?php if ($currentPlayer) :?>
    <?php echo ' Or ';
    echo CHtml::link('Start game with "'. $currentPlayer->name. '"', array('site/selectPlayer', 'id' => 'current'), array('class' => 'btn btn-success'));?>
    <?php endif;?>
    
</div>
<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_player_view',
    'template' => '{items}',
    'viewData' => array(
        'currentPlayer' => $currentPlayer,  
    ),
)); ?>


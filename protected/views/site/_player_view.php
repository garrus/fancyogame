<div class="view row">

<div class="span2">
    <?php echo CHtml::link('Start game', array('site/selectplayer', 'id' => $data->id), array('class' => 'btn btn-small'));?>
</div>

<div class="span2">
    <strong><?php echo CHtml::encode($data->name);?></strong>
</div>
<div class="span3">
    Owning <strong><?php echo $data->planetCount;?></strong> planets
</div>



</div>
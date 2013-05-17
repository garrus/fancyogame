<div class="view row">
    <div class="span2">
        <?php echo CHtml::link($data->name, array('enter', 'id' => $data->id));?>
    </div>
    
    <div class="span2">
        <?php echo CHtml::encode($data->formatLocation());?>
    </div>
    
    <div class="span2">
        Temperator: <?php echo $data->temperature;?> K
    </div>
	
	<div class="span3">
	    Gas production rate: <?php echo $data->gas_production_rate;?> k/hour
	</div>
	<div class="span3">
	    Metal/Crystal mining rate: <?php echo $data->mine_limit;?> k/hour
	</div>

</div>
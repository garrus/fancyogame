<h1>My Planets</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'dataProvider'=>$dataProvider,
    'template' => '{items}',
    'selectableRows' => 0,
	'columns'=>array(
	    array(
	        'header' => 'Planet Name',
	        'name' => 'name',
	        'value' => 'CHtml::link($data->name, array(\'enter\', \'id\' => $data->id))',
	        'type' => 'raw',
	        ),
	    array(
	        'header' => 'Location',
	        'name' => 'galaxy',
	        'value' => '$data->formatLocation()',
	        ),
	    array(
	        'header' => 'Temperature (K)',
	        'name' => 'temperature',
	        ),
	    array(
	        'header' => 'Gas Production Rate (K/Hour)',
	        'name' => 'gas_production_rate',
	        ),
	    array(
	        'header' => 'Metal/Crystal Mining Rate',
	        'name' => 'mine_limit',
	        ),
	    ),
)); ?>

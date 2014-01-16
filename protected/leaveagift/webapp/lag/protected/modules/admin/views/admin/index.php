<?php
$this->breadcrumbs=array(
	'Users',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('user-grid', {
        data: $(this).serialize()
    });
    return false;
});
");

?>

<h1>Admins</h1>
<hr />

<?php 
$this->beginWidget('zii.widgets.CPortlet', array(
	'htmlOptions'=>array(
		'class'=>''
	)
));
$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
		//array('label'=>'Create', 'icon'=>'icon-plus', 'url'=>Yii::app()->controller->createUrl('create'), 'linkOptions'=>array()),
                array('label'=>'List', 'icon'=>'icon-th-list', 'url'=>Yii::app()->controller->createUrl('index'),'active'=>true, 'linkOptions'=>array()),
		array('label'=>'Search', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
		array('label'=>'Export to PDF', 'icon'=>'icon-download', 'url'=>Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions'=>array('target'=>'_blank'), 'visible'=>true),
		array('label'=>'Export to Excel', 'icon'=>'icon-download', 'url'=>Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions'=>array('target'=>'_blank'), 'visible'=>true),
	),
));
$this->endWidget();
?>



<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(true),
        'type'=>'striped bordered condensed',
        'template'=>'{summary}{pager}{items}{pager}',
	'columns'=>array(
		'id_user',
		//'id_customer',
		//'facebook_userid',
		//'facebook_oauthid',
		'facebook_email',
		'name',
		/*
		'date_add',
		'date_upd',
		*/
       array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
                        //'htmlOptions'=>array('style'=>'width:200'),
			'template' => '{view} {makeadmin}  {removeadmin}',
			'buttons' => array(
			      'view' => array(
					'label'=> 'View',
					'options'=>array(
						'class'=>'btn btn-small view'
					)
				),	
                               'makeadmin' => array(
                                        'visible'=>'($data->is_admin==0)?true:false ',
					'label'=> 'Make admin',
                                        'url'=>'Yii::app()->createUrl("admin/user/makeAdmin",array("id"=>$data->id_user))',
					'options'=>array(
						'class'=>'btn btn-success'
					)
				),
                               'removeadmin' => array(
                                        'visible'=>'($data->is_admin==1)?true:false',  
					'label'=> 'Remove admin',
                                        'url'=>'Yii::app()->createUrl("admin/user/removeAdmin",array("id"=>$data->id_user))',
					'options'=>array(
						'class'=>'btn btn-danger'
					)
				),
                               'blockuser' => array(
                                        'visible'=>'($data->is_blocked==0)?true:false ',
					'label'=> 'Block User',
                                        'url'=>'Yii::app()->createUrl("admin/user/blockUser",array("id"=>$data->id_user))',
					'options'=>array(
						'class'=>'btn btn-success'
					)
				),
                               'unblockuser' => array(
                                        'visible'=>'($data->is_unblocked==1)?true:false',  
					'label'=> 'Unblock User',
                                        'url'=>'Yii::app()->createUrl("admin/user/unblockUser",array("id"=>$data->id_user))',
					'options'=>array(
						'class'=>'btn btn-danger'
					)
				),
				
                            /*  'update' => array(
					'label'=> 'Update',
					'options'=>array(
						'class'=>'btn btn-small update'
					)
				),
				'delete' => array(
					'label'=> 'Delete',
					'options'=>array(
						'class'=>'btn btn-small delete'
					)
				)*/
			),
            'htmlOptions'=>array('style'=>'width: 115px'),
           )
	),
)); ?>


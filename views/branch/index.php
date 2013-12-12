<?php
/* @var $this BranchController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Branches',
);

$this->menu=array(
	array('label'=>'Create Branch', 'url'=>array('create')),
	array('label'=>'Manage Branch', 'url'=>array('admin')),
);
?>

<h1>Branches</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'branch-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'district_id'); ?>
                <?php 
                $yajax = CHtml::ajax(array(
                                'type'=>'POST', 
                                'url'=>CController::createUrl('loadbranches'),
                                'success'=>'js:function(data) { createMarkers(data) }', 
                                'data'=>array('region_id'=>'js:this.value', 'city'=>'js:jQuery("#Branch_region_id option:selected").text()'),
                              ));
                echo CHtml::dropDownList('Branch[region_id]',$model->region_id, 
                        $regions,

                        array(
                          'prompt'=>'Select Region',
                          'ajax' => array(
                            'type'=>'POST', 
                            'url'=>CController::createUrl('loadcities'),
                            'update'=>'#Branch_city_id', 
                            'data'=>array('region_id'=>'js:this.value'),
                            ),
                            'onchange'=>$yajax.";setCenter(jQuery(\"#Branch_region_id option:selected\").text())",
                          )
                        ); 
                ?>
            
                <?php
                
                $yajax = CHtml::ajax(array(
                                'type'=>'POST', 
                                'url'=>CController::createUrl('loadbranches'),
                                'success'=>'js:function(data) { createMarkers(data) }', 
                                'data'=>array('city_id'=>'js:this.value', 'city'=>'js:jQuery("#Branch_city_id option:selected").text()'),
                              ));
                
                echo CHtml::dropDownList('Branch[city_id]',$model->city_id, 
                        $cities,

                        array(
                          'prompt'=>'Select City',
                          'ajax' => array(
                          'type'=>'POST', 
                          'url'=>CController::createUrl('loaddistricts'),
                          'update'=>'#Branch_district_id', 
                           'data'=>array('city_id'=>'js:this.value'),
                            ),
                            'onchange'=>$yajax.";setCenter(jQuery(\"#Branch_region_id option:selected\").text()+jQuery(\"#Branch_city_id option:selected\").text())",
                          )
                        ); 
                ?>
                <?php echo CHtml::dropDownList('Branch[district_id]',$model->district_id, 
                        $districts,
                        array(
                            'prompt'=>'Select District',
                            'ajax' => array(
                                'type'=>'POST', 
                                'url'=>CController::createUrl('loadbranches'),
                                'success'=>'js:function(data) { createMarkers(data) }', 
                                'data'=>array('district_id'=>'js:this.value', 'city'=>'js:jQuery("#Branch_city_id option:selected").text()'),
                              ),
                            'onchange'=>"setCenter(jQuery(\"#Branch_region_id option:selected\").text()+jQuery(\"#Branch_city_id option:selected\").text()+jQuery(\"#Branch_district_id option:selected\").text())",
                          )
                        );  
                ?>
		<?php echo $form->error($model,'[Branch]district_id'); ?>
	</div>        
	<div class="row">
            <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=I4WF7eYIkmHf3vgY8Ma01usn"></script>
            
            <div id="allmap" style="width:1000px; height:500px;"></div>
            <script type="text/javascript">

            // 百度地图API功能
            var map = new BMap.Map("allmap");            
            var point = new BMap.Point(116.404, 39.915);    // 创建点坐标
            map.centerAndZoom(point,15);                   // 初始化地图,设置中心点坐标和地图级别。
            map.enableScrollWheelZoom();                   //启用滚轮放大缩小

            createMarkers = function(data) {
                json = jQuery.parseJSON(data);

                var map = new BMap.Map("allmap");            // 创建Map实例
                        
                map.enableScrollWheelZoom();   //启用滚轮放大缩小
                map.centerAndZoom(json.city,10);    //初始化时，即可设置中心点和地图缩放级别。
                
                jQuery.each( json.markers, function (i, o) {
                     
                       var point = new BMap.Point(o.lng,o.lat);
                       var marker = new BMap.Marker(point);  
                       map.addOverlay(marker); 
                });
            }
            
            setCenter = function(location) {
                var map = new BMap.Map("allmap");  // 创建Map实例
                
                map.enableScrollWheelZoom();       //启用滚轮放大缩小
                map.centerAndZoom(location,10);    //初始化时，即可设置中心点和地图缩放级别。
                
            }
            </script>
	</div>

<?php $this->endWidget(); ?>
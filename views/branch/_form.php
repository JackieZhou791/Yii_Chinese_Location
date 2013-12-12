<?php
/* @var $this BranchController */
/* @var $model Branch */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'branch-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'district_id'); ?>
                <?php echo CHtml::dropDownList('Branch[region_id]',$model->region_id, 
                        $regions,

                        array(
                          'prompt'=>'Select Region',
                          'ajax' => array(
                            'type'=>'POST', 
                            'url'=>CController::createUrl('loadcities'),
                            'update'=>'#Branch_city_id', 
                            'data'=>array('region_id'=>'js:this.value'),
                            ),
                            'onchange'=>"setCenter(jQuery(\"#Branch_region_id option:selected\").text())",
                          )
                        ); 
                ?>
            
                <?php echo CHtml::dropDownList('Branch[city_id]',$model->city_id, 
                        $cities,

                        array(
                          'prompt'=>'Select City',
                          'ajax' => array(
                          'type'=>'POST', 
                          'url'=>CController::createUrl('loaddistricts'),
                          'update'=>'#Branch_district_id', 
                           'data'=>array('city_id'=>'js:this.value'),
                            ),
                            'onchange'=>"setCenter(jQuery(\"#Branch_region_id option:selected\").text()+jQuery(\"#Branch_city_id option:selected\").text())",
                          )
                        ); 
                ?>
                <?php echo CHtml::dropDownList('Branch[district_id]',$model->district_id, 
                        $districts,
                        array(
                          'prompt'=>'Select District',
//                          'ajax' => array(
//                          'type'=>'POST', 
//                          'url'=>CController::createUrl('loaddistricts'),
//                          'update'=>'#Branch_district_id', 
//                           'data'=>array('city_id'=>'js:this.value'),
//                        ),
                            'onchange'=>"setCenter(jQuery(\"#Branch_region_id option:selected\").text()+jQuery(\"#Branch_city_id option:selected\").text()+jQuery(\"#Branch_district_id option:selected\").text())",
                          )
                        );  
                ?>
		<?php echo $form->error($model,'[Branch]district_id'); ?>
	</div>
        
        
	<div class="row">
            <label>Click the map to set branch location</label>
            <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=I4WF7eYIkmHf3vgY8Ma01usn"></script>
            
            <div id="allmap" style="width:1000px; height:500px;"></div>
            <script type="text/javascript">

            // 百度地图API功能
            var map = new BMap.Map("allmap");            // 创建Map实例
            
                
                <?php if(isset($model->lng) && isset($model->lat)) : ?>
                   var point = new BMap.Point(<?=$model->lng ?>, <?=$model->lat ?>);
                   var marker = new BMap.Marker(point);  
                   map.addOverlay(marker); 
                   marker.enableDragging();  
                   
                    marker.addEventListener("dragend", function(eve){  
                        document.getElementById('Branch_lng').value = eve.point.lng;
                        document.getElementById('Branch_lat').value = eve.point.lat;
                    });
                <?php else: ?>
                    var point = new BMap.Point(116.404, 39.915);    // 创建点坐标
                <?php endif;?>
            map.centerAndZoom(point,15);                     // 初始化地图,设置中心点坐标和地图级别。
            map.enableScrollWheelZoom();                            //启用滚轮放大缩小

            function setLocation() {
                
                var marker = new BMap.Marker(point);        // 创建标注    
                map.addOverlay(marker); 
                marker.enableDragging();  
                marker.addEventListener("dragend", function(e){  
                    document.getElementById('Branch_lng').value = e.point.lng;
                    document.getElementById('Branch_lat').value = e.point.lat;
                });
            }
            
            setCenter = function(location) {
                var map = new BMap.Map("allmap");            // 创建Map实例
                
                map.enableScrollWheelZoom();                            //启用滚轮放大缩小
                map.centerAndZoom(location,10);    //初始化时，即可设置中心点和地图缩放级别。
                
                map.addEventListener("click", function(e){
                    map.clearOverlays();
                    var marker = new BMap.Marker(e.point);        // 创建标注   
                    
                    document.getElementById('Branch_lng').value = e.point.lng;
                    document.getElementById('Branch_lat').value = e.point.lat;
                    
                    map.addOverlay(marker); 
                    marker.enableDragging();  
                    marker.addEventListener("dragend", function(eve){  
                        document.getElementById('Branch_lng').value = eve.point.lng;
                        document.getElementById('Branch_lat').value = eve.point.lat;
                    });
                })
            }
            </script>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lng'); ?>
		<?php echo $form->textField($model,'lng'); ?>
		<?php echo $form->error($model,'lng'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lat'); ?>
		<?php echo $form->textField($model,'lat'); ?>
		<?php echo $form->error($model,'lat'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
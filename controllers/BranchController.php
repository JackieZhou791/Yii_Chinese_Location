<?php

class BranchController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','loadcities','loaddistricts', 'loadbranches'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Branch;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Branch']))
		{
			$model->attributes=$_POST['Branch'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
                        'regions' => $this->getRegionOptions(),
                        'cities' => array(),
                        'districts' => array(),
		));
                
	}
        
        

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Branch']))
		{
			$model->attributes=$_POST['Branch'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
                        'regions' => $this->getRegionOptions(),
                        'cities' => $this->getCityOptions($model->region_id),
                        'districts' => $this->getDistrictOptions($model->city_id),
		));
	}

        
        public function actionLoadcities() {
            $data = City::model()->findAll(
                        'region_id=:region_id', array(
                        ':region_id' => (string) $_POST['region_id']
                        )
                    );

            $data = CHtml::listData($data, 'city_id', 'name');

            $cityHtml = CHtml::dropDownList('Branch[city_id]','', 
                        $data,

                        array(
                          'prompt'=>'Select City',
                          'ajax' => array(
                          'type'=>'POST', 
                          'url'=>CController::createUrl('loaddistricts'),
                          'update'=>'#Branch_district_id', 
                           'data'=>array('city_id'=>'js:this.value'),
                        ))); 
            
            echo $cityHtml;
        }
        
        
        
        public function actionLoaddistricts() {
            $data = District::model()->findAll(
                        'city_id=:city_id', array(
                        ':city_id' => (string) $_POST['city_id']
                        )
                    );

            $data = CHtml::listData($data, 'district_id', 'name');

            $cityHtml = CHtml::dropDownList('Branch[district]','', 
                        $data,
                        array(
                          'prompt'=>'Select District',
                        )
                    ); 
            
            echo $cityHtml;
        }
        
        public function actionLoadbranches() {
            
            if(isset($_POST['district_id'])) {
                $data = Branch::model()->findAll(
                        'district_id=:district_id', array(
                        ':district_id' => (string) $_POST['district_id']
                        )
                    );
            } elseif(isset($_POST['city_id'])) {
                
                $data = Branch::model()->findAll(
                        'city_id=:city_id', array(
                        ':city_id' => (string) $_POST['city_id']
                        )
                    );
            } elseif(isset($_POST['region_id'])) {
                
                $data = Branch::model()->findAll(
                        'region_id=:region_id', array(
                        ':region_id' => (string) $_POST['region_id']
                        )
                    );
            }

            $data = CHtml::listData($data, 'lng', 'lat');
            
            $jsonArr = array();
            foreach($data as $lng=>$lat) {
                $jsonArr[] = array('lng'=>$lng, 'lat'=>$lat);
            }
            $branchStr = json_encode(array( 'city'=>$_POST['city'], 'markers'=>$jsonArr));
            
            echo $branchStr;
        }
        
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Branch');
		$this->render('index',array(
                                'model'=>Branch::model(),
                                'regions' => $this->getRegionOptions(),
                                'cities' => array(),
                                'districts' => array(),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Branch('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Branch']))
			$model->attributes=$_GET['Branch'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Branch the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Branch::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Branch $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='branch-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        protected function getRegionOptions() {
            
            $data = Region::model()->findAll();
            $regions = array();
            foreach($data as $item) {
                $regions[$item->region_id] = $item->name; 
            }
            return $regions;
        }
        
        protected function getCityOptions($regionId) {
            $data = City::model()->findAll(
                        'region_id=:region_id', array(
                        ':region_id' => (string) $regionId
                        )
                    );

            $data = CHtml::listData($data, 'city_id', 'name');
            return $data;
        }
        
        protected function getDistrictOptions($cityId) {
            $data = District::model()->findAll(
                        'city_id=:city_id', array(
                        ':city_id' => (string) $cityId
                        )
                    );

            $data = CHtml::listData($data, 'district_id', 'name');
            return $data;
        }
}

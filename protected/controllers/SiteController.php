<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
   public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	/**
	 * @return array rules for the "accessControl" filter.
	 */
	 
	public function accessRules()
	{
		return array(
		
		       array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(
				                
				         
				                 'paypal',
				                 'PaypalReturn',
				                 'PaypalCancel',
				                 'paypalDemo',
				                 'RequestPayment',
				                
				                 'BitcoinCancel',
				                 'BitcoinSuccess',
				                 'BitcoinCallback',
				                 'Logout',
				                 'index',
				       
				                ),
				'users'=>array('@'),
			),
			
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(
				               //  'login',
				                 //'index',
				                 'login',
				                 'error', 
				                 'signup',
				                 'Bitcoin',
				                 'Fileupload'
				               //  'logout'
				                ),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	public function actionLogin()
	{
		//$this->layout = 'main';
		

		$model = new LoginForm();

		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
		{
			echo CActiveForm::validate($model, array('username', 'password', 'verifyCode'));
			Yii::app()->end();
		}

		if (isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];
			if ($model->validate(array('username', 'password')) && $model->login())
			{
			    $this->redirect(Yii::app()->user->returnUrl);
			}
		}

		
		//$sent = r()->getParam('sent', 0);
		$this->render('login', array(
			'model' => $model,
			//'sent' => $sent,
		));
	}
        public function actionSignup()
	{
	    $model=new User;

	    // uncomment the following code to enable ajax-based validation
	    /*
	    if(isset($_POST['ajax']) && $_POST['ajax']==='user-signup-form')
	    {
		echo CActiveForm::validate($model);
		Yii::app()->end();
	    }
	    */

	    if(isset($_POST['User']))
	    {
		$model->attributes=$_POST['User'];
		if($model->validate())
		{ 
		    $login=new LoginForm;
		    $login->username=$model->email;
		    $login->password=$model->password;
		    
		  $model->save(false);
		  
		  
			if ($login->validate(array('username', 'password')) && $login->login())
			        {
			        
			        	$this->redirect(Yii::app()->user->returnUrl);
				}
			else
			{
			   echo "Email:".$model->email;
			   echo "Password:".$model->password;
			   
			       $this->render('login',array('model'=>$login));
			       return;
			}
		      
		    // form inputs are valid, do something here
		  
		}
	    }
	    $this->render('signup',array('model'=>$model));
	}
	 
	/**
	 * This is the action that handles user's logout
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

   

       /*
        public function actionAskPermission()
        {
         $session=new CHttpSession;
         $session->open(); 
        
         
         $auth_url = "https://www.facebook.com/dialog/oauth?client_id=".Yii::app()->controller->module->app_id. "&redirect_uri=" . urlencode($session['page_link']."?sk=app_".Yii::app()->controller->module->app_id)."&scope=email,user_location";
                  
         echo("<script> top.location.href='" . $auth_url . "'</script>");
             

        }
         */
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
	  $this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}


	public function actionAjaxCrop()
	{
	
	 $this->render('ajaxcrop');
	}
	public function actionDocrop()
	{
	  Yii::import('ext.jcrop.EJCropper');
	  $assetsDir = dirname(__FILE__).'/../assets';
	 // $imagetobecroped='689x1000.jpg';
	  $imagetobecroped='images.jpeg';
	  
	 //print_r($_REQUEST);
	  //exit;
	  $jcropper = new EJCropper();
	  $jcropper->thumbPath = $assetsDir."/cropedimage";
	  
	  // some settings ...
	  $jcropper->jpeg_quality = 95;
	  $jcropper->png_compression = 8;
	  
	  // get the image cropping coordinates (or implement your own method)
	   $coords = $jcropper->getCoordsFromPost('imageId');
	  
	  // returns the path of the cropped image, source must be an absolute path.
	  $thumbnail = $jcropper->crop($assetsDir."/". $imagetobecroped, $coords);
	} 
        public function actionIcrop()
        {
         Yii::import('ext.icrop.Icrop');
         $this->render("icrop");
        }

        public function actionIcropApi()
        {
         Yii::import('ext.icrop.Icrop');
         $this->render("icropapi");
        } 
        
        public function actionParse()
        {
          // echo "ok";
        //  Yii::import("application.extensions.parse.NParse");
          
          // $p=new NParse;
           
           $model=new ParseTest;
           $model->name="sirin";
           $model->age="123";
           
          // $model->save();
           
           
           if($model->save())
            {
              echo "saved"; 
              echo "<br/	>id:".$model->id;
            }
            else
            {
             $e=$model->getErrors();
             print_r($e);
            }
            
        }
        public function actionParseView($id)
        {
          $model=ParseTest::model()->findbyPk($id); 
          
          $model->name="Sirin";
          $model->age="24";
          if($model->save())
            {
              echo "saved"; 
              echo "<br/>id:".$model->id;
            }
            else
            {
             $e=$model->getErrors();
             print_r($e);
            }
           $model->name="coool";
           $model->save();
        }
        public function actionPaypalDemo()
        {
          $this->render('paypal_demo');
        }
        public function actionRequestPayment()
        {
          $products=array(
              
			'0'=>array(
				  'NAME'=>'Milk Shake with Ice Cream',
				  'AMOUNT'=>'20',
				  'QTY'=>'1'
				  ),
			
    
                         );
                         /*Optional */
             $shipping_address=array(
    
			'FIRST_NAME'=>'Sirin',
			'LAST_NAME'=>'K',
			'EMAIL'=>'sirinibin2006@gmail.com',
			'MOB'=>'0918606770278',
			'ADDRESS'=>'mannarkkad', 
			'SHIPTOSTREET'=>'mannarkkad',
			'SHIPTOCITY'=>'palakkad',
			'SHIPTOSTATE'=>'kerala',
			'SHIPTOCOUNTRYCODE'=>'IN',
			'SHIPTOZIP'=>'678761'
                 );
    
		    $e=new ExpressCheckout;
		    
		    $e->setCurrencyCode("USD");
		    
		    $e->setProducts($products);
		    
		    $e->setShippingCost(0);/*Optional*/
		    
		    $e->setShippingInfo($shipping_address); //Optional
		    
		    $e->returnURL=Yii::app()->createAbsoluteUrl("site/PaypalReturn");
    
                    $e->cancelURL=Yii::app()->createAbsoluteUrl("site/PaypalCancel");
		    
		    $result=$e->requestPayment();
		      
		
		    if(strtoupper($result["ACK"])=="SUCCESS")
		      {
			    //$token = urldecode($resArray["TOKEN"]);
			    
			    header("location:".$e->PAYPAL_URL.$result["TOKEN"]);
		      }
		      else
		      {
		    
		           $this->render("paypal_error",array('ack'=>$result));   
		   
		      }
                     
        
        }
        public function actionPaypalReturn()
        {
   
		     $e=new ExpressCheckout;
		    
		    if(isset($_REQUEST['token']))
		    {
		      $paymentDetails=$e->getPaymentDetails($_REQUEST['token']);
	          
	              if($paymentDetails['ACK']=="Success")
		      {
		        $ack=$e->doPayment($paymentDetails);
		        $this->render("paypal_success",array('ack'=>$ack));
		      }
		      else
		      {
		        $this->render("paypal_error",array('ack'=>$ack));   
		      }
		    } 
		   
	   
	
        }
        public function actionPaypalCancel()
        {
                     $this->render("paypal_cancel");        
        } 
        public function actionBitcoin()
        {
         $this->render("bitcoin");
        }
        public function actionBitcoinCallback()
        {
          $response=Yii::app()->bitcoin->getAccessToken();
          
          echo "response1:<pre>";
          print_r($response); 
          echo "</pre>";
          
          $response2=Yii::app()->bitcoin->getBalance($response['access_token']);
          
          echo "response2:<pre>";
          print_r($response2); 
          echo "</pre>";
         
          
          echo "<pre>";
          print_r($_REQUEST); 
          echo "</pre>";
        }
        public function actionBitcoinCancel()
        { 
          echo "<pre>";
          print_r($_REQUEST); 
          echo "</pre>";
        }
        public function actionBitcoinSuccess()
        {
           echo "<pre>";
           print_r($_REQUEST); 
           echo "</pre>";
        
        }
        public function actionFileupload()
	{
	    $model=new FileUpload;

	    // uncomment the following code to enable ajax-based validation
	    /*
	    if(isset($_POST['ajax']) && $_POST['ajax']==='file-upload-file_upload-form')
	    {
		echo CActiveForm::validate($model);
		Yii::app()->end();
	    }
	    */

	      $rows=array(); 
	      $columns=array();
	    if(isset($_POST['FileUpload']))
	    {
		$model->attributes=$_POST['FileUpload'];
		if($model->validate())
		{
		
		   $file=CUploadedFile::getInstance($model,'file'); 
		   
		  //  $file->saveAs("files/".$file->getName());
		   /*
		    echo "F:<pre>";
		    print_r($file);
		    echo "</pre>";
		    */
		    
		   //echo "URL:".Yii::app()->baseUrl."/".$file->getName();
		   
		     Yii::import('ext.phpexcelreader.JPhpExcelReader');
		     
		    // chmod($file->getTempName(), 0777);
                     
                     try{
                         $data=new JPhpExcelReader($file->getTempName());
                        }
                        catch(Exception $e)
                        {
                         $model->addError("file",$e->getMessage());
                         //echo "Error:".$e->getMessage();
                         
                        }
                      
                       
                       $i=0;
                      /* 
                        echo "Data:<pre>";
		    print_r($data);
		    echo "</pre>";
		    */
		    //exit;
		    
                      foreach($data->sheets as $k1=>$s)
                      {
                       
                        echo "Cells:<pre>";
                       print_r($s);
                        echo "</pre>";
                        
                        if(isset($s['cells']))
                         for($j=0;$j<count($s['cells']);$j++)
                         {
                              if($j==0)
				  {
				    $columns=$s['cells'][$j+1]; 
				    
				    /* echo "Columns:<pre>";
                                     print_r($columns);
                                     echo "</pre>";
                                     */
				  }
				  else
				  {
				      for($k=0;$k<count($columns);$k++)
                                      {
                                          $rows[$k1][$i][$columns[$k+1]]=$s['cells'][$j+1][$k+1];
                                                                               
                                           /*                                    
                                          $rows[$k1][$i][$columns[$k+1]]=array(
                                                                                 'value'=>$s['cells'][$j+1][$k+1],
                                                                                 'style'=>''
                                                                               );  
                                                    */                           
					    
				    
					    
                                      }
                                      
                                      $i++;
                                    
				    
				  }
                         
                          }
                         /*
                        foreach($s['cells'] as $k2=>$cell)
                        {
                            /*
				  if($k2==1)
				  {
				  $columns=$cell; 
				  }
				  else
				  {
				    foreach($columns as $k3=>$c)
				    {
				      $rows[$i]=array(
					    $c=>$cell[$k3],
				    
					    );
				    }
				    $i++;
				  }
                            */
                         //}
                         
                       //$rows[]=$s['cells'];
                      }
                      
                    /*
                     echo "Rows:<pre>";
		    print_r($rows);
		    echo "</pre>";
		    */
                    // echo json_encode($data->sheets);
                    /*
                    echo "Data:<pre>";
		    print_r($data);
		    echo "</pre>";
                     */
                     //echo $data->dump(true,true);
                     
                  //   exit; 
                      
                  // $tempLoc=$csvFile->getTempName();
		    // form inputs are valid, do something here
		    //return;
		}
	    }
	    $this->render('file_upload',array('model'=>$model,'sheets'=>$rows,'columns'=>$columns));
	}
       
}

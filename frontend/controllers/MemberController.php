<?php

namespace frontend\controllers;




use app\models\Cart;
use frontend\models\Address;
use frontend\models\Member;
use frontend\models\LoginForm;
use yii\captcha\Captcha;
use yii\captcha\CaptchaAction;
use yii\web\Request;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;


class MemberController extends \yii\web\Controller
{
    public $layout = false;
    //关闭csrf验证
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        return $this->render('index');
    }
    //用户注册
    public function actionRegister(){
        $model = new Member();
        $model->scenario = Member::SCENARIO_REGISTER;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $redis = \Yii::$app->redis;
            $tel = $redis->get('tel');
            $code = $redis->get('code');
            if($tel == $model->tel && $code == $model->captcha){


                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->auth_key = \Yii::$app->security->generateRandomString();
                $model->status = 1;
                $model->created_at = time();
                $model->save();
                return $this->redirect(['member/login']);
            }
        }
        return $this->render('register',['model'=>$model]);
    }
    //定义验证码操作
    public function actions(){
        return [
            'captcha'=>[
//                'class'=>'yii\captcha\CaptchaAction',
                'class'=>CaptchaAction::className(),
                //
                'minLength'=>4,
                'maxLength'=>4,
            ]
        ];
    }

    //用户登录
    public function actionLogin(){
        $model = new Member();
        $request = new Request();
        if($request->isPost) {
            $model->load($request->post());
            if ($model->validate() && $model->login()) {
                $cookies = \Yii::$app->request->cookies;
                $cart = $cookies->get('cart');
                $member_id = \Yii::$app->user->id;
                if($cart!=null){
                    $carts = unserialize($cart->value);

                    foreach($carts as $key=>$values){
                        $cartModel = \frontend\models\Cart::findOne(['goods_id'=>$key,'member_id'=>$member_id]);
                        if($cartModel){
                            $cartModel->amount += $values;
                            $cartModel->save();
                        }else{
                            $cartMo = new \frontend\models\Cart();
                            $cartMo->goods_id = $key;
                            $cartMo->amount = $values;
                            $cartMo->member_id = $member_id;
                            $cartMo->save();
                        }
                    }
                //清除cookie
                    \Yii::$app->response->cookies->remove('cart');
                }

                $member = Member::findOne(['username' => $model->username]);
                $member->last_login_time = time();
                $member->last_login_ip =ip2long( \Yii::$app->request->userIP);
                $member->save();
                //登录成功
                return $this->redirect(['index/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    //收获地址
    public function actionIndexAddress()
    {
       // $model = new Address();

        return $this->render('address');
    }

    //增加收获地址
    public function actionAddress(){
        $model = new Address();
        $models = Address::find()->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            $model->save();
            return $this->redirect(['member/address']);
        }
        return $this->render('address',['model'=>$model,'models'=>$models,]);
    }
    //修改收获地址
    public function actionEditAddress($id){
        $model = Address::findOne(['id'=>$id]);
        $models = Address::find()->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            $model->save();
            return $this->redirect(['member/address']);
        }
        return $this->render('address',['model'=>$model,'models'=>$models,]);

    }

    //删除收货地址
    public function actionDeleteAddress($id){
        $model = Address::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['member/address']);
    }

    //测试发送短信
    public function actionTest($tel){
        $code = rand(1000,9999);
       // $tel = '15283891081';
        $redis = \Yii::$app->redis;
        $redis->set('tel',$tel);
        $redis->set('code',$code);
        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
        //var_dump($res);exit;
        return json_encode($res);
    }

    //redis
    public function actionRedis(){
        $redis = \Yii::$app->redis;
        //$redis->set('name','guer');
        //$aa = $redis->get('name');
        //var_dump($aa);
        $tel = $redis->get('tel');
        $code = $redis->get('code');
        var_dump($tel);
        var_dump($code);
    }
}

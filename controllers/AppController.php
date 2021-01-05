<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\user\User;

/**
 * Class AppController
 * @package app\controllers
 * @param User $user
 */
class AppController extends Controller
{
    public $user;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' =>
                [
                    'class' => AccessControl::className(),
                    'denyCallback' => function ($rule, $action) {
                        Yii::$app->user->logout();
                        $this->redirect('/site/login');
                    },
                    'except' => [
                        'login',
                        'signup',
                        'captcha',
                        'request-password-reset',
                        'reset-password',
                        'verify-email',
                        'resend-verification-email',
                    ],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => User::getUserRoles(),
                        ],
                    ],

                ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        $this->user = Yii::$app->user->identity;

        return parent::beforeAction($action);
    }
}
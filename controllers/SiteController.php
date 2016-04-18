<?php namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\NotificationBrowser;
use app\models\NotificationView;

class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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

    public function actionIndex()
    {
        $notificationsProvider = new ActiveDataProvider([
            'query' => NotificationBrowser::find()->where('recipient_id = :user_id OR recipient_id IS NULL', ['user_id' => Yii::$app->user->id]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->view->registerJsFile('js/main.js', ['depends' => [
                'app\assets\AppAsset',
        ]]);

        return $this->render('index', [
                'notificationsProvider' => $notificationsProvider
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
                'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionMark()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $notification = NotificationBrowser::findOne(Yii::$app->request->post('id'));
        if (!$notification) {
            return ['status' => 'not found'];
        }

        $view = new NotificationView();
        $view->notification_id = $notification->id;
        $view->user_id = Yii::$app->user->id;

        $notification->link('notificationViews', $view);

        return ['status' => 'ok'];
    }
}

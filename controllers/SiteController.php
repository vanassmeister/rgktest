<?php namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\NotificationBrowser;
use app\models\NotificationView;

class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'mark' => ['post'],
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
        ];
    }

    public function actionIndex()
    {
        $notificationsProvider = new ActiveDataProvider([
            'query' => NotificationBrowser::find()
                ->where('recipient_id = :user_id OR recipient_id IS NULL', ['user_id' => Yii::$app->user->id])
                ->orderBy('created_at DESC'),
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

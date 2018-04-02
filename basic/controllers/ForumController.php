<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\models\forum\Registry;
use app\models\forum\Login;
use app\models\forum\CreateGroup;
use app\models\forum\CreateTheme;
use app\models\forum\AddComment;
use app\models\forum\Comments;
use app\models\forum\ValidatePermission;

class ForumController extends Controller
{
    private $close = 1;
    private $open = 0;
    const EDIT_ACTION = 'edit';
    const DELETE_ACTION = 'delete';
    const ACCESS_SUBJECT = 'Comment';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
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
     * Logout action.
     *
     * @return Response|string
     */
    public function actionIndex()
    {
        return $this->render('index', $this->_getAllGroups());
    }

    /**
     * @return string
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function actionRegistry()
    {
        $model = new Registry();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->saveNewUser();

            return $this->render('login', ['model' => $model]);
        } else {
            return $this->render('registry', ['model' => $model]);
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Login();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->render('index', $this->_getAllGroups());
        }

        $model->pass = '';
        return $this->render('login', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response|string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->render('index', $this->_getAllGroups());
    }

    /**
     * Create group action.
     *
     * @return Response|string
     */
    public function actionCreateGroup()
    {
        $model = new CreateGroup();

        if ($model->load(Yii::$app->request->post())){

            $model->saveNewGroup();

            return $this->render('index', $this->_getAllGroups());
        }
        else {
            return $this->render('create-group', ['model' => $model]);
        }
    }

    /**
     * Create theme action.
     *
     * @return Response|string
     */
    public function actionCreateTheme()
    {
        $model = new CreateTheme();
        $g_id = Yii::$app->request->get('g_id');

        if ($model->load(Yii::$app->request->post())) {
            $model->saveNewTheme($g_id);

            return $this->render('themes-list', $this->_getThemes($g_id));
        } else {
            return $this->render('create-theme', ['model' => $model, 'g_id' => $g_id]);
        }
    }

    /**
     * Display themes action.
     *
     * @return Response|string
     */
    public function actionThemesList()
    {
        return $this->render('themes-list', $this->_getThemes(Yii::$app->request->get('g_id')));
    }

    /**
     * Add comment action.
     *
     * @return Response|string
     */
    public function actionAddComment()
    {
        $model = new AddComment();
        $theme_id = Yii::$app->request->get('id');

        if ($model->load(Yii::$app->request->post())) {
            $model->saveNewComment($theme_id);

            return $this->render('theme', $this->_getComments($theme_id));
        } else {
            return $this->render('add-comment', ['model' => $model, 'theme_id' => $theme_id]);
        }
    }

    /**
     * Selected theme display action.
     *
     * @return Response|string
     */
    public function actionTheme()
    {
        return $this->render('theme', $this->_getComments(Yii::$app->request->get('id')));
    }

    /**
     * Edit comment display action.
     *
     * @return Response|string
     */
    public function actionEditComment()
    {
        $model = new AddComment();

        if (Yii::$app->request->post()) {
            $theme_id = $model->updateComment(Yii::$app->request->get('c_id'),
                                              Yii::$app->request->post('AddComment')['content']);

            return $this->render('theme', $this->_getComments($theme_id));
        } else {
            return $this->render('edit-comment', ['model' => $model]);
        }
    }

    /**
     * @return string
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteComment()
    {
        $model = new AddComment();
        $theme_id = $model->deleteComment(Yii::$app->request->get('c_id'));

        return $this->render('theme', $this->_getComments($theme_id));
    }

    /**
     * Close theme action.
     *
     * @return Response|string
     */
    public function actionCloseTheme()
    {
        $model = new CreateTheme();

        $g_id = $model->themeStatus(Yii::$app->request->get('id'), $this->close);

        return $this->render('themes-list', $this->_getThemes($g_id));
    }

    /**
     * Open theme action.
     *
     * @return Response|string
     */
    public function actionOpenTheme()
    {
        $model = new CreateTheme();

        $g_id = $model->themeStatus(Yii::$app->request->get('id'), $this->open);

        return $this->render('themes-list', $this->_getThemes($g_id));
    }

    /**
     * Like/Dislike adding action.
     *
     * @return Response|string
     */
    public function actionChangeLikeStatus()
    {
        $model = new AddComment();

        $theme_id = $model->changeLikeStatus(Yii::$app->request->get('c_id'));

        return $this->render('theme', $this->_getComments($theme_id));
    }

    /**
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $comment = null;
        $error = false;

        if ($action->id === 'edit-comment' ||
            $action->id === 'delete-comment') {
            $comment = Comments::findOne(Yii::$app->request->get('c_id'));
        }

        if ($action->id === 'edit-comment' &&
            !ValidatePermission::selfOnlyAccessAction(self::EDIT_ACTION,self::ACCESS_SUBJECT, $comment)) {
            $error = true;
        }

        if ($action->id === 'delete-comment' &&
            !ValidatePermission::selfOnlyAccessAction(self::DELETE_ACTION,self::ACCESS_SUBJECT, $comment)) {
            $error = true;
        }

        if ($action->id === 'create-group' &&
            !\Yii::$app->user->can('addGroup')) {
            $error = true;
        }

        if ($action->id === 'open-theme' &&
            !\Yii::$app->user->can('openThemes')) {
            $error = true;
        }

        if ($action->id === 'close-theme' &&
            !\Yii::$app->user->can('closeThemes')) {
            $error = true;
        }

        if ($error) {
            throw new ForbiddenHttpException('Access denied!');
        }

        return parent::beforeAction($action);
    }

    // ---------------------------------------------------
    // Service functions                                 |
    // ---------------------------------------------------

    /**
     * @return array
     */
    private function _getAllGroups()
    {
        $model = new CreateGroup();

        return $model->getAllGroups();
    }

    /**
     * @param int $g_id
     * @return array
     */
    private function _getThemes($g_id = 0)
    {
        $model = new CreateTheme();

        return $model->getThemes($g_id);
    }

    /**
     * @param int $theme_id
     * @return array
     */
    private function _getComments($theme_id = 0)
    {
        $model = new AddComment();

        return $model->getComments($theme_id);
    }
}
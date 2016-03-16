<?php
namespace frontend\controllers;

use frontend\actions\CustomAction;
use Yii;
use common\models\LoginForm;
use common\models\EbayForm;
use frontend\models\SingleForm;
use frontend\models\OrderForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                       // 'roles' => ['?'],
                    ],
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

    /**
     * @inheritdoc
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
            'root' => [
                'class' => CustomAction::className(),
            ],
        ];
    }

    /** Рендерим наши страницы */

    /**
     * Лендинг (информация)
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * Выводим каталог товара
     *
     * @return mixed
     */
    public function actionItemslist() {
        $model = new EbayForm();
        $model->getCategories();
        return $this->render('itemslist', [
            'result' => false,
            'model' => false,
        ]);
    }

    public function actionGetItemsBy($category = null, $brand = null, $ser = null, $page, $sort, $queryText = " ") {

        $text ='';
        if(!empty($brand)){
            $text.=$brand.' ';
        }
        if(!empty($ser)){
            $text.=$ser.' ';
        }
        $text.=$queryText;
        $text = trim($text);
        $model = new EbayForm($category, $brand, $ser, (int)$page, $sort, $text);
        $model->queryTextShow = $queryText;
        $result = $model->getItems();
        return $this->render('itemslist', [
            'result' => $result,
            'model' => $model,
        ]);
    }

    public function actionGetItemByQuery($queryText, $page, $sort) {
        $model = new EbayForm(null, null, null, $page, $sort, $queryText);
        $result = $model->getItems();
        return $this->render('itemslist', [
            'result' => $result,
            'model' => $model,
        ]);
    }
    /**
     * Выводим просмотр подробностей о товаре
     *
     * @return mixed
     */
    public function actionSingle($ebayitemid)
    {
        $model = new EbayForm();
        $model->checkDataAboutSingleItem($ebayitemid);
        $result = $model->getSingleItem($ebayitemid);
        $images = $model->getItemImages($ebayitemid);
        return $this->render('single',[
            'result' => $result,
            'model' => $model,
            'images' => $images,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Выводим оформление товаров
     *
     * @return mixed
     */
    public function actionOrder()
    {
        $model = new OrderForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                //Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                return $this->render('success');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }
            return $this->refresh();
        } else {
            return $this->render('order', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}

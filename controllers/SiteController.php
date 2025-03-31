<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
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
            'request-password-reset' => [
            'class' => 'yii\web\ErrorAction',
        ],
        'reset-password' => [
            'class' => 'yii\web\ErrorAction',
        ],
        ];

        
    }

    public function actionRequestPasswordReset()
{
    $model = new \app\models\PasswordResetRequestForm();
    
    if ($model->load(Yii::$app->request->post())) {
        if ($model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Revise su correo electrónico.');
                return $this->goHome();
            }
        }
    }
    
    // PASA EXPLÍCITAMENTE EL MODELO A LA VISTA
    return $this->render('request-password-reset', [
        'model' => $model // ← ¡Esta línea es crucial!
    ]);
}

public function actionResetPassword($token)
{
    try {
        $model = new ResetPasswordForm($token);
    } catch (InvalidParamException $e) {
        throw new BadRequestHttpException($e->getMessage());
    }
    
    if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
        Yii::$app->session->setFlash('success', 'Nueva contraseña guardada correctamente.');
        return $this->goHome();
    }
    
    return $this->render('resetPassword', [
        'model' => $model,
    ]);
}

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
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

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionPruebaLogin()
{
    $correo = 'veamos@gmail.com'; // Reemplázalo con un correo real
    $password = '12345678'; // La contraseña real que quieres probar

    // Buscar el usuario en la BD
    $usuario = User::findOne(['correo' => $correo]);

    if (!$usuario) {
        return "Usuario no encontrado ❌";
    }

    // Ver los datos almacenados en la BD
    echo "Password en BD: " . $usuario->password . "<br>";
    echo "Salt en BD: " . $usuario->salt . "<br>";

    // Generar el hash de la contraseña ingresada
    $inputHash = hash('sha256', $password . $usuario->salt);
    echo "Hash generado en PHP: " . $inputHash . "<br>";

    // Comparar hashes
    if (hash_equals($inputHash, $usuario->password)) {
        return "Contraseña válida ✅";
    } else {
        return "Contraseña incorrecta ❌";
    }
}


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {   
        User::registrarSalida(Yii::$app->user->getId());
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
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

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionAdmin()
    {
        return $this->render('admin');
    }


    
}

<?php

namespace app\modules\admin\controllers;

use app\models\Product;
use app\modules\admin\models\ProductSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
{
    $model = new Product();

    if ($this->request->isPost) {
        if ($model->load($this->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->imageFile) {
                // Если файл выбран ОБРАБОТКА ОШИБКИ 
                if (!$model->upload()) {
                    Yii::$app->session->setFlash('error', 'Ошибка при загрузке файла.');// НЕ РАБОТАЕТ ОСТАВИТЬ
                    return $this->render('create', ['model' => $model]);
                }
            } else {
                // Если файл НЕ выбран, устанавливаем заглушку
                $model->image_url = '/uploads/products/default.jpg';
            }

            // Дата создания точно
            $model->created_at = date('Y-m-d H:i:s');

            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
    } else {
        $model->loadDefaultValues();
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}


    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */



    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->image_url;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->imageFile) 
            {
                // Загружен новый файл — обрабатываем загрузку
                if (!$model->upload()) 
                {
                    Yii::$app->session->setFlash('error', 'Ошибка при загрузке файла.');
                    return $this->render('update', ['model' => $model]);
                }
            } else {
                // Новый файл не вставили старые данные
                $model->image_url = $oldImage;
            }
            // Дата обновления точно
            $model->updated_at = date('Y-m-d H:i:s');

            if($model->save(false))
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace app\modules\admin\controllers;

use app\models\Service;
use app\modules\admin\models\ServiceSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServiceController implements the CRUD actions for Service model.
 */
class ServiceController extends Controller
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
     * Lists all Service models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ServiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Service model.
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
     * Creates a new Service model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // public function actionCreate()
    // {
    //     $model = new Service();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post()) && $model->save()) {
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    
public function actionCreate()
{
    $model = new \app\models\Service();
    if ($model->load(Yii::$app->request->post())) {
        $model->price = 0;
        if ($model->save()) {
            $totalPrice = 0;
            $productSelection = Yii::$app->request->post('Service')['product_selection'] ?? [];

            foreach ($productSelection as $productId => $selection) {
                $product = \app\models\Product::findOne($productId);

                if ($product) {
                    $quantity = (int)($selection['quantity'] ?? 1);

                    $serviceProduct = new \app\models\ServiceProduct();
                    $serviceProduct->service_id = $model->id;
                    $serviceProduct->product_id = $productId;
                    $serviceProduct->quantity = $quantity;
                    $serviceProduct->save(false);

                    $totalPrice += $product->price * $quantity;
                }
            }
            $workSelection = Yii::$app->request->post('Service')['work_selection'] ?? [];
            foreach ($workSelection as $workId) {
                $work = \app\models\Work::findOne($workId);

                if ($work) {
                    $serviceWork = new \app\models\ServiceWork();
                    $serviceWork->service_id = $model->id;
                    $serviceWork->work_id = $workId;
                    $serviceWork->save(false);
                    $totalPrice += $work->price;
                }
            }
            $model->created_at = date('Y-m-d H:i:s');
            $model->price = $totalPrice;
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }
    return $this->render('create', [
        'model' => $model,
    ]);
}







    /**
     * Updates an existing Service model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            // Очищаем старые связи перед обновлением
            \app\models\ServiceProduct::deleteAll(['service_id' => $model->id]);
            \app\models\ServiceWork::deleteAll(['service_id' => $model->id]);

            // Ставим временную цену
            $model->price = 0;

            if ($model->save()) {
                $totalPrice = 0;

                // Обработка выбранных товаров с количеством
                $productSelection = Yii::$app->request->post('Service')['product_selection'] ?? [];

                foreach ($productSelection as $productId => $selection) {
                    $product = \app\models\Product::findOne($productId);

                    if ($product) {
                        $quantity = (int)($selection['quantity'] ?? 1);

                        $serviceProduct = new \app\models\ServiceProduct();
                        $serviceProduct->service_id = $model->id;
                        $serviceProduct->product_id = $productId;
                        $serviceProduct->quantity = $quantity;
                        $serviceProduct->save(false);

                        $totalPrice += $product->price * $quantity;
                    }
                }

                // Обработка выбранных работ без количества
                $workSelection = Yii::$app->request->post('Service')['work_selection'] ?? [];

                foreach ($workSelection as $workId) {
                    $work = \app\models\Work::findOne($workId);

                    if ($work) {
                        $serviceWork = new \app\models\ServiceWork();
                        $serviceWork->service_id = $model->id;
                        $serviceWork->work_id = $workId;
                        $serviceWork->save(false);

                        $totalPrice += $work->price;
                    }
                }

                // Сохраняем итоговую цену
                $model->updated_at = date('Y-m-d H:i:s');
                $model->price = $totalPrice;
                $model->save(false);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Service model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    //ОШИБКА СВЯЗИ НЕЛЬЗЯ УДАЛИТЬ УСЛУГУ ТАК КАК ПРИВЯЗАНЫ РАБОТЫ И ТОВРЫ ЧЕРПЕЗ СМЕЖНУЮ ТАБЛИЦУ ДУМАЙ

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Удаляем все связанные записи
        \app\models\ServiceProduct::deleteAll(['service_id' => $model->id]);
        \app\models\ServiceWork::deleteAll(['service_id' => $model->id]);

        // Удаляем саму услугу
        $model->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Service::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

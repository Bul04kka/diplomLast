<?php

namespace app\modules\account\controllers;

use app\models\Service;
use app\modules\account\models\ServiceSearch;
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
    public function actionCreate()
    {
        $model = new Service();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

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


    ///////////////////////////////////////////
// public function actionRecommend()
// {
//     $recommendations = [
//         'curtains' => [
//             'product_id' => 10,
//             'work_id' => 15,
//             'unit_per_item' => 0.5,
//         ],
//         'lights' => [
//             'product_id' => 12,
//             'work_id' => 16,
//             'unit_per_item' => 0.2,
//         ],
//         // Добавь свои рекомендации дальше
//     ];

//     foreach ($recommendations as $key => $rec) {
//         if (Yii::$app->request->post($key . '_enabled')) {
//             $count = (int)Yii::$app->request->post($key . '_count', 1);
//             $unitCount = ceil($count * $rec['unit_per_item']);

//             // Товар
//             Yii::$app->runAction('cart/add', [
//                 'type' => 'product',
//                 'id' => $rec['product_id'],
//                 'quantity' => $unitCount,
//             ]);

//             // Работа
//             if (!empty($rec['work_id'])) {
//                 Yii::$app->runAction('cart/add', [
//                     'type' => 'work',
//                     'id' => $rec['work_id'],
//                     'quantity' => $unitCount,
//                 ]);
//             }
//         }
//     }

//     Yii::$app->session->setFlash('success', 'Рекомендации добавлены в корзину!');
//     return $this->redirect(['index']);
// }

}

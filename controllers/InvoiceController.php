<?php

namespace app\controllers;

use app\models\InvExtLotNumber;
use app\models\Products;
use app\models\ProductsToInvoice;
use Yii;

use app\models\Invoice;
use app\models\InvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Model;
/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Invoice;
        $modelsProducts = new Products() ; // наш товарчик
        //подключаем остальные модельки
        $modelsProductInvoice = [new ProductsToInvoice ]; // сюда пишем товары
        $modelsExt = [[new InvExtLotNumber ]]; //сюда пишем склады


        if ($model->load(Yii::$app->request->post())) {

            $modelsProductInvoice = Model::createMultiple(ProductsToInvoice::classname());
            Model::loadMultiple($modelsProductInvoice, Yii::$app->request->post());

       //валидируем
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsProductInvoice) && $valid;

            if (isset($_POST['InvExtLotNumber'][0][0])) {
                foreach ($_POST['InvExtLotNumber'] as $indexPrInv => $exts) {
                    foreach ($exts as $indexExt => $ext) {
                        $data['InvExtLotNumber'] = $ext;
                        $modelInvExt = new InvExtLotNumber;
                        $modelInvExt->load($data);
                        $modelsExt[$indexPrInv][$indexExt] = $modelInvExt;
                        $valid = $modelInvExt->validate();
                    }
                }
            }

            if ($valid) {   //если все гуд
                $transaction = Yii::$app->db->beginTransaction();  // запускаем транзакцию
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsProductInvoice as $indexInvProd => $modelInv) {

                            if ($flag === false) {
                                break;
                            }
                              //если инвойс сохранился
                            $modelInv->invoice_id = $model->invoice_id;
                            $modelInv->product_id = $_POST['Products']['products_id'];

                            if (!($flag = $modelInv->save(false))) {
                                break;
                            }
                                //записываем отношения по ключам
                            if (isset($modelsExt[$indexInvProd]) && is_array($modelsExt[$indexInvProd])) {
                                foreach ($modelsExt[$indexInvProd] as $indexExt => $modelExt) {
                                    $modelExt->prod_t_inv_id = $modelInv->pr_to_inv_id;
                                    $modelExt->inv_id = $model->invoice_id;
                                    if (!($flag = $modelExt->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($flag) {
                        //все ок комитим
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->invoice_id]);
                    } else {
                        // не все ок отктываем
                        $transaction->rollBack();
                    }
                } catch (Exception $e) {
                    //сразу ошибку в лицо
                    $transaction->rollBack();
                }
            }


        } else {
            return $this->render('create', [
                'model' => $model,
                'modelsExt' => (empty($modelsExt)) ? [new InvExtLotNumber] : $modelsExt,
                'modelsProducts' => (empty($modelsProducts)) ? [[new Products]] : $modelsProducts,
                'modelsProductsInvoice' => (empty($modelsProductInvoice)) ? [[new Products]] : $modelsProductInvoice,
            ]);
        }
    }

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->invoice_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    //получить подсчитать цену
    public function actionGet_price(){
        if (Yii::$app->request->isAjax){
            $model = Products::find()->where(['products_id' => $_POST['id']])->one();
            if($model){
                $total = $model->price * $_POST['count'];
            }else{
                $total = 0;
            }
            return $total;
        }

    }

    /**
     * Deletes an existing Invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

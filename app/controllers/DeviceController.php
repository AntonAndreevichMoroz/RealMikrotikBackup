<?php

namespace app\controllers;

use app\models\Device;
use app\models\DeviceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\httpclient\Client;

/**
 * DevicesController implements the CRUD actions for Devices model.
 */
class DeviceController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
        ];
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'backupone' => ['POST'],
                        'backupall' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Device models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeviceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Device model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Device model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Device();

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
     * Updates an existing Device model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Device model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionBackupone($id)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://n8n:5678/webhook/backupone')
            ->setData(['id' => $id])
            ->send();

        if ($response->isOk) {
            return $this->redirect(['view', 'id' => $id, 'resultn8n' => "OK"]);
        } else {
            return $this->redirect(['view', 'id' => $id, 'resultn8n' => "FAIL"]);
        }
    }

    public function actionBackupall()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://n8n:5678/webhook/backupall')
            ->send();

        if ($response->isOk) {
            return $this->redirect(['index', 'resultn8n' => "OK"]);
        } else {
            return $this->redirect(['index', 'resultn8n' => "FAIL"]);
        }
    }

    public function actionDownloadbin($id, $name)
    {
        $response = \Yii::$app->n8n->get('download', [ 'type' => 'bin', 'id' => $id, 'name' => $name ])->send();
        if ($response->isOk) {
            $content = $response->content;
            $filename = $id . '_' . $name . '_last.backup';
            return \Yii::$app->response->sendContentAsFile($content, $filename);
        } else {
            return $this->redirect(['view', 'id' => $id, 'downloaderror' => "true"]);
        }
    }

    public function actionDownloadrsc($id, $name)
    {
        $response = \Yii::$app->n8n->get('download', [ 'type' => 'rsc', 'id' => $id, 'name' => $name ])->send();
        if ($response->isOk) {
            $content = $response->content;
            $filename = $id . '_' . $name . '_last.rsc';
            return \Yii::$app->response->sendContentAsFile($content, $filename);
        } else {
            return $this->redirect(['view', 'id' => $id, 'downloaderror' => "true"]);
        }
    }

    /**
     * Finds the Device model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Devices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Device::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

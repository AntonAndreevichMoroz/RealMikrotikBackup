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
                        [
                            'allow' => true,
                            'actions' => ['passdecrypt'],
                            'roles' => ['?'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['getalldevices','getlaststatus'],
                            'matchCallback' => function () {
                                 $headers = \Yii::$app->request->headers;
                                 if ($headers->has('authkey')) {
			             return $headers->get('authkey') == $_ENV["ZABBIX_AUTHKEY"];
                                 } else {
                                     return false;
                                 };
			    },
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
            if (empty($_ENV["DATA_ENCRYPT_PASSWORD"])) {
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $model->loadDefaultValues();
                }
            } else {
                if ($model->load($this->request->post())) {
                    $model->password = base64_encode(\Yii::$app->getSecurity()->encryptByKey($model->password, $_ENV["DATA_ENCRYPT_PASSWORD"]));
                    if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $model->loadDefaultValues();
                }
            }
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

        if ($this->request->isPost) {
            if (empty($_ENV["DATA_ENCRYPT_PASSWORD"])) {
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                if ($model->load($this->request->post())) {
                    $model->password = base64_encode(\Yii::$app->getSecurity()->encryptByKey($model->password, $_ENV["DATA_ENCRYPT_PASSWORD"]));
                    if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
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

    public function actionPassdecrypt($encryptpass, $secretkey)
    {
        $password = \Yii::$app->getSecurity()->decryptByKey(base64_decode($encryptpass), $secretkey);
        return $password;
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

    public function actionGetalldevices()
    {
        $response = \Yii::$app->n8n->get('lld', [])->send();
        if ($response->isOk) {
            return $response->content;
        } else {
            return false;
        }
    }

    public function actionGetlaststatus($id)
    {
        $response = \Yii::$app->n8n->get('getstatus', ['id' => $id])->send();
        if ($response->isOk) {
            return $response->content;
        } else {
            return false;
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

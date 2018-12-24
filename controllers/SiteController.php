<?php
namespace app\controllers;

use app\components\TreeComponent;
use app\models\TreeItem;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    protected $treeComponent;

    public function __construct(string $id, Module $module, TreeComponent $treeComponent, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->treeComponent = $treeComponent;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'allow' => true,
                    ],
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'regenerate-tree' => ['POST'],
                    'delete-tree' => ['POST'],
                ],
            ]
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
        ];
    }

    public function actionRegenerateTree()
    {
        $this->treeComponent->deleteAll();
        $this->treeComponent->save([
            [
                'position' => '1.3.2',
                'title' => 'грунт, грунт замусоренный (техн.)',
                'value' => '223.10 ₽',
            ],
            [
                'position' => '1.2.1',
                'title' => 'до 10 км',
                'value' => '344.10 ₽',
            ],
            [
                'position' => '1',
                'title' => 'Земляные работы',
                'value' => '43.00 ₽',
            ],
            [
                'position' => '2.1.2',
                'title' => 'песок',
                'value' => '123.00 ₽',
            ],
            [
                'position' => '1.2.4',
                'title' => 'до 40 км',
                'value' => '1045.00 ₽',
            ],
            [
                'position' => '2.1.8',
                'title' => 'щебень фр.5-20',
                'value' => '6783.00 ₽',
            ],
            [
                'position' => '2.1.5',
                'title' => 'основание из щебня',
                'value' => '1345.00 ₽',
            ],
            [
                'position' => '1.2.2',
                'title' => 'до 20 км',
                'value' => '567.43 ₽',
            ],
            [
                'position' => '1.3.1',
                'title' => 'грунт, грунт замусоренный (экологич.чистый)',
                'value' => '654.40 ₽',
            ],
            [
                'position' => '2.1.4',
                'title' => 'ПГС',
                'value' => '735.00 ₽',
            ],
            [
                'position' => '2.1.7',
                'title' => 'щебень фр.20-40',
                'value' => '4112.00 ₽',
            ],
            [
                'position' => '2',
                'title' => 'Устройство основания ("пирога")',
                'value' => '534.50 ₽',
            ],
            [
                'position' => '1.2.3',
                'title' => 'до 30 км',
                'value' => '979.00 ₽',
            ],
            [
                'position' => '2.1.6',
                'title' => 'щебень фр.40-70',
                'value' => '983.00 ₽',
            ],
            [
                'position' => '1.2',
                'title' => 'Вывоз грунта',
                'value' => '3554.00 ₽',
            ],
            [
                'position' => '1.4',
                'title' => 'Засыпка',
                'value' => '7648.56 ₽',
            ],
            [
                'position' => '2.1.3',
                'title' => 'основание из ПГС',
                'value' => '7895.00 ₽',
            ],
            [
                'position' => '2.2',
                'title' => 'Устройство бетонной подготовки',
                'value' => '113.00 ₽',
            ],
            [
                'position' => '1.2.5',
                'title' => 'до 50 км',
                'value' => '1287.00 ₽',
            ],
            [
                'position' => '1.1',
                'title' => 'Разработка грунта комплексная (с учетом погрузки в автомобили-самосвалы, доработки вручную, планировки и т.п.), вывоз до 1 км',
                'value' => '4123.00 ₽',
            ],
            [
                'position' => '1.3',
                'title' => 'Утилизация грунта',
                'value' => '1000.00 ₽',
            ],
            [
                'position' => '2.1',
                'title' => 'Устройство основания',
                'value' => '1123.00 ₽',
            ],
        ]);

        $this->redirect('/');
    }

    public function actionDeleteTree()
    {
        $this->treeComponent->deleteAll();

        $this->redirect('/');
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'tree' => $this->treeComponent->getTree(),
        ]);
    }
}

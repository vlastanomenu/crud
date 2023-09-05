<?php

use App\Presenters\entities\CraEntity;
use App\Presenters\forms\CraForm;
use App\Presenters\service\CraService;
use Nette\Caching\Storages\FileStorage;
use Nette\Database\Connection;
use Nette\Database\Explorer;
use Nette\Database\IStructure;
use Nette\Database\Structure;
use Nette\Database\Table\ActiveRow;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

class CraServiceTest extends TestCase
{
    /** @var Connection */
    private $connection;

    /** @var IStructure */
    private $structure;

    /** @var CraService */
    private $craService;

    protected $fakeData;
    protected $fakeUpdateData;

    public function __construct(Connection $connection, IStructure $structure)
    {
        $this->connection = $connection;
        $this->structure = $structure;

        $this->fakeData = [
            CraForm::NAME => 'Test123',
            CraForm::DESCRIPTION => 'testovaci zaznam',
            CraForm::PUBLISH => true
        ];

        $this->fakeUpdateData  = [
            CraForm::NAME => 'Test123 Update',
            CraForm::DESCRIPTION => 'update testovaciho zaznamu',
            CraForm::PUBLISH => true
        ];
    }

    public function setUp()
    {
        $this->craService = new CraService(
            $this->connection,
            $this->structure
        );
    }

    public function testInsert()
    {
        $data = new CraEntity();
        $data->fromArray($this->fakeData);
        /** @var ActiveRow $insert */
        $insert = $this->craService->insert($data);

        $result = $this->craService->get($insert->id);
        Assert::notNull($result);
        Assert::equal($this->fakeData[CraForm::NAME], $result->getName());
    }

    public function testUpdate()
    {
        $data = new CraEntity();
        $data->fromArray($this->fakeData);
        /** @var ActiveRow $insert */
        $insert = $this->craService->insert($data);


        $updateData = new CraEntity();
        $updateData->fromArray($this->fakeUpdateData);
        $updateData->setId($insert->id);
        $this->craService->update($updateData);

        $result = $this->craService->get($insert->id);

        Assert::notNull($insert);
        Assert::equal($this->fakeUpdateData[CraForm::NAME], $result->getName());

    }

    public function testGetDataList()
    {
        $dataList = $this->craService->getDataList();
        /** @var CraEntity $data */
        $data = current($dataList);

        $result = $this->craService->get($data->getId());
        Assert::notNull($dataList);
        Assert::equal($data->getName(), $result->getName());

    }

    public function testDelete()
    {
        $dataList = $this->craService->getDataList();
        /** @var CraEntity $data */
        $data = end($dataList);

        $result = $this->craService->delete($data->getId());
        Assert::notNull($dataList);
        Assert::true($result);
    }
}

$configurator = App\Bootstrap::boot();
$configurator->addConfig(dirname(__DIR__).'/../../config/config.tests.neon');
/** @var Container $container */
$container = $configurator->createContainer();
$connection = $container->getByType(Connection::class);

// Spuštění testů
$testCase = new CraServiceTest($connection, new Structure($connection, new FileStorage(__DIR__ . '/../../temp')));
$testCase->run();

<?php

namespace App\Presenters\service;

use App\Presenters\entities\CraEntity;
use App\Presenters\forms\CraForm;
use Nette\Database\Connection;
use Nette\Database\Explorer;
use Nette\Database\IStructure;
use Nette\Database\Table\Selection;

class CraService
{
    const TABLE_NAME = 'cra';

    protected Connection $connection;
    private IStructure $structure;
    /** @var Explorer */
    private $database;

    public function __construct(
        Connection $connection,
        IStructure $structure
    )
    {
        $this->connection = $connection;
        $this->structure = $structure;
    }

    public function initDatabase()
    {
        $this->database = new Explorer(
            $this->connection,
            $this->structure
        );
    }

    public function get(?int $id = null): ?CraEntity
    {
        if (!$id) return null;

        $data = $this->getTable()
            ->get($id);

        if ($data)
        {
            return (new CraEntity())->fromArray($data->toArray());
        }
        else
        {
            return null;
        }
    }

    public function insert(CraEntity $craEntity)
    {
        return $this->getTable()
            ->insert($craEntity->toArray());
    }

    public function update(CraEntity $craEntity)
    {
        $this->getTable()
            ->get($craEntity->getId())
            ->update($craEntity->toArray());
    }

    public function delete(?int $id = null): bool
    {
        if (!$id) return false;

        $data = $this->getTable()
            ->get($id);
        if ($data)
        {
            $data->update(
                [
                    CraForm::PUBLISH => false
                ]
            );
            return true;
        }
        return false;
    }

    public function getDataList(): array
    {
        $result = [];

        foreach ($this->getTable()
                     ->where(
                         [
                             CraForm::PUBLISH => true
                         ]
                     ) as $item) {
            $result[] = (new CraEntity())->fromArray($item->toArray());
        }
        return $result;
    }

    private function getTable(): Selection
    {
        if (!$this->database)
        {
            $this->initDatabase();
        }
        return $this->database->table(self::TABLE_NAME);
    }
}
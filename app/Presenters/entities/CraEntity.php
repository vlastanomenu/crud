<?php

namespace App\Presenters\entities;

use Nette\Utils\DateTime;

class CraEntity extends BaseEntity
{
    protected ?int $id;
    protected ?string $name;
    protected ?string $description;
    protected ?DateTime $date_insert;
    protected ?DateTime $date_update;
    protected ?bool $publish;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return DateTime|null
     */
    public function getDateInsert(): ?DateTime
    {
        return $this->date_insert;
    }

    /**
     * @param DateTime|null $date_insert
     */
    public function setDateInsert(?DateTime $date_insert): void
    {
        $this->date_insert = $date_insert;
    }

    /**
     * @return DateTime|null
     */
    public function getDateUpdate(): ?DateTime
    {
        return $this->date_update;
    }

    /**
     * @param DateTime|null $date_update
     */
    public function setDateUpdate(?DateTime $date_update): void
    {
        $this->date_update = $date_update;
    }

    /**
     * @return bool|null
     */
    public function getPublish(): ?bool
    {
        return $this->publish;
    }

    /**
     * @param bool|null $publish
     */
    public function setPublish(?bool $publish): void
    {
        $this->publish = $publish;
    }

}
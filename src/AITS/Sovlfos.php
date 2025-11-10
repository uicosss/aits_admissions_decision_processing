<?php

namespace Uicosss\AITS;

class Sovlfos
{
    /**
     * @var string|null
     */
    private ?string $lfstCode = null;

    /**
     * @var string|null
     */
    private ?string $majrCode = null;

    /**
     * @var string|null
     */
    private ?string $deptCode = null;

    /**
     * @param $jsonObject
     * @return Sovlfos
     */
    public static function buildFromJson($jsonObject): Sovlfos
    {
        $sovlfos = new Sovlfos();

        $sovlfos->setLfstCode($jsonObject->lfstCode);
        $sovlfos->setMajrCode($jsonObject->majrCode);
        $sovlfos->setDeptCode($jsonObject->deptCode);

        return $sovlfos;
    }

    /**
     * @return string|null
     */
    public function getLfstCode(): ?string
    {
        return $this->lfstCode;
    }

    /**
     * @param string|null $lfstCode
     */
    public function setLfstCode(?string $lfstCode): void
    {
        $this->lfstCode = $lfstCode;
    }

    /**
     * @return string|null
     */
    public function getMajrCode(): ?string
    {
        return $this->majrCode;
    }

    /**
     * @param string|null $majrCode
     */
    public function setMajrCode(?string $majrCode): void
    {
        $this->majrCode = $majrCode;
    }

    /**
     * @return string|null
     */
    public function getDeptCode(): ?string
    {
        return $this->deptCode;
    }

    /**
     * @param string|null $deptCode
     */
    public function setDeptCode(?string $deptCode): void
    {
        $this->deptCode = $deptCode;
    }

}
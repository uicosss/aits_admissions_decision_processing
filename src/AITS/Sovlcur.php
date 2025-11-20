<?php

namespace Uicosss\AITS;

class Sovlcur
{
    /**
     * @var string|null
     */
    private ?string $termCodeCtlg = null;

    /**
     * @var string|null
     */
    private ?string $campCode = null;

    /**
     * @var int|null
     */
    private ?int $priorityNo = null;

    /**
     * @var string|null
     */
    private ?string $degcCode = null;

    /**
     * @var string|null
     */
    private ?string $program = null;

    /**
     * @var string|null
     */
    private ?string $collCode = null;

    /**
     * @var string|null
     */
    private ?string $levlCode = null;

    /**
     * @param $jsonObject
     * @return Sovlcur
     */
    public static function buildFromJson($jsonObject): Sovlcur
    {
        $sovlcur = new Sovlcur();

        $sovlcur->setTermCodeCtlg($jsonObject->termCodeCtlg);
        $sovlcur->setCampCode($jsonObject->campCode);
        $sovlcur->setPriorityNo($jsonObject->priorityNo);
        $sovlcur->setDegcCode($jsonObject->degcCode);
        $sovlcur->setProgram($jsonObject->program);
        $sovlcur->setCollCode($jsonObject->collCode);
        $sovlcur->setLevlCode($jsonObject->levlCode);

        return $sovlcur;
    }

    /**
     * @return string|null
     */
    public function getTermCodeCtlg(): ?string
    {
        return $this->termCodeCtlg;
    }

    /**
     * @param string|null $termCodeCtlg
     */
    public function setTermCodeCtlg(?string $termCodeCtlg): void
    {
        $this->termCodeCtlg = $termCodeCtlg;
    }

    /**
     * @return string|null
     */
    public function getCampCode(): ?string
    {
        return $this->campCode;
    }

    /**
     * @param string|null $campCode
     */
    public function setCampCode(?string $campCode): void
    {
        $this->campCode = $campCode;
    }

    /**
     * @return int|null
     */
    public function getPriorityNo(): ?int
    {
        return $this->priorityNo;
    }

    /**
     * @param mixed $priorityNo
     */
    public function setPriorityNo(mixed $priorityNo): void
    {
        $this->priorityNo = is_numeric($priorityNo) ? (int) $priorityNo : null;
    }

    /**
     * @return string|null
     */
    public function getDegcCode(): ?string
    {
        return $this->degcCode;
    }

    /**
     * @param string|null $degcCode
     */
    public function setDegcCode(?string $degcCode): void
    {
        $this->degcCode = $degcCode;
    }

    /**
     * @return string|null
     */
    public function getProgram(): ?string
    {
        return $this->program;
    }

    /**
     * @param string|null $program
     */
    public function setProgram(?string $program): void
    {
        $this->program = $program;
    }

    /**
     * @return string|null
     */
    public function getCollCode(): ?string
    {
        return $this->collCode;
    }

    /**
     * @param string|null $collCode
     */
    public function setCollCode(?string $collCode): void
    {
        $this->collCode = $collCode;
    }

    /**
     * @return string|null
     */
    public function getLevlCode(): ?string
    {
        return $this->levlCode;
    }

    /**
     * @param string|null $levlCode
     */
    public function setLevlCode(?string $levlCode): void
    {
        $this->levlCode = $levlCode;
    }

}
<?php

namespace Uicosss\AITS;

use Carbon\Carbon;

class Saradap
{
    /**
     * @var bool|null
     */
    private ?bool $reqDocInd = null;

    /**
     * @var string|null
     */
    private ?string $admtCode = null;

    /**
     * @var Carbon|null
     */
    private ?Carbon $applDate = null;

    /**
     * @var int|null
     */
    private ?int $applNo= null;

    /**
     * @var string|null
     */
    private ?string $apstCode = null;

    /**
     * @var string|null
     */
    private ?string $resdCode = null;

    /**
     * @var string|null
     */
    private ?string $stypCode = null;

    /**
     * @var int|null
     */
    private ?int $termCodeEntry = null;

    /**
     * @var int|null
     */
    private ?int $sarappdApdcCode = null;

    /**
     * @var string|null
     */
    private ?string $stvadmtDesc = null;

    /**
     * @var string|null
     */
    private ?string $stvapdcDesc = null;

    /**
     * @var string|null
     */
    private ?string $stvapstDesc = null;

    /**
     * @var string|null
     */
    private ?string $stvresdDesc = null;

    /**
     * @var string|null
     */
    private ?string $stvstypDesc = null;

    /**
     * @return bool|null
     */
    public function getReqDocInd(): ?bool
    {
        return $this->reqDocInd;
    }

    /**
     * @param $jsonObject
     * @return Saradap
     */
    public static function buildFromJson($jsonObject): Saradap
    {
        $saradap = new Saradap();

        $saradap->setReqDocInd($jsonObject->reqDocInd);
        $saradap->setAdmtCode($jsonObject->admtCode);
        $saradap->setApplDate($jsonObject->applDate);
        $saradap->setApplNo($jsonObject->applNo);
        $saradap->setApstCode($jsonObject->apstCode);
        $saradap->setResdCode($jsonObject->resdCode);
        $saradap->setStypCode($jsonObject->stypCode);
        $saradap->setTermCodeEntry($jsonObject->termCodeEntry);
        $saradap->setSarappdApdcCode($jsonObject->sarappdApdcCode);
        $saradap->setStvadmtDesc($jsonObject->stvadmtDesc);
        $saradap->setStvapdcDesc($jsonObject->stvapdcDesc);
        $saradap->setStvapstDesc($jsonObject->stvapstDesc);
        $saradap->setStvresdDesc($jsonObject->stvresdDesc);
        $saradap->setStvstypDesc($jsonObject->stvstypDesc);

        return $saradap;
    }

    /**
     * @param string|null $reqDocInd
     */
    public function setReqDocInd(?string $reqDocInd): void
    {
        $this->reqDocInd = $reqDocInd == 'Y';
    }

    /**
     * @return string|null
     */
    public function getAdmtCode(): ?string
    {
        return $this->admtCode;
    }

    /**
     * @param string|null $admtCode
     */
    public function setAdmtCode(?string $admtCode): void
    {
        $this->admtCode = $admtCode;
    }

    /**
     * @return Carbon|null
     */
    public function getApplDate(): ?Carbon
    {
        return $this->applDate;
    }

    /**
     * @param string|null $applDate
     */
    public function setApplDate(?string $applDate): void
    {
        $this->applDate = $applDate !== null ? Carbon::parse($applDate) : null;;
    }

    /**
     * @return int|null
     */
    public function getApplNo(): ?int
    {
        return $this->applNo;
    }

    /**
     * @param $applNo
     */
    public function setApplNo($applNo): void
    {
        $this->applNo = is_numeric($applNo) ? (int) $applNo : null;
    }

    /**
     * @return string|null
     */
    public function getApstCode(): ?string
    {
        return $this->apstCode;
    }

    /**
     * @param string|null $apstCode
     */
    public function setApstCode(?string $apstCode): void
    {
        $this->apstCode = $apstCode;
    }

    /**
     * @return string|null
     */
    public function getResdCode(): ?string
    {
        return $this->resdCode;
    }

    /**
     * @param string|null $resdCode
     */
    public function setResdCode(?string $resdCode): void
    {
        $this->resdCode = $resdCode;
    }

    /**
     * @return string|null
     */
    public function getStypCode(): ?string
    {
        return $this->stypCode;
    }

    /**
     * @param string|null $stypCode
     */
    public function setStypCode(?string $stypCode): void
    {
        $this->stypCode = $stypCode;
    }

    /**
     * @return int|null
     */
    public function getTermCodeEntry(): ?int
    {
        return $this->termCodeEntry;
    }

    /**
     * @param $termCodeEntry
     */
    public function setTermCodeEntry($termCodeEntry): void
    {
        $this->termCodeEntry = is_numeric($termCodeEntry) ? (int) $termCodeEntry : null;
    }

    /**
     * @return int|null
     */
    public function getSarappdApdcCode(): ?int
    {
        return $this->sarappdApdcCode;
    }

    /**
     * @param $sarappdApdcCode
     */
    public function setSarappdApdcCode($sarappdApdcCode): void
    {
        $this->sarappdApdcCode = is_numeric($sarappdApdcCode) ? (int) $sarappdApdcCode : null;
    }

    /**
     * @return string|null
     */
    public function getStvadmtDesc(): ?string
    {
        return $this->stvadmtDesc;
    }

    /**
     * @param string|null $stvadmtDesc
     */
    public function setStvadmtDesc(?string $stvadmtDesc): void
    {
        $this->stvadmtDesc = $stvadmtDesc;
    }

    /**
     * @return string|null
     */
    public function getStvapdcDesc(): ?string
    {
        return $this->stvapdcDesc;
    }

    /**
     * @param string|null $stvapdcDesc
     */
    public function setStvapdcDesc(?string $stvapdcDesc): void
    {
        $this->stvapdcDesc = $stvapdcDesc;
    }

    /**
     * @return string|null
     */
    public function getStvapstDesc(): ?string
    {
        return $this->stvapstDesc;
    }

    /**
     * @param string|null $stvapstDesc
     */
    public function setStvapstDesc(?string $stvapstDesc): void
    {
        $this->stvapstDesc = $stvapstDesc;
    }

    /**
     * @return string|null
     */
    public function getStvresdDesc(): ?string
    {
        return $this->stvresdDesc;
    }

    /**
     * @param string|null $stvresdDesc
     */
    public function setStvresdDesc(?string $stvresdDesc): void
    {
        $this->stvresdDesc = $stvresdDesc;
    }

    /**
     * @return string|null
     */
    public function getStvstypDesc(): ?string
    {
        return $this->stvstypDesc;
    }

    /**
     * @param string|null $stvstypDesc
     */
    public function setStvstypDesc(?string $stvstypDesc): void
    {
        $this->stvstypDesc = $stvstypDesc;
    }


}
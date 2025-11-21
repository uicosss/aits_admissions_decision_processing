<?php

namespace Uicosss\AITS;

use Carbon\Carbon;

class Sarappd
{
    /**
     * @var string|null
     */
    private ?string $maintDesc = null;

    /**
     * @var int|null
     */
    private ?int $apdcCode = null;

    /**
     * @var Carbon|null
     */
    private ?Carbon $apdcDate = null;

    /**
     * @var string|null
     */
    private ?string $maintInd = null;

    /**
     * @var string|null
     */
    private ?string $user = null;

    /**
     * @var bool|null
     */
    private ?bool $stvapdcApplInact = null;

    /**
     * @var string|null
     */
    private ?string $stvapdcDesc = null;

    /**
     * @var bool|null
     */
    private ?bool $stvapdcRejectInd = null;

    /**
     * @var bool|null
     */
    private ?bool $stvapdcSignfInd = null;

    /**
     * @param $jsonObject
     * @return Sarappd
     */
    public static function buildFromJson($jsonObject): Sarappd
    {
        $sarappd = new Sarappd();

        $sarappd->setMaintDesc($jsonObject->maintDesc);
        $sarappd->setApdcCode($jsonObject->apdcCode);
        $sarappd->setApdcDate($jsonObject->apdcDate);
        $sarappd->setMaintInd($jsonObject->maintInd);
        $sarappd->setUser($jsonObject->user);
        $sarappd->setStvapdcApplInact($jsonObject->stvapdcApplInact);
        $sarappd->setStvapdcDesc($jsonObject->stvapdcDesc);

        if (isset($jsonObject->stvapdcRejectInd)) {
            $sarappd->setStvapdcRejectInd($jsonObject->stvapdcRejectInd);
        }

        if (isset($jsonObject->stvapdcSignfInd)) {
            $sarappd->setStvapdcSignfInd($jsonObject->stvapdcSignfInd);
        }

        return $sarappd;
    }

    /**
     * @return string|null
     */
    public function getMaintDesc(): ?string
    {
        return $this->maintDesc;
    }

    /**
     * @param string|null $maintDesc
     */
    public function setMaintDesc(?string $maintDesc): void
    {
        $this->maintDesc = $maintDesc;
    }

    /**
     * @return int|null
     */
    public function getApdcCode(): ?int
    {
        return $this->apdcCode;
    }

    /**
     * @param string|null $apdcCode
     */
    public function setApdcCode(?string $apdcCode): void
    {
        $this->apdcCode = is_numeric($apdcCode) ? (int) $apdcCode : 0;
    }

    /**
     * @return Carbon|null
     */
    public function getApdcDate(): ?Carbon
    {
        return $this->apdcDate;
    }

    /**
     * @param string|null $apdcDate
     */
    public function setApdcDate(?string $apdcDate): void
    {
        $this->apdcDate = $apdcDate !== null ? Carbon::parse($apdcDate) : null;
    }

    /**
     * @return string|null
     */
    public function getMaintInd(): ?string
    {
        return $this->maintInd;
    }

    /**
     * @param string|null $maintInd
     */
    public function setMaintInd(?string $maintInd): void
    {
        $this->maintInd = $maintInd;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string|null $user
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return bool|null
     */
    public function getStvapdcApplInact(): ?bool
    {
        return $this->stvapdcApplInact;
    }

    /**
     * @param string|null $stvapdcApplInact
     */
    public function setStvapdcApplInact(?string $stvapdcApplInact): void
    {
        $this->stvapdcApplInact = $stvapdcApplInact == 'Y';
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
     * @return bool|null
     */
    public function getStvapdcRejectInd(): ?bool
    {
        return $this->stvapdcRejectInd;
    }

    /**
     * @param string|null $stvapdcRejectInd
     */
    public function setStvapdcRejectInd(?string $stvapdcRejectInd): void
    {
        $this->stvapdcRejectInd = $stvapdcRejectInd == 'Y';
    }

    /**
     * @return bool|null
     */
    public function getStvapdcSignfInd(): ?bool
    {
        return $this->stvapdcSignfInd;
    }

    /**
     * @param string|null $stvapdcSignfInd
     */
    public function setStvapdcSignfInd(?string $stvapdcSignfInd): void
    {
        $this->stvapdcSignfInd = $stvapdcSignfInd == 'Y';
    }


}
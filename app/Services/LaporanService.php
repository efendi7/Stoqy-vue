<?php

namespace App\Services;

use App\Repositories\LaporanRepository;

class LaporanService
{
    protected $laporanRepository;

    public function __construct(LaporanRepository $laporanRepository)
    {
        $this->laporanRepository = $laporanRepository;
    }

    public function getLaporanStok()
    {
        return $this->laporanRepository->getStokData();
    }

    public function getLaporanTransaksi()
    {
        return $this->laporanRepository->getTransaksiData();
    }

    public function getLaporanAktivitas()
    {
        return $this->laporanRepository->getAktivitasData();
    }
}

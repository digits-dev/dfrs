<?php

namespace App\Imports;

use App\Models\TradingPartner;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TradingPartnerImport implements ToModel, WithHeadingRow, WithChunkReading, SkipsOnError, WithValidation
{
    use Importable;


    public function model(array $row)
    {
        TradingPartner::updateOrCreate([
            'trading_partner' => trim(strtoupper($row['trading_partner'])),
            'status' => trim(strtoupper($row['status']))
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            'trading_partner' => ['required','unique:trading_partners,trading_partner']
        ];
    }

    public function onError(\Throwable $e)
    {
        \Log::error(json_encode($e));
    }
}

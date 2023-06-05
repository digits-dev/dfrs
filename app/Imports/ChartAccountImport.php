<?php

namespace App\Imports;

use App\Models\ChartAccount;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ChartAccountImport implements ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        ChartAccount::updateOrCreate([
            'fs' => trim(strtoupper($row['fs_type'])),
            'chart_account_types_id',
            'chart_account_subtypes_id',
            'chart_code' => trim($row['chart_account_code']),
            'chart_account' => trim(strtoupper($row['chart_account_description'])),
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
            '*.fs_type' => ['required','in:PNL,BS'],
            '*.chart_code' => ['required','numeric','unique:chart_accounts,chart_code'],
            '*.chart_account' => ['required'],
            '*.status' => ['required','in:ACTIVE,INACTIVE']
        ];
    }

    public function onError(\Throwable $e)
    {
        \Log::error(json_encode($e));
    }
}

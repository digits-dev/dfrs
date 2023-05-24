<?php

namespace App\Imports;

use App\Models\InterCompany;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class IntercoImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnError
{
    use Importable;

    public function model(array $row)
    {
        InterCompany::updateOrCreate([
            'inter_company_code' => trim($row['id']),
            'inter_company_name' => trim(strtoupper($row['interco'])),
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
            'id' => ['required','unique:inter_companies,inter_company_code'],
            'interco' => ['required'],
            'status' => ['required']
        ];
    }

    public function onError(\Throwable $e)
    {
        \Log::error(json_encode($e));
    }
}

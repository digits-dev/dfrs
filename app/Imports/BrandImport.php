<?php

namespace App\Imports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BrandImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnError
{
    use Importable;

    public function model(array $row)
    {
        Brand::updateOrCreate([
            'brand_code' => trim($row['id']),
            'brand_name' => trim(strtoupper($row['brand'])),
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
            'id' => ['required','unique:brands,brand_code'],
            'brand' => ['required'],
            'status' => ['required']
        ];
    }

    public function onError(\Throwable $e)
    {
        \Log::error(json_encode($e));
    }
}

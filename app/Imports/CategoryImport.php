<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoryImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnError
{
    use Importable;

    public function model(array $row)
    {
        Category::updateOrCreate([
            'category_code' => trim($row['id']),
            'category_name' => trim(strtoupper($row['category'])),
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
            'id' => ['required','numeric','unique:categories,category_code'],
            'category' => ['required'],
            'status' => ['required','in:ACTIVE,INACTIVE']
        ];
    }

    public function onError(\Throwable $e)
    {
        \Log::error(json_encode($e));
    }
}

<?php

namespace App\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LocationImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        Location::updateOrCreate([
            'location_code' => trim($row['id']),
            'location_name' => trim(strtoupper($row['location'])),
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
            '*.id' => ['required','numeric','unique:locations,location_code'],
            '*.location' => ['required'],
            '*.status' => ['required','in:ACTIVE,INACTIVE']
        ];
    }

    public function onError(\Throwable $e)
    {
        \Log::error(json_encode($e));
    }
}

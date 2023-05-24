<?php

namespace App\Imports;

use App\Models\Currency;
use App\Models\FinancialReport;
use App\Models\InvoiceStatus;
use App\Models\InvoiceType;
use App\Models\PaymentStatus;
use App\Models\TradingPartner;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JournalImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        FinancialReport::updateOrCreate([
            'invoice_date' => $row['invoice_date'],
            'invoice_number' => $row['invoice_number'],
            'invoice_types_id' => InvoiceType::withName($row['invoice_type'])->id,
            'voucher_number' => $row['voucher_number'],
            'trading_partners_id' => TradingPartner::withName($row['trading_partner'])->id,
            'invoice_statuses_id' => InvoiceStatus::withName($row['invoice_status'])->id,
            'payment_statuses_id' => PaymentStatus::withName($row['payment_status'])->id,
            'is_accounted' => $row['accounting'],
            'amount' => $row['amount'],
            'currencies_id' => Currency::withCode($row['currency'])->id,
            'exchange_rate' => $row['exchange_rate'],
            'exchange_date' => $row['exchange_date'],
            'invoice_amount' => $row['invoice_amount'],
            'po_number' => $row['po_number'],
            'gl_date' => $row['gl_date'],
            'description' => $row['description'],
            'chart_account' => $row['account']

        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            'invoice_type' => ['required'],
            'trading_partner' => ['required'],
            'invoice_status' => ['required'],
            'payment_status' => ['required'],
            'currency' => ['required']
        ];
    }

    public function onError(\Throwable $e)
    {
        \Log::error(json_encode($e));
    }
}

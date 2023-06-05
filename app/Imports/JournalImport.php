<?php

namespace App\Imports;

use App\Models\Currency;
use App\Models\FinancialReport;
use App\Models\InvoiceStatus;
use App\Models\InvoiceType;
use App\Models\PaymentStatus;
use App\Models\TradingPartner;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use CRUDBooster;

class JournalImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithValidation,
    WithEvents,
    ShouldQueue
{
    use Importable, RegistersEventListeners;

    private $invoiceTypes;
    private $tradingPartners;
    private $invoiceStatuses;
    private $paymentStatuses;
    private $currencies;
    private $accounts;

    public function __construct()
    {
        $this->invoiceTypes = InvoiceType::active();
        $this->tradingPartners = TradingPartner::active();
        $this->invoiceStatuses = InvoiceStatus::active();
        $this->paymentStatuses = PaymentStatus::active();
        $this->currencies = Currency::active();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $invoiceType = $this->invoiceTypes->where('invoice_type',$row['invoice_type'])->first();
        $tradingPartner = $this->tradingPartners->where('trading_partner',$row['trading_partner'])->first();
        $invoiceStatus = $this->invoiceStatuses->where('invoice_status',$row['invoice_status'])->first();
        $paymentStatus = $this->paymentStatuses->where('payment_status',$row['payment_status'])->first();
        $currency = $this->currencies->where('currency_code',$row['currency'])->first();
        $this->accounts = explode('.',$row['account']);

        FinancialReport::updateOrCreate([
            'invoice_date' => Carbon::parse($row['invoice_date'])->format('Y-m-d'),
            'invoice_number' => $row['invoice_number'],
            'invoice_types_id' => $invoiceType->id ?? NULL,
            'voucher_number' => $row['voucher_number'],
            'trading_partners_id' => $tradingPartner->id ?? NULL,
            'invoice_statuses_id' => $invoiceStatus->id ?? NULL,
            'payment_statuses_id' => $paymentStatus->id ?? NULL,
            'is_accounted' => $row['accounting'],
            'amount' => $row['amount'],
            'currencies_id' => $currency->id ?? NULL,
            'exchange_rate' => $row['exchange_rate'],
            'exchange_date' => Carbon::parse($row['exchange_date'])->format('Y-m-d'),
            'invoice_amount' => $row['invoice_amount'],
            'po_number' => $row['po_number'],
            'gl_date' => Carbon::parse($row['gl_date'])->format('Y-m-d'),
            'description' => trim(strtoupper($row['description'])),
            'chart_account' => $row['account'],
            'company' => $this->accounts[0],
            'location' => $this->accounts[1],
            'department' => $this->accounts[2],
            'account' => $this->accounts[3],
            'customer' => $this->accounts[4],
            'brand' => $this->accounts[5],
            'product' => $this->accounts[6],
            'interco' => $this->accounts[7]
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            '*.invoice_type' => ['required'],
            '*.trading_partner' => ['required'],
            '*.invoice_status' => ['required'],
            '*.payment_status' => ['required'],
            '*.currency' => ['required']
        ];
    }

    public function onError(\Throwable $e)
    {
        \Log::error(json_encode($e));
    }

    public static function afterImport(AfterImport $event)
    {
        $config['content'] = "Your import job is complete!";
        $config['to'] = CRUDBooster::adminPath('financial_reports');
        $config['id_cms_users'] = [1];
        CRUDBooster::sendNotification($config);
    }
}

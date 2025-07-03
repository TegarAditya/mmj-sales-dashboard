<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Invoice;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BillSummaryListTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 2;

    protected static bool $isDiscovered = false;

    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getListBillSummary())
            ->heading(null)
            ->columns([
                Tables\Columns\TextColumn::make('semester_name')
                    ->label('Semester')
                    ->sortable('semesters.id'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Nama Customer')
                    ->sortable('customers.name')
                    ->searchable('customers.name'),
                Tables\Columns\TextColumn::make('total_due')
                    ->label('Total Tagihan')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_discount')
                    ->label('Total Diskon')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_discounted_due')
                    ->label('Total Tagihan Diskon')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('return_amount')
                    ->label('Total Retur')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('bills')
                    ->label('Total Tagihan Setelah Retur')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_payments')
                    ->label('Total Pembayaran')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('remaining_bills')
                    ->label('Sisa Tagihan')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold')
                    ->alignCenter(),
            ]);
    }

    public function getListBillSummary()
    {
        $subqueryRetur = DB::table('return_goods')
            ->select(
                'customer_id',
                'semester_id',
                DB::raw('SUM(total_price) AS total_return_goods_amount')
            )
            ->groupBy('customer_id', 'semester_id');

        $subqueryPayments = DB::table('payments')
            ->select(
                'customer_id',
                'semester_id',
                DB::raw('SUM(amount) AS total_payments')
            )
            ->groupBy('customer_id', 'semester_id');

        return Invoice::query()
            ->leftJoinSub($subqueryRetur, 'return_goods_subquery', function ($join) {
                $join->on('invoices.customer_id', '=', 'return_goods_subquery.customer_id')
                    ->on('invoices.semester_id', '=', 'return_goods_subquery.semester_id');
            })
            ->leftJoinSub($subqueryPayments, 'payments_subquery', function ($join) {
                $join->on('invoices.customer_id', '=', 'payments_subquery.customer_id')
                    ->on('invoices.semester_id', '=', 'payments_subquery.semester_id');
            })
            ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin('semesters', 'invoices.semester_id', '=', 'semesters.id')
            ->select([
                'invoices.customer_id',
                'invoices.semester_id',
                'customers.name as customer_name',
                'semesters.name as semester_name',
                DB::raw('COALESCE(SUM(invoices.total_due), 0) AS total_due'),
                DB::raw('COALESCE(SUM(invoices.total_discount), 0) AS total_discount'),
                DB::raw('COALESCE(SUM(invoices.total_due), 0) - COALESCE(SUM(invoices.total_discount), 0) AS total_discounted_due'),
                DB::raw('COALESCE(return_goods_subquery.total_return_goods_amount, 0) AS return_amount'),
                DB::raw('COALESCE(SUM(invoices.total_due), 0) - COALESCE(SUM(invoices.total_discount), 0) - COALESCE(return_goods_subquery.total_return_goods_amount, 0) AS bills'),
                DB::raw('COALESCE(payments_subquery.total_payments, 0) AS total_payments'),
                DB::raw('COALESCE(SUM(invoices.total_due), 0) - COALESCE(SUM(invoices.total_discount), 0) - COALESCE(return_goods_subquery.total_return_goods_amount, 0) - COALESCE(payments_subquery.total_payments, 0) AS remaining_bills'),
            ])
            ->where('invoices.semester_id', '<=', $this->filters['semester_id'])
            ->groupBy(
                'invoices.customer_id',
                'invoices.semester_id',
                'customers.name',
                'semesters.name',
                'return_goods_subquery.total_return_goods_amount',
                'payments_subquery.total_payments'
            );
    }
}

<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Customer;
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
                    ->label('Invoice')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('total_discount')
                    ->label('Diskon')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('total_discounted_due')
                    ->label('Tagihan Diskon')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('return_amount')
                    ->label('Retur')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('bills')
                    ->label('Tagihan')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('total_payments')
                    ->label('Pembayaran')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('remaining_bills')
                    ->label('Sisa Tagihan')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight('bold'),
            ]);
    }

    public function getListBillSummary()
    {
        $subqueryInvoices = DB::table('invoices')
            ->select(
                'customer_id',
                'semester_id',
                DB::raw('SUM(total_due) AS total_due'),
                DB::raw('SUM(total_discount) AS total_discount')
            )
            ->whereNull('deleted_at')
            ->groupBy('customer_id', 'semester_id');

        $subqueryReturns = DB::table('return_goods')
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

        return Customer::query()
            ->crossJoin('semesters')
            ->where('semesters.id', '=', $this->filters['semester_id'])
            ->leftJoinSub($subqueryInvoices, 'inv', function ($join) {
                $join->on('customers.id', '=', 'inv.customer_id')
                    ->on('semesters.id', '=', 'inv.semester_id');
            })
            ->leftJoinSub($subqueryReturns, 'ret', function ($join) {
                $join->on('customers.id', '=', 'ret.customer_id')
                    ->on('semesters.id', '=', 'ret.semester_id');
            })
            ->leftJoinSub($subqueryPayments, 'pay', function ($join) {
                $join->on('customers.id', '=', 'pay.customer_id')
                    ->on('semesters.id', '=', 'pay.semester_id');
            })
            ->select([
                'customers.id as customer_id',
                'customers.name as customer_name',
                'semesters.id as semester_id',
                'semesters.name as semester_name',
                DB::raw('COALESCE(inv.total_due, 0) AS total_due'),
                DB::raw('COALESCE(inv.total_discount, 0) AS total_discount'),
                DB::raw('COALESCE(inv.total_due, 0) - COALESCE(inv.total_discount, 0) AS total_discounted_due'),
                DB::raw('COALESCE(ret.total_return_goods_amount, 0) AS return_amount'),
                DB::raw('COALESCE(inv.total_due, 0) - COALESCE(inv.total_discount, 0) - COALESCE(ret.total_return_goods_amount, 0) AS bills'),
                DB::raw('COALESCE(pay.total_payments, 0) AS total_payments'),
                DB::raw('COALESCE(inv.total_due, 0) - COALESCE(inv.total_discount, 0) - COALESCE(ret.total_return_goods_amount, 0) - COALESCE(pay.total_payments, 0) AS remaining_bills'),
            ])
            ->orderBy('customers.name')
            ->orderBy('semesters.id');
    }
}

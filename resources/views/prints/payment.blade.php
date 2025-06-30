@extends('layouts.print')

@section('header.left')
<h5>KWITANSI</h5>

<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 10cm">
    <tbody>
        <tr>
            <td width="120"><strong>No. Kwitansi</strong></td>
            <td width="8">:</td>
            <td>{{ $payment->document_number }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ Carbon\Carbon::parse($payment->date)->format('d-m-Y') }}</td>
        </tr>
    </tbody>
</table>
@stop

@section('content')
<div class="row">
    <div class="col-auto">
        <table cellpadding="0" cellspacing="0" class="mb-0">
            <tbody>
                <tr>
                    <td width="180">Telah diterima dari</td>
                    <td width="12">:</td>
                    <td class="px-0">{{ $payment->customer->name }}</td>
                </tr>

                <tr>
                    <td width="120">Sejumlah uang</td>
                    <td width="8">:</td>
                    <td class="px-0">{{ format_currency($payment->paid) }}</td>
                </tr>

                <tr>
                    <td width="120">Dari Tagihan Sejumlah </td>
                    <td width="8">:</td>
                    <td class="px-0">{{ format_currency($payment->amount) }} <em>(Diskon {{ $payment->discount }}%)</em></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col ml-5 align-self-end">
        <p class="mb-2 font-weight-bold small">Terbilang</p>

        <div class="d-flex align-items-center" style="border-radius:.5rem;border: 1px dashed #000;padding: .5rem 1rem; min-height: 3em">
            <p class="m-0 text-uppercase">{{ terbilang($payment->paid) }} RUPIAH</p>
        </div>

        @if ($payment->note)
            <p class="mt-2 mb-0 small">
                <em>Catatan: {{ $payment->note }}</em>
            </p>
        @endif
    </div>
</div>
@endsection

@section('footer')
<div class="row">
    <div class="col align-self-end">

    </div>

    <div class="col-auto text-center">
        <p class="mb-5">Penerima</p>
        <p class="mb-0">( _____________ )</p>
    </div>
</div>
@endsection

@push('styles')
<style type="text/css" media="print">
@page {
    size: landscape;
}
</style>
@endpush

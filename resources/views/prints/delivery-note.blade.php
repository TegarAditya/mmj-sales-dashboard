@extends('layouts.print')

@section('title')
Surat Jalan - {{ $delivery->document_number }}
@endsection

@section('header.center')
<h6>SURAT JALAN</h6>
@endsection

@section('header.left')
<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 8cm">
    <tbody>

        <tr>
            <td width="150"><strong>No. Surat Jalan</strong></td>
            <td width="8">:</td>
            <td>{{ $delivery->document_number }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ $date }}</td>
        </tr>

    </tbody>
</table>
@stop

@section('header.right')
<table cellspacing="0" cellpadding="0" class="text-sm align-top" style="width: 15cm">
    <tbody>
        <tr>
            <td style="vertical-align: top;"><strong>Customer</strong></td>
            <td style="vertical-align: top;"><span class="ml-1 mr-1">:</span></td>
            <td style="vertical-align: top;">{{ $delivery->customer->name }}</td>
        </tr>

        <tr>
            <td style="vertical-align: top;"><strong>Alamat</strong></td>
            <td style="vertical-align: top;"><span class="ml-1 mr-1">:</span></td>
            <td style="vertical-align: top; width: 100%">
                {{ $delivery->customer->address }}
            </td>
        </tr>
    </tbody>
</table>
@endsection

@section('content')
<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered" style="width: 100%">
    <thead>
        <th width="1%" class="text-center">No.</th>
        <th>Jenjang</th>
        <th>Penerbit</th>
        <th>Mata Pelajaran</th>
        <th width="1%" class="text-center">Kelas</th>
        <th width="1%" class="text-center">Hal</th>
        <th class="px-3" width="1%">Jumlah</th>
    </thead>

    <tbody>
        @foreach ($delivery->items as $item)
        <tr>
            <td class="px-3">{{ $loop->index + 1 }}.</td>
            <td>{{ $item->product->educationalLevel->name ?? '' }} - {{ $item->product->curriculum->name ?? '' }}</td>
            <td>{{ $item->product->publisher->name ?? '' }}</td>
            <td>{{ $item->product->educationalSubject->name }}</td>
            <td class="text-center">{{ $item->product->educationalClass->code ?? '' }}</td>
            <td class="text-center">{{ $item->product->page_count ?? '' }}</td>
            <td class="px-3 text-center">{{ $item->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" class="text-center"><strong>TOTAL</strong></th>
            <th class="text-center"><strong>{{ $total }}</strong></th>
        </tr>
    </tfoot>
</table>
@endsection

@section('footer')
<div class="row">
    <div class="col align-self-end">
        <p class="mb-2">Dikeluarkan oleh,</p>
        <p class="mb-0">Media Pressindo</p>
    </div>

    <div class="col-auto text-center">
        <p class="mb-5">Pengirim</p>
        <p class="mb-0">( _____________ )</p>
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
        size: portrait;
    }
</style>
@endpush
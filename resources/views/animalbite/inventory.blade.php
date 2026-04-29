@extends('layouts.app')

@push('styles')
<style>
    .inventory-header {
        color: #2953e8;
        letter-spacing: 2px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .table-inventory {
        border: 1px solid #e2e8f0;
    }
    .table-inventory th {
        background-color: #2953e8;
        color: white;
        font-size: 11px;
        text-transform: uppercase;
        border: 1px solid #1e40af;
        padding: 8px;
    }
    .category-row {
        background-color: #22d3ee;
        color: #164e63;
        font-weight: 800;
        font-size: 12px;
        text-align: center;
        text-transform: uppercase;
    }
    .table-inventory td {
        font-size: 13px;
        vertical-align: middle;
        border: 1px solid #e2e8f0;
        padding: 5px 10px;
    }
    .table-inventory input {
        border: none;
        background: transparent;
        text-align: center;
        width: 100%;
        font-weight: 600;
    }
    .table-inventory input:focus {
        outline: 2px solid #2953e8;
        background: white;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        padding: 12px 30px;
        border-bottom: 3px solid transparent;
    }
    .nav-tabs-custom .nav-link.active {
        color: #2953e8;
        border-bottom: 3px solid #2953e8;
        background: transparent;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                <h2 class="inventory-header mb-0">VACCINATION INVENTORY</h2>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-custom mb-4" id="inventoryTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="opening-tab" data-bs-toggle="tab" data-bs-target="#opening" type="button" role="tab">OPENING SHIFT</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="closing-tab" data-bs-toggle="tab" data-bs-target="#closing" type="button" role="tab">CLOSING SHIFT</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="endorsement-tab" data-bs-toggle="tab" data-bs-target="#endorsement" type="button" role="tab">ENDORSEMENT TO PM SHIFT</button>
                    </li>
                </ul>

                <div class="tab-content">
                    @php
                        $shifts = ['opening', 'closing', 'endorsement'];
                        $categories = [
                            'ACTIVE Anti-Rabies Vaccine' => ['Abhayrab', 'Speeda', 'Vaxirab N', 'Verorab'],
                            'PASSIVE Anti-Rabies Vaccine' => ['Vinrab (ERIG)', 'Equirab (ERIG)', 'Berirab (HRIG)'],
                            'Tetanus Toxoid' => ['Abhaytox'],
                            'Tetanus Immunoglobulin' => ['Serotet', 'Tetagam']
                        ];
                        $shiftData = [
                            'opening' => $opening,
                            'closing' => $closing,
                            'endorsement' => $endorsement
                        ];
                    @endphp

                    @foreach($shifts as $index => $shift)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ $shift }}" role="tabpanel">
                        <form action="{{ route('animal-bite.inventory.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="shift" value="{{ $shift }}">
                            <div class="table-responsive">
                                <table class="table table-inventory text-center">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%">VACCINE</th>
                                            <th style="width: 15%">QUANTITY</th>
                                            <th style="width: 20%">RECEIVED THRU DELIVERY</th>
                                            <th style="width: 25%">QTY TRANSFERRED TO ANOTHER BRANCH</th>
                                            <th style="width: 15%">USED</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $entryIdx = 0; @endphp
                                        @foreach($categories as $category => $vaccines)
                                            <tr class="category-row">
                                                <td colspan="5">{{ $category }}</td>
                                            </tr>
                                            @foreach($vaccines as $vIdx => $vaccine)
                                            @php
                                                $data = $shiftData[$shift];
                                                $entry = $data ? $data->entries->where('vaccine_name', $vaccine)->first() : null;
                                            @endphp
                                            <tr>
                                                <td class="text-start ps-4">
                                                    {{ $vIdx + 1 }}. {{ $vaccine }}
                                                    <input type="hidden" name="entries[{{ $entryIdx }}][vaccine_name]" value="{{ $vaccine }}">
                                                </td>
                                                <td><input type="number" name="entries[{{ $entryIdx }}][quantity]" value="{{ $entry->quantity ?? 0 }}"></td>
                                                <td><input type="text" name="entries[{{ $entryIdx }}][received]" value="{{ $entry->received ?? '' }}" placeholder="-"></td>
                                                <td><input type="number" name="entries[{{ $entryIdx }}][transferred]" value="{{ $entry->transferred ?? 0 }}"></td>
                                                <td><input type="number" name="entries[{{ $entryIdx }}][used]" value="{{ $entry->used ?? 0 }}"></td>
                                            </tr>
                                            @php $entryIdx++; @endphp
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold" style="border-radius: 10px;">SAVE {{ strtoupper($shift) }} DATA</button>
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.main')
@section('container')
<style>
    /* Reset & Document Style */
    .jsa-container {
        font-family: Arial, sans-serif;
        font-size: 10px; /* Ukuran font diperkecil agar mirip dokumen asli */
        color: #000;
        line-height: 1.2;
    }
    .table-jsa {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }
    .table-jsa th, .table-jsa td {
        border: 1px solid #000 !important;
        padding: 3px 5px !important;
        vertical-align: top;
    }
    .bg-grey { background-color: #e9ecef !important; font-weight: bold; }
    .text-center { text-align: center; }
    .align-middle { vertical-align: middle !important; }
    
    /* Header Styles */
    .header-title {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
    }
    
    /* Input & Checkbox Styles */
    .form-check-input {
        width: 15px;
        height: 15px;
        cursor: pointer;
        margin: 0;
    }
    .feedback-input {
        width: 100%;
        border: 1px solid #ccc;
        font-size: 9px;
        padding: 2px;
    }
    .no-border-top { border-top: none !important; }
</style>

<div class="card jsa-container">
    <div class="card-body">
        <form action="{{ route('dataJsaBaru.updateReview', $jsa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table-jsa">
                <tr>
                    <td rowspan="3" width="15%" class="text-center align-middle">
                        <img src="{{ asset('assets/img/LogoPPA.png') }}" width="70">
                    </td>
                    <td rowspan="3" width="55%" class="header-title align-middle">
                        FORMULIR JOB SAFETY ANALYSIS
                    </td>
                    <td width="12%" class="bg-grey">No.Dokumen</td>
                    <td width="18%">: PPA-ADRO-F-SHE-03B </td>
                </tr>
                <tr>
                    <td class="bg-grey">Revisi</td>
                    <td>: 2</td>
                </tr>
                <tr>
                    <td class="bg-grey">Tgl Efektif</td>
                    <td>: 6 September 2022</td>
                </tr>
            </table>

            <table class="table-jsa no-border-top">
                <tr>
                    <td width="15%" class="bg-grey">No. Pekerjaan/JSA</td>
                    <td width="35%">: {{ $jsa->no_jsa }}</td>
                    <td width="16%" class="text-center bg-grey">Dibuat Oleh</td>
                    <td width="17%" class="text-center bg-grey">Direview Oleh</td>
                    <td width="17%" class="text-center bg-grey">Disetujui Oleh</td>
                </tr>
                <tr>
                    <td class="bg-grey">Tanggal Pembuatan</td>
                    <td>: {{ \Carbon\Carbon::parse($jsa->tgl_pembuatan)->format('d F Y') }}</td>
                    <td rowspan="5" class="align-middle text-center" style="height: 60px;">
                        </td>
                    <td rowspan="5" class="align-middle text-center">
                        </td>
                    <td rowspan="5" class="align-middle text-center">
                        </td>
                </tr>
                <tr>
                    <td class="bg-grey">Nama Pekerjaan</td>
                    <td class="fw-bold">: {{ $jsa->nama_pekerjaan }} </td>
                </tr>
                <tr>
                    <td class="bg-grey">Departemen </td>
                    <td>: {{ $jsa->departemen }} </td>
                </tr>
                <tr>
                    <td class="bg-grey">Lokasi kerja</td>
                    <td>: {{ $jsa->lokasi_kerja }} </td>
                </tr>
                <tr>
                    <td class="bg-grey">APD yang digunakan</td>
                    <td>: {{ $jsa->apd_wajib }} </td>
                </tr>
                <tr>
                    <td class="bg-grey">Peralatan yang digunakan</td>
                    <td>: {{ $jsa->peralatan_pendukung }} </td>
                    <td class="small p-1">Nama : {{ $jsa->dibuat_oleh }} </td>
                    <td class="small p-1">Nama : {{ $jsa->direview_oleh }} </td>
                    <td class="small p-1">Nama : {{ $jsa->disetujui_oleh }} </td>
                </tr>
            </table>

            <table class="table-jsa mt-0">
                <thead class="bg-grey text-center align-middle">
                    <tr>
                        <th width="20%">Uraian Langkah Pekerjaan </th>
                        <th width="20%">Bahaya dan Risiko </th>
                        <th width="35%">Tindakan Pengendalian </th>
                        <th width="10%">Check (✓) </th>
                        <th width="15%">Feedback Reviewer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jsa->steps as $step)
                        @php 
                            $stepRowspan = $step->hazards->sum(function($h){ return $h->controls->count(); });
                            $firstStep = true; 
                        @endphp
                        @foreach($step->hazards as $hazard)
                            @php 
                                $hazardRowspan = $hazard->controls->count();
                                $firstHazard = true; 
                            @endphp
                            @foreach($hazard->controls as $control)
                                <tr>
                                    @if($firstStep)
                                        <td rowspan="{{ $stepRowspan }}">
                                            {{ $loop->parent->parent->iteration }} {{ $step->description }} 
                                        </td>
                                        @php $firstStep = false; @endphp
                                    @endif

                                    @if($firstHazard)
                                        <td rowspan="{{ $hazardRowspan }}">
                                            {{ $hazard->hazard_no }} {{ $hazard->hazard_description }} 
                                        </td>
                                        @php $firstHazard = false; @endphp
                                    @endif

                                    <td>
                                        {{ $control->control_no }} {{ $control->control_description }} 
                                    </td>
                                    
                                    <td class="text-center align-middle">
                                        <input type="hidden" name="reviews[{{ $control->id }}][approved]" value="0">
                                        <input type="checkbox" name="reviews[{{ $control->id }}][approved]" value="1" 
                                               class="form-check-input" {{ $control->approved ? 'checked' : '' }}>
                                    </td>

                                    <td class="align-middle">
                                        <textarea name="reviews[{{ $control->id }}][feedback]" 
                                                  class="feedback-input" rows="2" 
                                                  placeholder="Input catatan...">{{ $control->feedback }}</textarea>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            
                <div class="mt-3 text-end">
                    <p class="small text-danger italic">* Dokumen akan otomatis berstatus <strong>REJECTED</strong> jika ada salah satu item yang tidak dicentang.</p>
                    <a href="{{ route('dataJsaBaru.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan & Kirim Status Review</button>
                </div>
           
        </form>
    </div>
</div>
@endsection
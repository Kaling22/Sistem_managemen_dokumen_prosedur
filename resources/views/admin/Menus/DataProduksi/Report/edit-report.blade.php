@extends('layouts.main')

@section('container')

<style>
    input[readonly],
    textarea[readonly] {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }
</style>

<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Progress Report</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('dataReportProduksi.update', $report->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- No Dokumen --}}
                <div class="mb-3">
                    <label class="form-label">No Dokumen</label>
                    <input type="text"
                           class="form-control"
                           name="doc_number"
                           value="{{ $report->doc_number }}"
                           readonly>
                </div>

                {{-- Judul Dokumen --}}
                <div class="mb-3">
                    <label class="form-label">Judul Dokumen</label>
                    <input type="text"
                           class="form-control"
                           name="doc_name"
                           value="{{ $report->doc_name }}"
                           readonly>
                </div>

                {{-- Pelapor --}}
                <div class="mb-3">
                    <label class="form-label">Pelapor</label>
                    <input type="text"
                           class="form-control"
                           name="name"
                           value="{{ $report->name }}"
                           readonly>
                </div>

                {{-- NRP --}}
                <div class="mb-3">
                    <label class="form-label">NRP Pelapor</label>
                    <input type="text"
                           class="form-control"
                           name="nrp"
                           value="{{ $report->nrp }}"
                           readonly>
                </div>

                {{-- Laporan --}}
                <div class="mb-3">
                    <label class="form-label">Laporan</label>
                    <textarea class="form-control"
                              name="isi_report"
                              rows="3"
                              readonly>{{ $report->isi_report }}</textarea>
                </div>

                {{-- Feedback --}}
                <div class="mb-3">
                    <label class="form-label">Feedback</label>
                    <textarea class="form-control"
                              name="feedback"
                              rows="3"
                              required>{{ old('feedback', $report->feedback) }}</textarea>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-control" name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="open" {{ $report->status == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="progress" {{ $report->status == 'progress' ? 'selected' : '' }}>Progress</option>
                        <option value="closed" {{ $report->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

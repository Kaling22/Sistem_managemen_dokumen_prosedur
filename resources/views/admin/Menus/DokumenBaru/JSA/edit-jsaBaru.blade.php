@extends('layouts.main')
@section('container')
<style>
    input[readonly] { background-color: #f5f5f5; cursor: not-allowed; }
    .step-content { display: none; } 
    .step-content.active { display: block; } 
    .form-switch .form-check-input { width: 2.5em; height: 1.25em; cursor: pointer; }
    .jsa-row { border: 1px solid #e4e6e8; padding: 15px; margin-bottom: 15px; border-radius: 8px; background: #fff; }
</style>

<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">EDIT JSA - <span id="step-title" class="text-primary">Langkah 1 (Informasi Umum)</span></h5>
            <span class="badge bg-label-danger">Status: {{ strtoupper($jsa->direview_oleh_approve) }}</span>
        </div>
        <div class="card-body">
            <form action="{{ route('dataJsaBaru.update', $jsa->id) }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
                @csrf
                @method('PUT')
                
                <div class="step-content active" id="step-1">
                    <div class="mb-3">
                        <label class="form-label">No Dokumen</label>
                        <input type="text" class="form-control" name="no_dokumen" id="no_dokumen" value="{{ $jsa->no_dokumen }}" readonly required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Pekerjaan (JSA)</label>
                        <input type="text" class="form-control" name="judul" value="{{ $jsa->nama_pekerjaan }}" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Departemen</label>
                        <input type="text" class="form-control" name="departemen" value="{{ $jsa->departemen }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Kerja</label>
                        <input type="text" class="form-control" name="lokasi_kerja" value="{{ $jsa->lokasi_kerja }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">APD</label>
                        <input type="text" class="form-control" name="apd_wajib" value="{{ $jsa->apd_wajib }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tools</label>
                        <input type="text" class="form-control" name="peralatan_pendukung" value="{{ $jsa->peralatan_pendukung }}" required>
                    </div>

                    <div class="row border-top pt-3">
                        <h6 class="fw-bold">Verifikasi & Approval</h6>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Reviewer</label>
                            <select class="form-select" name="direview_oleh" id="select-reviewer" required>
                                <option value="{{ $jsa->direview_oleh }}">{{ $jsa->direview_oleh }} (Saat ini)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">DH / SH</label>
                            <select class="form-select" name="disetujui_oleh" id="select-dhsh" required>
                                <option value="{{ $jsa->disetujui_oleh }}">{{ $jsa->disetujui_oleh }} (Saat ini)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="step-content" id="step-2">
                    <div id="jsa-container">
                        @foreach($jsa->steps as $sIndex => $step)
                        @php $sCount = $loop->iteration; @endphp
                        <div class="jsa-langkah-card card mb-4 border shadow-none" data-langkah="{{ $sCount }}">
                            <div class="card-header bg-light d-flex justify-content-between py-2">
                                <span class="fw-bold">Langkah Kerja #{{ $sCount }}</span>
                                <button type="button" class="btn btn-sm btn-label-danger" onclick="removeLangkah(this)">Hapus Langkah</button>
                            </div>
                            <div class="card-body pt-3">
                                <textarea class="form-control mb-3" name="jsa[{{ $sCount }}][langkah]" required>{{ $step->description }}</textarea>
                                
                                <div class="bahaya-container ms-4">
                                    @foreach($step->hazards as $hIndex => $hazard)
                                    @php $hCount = $loop->iteration; @endphp
                                    <div class="bahaya-row border-start ps-3 mb-3" data-bahaya="{{ $hCount }}">
                                        <div class="d-flex gap-2 mb-2">
                                            <input type="text" class="form-control form-control-sm border-danger" name="jsa[{{ $sCount }}][bahaya][{{ $hCount }}][teks]" value="{{ $hazard->hazard_description }}" required>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.bahaya-row').remove()">×</button>
                                        </div>

                                        <div class="pengendalian-container ms-3">
                                            @foreach($hazard->controls as $control)
                                            <div class="d-flex gap-2 mb-1">
                                                <input type="text" class="form-control form-control-sm border-success" name="jsa[{{ $sCount }}][bahaya][{{ $hCount }}][pengendalian][]" value="{{ $control->control_description }}" required>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">-</button>
                                            </div>
                                            @endforeach
                                            <button type="button" class="btn btn-xs btn-outline-success mt-1" onclick="addPengendalian(this)">+ Tambah Pengendalian</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-primary ms-4" onclick="addBahaya(this)">+ Tambah Bahaya</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary w-100" onclick="addLangkah()">+ Tambah Langkah Kerja Baru</button>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" id="prevBtn" style="display:none;" onclick="changeStep(-1)">Kembali</button>
                    <div>
                        <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">Lanjut ke Analisa</button>
                        <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">Simpan & Ajukan Ulang JSA</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 2;
    // Inisialisasi count berdasarkan jumlah data yang ada
    let langkahCount = {{ $jsa->steps->count() }};

    document.addEventListener('DOMContentLoaded', function() {
        const selectReviewer = document.getElementById('select-reviewer');
        const selectDHSH = document.getElementById('select-dhsh');

        fetch('/api/get-approvers')
            .then(response => response.json())
            .then(data => {
                data.jsa_reviewers.forEach(user => {
                    if(user.nama !== "{{ $jsa->direview_oleh }}") {
                        selectReviewer.add(new Option(`${user.nama} (${user.nrp}) - ${user.departemen}`, user.nama));
                    }
                });
                data.dhsh.forEach(user => {
                    if(user.nama !== "{{ $jsa->disetujui_oleh }}") {
                        selectDHSH.add(new Option(`${user.nama} (${user.nrp})`, user.nama));
                    }
                });
            });
    });

    // Gunakan fungsi addLangkah, addBahaya, addPengendalian, changeStep 
    // yang sama dengan halaman Create Anda (sudah mendukung penomoran dinamis).
    
    function addLangkah() {
        langkahCount++;
        const container = document.getElementById('jsa-container');
        const html = `
            <div class="jsa-langkah-card card mb-4 border shadow-none" data-langkah="${langkahCount}">
                <div class="card-header bg-light d-flex justify-content-between py-2">
                    <span class="fw-bold">Langkah Kerja #${langkahCount}</span>
                    <button type="button" class="btn btn-sm btn-label-danger" onclick="removeLangkah(this)">Hapus Langkah</button>
                </div>
                <div class="card-body pt-3">
                    <textarea class="form-control mb-3" name="jsa[${langkahCount}][langkah]" placeholder="Deskripsi Langkah Kerja..." required></textarea>
                    <div class="bahaya-container ms-4"></div>
                    <button type="button" class="btn btn-xs btn-outline-primary ms-4" onclick="addBahaya(this)">+ Tambah Bahaya</button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        addBahaya(container.lastElementChild.querySelector('.btn-outline-primary'));
    }

    function addBahaya(btn) {
        const card = btn.closest('.jsa-langkah-card');
        const LIndex = card.dataset.langkah;
        const container = card.querySelector('.bahaya-container');
        const BIndex = container.children.length + 1;

        const html = `
            <div class="bahaya-row border-start ps-3 mb-3" data-bahaya="${BIndex}">
                <div class="d-flex gap-2 mb-2">
                    <input type="text" class="form-control form-control-sm border-danger" name="jsa[${LIndex}][bahaya][${BIndex}][teks]" placeholder="Potensi Bahaya..." required>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.bahaya-row').remove()">×</button>
                </div>
                <div class="pengendalian-container ms-3">
                    <div class="d-flex gap-2 mb-1">
                        <input type="text" class="form-control form-control-sm border-success" name="jsa[${LIndex}][bahaya][${BIndex}][pengendalian][]" placeholder="Tindakan Pengendalian..." required>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="addPengendalian(this)">+</button>
                    </div>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
    }

    function addPengendalian(btn) {
        const row = btn.closest('.bahaya-row');
        const card = btn.closest('.jsa-langkah-card');
        const LIndex = card.dataset.langkah;
        const BIndex = row.dataset.bahaya;
        const container = btn.closest('.pengendalian-container');

        const html = `
            <div class="d-flex gap-2 mb-1">
                <input type="text" class="form-control form-control-sm border-success" name="jsa[${LIndex}][bahaya][${BIndex}][pengendalian][]" placeholder="Tindakan Pengendalian..." required>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">-</button>
            </div>`;
        // Masukkan sebelum tombol "+"
        btn.insertAdjacentHTML('beforebegin', html);
    }

    function removeLangkah(btn) { btn.closest('.jsa-langkah-card').remove(); }

    function changeStep(step) {
        if (step === 1 && !validateStep(currentStep)) return;
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        currentStep += step;
        document.getElementById(`step-${currentStep}`).classList.add('active');
        document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'inline-block';
        document.getElementById('nextBtn').style.display = currentStep === totalSteps ? 'none' : 'inline-block';
        document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'inline-block' : 'none';
        document.getElementById('step-title').innerText = currentStep === 1 ? "Langkah 1 (Informasi Umum)" : "Langkah 2 (Analisa Bahaya)";
    }

    function validateStep(step) {
        const activeStep = document.getElementById(`step-${step}`);
        const inputs = activeStep.querySelectorAll('[required]');
        let isValid = true;
        inputs.forEach(input => {
            if (input.value.trim() === "") {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        return isValid;
    }
</script>
@endsection
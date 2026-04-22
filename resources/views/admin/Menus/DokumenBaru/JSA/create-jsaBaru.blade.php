@extends ('layouts.main')
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
            <h5 class="mb-0">BUAT JSA BARU - <span id="step-title" class="text-primary">Langkah 1 (Informasi Umum)</span></h5>
        </div>
        <div class="card-body">
            <form action="{{route('dataJsaBaru.store')}}" method="POST" enctype="multipart/form-data" id="multiStepForm">
                @csrf
                
                <div class="step-content active" id="step-1">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0">No Dokumen</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="manualSwitch">
                                <label class="form-check-label" for="manualSwitch" style="font-size: 0.75rem;">Input Manual</label>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="no_dokumen" id="no_dokumen" value="{{ $autoNumber }}" readonly required/>
                        <div id="no-doc-hint" class="form-text">Format: Otomatis Sistem</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Pekerjaan (JSA)</label>
                        <input type="text" class="form-control" name="judul" placeholder="Contoh: Perbaikan Jaringan Kabel FO" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Departemen</label>
                        <input type="text" class="form-control" name="departemen" value="{{ Auth::user()->departemen }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pembuat</label>
                        <input type="text" class="form-control" name="dibuat_oleh" value="{{ Auth::user()->nama }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi Kerja</label>
                        <input type="text" class="form-control" name="lokasi_kerja" placeholder="Contoh: View Point" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">APD</label>
                        <input type="text" class="form-control" name="apd_wajib" placeholder="Contoh: Standar PPE" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tools</label>
                        <input type="text" class="form-control" name="peralatan_pendukung" placeholder="Contoh: Obeng, Bor" required>
                    </div>

                    <div class="row border-top pt-3">
                        <h6 class="fw-bold">Verifikasi & Approval</h6>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Reviewer (Pilih Staf/Admin)</label>
                            <select class="form-select" name="direview_oleh" id="select-reviewer" required>
                                <option value="">Memuat data...</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">DH / SH (Persetujuan)</label>
                            <select class="form-select" name="disetujui_oleh" id="select-dhsh" required>
                                <option value="">Memuat data...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="step-content" id="step-2">
                    <div class="alert alert-info py-2" style="font-size: 0.85rem;">
                        <i class="bx bx-info-circle me-1"></i> Masukkan tahapan pekerjaan, potensi bahaya yang mungkin timbul, dan langkah pengendaliannya.
                    </div>

                    <div id="jsa-container">
                    <div class="jsa-langkah-card card mb-4 border shadow-none" data-langkah="1">
                        <div class="card-header bg-light d-flex justify-content-between py-2">
                            <span class="fw-bold">Langkah Kerja #1</span>
                            <button type="button" class="btn btn-sm btn-label-danger" onclick="removeLangkah(this)">Hapus Langkah</button>
                        </div>
                        <div class="card-body pt-3">
                            <textarea class="form-control mb-3" name="jsa[1][langkah]" placeholder="Deskripsi Langkah Kerja..." required></textarea>
                            
                            <div class="bahaya-container ms-4">
                                <div class="bahaya-row border-start ps-3 mb-3" data-bahaya="1">
                                    <div class="d-flex gap-2 mb-2">
                                        <input type="text" class="form-control form-control-sm border-danger" name="jsa[1][bahaya][1][teks]" placeholder="Potensi Bahaya..." required>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBahaya(this)">×</button>
                                    </div>

                                    <div class="pengendalian-container ms-3">
                                        <div class="d-flex gap-2 mb-1">
                                            <input type="text" class="form-control form-control-sm border-success" name="jsa[1][bahaya][1][pengendalian][]" placeholder="Tindakan Pengendalian..." required>
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="addPengendalian(this)">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-xs btn-outline-primary ms-4" onclick="addBahaya(this)">+ Tambah Bahaya</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary w-100" onclick="addLangkah()">+ Tambah Langkah Kerja Baru</button>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" id="prevBtn" style="display:none;" onclick="changeStep(-1)">Kembali</button>
                    <div>
                        <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">Lanjut ke Analisa</button>
                        <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">Simpan & Ajukan JSA</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 2;
    const autoDocValue = "{{ $autoNumber }}";

    // Data Approvers
    document.addEventListener('DOMContentLoaded', function() {
        const selectReviewer = document.getElementById('select-reviewer');
        const selectDHSH = document.getElementById('select-dhsh');

        fetch('/api/get-approvers')
            .then(response => response.json())
            .then(data => {
                // Isi Dropdown Reviewer (Biasanya Staf/Admin Role 0,1,5)
                selectReviewer.innerHTML = '<option value="">-- Pilih Reviewer --</option>';
                data.all_users.forEach(user => {
                    selectReviewer.add(new Option(`${user.nama} (${user.nrp})`, user.nama));
                });

                // Isi Dropdown DH/SH (Role 3 & 4)
                selectDHSH.innerHTML = '<option value="">-- Pilih DH/SH --</option>';
                data.dhsh.forEach(user => {
                    selectDHSH.add(new Option(`${user.nama} (${user.nrp})`, user.nama));
                });
            });
    });

    // Tambah Baris JSA Dinamis
    let langkahCount = 1;

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
        // Tambahkan bahaya pertama otomatis
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
        container.insertAdjacentHTML('beforeend', html);
    }

    function removeLangkah(btn) { btn.closest('.jsa-langkah-card').remove(); }

    // Navigasi Step
    function changeStep(step) {
        if (step === 1 && !validateStep(currentStep)) return;

        document.getElementById(`step-${currentStep}`).classList.remove('active');
        currentStep += step;
        document.getElementById(`step-${currentStep}`).classList.add('active');

        // Update UI
        document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'inline-block';
        document.getElementById('nextBtn').style.display = currentStep === totalSteps ? 'none' : 'inline-block';
        document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'inline-block' : 'none';
        
        const title = currentStep === 1 ? "Langkah 1 (Informasi Umum)" : "Langkah 2 (Analisa Bahaya)";
        document.getElementById('step-title').innerText = title;
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
        if (!isValid) alert("Lengkapi data yang wajib diisi!");
        return isValid;
    }

    // Switch No Dokumen
    document.getElementById('manualSwitch').addEventListener('change', function() {
        const inputNoDoc = document.getElementById('no_dokumen');
        inputNoDoc.readOnly = !this.checked;
        if (!this.checked) inputNoDoc.value = autoDocValue;
    });
</script>
@endsection
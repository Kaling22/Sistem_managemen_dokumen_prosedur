@extends ('layouts.main')
@section('container')
<style>
    input[readonly] { background-color: #f5f5f5; cursor: not-allowed; }
    .step-content { display: none; }
    .step-content.active { display: block; }
    .form-switch .form-check-input { width: 2.5em; height: 1.25em; cursor: pointer; }
</style>

<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">EDIT DOKUMEN - <span id="step-title">Langkah 1</span></h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dataSopBaru.update', $sop->id) }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
                @csrf
                @method('PUT')
                
                <div class="step-content active" id="step-1">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0">No Dokumen</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="manualSwitch">
                                <label class="form-check-label" for="manualSwitch" style="font-size: 0.75rem;">Input Manual</label>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="no_dokumen" id="no_dokumen" value="{{ $sop->no_dokumen }}" readonly required/>
                        <div id="no-doc-hint" class="form-text">Format: Otomatis Sistem</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Dokumen</label>
                        <input type="text" class="form-control" name="judul" value="{{ $sop->judul }}" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Dokumen</label>
                        <input type="text" class="form-control" name="jenis_doc" readonly value="{{ $sop->jenis_doc }}"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Departemen</label>
                        <input type="text" class="form-control" name="departemen" value="{{ $sop->departemen }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pembuat</label>
                        <input type="text" class="form-control" name="pembuat" value="{{ $sop->pembuat }}" readonly>
                    </div>
                </div>

                <div class="step-content" id="step-2">
                    <div class="mb-3">
                        <label class="form-label">Tujuan</label>
                        <textarea class="form-control" name="tujuan" rows="3">{{ $sop->tujuan }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruang lingkup</label>
                        <textarea class="form-control" name="ruang_lingkup" rows="3">{{ $sop->ruang_lingkup }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Referensi</label>
                        <div id="referensi-container">
                            @foreach(explode("\n", $sop->referensi) as $ref)
                                @if(trim($ref) != "")
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control ref-input-referensi" value="{{ trim($ref) }}">
                                    <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <button class="btn btn-outline-primary btn-sm" type="button" onclick="addRow('referensi')">+</button>
                        <input type="hidden" name="referensi" id="referensi-final">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Definisi</label>
                        <div id="definisi-container">
                            @foreach(explode("\n", $sop->definisi) as $def)
                                @if(trim($def) != "")
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control ref-input-definisi" value="{{ trim($def) }}">
                                    <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <button class="btn btn-outline-primary btn-sm" type="button" onclick="addRow('definisi')">+</button>
                        <input type="hidden" name="definisi" id="definisi-final">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Aktifitas dan Tanggung Jawab</label>
                        <div id="aktivitas-container">
                            @php
                                $items = explode('[END]', $sop->aktifitas_tanggung_jawab);
                            @endphp
                            @foreach($items as $item)
                                @if(trim($item) != "")
                                    @php
                                        // Memisahkan Judul, Isi dan PIC sesuai format simpan Anda
                                        $judulPart = explode('**', $item);
                                        $judul = isset($judulPart[1]) ? $judulPart[1] : '';
                                        $sisa = isset($judulPart[2]) ? $judulPart[2] : '';
                                        $picPart = explode('[PIC]', $sisa);
                                        $isi = isset($picPart[0]) ? trim($picPart[0]) : '';
                                        $pic = isset($picPart[1]) ? trim($picPart[1]) : '';
                                    @endphp
                                    <div class="aktivitas-item border p-3 mb-3 rounded bg-light">
                                        <div class="mb-2"><input type="text" class="form-control aktivitas-judul" value="{{ $judul }}" placeholder="Sub Judul"></div>
                                        <div class="mb-2"><textarea class="form-control aktivitas-isi" rows="3">{{ $isi }}</textarea></div>
                                        <div class="mb-2"><input type="text" class="form-control aktivitas-pic" value="{{ $pic }}" placeholder="PIC"></div>
                                        <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Poin Ini</button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <button class="btn btn-outline-primary btn-sm w-100" type="button" onclick="addAktivitas()">+ Tambah Aktivitas/Tanggung Jawab</button> 
                        <input type="hidden" name="aktifitas_tanggung_jawab" id="aktifitas-final">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lampiran (File Lama Tetap Tersimpan Jika Tidak Diganti)</label>
                        <div id="lampiran-container">
                            @php $lampirans = json_decode($sop->lampiran, true) ?: []; @endphp
                            @foreach($lampirans as $lamp)
                            <div class="lampiran-item border p-3 mb-3 rounded bg-light">
                                <div class="mb-2">
                                    <input type="text" class="form-control" name="lampiran_judul[]" value="{{ $lamp['judul'] }}">
                                </div>
                                <div class="mb-2">
                                    <textarea class="form-control mb-2" name="lampiran_text[]" rows="2">{{ $lamp['text'] }}</textarea>
                                    @if($lamp['file'])
                                        <div class="mb-1"><small class="text-primary">File: {{ basename($lamp['file']) }}</small></div>
                                        <input type="hidden" name="existing_files[]" value="{{ $lamp['file'] }}">
                                    @endif
                                    <input type="file" class="form-control" name="lampiran_file[]" accept="image/*">
                                </div>
                                <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Lampiran</button>
                            </div>
                            @endforeach
                        </div>
                        <button class="btn btn-outline-primary btn-sm w-100" type="button" onclick="addLampiran()">+ Tambah Lampiran Baru</button>
                    </div>

                    <div class="mb-3 border-top pt-4">
                        <h6 class="fw-bold">Verifikasi & Approval</h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Pembuat Tambahan</label>
                                <div id="people-container">
                                    @php $peoples = json_decode($sop->people, true) ?: []; @endphp
                                    @foreach($peoples as $p)
                                    <div class="input-group mb-2">
                                        <select class="form-select select-people" name="people[]" data-selected="{{ $p }}">
                                            <option value="{{ $p }}">{{ $p }}</option>
                                        </select>
                                        <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>
                                    </div>
                                    @endforeach
                                </div>
                                <button class="btn btn-outline-primary btn-sm" type="button" onclick="addPeopleRow()">+</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">DH / SH</label>
                                <select class="form-select" name="DHdanSH" id="select-dhsh" required>
                                    <option value="{{ $sop->DHdanSH }}">{{ $sop->DHdanSH }}</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">PJO</label>
                                <select class="form-select" name="PJO" id="select-pjo" required>
                                    <option value="{{ $sop->PJO }}">{{ $sop->PJO }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-secondary" id="prevBtn" style="display:none;" onclick="changeStep(-1)">Kembali</button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">Next</button>
                    <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 2;
    let allUsersData = [];
    const oldDhsh = "{{ $sop->DHdanSH }}";
    const oldPjo = "{{ $sop->PJO }}";

    document.addEventListener('DOMContentLoaded', function() {
        const selectDHSH = document.getElementById('select-dhsh');
        const selectPJO = document.getElementById('select-pjo');

        fetch('/api/get-approvers')
            .then(response => response.json())
            .then(data => {
                allUsersData = data.all_users; 

                // Load DHSH
                selectDHSH.innerHTML = '<option value="">-- Pilih DH/SH --</option>';
                data.dhsh.forEach(user => {
                    selectDHSH.add(new Option(`${user.nama} (NRP : ${user.nrp})`, user.nama, false, user.nama === oldDhsh));
                });

                // Load PJO
                selectPJO.innerHTML = '<option value="">-- Pilih PJO --</option>';
                data.pjo.forEach(user => {
                    selectPJO.add(new Option(`${user.nama} (NRP : ${user.nrp})`, user.nama, false, user.nama === oldPjo));
                });

                // Re-sync existing people dropdowns
                document.querySelectorAll('.select-people').forEach(select => {
                    const selectedVal = select.getAttribute('data-selected');
                    select.innerHTML = '<option value="">-- Pilih Pembuat Tambahan --</option>';
                    allUsersData.forEach(user => {
                        select.add(new Option(`${user.nama} (NRP : ${user.nrp})`, user.nama, false, user.nama === selectedVal));
                    });
                });
            });
    });

    function addPeopleRow() {
        const container = document.getElementById('people-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        let options = '<option value="">-- Pilih Pembuat Tambahan --</option>';
        allUsersData.forEach(user => { options += `<option value="${user.nama}">${user.nama} (NRP : ${user.nrp})</option>`; });
        div.innerHTML = `<select class="form-select select-people" name="people[]">${options}</select><button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>`;
        container.appendChild(div);
    }

    document.getElementById('manualSwitch').addEventListener('change', function() {
        const inputNoDoc = document.getElementById('no_dokumen');
        inputNoDoc.readOnly = !this.checked;
    });

    function addRow(field) {
        const container = document.getElementById(`${field}-container`);
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `<input type="text" class="form-control ref-input-${field}" placeholder="Masukkan ${field}..."><button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>`;
        container.appendChild(div);
    }

    function addAktivitas() {
        const container = document.getElementById('aktivitas-container');
        const div = document.createElement('div');
        div.className = 'aktivitas-item border p-3 mb-3 rounded bg-light';
        div.innerHTML = `<div class="mb-2"><input type="text" class="form-control aktivitas-judul" placeholder="Sub Judul"></div><div class="mb-2"><textarea class="form-control aktivitas-isi" rows="3" placeholder="Deskripsi..."></textarea></div><div class="mb-2"><input type="text" class="form-control aktivitas-pic" placeholder="PIC"></div><button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Poin Ini</button>`;
        container.appendChild(div);
    }

    function addLampiran() {
        const container = document.getElementById('lampiran-container');
        const div = document.createElement('div');
        div.className = 'lampiran-item border p-3 mb-3 rounded bg-light';
        div.innerHTML = `<div class="mb-2"><input type="text" class="form-control" name="lampiran_judul[]" placeholder="Judul Lampiran..."></div><div class="mb-2"><textarea class="form-control mb-2" name="lampiran_text[]" rows="2" placeholder="Teks..."></textarea><input type="file" class="form-control" name="lampiran_file[]" accept="image/*"></div><button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus</button>`;
        container.appendChild(div);
    }

    function combineAllFields() {
        ['referensi', 'definisi'].forEach(field => {
            const inputs = document.querySelectorAll(`.ref-input-${field}`);
            const values = Array.from(inputs).map(i => i.value.trim()).filter(v => v !== "");
            document.getElementById(`${field}-final`).value = values.join('\n');
        });

        const items = document.querySelectorAll('.aktivitas-item');
        let combinedText = "";
        items.forEach(item => {
            const judul = item.querySelector('.aktivitas-judul').value.trim();
            const isi = item.querySelector('.aktivitas-isi').value.trim();
            const pic = item.querySelector('.aktivitas-pic').value.trim() || "User";
            if (judul !== "" || isi !== "") combinedText += `**${judul}**\n${isi}[PIC]${pic}[END]`;
        });
        document.getElementById('aktifitas-final').value = combinedText.trim();
    }

    function changeStep(step) {
        if (step === 1 && !validateStep(currentStep)) return;
        combineAllFields();
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        currentStep += step;
        document.getElementById(`step-${currentStep}`).classList.add('active');
        document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'inline-block';
        document.getElementById('nextBtn').style.display = currentStep === totalSteps ? 'none' : 'inline-block';
        document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'inline-block' : 'none';
        document.getElementById('step-title').innerText = `Langkah ${currentStep}`;
    }

    function validateStep(step) {
        const activeStep = document.getElementById(`step-${step}`);
        const inputs = activeStep.querySelectorAll('[required]');
        let isValid = true;
        inputs.forEach(input => {
            if (input.value.trim() === "") { input.classList.add('is-invalid'); isValid = false; }
            else { input.classList.remove('is-invalid'); }
        });
        return isValid;
    }

    document.getElementById('multiStepForm').addEventListener('submit', function() { combineAllFields(); });
</script>
@endsection
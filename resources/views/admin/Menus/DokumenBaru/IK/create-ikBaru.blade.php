@extends ('layouts.main')
@section('container')
<style>
    input[readonly] { background-color: #f5f5f5; cursor: not-allowed; }
    .step-content { display: none; } /* Sembunyikan semua step dulu */
    .step-content.active { display: block; } /* Tampilkan hanya yang aktif */
    .form-switch .form-check-input { width: 2.5em; height: 1.25em; cursor: pointer; }
</style>

<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">DOKUMEN BARU - <span id="step-title">Langkah 1</span></h5>
        </div>
        <div class="card-body">
            <form action="{{route('dataIkBaru.store')}}" method="POST" enctype="multipart/form-data" id="multiStepForm">
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
                        <label class="form-label">Judul Dokumen</label>
                        <input type="text" class="form-control" name="judul" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Dokumen</label>
                        <input type="text" class="form-control" name="jenis_doc" readonly value="IK"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Departemen</label>
                        <input type="text" class="form-control" name="departemen" value="{{ Auth::user()->departemen }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pembuat</label>
                        <input type="text" class="form-control" name="pembuat" value="{{ Auth::user()->nama }}" readonly>
                    </div>
                </div>

                <div class="step-content" id="step-2">
                    <div class="mb-3">
                        <label class="form-label">Aktifitas dan Tanggung Jawab</label>
                        <div id="aktivitas-container">
                            <div class="aktivitas-item border p-3 mb-3 rounded bg-light">
                                <div class="mb-2">
                                    <input type="text" class="form-control aktivitas-judul" placeholder="Sub Judul (Contoh: Tahap Persiapan)">
                                </div>
                                <div class="mb-2">
                                    <textarea class="form-control aktivitas-isi" rows="3" placeholder="Deskripsi aktivitas..."></textarea>
                                </div>
                                <div class="mb-2">
                                    <input type="text" class="form-control aktivitas-pic" placeholder="PIC (Contoh: Tim ICT)">
                                </div>
                                <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Poin Ini</button>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary btn-sm w-100" type="button" onclick="addAktivitas()">+ Tambah Aktivitas/Tanggung Jawab</button> 
                        <input type="hidden" name="aktifitas_tanggung_jawab" id="aktifitas-final">
                    </div>

                        <div class="mb-3 border-top pt-4">
                            <h6 class="fw-bold">Verifikasi & Approval</h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Pembuat Tambahan (Opsional)</label>
                                    <div id="people-container">
                                        <div class="input-group mb-2">
                                            <select class="form-select select-people" name="people[]">
                                                <option value="">-- Pilih Pembuat Tambahan --</option>
                                            </select>
                                            <button class="btn btn-outline-primary" type="button" onclick="addPeopleRow()">+</button>
                                        </div>
                                    </div>
                                    <div class="form-text">Gunakan tombol + jika pembuat lebih dari 1 orang.</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">DH / SH</label>
                                    <select class="form-select" name="DHdanSH" id="select-dhsh" required>
                                        <option value="">Memuat data...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="mt-4">
                    <button type="button" class="btn btn-secondary" id="prevBtn" style="display:none;" onclick="changeStep(-1)">Kembali</button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">Next</button>
                    <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">Simpan Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 2;
    const autoDocValue = "{{ $autoNumber }}"; 
    let allUsersData = []; // Akan menampung SEMUA user dari role 0-6

    // Fungsi untuk menambah baris Pembuat Tambahan
    function addPeopleRow() {
        const container = document.getElementById('people-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        
        let options = '<option value="">-- Pilih Pembuat Tambahan --</option>';
        allUsersData.forEach(user => {
            options += `<option value="${user.nama}">${user.nama} (NRP : ${user.nrp})</option>`;
        });

        div.innerHTML = `
            <select class="form-select select-people" name="people[]">
                ${options}
            </select>
            <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>
        `;
        container.appendChild(div);
    }

    // Event Listener untuk Switch No Dokumen Manual/Otomatis
    document.getElementById('manualSwitch').addEventListener('change', function() {
        const inputNoDoc = document.getElementById('no_dokumen');
        const hintDoc = document.getElementById('no-doc-hint');
        
        if (this.checked) {
            inputNoDoc.readOnly = false;
            inputNoDoc.focus();
            hintDoc.innerHTML = "Contoh Format: <span class='text-primary'>PPA-ADRO-IK-ICTMD-01</span>";
        } else {
            inputNoDoc.readOnly = true;
            inputNoDoc.value = autoDocValue;
            hintDoc.innerHTML = "Format: Otomatis Sistem";
        }
    });

    // Ambil Data dari API saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const selectDHSH = document.getElementById('select-dhsh');
        const selectPeopleInitial = document.querySelector('.select-people');

        fetch('/api/get-approvers')
            .then(response => response.json())
            .then(data => {
                // 1. Ambil data all_users (Role 0-6) dari API
                // Ini kuncinya agar Non-staf dan Group Leader muncul
                allUsersData = data.all_users; 

                // 2. Isi dropdown DH/SH (Role 3 & 4)
                selectDHSH.innerHTML = '<option value="">-- Pilih DH/SH --</option>';
                data.dhsh.forEach(user => {
                    selectDHSH.add(new Option(`${user.nama} (NRP : ${user.nrp})`, user.nama));
                });

                // 3. Isi dropdown Pembuat Tambahan pertama (Initial) dengan allUsersData
                selectPeopleInitial.innerHTML = '<option value="">-- Pilih Pembuat Tambahan --</option>';
                allUsersData.forEach(user => {
                    selectPeopleInitial.add(new Option(`${user.nama} (NRP : ${user.nrp})`, user.nama));
                });
            })
            .catch(error => {
                console.error('Error fetching users:', error);
                alert("Gagal memuat daftar user. Silakan refresh halaman.");
            });
    });

    // --- Fungsi Helper Lainnya (Aktivitas, Multi-step) ---

    function addRow(field) {
        const container = document.getElementById(`${field}-container`);
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" class="form-control ref-input-${field}" placeholder="Masukkan ${field}...">
            <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>
        `;
        container.appendChild(div);
    }

    function addAktivitas() {
        const container = document.getElementById('aktivitas-container');
        const div = document.createElement('div');
        div.className = 'aktivitas-item border p-3 mb-3 rounded bg-light';
        div.innerHTML = `
            <div class="mb-2">
                <input type="text" class="form-control aktivitas-judul" placeholder="Sub Judul (Contoh: Tahap Persiapan)">
            </div>
            <div class="mb-2">
                <textarea class="form-control aktivitas-isi" rows="3" placeholder="Deskripsi aktivitas..."></textarea>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control aktivitas-pic" placeholder="PIC (Contoh: Tim ICT)">
            </div>
            <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Poin Ini</button>
        `;
        container.appendChild(div);
    }

    function combineAllFields() {
        const aktivitasFinal = document.getElementById('aktifitas-final');
        if (aktivitasFinal) {
            const items = document.querySelectorAll('.aktivitas-item');
            let combinedText = "";
            items.forEach((item, index) => {
                const judul = item.querySelector('.aktivitas-judul').value.trim();
                const isi = item.querySelector('.aktivitas-isi').value.trim();
                const pic = item.querySelector('.aktivitas-pic').value.trim() || "User PIC";
                if (judul !== "" || isi !== "") {
                    combinedText += `**${judul}**\n${isi}[PIC]${pic}[END]`;
                }
            });
            aktivitasFinal.value = combinedText.trim();
        }
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
            if (input.value.trim() === "") {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) alert("Mohon lengkapi semua field yang wajib diisi.");
        return isValid;
    }

    // Tambahkan event preventDefault untuk memastikan data digabung SEBELUM dikirim
    document.getElementById('multiStepForm').addEventListener('submit', function(e) {
        combineAllFields(); // Gabungkan data aktivitas ke input hidden
        
        // Validasi terakhir sebelum benar-benar kirim
        const aktivitasValue = document.getElementById('aktifitas-final').value;
        if (!aktivitasValue) {
            e.preventDefault();
            alert("Mohon isi minimal satu Aktivitas dan Tanggung Jawab!");
        }
    });
</script>
@endsection
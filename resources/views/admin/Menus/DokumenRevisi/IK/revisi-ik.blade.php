@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">REVISI DOKUMEN: {{ $ikRevisi->no_dokumen }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dataIkRevisi.store') }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
                @csrf
                <input type="hidden" name="no_dokumen" value="{{ $ikRevisi->no_dokumen }}">
                <input type="hidden" name="jenis_doc" value="{{ $ikRevisi->jenis_doc }}">
                <input type="hidden" name="departemen" value="{{ $ikRevisi->departemen }}">
                <input type="hidden" name="edisi" value="{{ $ikRevisi->edisi }}">
                <input type="hidden" name="revisi" value="{{ $ikRevisi->revisi }}">
                <input type="hidden" name="efektif_date" value="{{ $ikRevisi->efektif_date }}">

                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <textarea class="form-control" name="judul" rows="3">{{ old('judul', $ikRevisi->judul) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Aktifitas dan Tanggung Jawab</label>
                    <div id="aktivitas-container">
                        @php 
                            // Pecah berdasarkan marker [END]
                            $items = explode('[END]', $ikRevisi->aktifitas_tanggung_jawab); 
                        @endphp

                        @foreach($items as $item)
                            @if(!empty(trim($item)))
                                @php
                                    // 1. Pisahkan Konten Utama dengan PIC
                                    $parts = explode('[PIC]', $item);
                                    $kontenUtama = isset($parts[0]) ? trim($parts[0]) : '';
                                    $pic = isset($parts[1]) ? trim($parts[1]) : '';

                                    // 2. Pisahkan Judul (Baris 1) dan Isi (Baris sisanya)
                                    $lines = explode("\n", str_replace("\r", "", $kontenUtama));
                                    $judulRaw = isset($lines[0]) ? trim($lines[0]) : '';
                                    
                                    // Bersihkan ** dan nomor (misal 5.1) agar tidak double saat diedit
                                    $judul = preg_replace('/^\*\*?[\d\.]*\s*|\*\*$/', '', $judulRaw);
                                    
                                    // Ambil sisanya sebagai isi
                                    array_shift($lines);
                                    $isi = implode("\n", $lines);
                                @endphp

                                <div class="aktivitas-item border p-3 mb-3 rounded bg-light">
                                    <div class="mb-2">
                                        <label class="small text-muted">Sub Judul</label>
                                        <input type="text" class="form-control aktivitas-judul" value="{{ $judul }}" placeholder="Masukkan Judul Aktivitas">
                                    </div>
                                    <div class="mb-2">
                                        <label class="small text-muted">Deskripsi Aktivitas</label>
                                        <textarea class="form-control aktivitas-isi" rows="3" placeholder="Jelaskan langkah-langkahnya...">{{ trim($isi) }}</textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="small text-muted">PIC (Tanggung Jawab)</label>
                                        <input type="text" class="form-control aktivitas-pic" value="{{ $pic }}" placeholder="Contoh: Group Leader / All Employee">
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.parentElement.remove()">
                                            <i class="bx bx-trash"></i> Hapus Poin Ini
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <button class="btn btn-outline-primary btn-sm w-100 mb-3" type="button" onclick="addAktivitas()">
                        <i class="bx bx-plus"></i> Tambah Aktivitas Baru
                    </button>
                    
                    <input type="hidden" name="aktifitas_tanggung_jawab" id="aktifitas-final">
                </div>

                <div class="mb-3 border p-3 rounded bg-light">
                    <label class="form-label fw-bold">Dibuat Oleh (Selain DOCO)</label>
                    <div id="people-container">
                        @php 
                            $rawPeople = $ikRevisi->people;
                            $currentPeople = [];

                            if (!empty($rawPeople)) {
                                // Cek apakah data diawali '[' (format JSON)
                                if (str_starts_with($rawPeople, '[')) {
                                    $currentPeople = json_decode($rawPeople, true) ?? [];
                                } else {
                                    // Jika format lama (string dipisah koma)
                                    $currentPeople = explode(',', $rawPeople);
                                }
                            }
                        @endphp
                        
                        @foreach($currentPeople as $person)
                            @php $personName = trim($person, " \"\t\n\r\0\x0B"); @endphp {{-- Membersihkan sisa kutip jika ada --}}
                            @if(!empty($personName))
                            <div class="input-group mb-2">
                                <select class="form-select select-people-item" data-selected="{{ $personName }}">
                                    <option value="">Memuat data...</option>
                                </select>
                                <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <button class="btn btn-outline-primary btn-sm" type="button" onclick="addPeopleRow()">+ Tambah Penyusun</button>
                    <input type="hidden" name="people" id="people-final">
                    <div class="form-text text-muted mt-2">Nama-nama ini akan muncul di kolom "Dibuat Oleh" pada halaman pengesahan.</div>
                </div>
                
                <div class="mb-3 border-top pt-4">
                    <h6 class="fw-bold">Verifikasi & Approval</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">DH / SH</label>
                            <select class="form-select" name="DHdanSH" id="select-dhsh" data-selected="{{ $ikRevisi->DHdanSH }}" required></select>
                        </div>
                    </div>
                </div>

                <div class="mb-3 border-top pt-4">
                    <h6 class="fw-bold">Catatan Perubahan (Revisi)</h6>
                    <div id="catatan-revisi-container">
                        <div class="catatan-item border p-3 mb-3 rounded bg-light">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <input type="text" class="form-control catatan-halaman" placeholder="Halaman (Contoh: 1 atau All)" required>
                                </div>
                                <div class="col-md-9 mb-2">
                                    <textarea class="form-control catatan-isi" rows="2" placeholder="Jelaskan detail perubahan pada halaman ini..." required></textarea>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-outline-danger d-none remove-catatan-btn" type="button" onclick="this.parentElement.remove()">Hapus Poin Catatan</button>
                        </div>
                    </div>
                    <button class="btn btn-outline-primary btn-sm w-100" type="button" onclick="addCatatanRow()">+ Tambah Catatan Per Halaman</button>
                    <input type="hidden" name="catatan" id="catatan-final">
                </div>

                <button type="submit" class="btn btn-primary w-100">Simpan Perubahan & Ajukan Ulang</button>
            </form>
        </div>
    </div>
</div>

<script>

    function addLampiran() {
        const container = document.getElementById('lampiran-container');
        const div = document.createElement('div');
        div.className = 'lampiran-item border p-3 mb-3 rounded bg-light';
        div.innerHTML = `
            <input type="text" class="form-control mb-2" name="lampiran_judul[]" placeholder="Judul Lampiran">
            <textarea class="form-control mb-2" name="lampiran_text[]" rows="2" placeholder="Keterangan lampiran..."></textarea>
            <input type="file" class="form-control" name="lampiran_file[]" accept="image/*">
            <button class="btn btn-sm btn-outline-danger mt-2" type="button" onclick="this.parentElement.remove()">Hapus Lampiran</button>
        `;
        container.appendChild(div);
    }
    // --- FUNGSI UI (TAMBAH ROW) ---

    function addCatatanRow() {
        const container = document.getElementById('catatan-revisi-container');
        const div = document.createElement('div');
        div.className = 'catatan-item border p-3 mb-3 rounded bg-light';
        div.innerHTML = `
            <div class="row">
                <div class="col-md-3 mb-2">
                    <input type="text" class="form-control catatan-halaman" placeholder="Halaman">
                </div>
                <div class="col-md-9 mb-2">
                    <textarea class="form-control catatan-isi" rows="2" placeholder="Jelaskan detail perubahan..."></textarea>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Poin Catatan</button>
        `;
        container.appendChild(div);
    }

    let allStaffData = [];

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

    function addPeopleRow(selectedValue = "") {
        const container = document.getElementById('people-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        
        let options = '<option value="">-- Pilih Nama --</option>';
        allStaffData.forEach(user => {
            const isSelected = user.nama === selectedValue ? 'selected' : '';
            options += `<option value="${user.nama}" ${isSelected}>${user.nama} (NRP: ${user.nrp})</option>`;
        });

        div.innerHTML = `
            <select class="form-select select-people-item">
                ${options}
            </select>
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
                <input type="text" class="form-control aktivitas-judul" placeholder="Sub Judul">
            </div>
            <div class="mb-2">
                <textarea class="form-control aktivitas-isi" rows="3" placeholder="Deskripsi aktivitas..."></textarea>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control aktivitas-pic" placeholder="PIC">
            </div>
            <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Poin Ini</button>
        `;
        container.appendChild(div);
    }

    // --- LOGIKA PENGGABUNGAN DATA (PENTING) ---

    function combineAllFields() {
        // 2. People (Penyusun)
        const peopleFinal = document.getElementById('people-final');
        if (peopleFinal) {
            const peopleSelects = document.querySelectorAll('.select-people-item');
            const peopleValues = Array.from(peopleSelects)
                .map(s => s.value.trim())
                .filter(v => v !== "");
            peopleFinal.value = peopleValues.join(',');
        }

        // 3. Aktivitas dan Tanggung Jawab
        const aktivitasFinal = document.getElementById('aktifitas-final');
        if (aktivitasFinal) {
            const items = document.querySelectorAll('.aktivitas-item');
            let combinedText = "";
            items.forEach((item) => {
                const judul = item.querySelector('.aktivitas-judul').value.trim();
                const isi = item.querySelector('.aktivitas-isi').value.trim();
                const pic = item.querySelector('.aktivitas-pic').value.trim() || "User";
                
                if (judul !== "" || isi !== "") {
                    // Gunakan \n yang konsisten agar regex PHP mudah membaca
                    combinedText += `** ${judul}**\n${isi} [PIC] ${pic} [END] `;
                }
            });
            aktivitasFinal.value = combinedText.trim();
        }

        // 4. Catatan Perubahan
        const catatanFinal = document.getElementById('catatan-final');
        if (catatanFinal) {
            const items = document.querySelectorAll('.catatan-item');
            let combinedCatatan = "";
            items.forEach((item) => {
                const halaman = item.querySelector('.catatan-halaman').value.trim();
                const isi = item.querySelector('.catatan-isi').value.trim();
                if (halaman !== "" || isi !== "") {
                    combinedCatatan += `[HAL:${halaman}] ${isi} [END] `;
                }
            });
            catatanFinal.value = combinedCatatan.trim();
        }
    }

    // --- EVENT LISTENER ---

    document.addEventListener('DOMContentLoaded', function() {
        // A. Handle Form Submission
        const form = document.querySelector('form');
        if(form) {
            form.addEventListener('submit', function(e) {
                combineAllFields(); // Jalankan penggabungan tepat sebelum submit
                console.log("Data combined!"); // Untuk debug
            });
        }

        // B. Fetch Data Approver
        const selectDHSH = document.getElementById('select-dhsh');
        
        fetch('/api/get-approvers')
            .then(response => response.json())
            .then(data => {
                allStaffData = data.all_users; 
                
                if(selectDHSH) {
                    selectDHSH.innerHTML = '<option value="">-- Pilih DH/SH --</option>';
                    data.dhsh.forEach(user => {
                        let option = new Option(`${user.nama} (NRP : ${user.nrp})`, user.nama);
                        if(user.nama === selectDHSH.getAttribute('data-selected')) option.selected = true;
                        selectDHSH.add(option);
                    });
                }

                // Sinkronisasi People Item yang sudah ada di HTML saat load
                document.querySelectorAll('.select-people-item').forEach(select => {
                    const selectedVal = select.getAttribute('data-selected');
                    select.innerHTML = '<option value="">-- Pilih Nama --</option>';
                    allStaffData.forEach(user => {
                        let option = new Option(`${user.nama} (NRP : ${user.nrp})`, user.nama);
                        if(user.nama === selectedVal) option.selected = true;
                        select.add(option);
                    });
                });
            });
    });

    document.getElementById('multiStepForm').addEventListener('submit', function() {
        combineAllFields();
    });
</script>
@endsection
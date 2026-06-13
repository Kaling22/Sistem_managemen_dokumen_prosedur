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
            <h5 class="mb-0">EDIT DOKUMEN SP - <span id="step-title">Langkah 1</span></h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dataSpBaru.update', $data->id) }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
                @csrf
                @method('PUT')

                <div class="step-content active" id="step-1">
                    <div class="mb-3">
                        <label class="form-label">No Dokumen</label>
                        <input type="text" class="form-control" name="no_dokumen" value="{{ $data->no_dokumen }}" readonly required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Dokumen</label>
                        <input type="text" class="form-control" name="judul" value="{{ $data->judul }}" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Dokumen</label>
                        <input type="text" class="form-control" name="jenis_doc" value="{{ $data->jenis_doc }}" readonly/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Departemen</label>
                        <input type="text" class="form-control" name="departemen" value="{{ $data->departemen }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pembuat</label>
                        <input type="text" class="form-control" name="pembuat" value="{{ $data->pembuat }}" readonly>
                    </div>
                </div>

                <div class="step-content" id="step-2">
                    <div class="mb-3">
                        <label class="form-label">Tujuan</label>
                        <textarea class="form-control" name="tujuan" rows="3">{{ $data->tujuan }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruang lingkup</label>
                        <textarea class="form-control" name="ruang_lingkup" rows="3">{{ $data->ruang_lingkup }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Referensi</label>
                        <div id="referensi-container">
                            @php $refs = explode("\n", $data->referensi); @endphp
                            @foreach($refs as $ref)
                                @php $cleanRef = preg_replace('/^[0-9]+\.\s+/', '', $ref); @endphp
                                @if(trim($cleanRef))
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control ref-input-referensi" value="{{ $cleanRef }}">
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
                            @php $defs = explode("\n", $data->definisi); @endphp
                            @foreach($defs as $def)
                                @php $cleanDef = preg_replace('/^[0-9]+\.\s+/', '', $def); @endphp
                                @if(trim($cleanDef))
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control ref-input-definisi" value="{{ $cleanDef }}">
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
        // 1. Bersihkan data dari kemungkinan double quotes atau karakter aneh
        $rawAktifitas = trim($data->aktifitas_tanggung_jawab, " \t\n\r\0\x0B\"");
        
        // 2. Pecah berdasarkan [END]
        $items = array_filter(explode('[END]', $rawAktifitas)); 
    @endphp

    @if(count($items) > 0)
        @foreach($items as $item)
            @php
                // Pisahkan antara bagian Konten dan PIC
                $parts = explode('[PIC]', $item);
                $konten = $parts[0] ?? '';
                $picAkt = isset($parts[1]) ? trim($parts[1]) : '';

                // Ambil Judul (teks di antara **)
                preg_match('/\*\*(.*?)\*\*/', $konten, $judulMatch);
                $judulRaw = $judulMatch[1] ?? '';
                
                // Bersihkan nomor urut (misal "1. dwad" jadi "dwad")
                $judulAkt = preg_replace('/^[0-9]+\.\s+/', '', $judulRaw);

                // Ambil Isi (semua teks setelah judul sampai sebelum [PIC])
                // Kita hapus tag **Judul** dari bagian konten
                $isiAkt = str_replace("**$judulRaw**", "", $konten);
                // Bersihkan sisa-sisa karakter baris baru (\r atau \n)
                $isiAkt = trim($isiAkt);
            @endphp

            <div class="aktivitas-item border p-3 mb-3 rounded bg-light">
                <div class="mb-2">
                    <label class="small fw-bold">Sub Judul</label>
                    <input type="text" class="form-control aktivitas-judul" value="{{ $judulAkt }}" placeholder="Sub Judul">
                </div>
                <div class="mb-2">
                    <label class="small fw-bold">Isi Aktifitas</label>
                    <textarea class="form-control aktivitas-isi" rows="3" placeholder="Deskripsi">{{ $isiAkt }}</textarea>
                </div>
                <div class="mb-2">
                    <label class="small fw-bold">PIC</label>
                    <input type="text" class="form-control aktivitas-pic" value="{{ $picAkt }}" placeholder="PIC">
                </div>
                <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Poin Ini</button>
            </div>
        @endforeach
    @else
        <div class="alert alert-light border small text-muted">Belum ada data aktifitas. Klik tombol di bawah untuk menambah.</div>
    @endif
</div>
                        <button class="btn btn-outline-primary btn-sm w-100" type="button" onclick="addAktivitas()">+ Tambah Aktivitas/Tanggung Jawab</button> 
                        <input type="hidden" name="aktifitas_tanggung_jawab" id="aktifitas-final">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lampiran</label>
                        <div id="lampiran-container">
                            @php $lampirans = json_decode($data->lampiran, true) ?? []; @endphp
                            @foreach($lampirans as $lamp)
                            <div class="lampiran-item border p-3 mb-3 rounded bg-light">
                                <div class="mb-2">
                                    <input type="text" class="form-control" name="lampiran_judul[]" value="{{ $lamp['judul'] }}" placeholder="Judul Lampiran">
                                </div>
                                <div class="mb-2">
                                    <textarea class="form-control mb-2" name="lampiran_text[]" rows="2">{{ $lamp['text'] }}</textarea>
                                    @if(isset($lamp['file']))
                                        <div class="mb-2"><small class="text-primary">File Aktif: {{ $lamp['file'] }}</small></div>
                                    @endif
                                    <input type="file" class="form-control" name="lampiran_file[]">
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
                                    @php
                                        $peoples = json_decode($data->people, true);
                                        if(is_string($peoples)) $peoples = json_decode($peoples, true);
                                        $peoples = is_array($peoples) ? $peoples : [];
                                    @endphp
                                    @foreach($peoples as $p)
                                    <div class="input-group mb-2">
                                        <select class="form-select select-people" name="people[]">
                                            <option value="{{ $p }}" selected>{{ $p }}</option>
                                        </select>
                                        <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>
                                    </div>
                                    @endforeach
                                </div>
                                <button class="btn btn-outline-primary btn-sm" type="button" onclick="addPeopleRow()">+</button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">DH / SH</label>
                                <select class="form-select" name="DHdanSH" id="select-dhsh" required>
                                    <option value="{{ $data->DHdanSH }}" selected>{{ $data->DHdanSH }}</option>
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

    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/get-approvers')
            .then(response => response.json())
            .then(data => {
                allUsersData = data.all_users;
                
                // Isi SH
                const selectDHSH = document.getElementById('select-dhsh');
                let shOptions = '<option value="">-- Pilih DH/SH --</option>';
                data.dhsh.forEach(u => {
                    let sel = u.nama === "{{ $data->DHdanSH }}" ? 'selected' : '';
                    shOptions += `<option value="${u.nama}" ${sel}>${u.nama}</option>`;
                });
                selectDHSH.innerHTML = shOptions;

                // Isi People Dropdown yang sudah ada
                document.querySelectorAll('.select-people').forEach(select => {
                    const existingVal = select.value;
                    let pOptions = '<option value="">-- Pilih Pembuat --</option>';
                    allUsersData.forEach(u => {
                        let sel = u.nama === existingVal ? 'selected' : '';
                        pOptions += `<option value="${u.nama}" ${sel}>${u.nama}</option>`;
                    });
                    select.innerHTML = pOptions;
                });
            });
    });

    function addRow(field) {
        const container = document.getElementById(`${field}-container`);
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `<input type="text" class="form-control ref-input-${field}"><button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>`;
        container.appendChild(div);
    }

    function addAktivitas() {
        const container = document.getElementById('aktivitas-container');
        const div = document.createElement('div');
        div.className = 'aktivitas-item border p-3 mb-3 rounded bg-light';
        div.innerHTML = `
            <div class="mb-2"><input type="text" class="form-control aktivitas-judul" placeholder="Sub Judul"></div>
            <div class="mb-2"><textarea class="form-control aktivitas-isi" rows="3" placeholder="Deskripsi"></textarea></div>
            <div class="mb-2"><input type="text" class="form-control aktivitas-pic" placeholder="PIC"></div>
            <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Poin Ini</button>`;
        container.appendChild(div);
    }

    function addLampiran() {
        const container = document.getElementById('lampiran-container');
        const div = document.createElement('div');
        div.className = 'lampiran-item border p-3 mb-3 rounded bg-light';
        div.innerHTML = `
            <div class="mb-2"><input type="text" class="form-control" name="lampiran_judul[]" placeholder="Judul Lampiran"></div>
            <div class="mb-2"><textarea class="form-control mb-2" name="lampiran_text[]" rows="2"></textarea><input type="file" class="form-control" name="lampiran_file[]"></div>
            <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Lampiran</button>`;
        container.appendChild(div);
    }

    function addPeopleRow() {
        const container = document.getElementById('people-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        let options = '<option value="">-- Pilih Pembuat --</option>';
        allUsersData.forEach(u => options += `<option value="${u.nama}">${u.nama}</option>`);
        div.innerHTML = `<select class="form-select select-people" name="people[]">${options}</select><button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>`;
        container.appendChild(div);
    }

    function combineAllFields() {
        // Referensi & Definisi
        ['referensi', 'definisi'].forEach(field => {
            const inputs = document.querySelectorAll(`.ref-input-${field}`);
            const values = Array.from(inputs).map((input, i) => input.value.trim() ? `${i + 1}. ${input.value.trim()}` : '').filter(v => v);
            document.getElementById(`${field}-final`).value = values.join('\n');
        });

        // Aktifitas & Tanggung Jawab
        const items = document.querySelectorAll('.aktivitas-item');
        let combined = "";
        items.forEach((item, i) => {
            const j = item.querySelector('.aktivitas-judul').value.trim();
            const s = item.querySelector('.aktivitas-isi').value.trim();
            const p = item.querySelector('.aktivitas-pic').value.trim() || "User";
            if (j || s) combined += `**${i + 1}. ${j}**\n${s}[PIC]${p}[END]`;
        });
        document.getElementById('aktifitas-final').value = combined.trim();
    }

    function changeStep(step) {
        combineAllFields();
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        currentStep += step;
        document.getElementById(`step-${currentStep}`).classList.add('active');
        document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'inline-block';
        document.getElementById('nextBtn').style.display = currentStep === totalSteps ? 'none' : 'inline-block';
        document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'inline-block' : 'none';
        document.getElementById('step-title').innerText = `Langkah ${currentStep}`;
    }

    document.getElementById('multiStepForm').addEventListener('submit', function() {
        combineAllFields();
    });
</script>
@endsection
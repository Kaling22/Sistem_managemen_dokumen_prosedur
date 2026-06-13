@extends ('layouts.main')
@section('container')
<style>
    input[readonly] { background-color: #f5f5f5; cursor: not-allowed; }
</style>

<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">EDIT REVISI DOKUMEN: {{ $sopRevisi->no_dokumen }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dataSopRevisi.update', $sopRevisi->id) }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="no_dokumen" value="{{ $sopRevisi->no_dokumen }}">
                <input type="hidden" name="jenis_doc" value="{{ $sopRevisi->jenis_doc }}">
                <input type="hidden" name="departemen" value="{{ $sopRevisi->departemen }}">
                <input type="hidden" name="edisi" value="{{ $sopRevisi->edisi }}">
                <input type="hidden" name="revisi" value="{{ $sopRevisi->revisi }}">
                <input type="hidden" name="efektif_date" value="{{ $sopRevisi->efektif_date }}">

                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <textarea class="form-control" name="judul" rows="3" required>{{ old('judul', $sopRevisi->judul) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tujuan</label>
                    <textarea class="form-control" name="tujuan" rows="3">{{ old('tujuan', $sopRevisi->tujuan) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ruang lingkup</label>
                    <textarea class="form-control" name="ruang_lingkup" rows="3">{{ old('ruang_lingkup', $sopRevisi->ruang_lingkup) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Referensi</label>
                    <div id="referensi-container">
                        @php $refs = explode("\n", $sopRevisi->referensi); @endphp
                        @foreach($refs as $ref)
                            @php $cleanRef = preg_replace('/^\d+\.\s+/', '', $ref); @endphp
                            @if(!empty(trim($cleanRef)))
                            <div class="input-group mb-2">
                                <input type="text" class="form-control ref-input-referensi" value="{{ trim($cleanRef) }}">
                                <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <button class="btn btn-outline-primary btn-sm" type="button" onclick="addRow('referensi')">+ Tambah Baris</button>
                    <input type="hidden" name="referensi" id="referensi-final">
                </div>

                <div class="mb-3">
                    <label class="form-label">Definisi</label>
                    <div id="definisi-container">
                        @php $defs = explode("\n", $sopRevisi->definisi); @endphp
                        @foreach($defs as $def)
                            @php $cleanDef = preg_replace('/^\d+\.\s+/', '', $def); @endphp
                            @if(!empty(trim($cleanDef)))
                            <div class="input-group mb-2">
                                <input type="text" class="form-control ref-input-definisi" value="{{ trim($cleanDef) }}">
                                <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <button class="btn btn-outline-primary btn-sm" type="button" onclick="addRow('definisi')">+ Tambah Baris</button>
                    <input type="hidden" name="definisi" id="definisi-final">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Aktifitas dan Tanggung Jawab</label>
                    <div id="aktivitas-container">
                        @php $items = explode('[END]', $sopRevisi->aktifitas_tanggung_jawab); @endphp
                        @foreach($items as $item)
                            @if(!empty(trim($item)))
                                @php
                                    $parts = explode('[PIC]', $item);
                                    $kontenUtama = isset($parts[0]) ? trim($parts[0]) : '';
                                    $pic = isset($parts[1]) ? trim($parts[1]) : '';
                                    $lines = explode("\n", str_replace("\r", "", $kontenUtama));
                                    $judulRaw = isset($lines[0]) ? trim($lines[0]) : '';
                                    $judul = preg_replace('/^\*\*?[\d\.]*\s*|\*\*$/', '', $judulRaw);
                                    array_shift($lines);
                                    $isi = implode("\n", $lines);
                                @endphp
                                <div class="aktivitas-item border p-3 mb-3 rounded bg-light">
                                    <div class="mb-2">
                                        <label class="small text-muted">Sub Judul</label>
                                        <input type="text" class="form-control aktivitas-judul" value="{{ $judul }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="small text-muted">Deskripsi Aktivitas</label>
                                        <textarea class="form-control aktivitas-isi" rows="3">{{ trim($isi) }}</textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="small text-muted">PIC</label>
                                        <input type="text" class="form-control aktivitas-pic" value="{{ $pic }}">
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Poin Ini</button>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <button class="btn btn-outline-primary btn-sm w-100 mb-3" type="button" onclick="addAktivitas()">+ Tambah Aktivitas Baru</button>
                    <input type="hidden" name="aktifitas_tanggung_jawab" id="aktifitas-final">
                </div>

                <div class="mb-3">
                    <label class="form-label">Lampiran</label>
                    <div id="lampiran-container">
                        @php $lampirans = json_decode($sopRevisi->lampiran, true) ?? []; @endphp
                        @foreach($lampirans as $lamp)
                        <div class="lampiran-item border p-3 mb-3 rounded bg-light">
                            <input type="text" class="form-control mb-2" name="lampiran_judul[]" value="{{ $lamp['judul'] }}" placeholder="Judul Lampiran">
                            <textarea class="form-control mb-2" name="lampiran_text[]" rows="2">{{ $lamp['text'] }}</textarea>
                            @if($lamp['file'])
                                <div class="mb-2">
                                    <small class="text-success">File saat ini: {{ basename($lamp['file']) }}</small>
                                    <input type="hidden" name="existing_lampiran_file[]" value="{{ $lamp['file'] }}">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="lampiran_file[]" accept="image/*">
                            <button class="btn btn-sm btn-outline-danger mt-2" type="button" onclick="this.parentElement.remove()">Hapus Lampiran</button>
                        </div>
                        @endforeach
                    </div>
                    <button class="btn btn-outline-primary btn-sm w-100" type="button" onclick="addLampiran()">+ Tambah Lampiran Baru</button>
                </div>

                <div class="mb-3 border p-3 rounded bg-light">
                    <label class="form-label fw-bold">Dibuat Oleh (Selain DOCO)</label>
                    <div id="people-container">
                        @php 
                            $rawPeople = $sopRevisi->people;
                            $currentPeople = [];
                            if (!empty($rawPeople)) {
                                if (str_starts_with($rawPeople, '[')) { $currentPeople = json_decode($rawPeople, true) ?? []; } 
                                else { $currentPeople = explode(',', $rawPeople); }
                            }
                        @endphp
                        @foreach($currentPeople as $person)
                            @php $personName = trim($person, " \"\t\n\r\0\x0B"); @endphp
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
                </div>

                <div class="mb-3 border-top pt-4">
                    <h6 class="fw-bold">Verifikasi & Approval</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">DH / SH</label>
                            <select class="form-select" name="DHdanSH" id="select-dhsh" data-selected="{{ $sopRevisi->DHdanSH }}" required></select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">PJO</label>
                            <select class="form-select" name="PJO" id="select-pjo" data-selected="{{ $sopRevisi->PJO }}" required></select>
                        </div>
                    </div>
                </div>

                <div class="mb-3 border-top pt-4">
                    <h6 class="fw-bold">Catatan Perubahan (Revisi)</h6>
                    <div id="catatan-revisi-container">
                        @php 
                            $catatans = explode('[END]', $sopRevisi->catatan);
                        @endphp
                        @foreach($catatans as $cat)
                            @if(!empty(trim($cat)))
                                @php
                                    preg_match('/\[HAL:(.*?)\](.*)/s', $cat, $match);
                                    $hal = $match[1] ?? '';
                                    $msg = $match[2] ?? '';
                                @endphp
                                <div class="catatan-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <input type="text" class="form-control catatan-halaman" value="{{ trim($hal) }}" placeholder="Halaman">
                                        </div>
                                        <div class="col-md-9 mb-2">
                                            <textarea class="form-control catatan-isi" rows="2">{{ trim($msg) }}</textarea>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus Poin Catatan</button>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <button class="btn btn-outline-primary btn-sm w-100" type="button" onclick="addCatatanRow()">+ Tambah Catatan Per Halaman</button>
                    <input type="hidden" name="catatan" id="catatan-final">
                </div>

                <button type="submit" class="btn btn-success w-100 mt-4">Update Data Revisi</button>
            </form>
        </div>
    </div>
</div>

<script>
    let allStaffData = [];

    document.addEventListener('DOMContentLoaded', function() {
        const selectDHSH = document.getElementById('select-dhsh');
        const selectPJO = document.getElementById('select-pjo');
        
        fetch('/api/get-approvers')
            .then(response => response.json())
            .then(data => {
                allStaffData = data.all_users; 
                
                // Load DHSH
                selectDHSH.innerHTML = '<option value="">-- Pilih DH/SH --</option>';
                data.dhsh.forEach(user => {
                    let option = new Option(`${user.nama} (NRP : ${user.nrp})`, user.nama, false, user.nama === selectDHSH.getAttribute('data-selected'));
                    selectDHSH.add(option);
                });

                // Load PJO
                selectPJO.innerHTML = '<option value="">-- Pilih PJO --</option>';
                data.pjo.forEach(user => {
                    let option = new Option(`${user.nama} (NRP : ${user.nrp})`, user.nama, false, user.nama === selectPJO.getAttribute('data-selected'));
                    selectPJO.add(option);
                });

                // Sync People Selects
                document.querySelectorAll('.select-people-item').forEach(select => {
                    const selectedVal = select.getAttribute('data-selected');
                    select.innerHTML = '<option value="">-- Pilih Nama --</option>';
                    allStaffData.forEach(user => {
                        let option = new Option(`${user.nama} (NRP : ${user.nrp})`, user.nama, false, user.nama === selectedVal);
                        select.add(option);
                    });
                });
            });
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
        div.innerHTML = `<div class="mb-2"><input type="text" class="form-control aktivitas-judul" placeholder="Sub Judul"></div><div class="mb-2"><textarea class="form-control aktivitas-isi" rows="3" placeholder="Deskripsi..."></textarea></div><div class="mb-2"><input type="text" class="form-control aktivitas-pic" placeholder="PIC"></div><button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus</button>`;
        container.appendChild(div);
    }

    function addLampiran() {
        const container = document.getElementById('lampiran-container');
        const div = document.createElement('div');
        div.className = 'lampiran-item border p-3 mb-3 rounded bg-light';
        div.innerHTML = `<input type="text" class="form-control mb-2" name="lampiran_judul[]" placeholder="Judul Lampiran"><textarea class="form-control mb-2" name="lampiran_text[]" rows="2" placeholder="Keterangan..."></textarea><input type="file" class="form-control" name="lampiran_file[]" accept="image/*"><button class="btn btn-sm btn-outline-danger mt-2" type="button" onclick="this.parentElement.remove()">Hapus</button>`;
        container.appendChild(div);
    }

    function addPeopleRow() {
        const container = document.getElementById('people-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        let options = '<option value="">-- Pilih Nama --</option>';
        allStaffData.forEach(user => { options += `<option value="${user.nama}">${user.nama} (NRP: ${user.nrp})</option>`; });
        div.innerHTML = `<select class="form-select select-people-item">${options}</select><button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">-</button>`;
        container.appendChild(div);
    }

    function addCatatanRow() {
        const container = document.getElementById('catatan-revisi-container');
        const div = document.createElement('div');
        div.className = 'catatan-item border p-3 mb-3 rounded bg-light';
        div.innerHTML = `<div class="row"><div class="col-md-3 mb-2"><input type="text" class="form-control catatan-halaman" placeholder="Halaman"></div><div class="col-md-9 mb-2"><textarea class="form-control catatan-isi" rows="2" placeholder="Detail perubahan..."></textarea></div></div><button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus</button>`;
        container.appendChild(div);
    }

    function combineFields() {
        ['referensi', 'definisi'].forEach(field => {
            const inputs = document.querySelectorAll(`.ref-input-${field}`);
            const values = Array.from(inputs).map(i => i.value.trim()).filter(v => v !== "");
            document.getElementById(`${field}-final`).value = values.join('\n');
        });

        const people = Array.from(document.querySelectorAll('.select-people-item')).map(s => s.value).filter(v => v !== "");
        document.getElementById('people-final').value = people.join(',');

        const aktItems = document.querySelectorAll('.aktivitas-item');
        let aktText = "";
        aktItems.forEach(item => {
            const j = item.querySelector('.aktivitas-judul').value.trim();
            const i = item.querySelector('.aktivitas-isi').value.trim();
            const p = item.querySelector('.aktivitas-pic').value.trim() || "User";
            if(j || i) aktText += `**${j}**\n${i} [PIC] ${p} [END] `;
        });
        document.getElementById('aktifitas-final').value = aktText.trim();

        const catItems = document.querySelectorAll('.catatan-item');
        let catText = "";
        catItems.forEach(item => {
            const h = item.querySelector('.catatan-halaman').value.trim();
            const i = item.querySelector('.catatan-isi').value.trim();
            if(h || i) catText += `[HAL:${h}] ${i} [END] `;
        });
        document.getElementById('catatan-final').value = catText.trim();
    }

    document.getElementById('multiStepForm').addEventListener('submit', function() {
        combineFields();
    });
</script>
@endsection
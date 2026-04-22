@extends ('layouts.main')
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
            <h5 class="mb-0">REVISI DOKUMEN</h5>
        </div>
        <div class="card-body">
            <form action="{{route('dataDokumenRevisi.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Jenis Dokumen</label>
                    <select class="form-select @error('jenis_doc') is-invalid @enderror" name="jenis_doc" id="jenis_doc" required>
                        <option value="" selected disabled>-- Pilih Jenis Dokumen --</option>
                        <option value="SOP" {{ old('jenis_doc') == 'SOP' ? 'selected' : '' }}>SOP (Standard Operating Procedure)</option>
                        <option value="IK" {{ old('jenis_doc') == 'IK' ? 'selected' : '' }}>IK (Instruksi Kerja)</option>
                        <option value="SP" {{ old('jenis_doc') == 'SP' ? 'selected' : '' }}>SP (Standar Parameter)</option>
                        <option value="JSA" {{ old('jenis_doc') == 'JSA' ? 'selected' : '' }}>JSA (Job Safety Analysis)</option>
                    </select>
                    @error('jenis_doc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">No Dokumen</label>
                    <select class="form-select" name="no_dokumen" id="no_dokumen" required disabled>
                        <option value="">-- Pilih Jenis Terlebih Dahulu --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Judul Dokumen</label>
                    <input type="text" class="form-control" name="judul" id="judul" readonly required/>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload PDF</label>
                    <input type="file" class="form-control" name="file" accept="application/pdf" required/>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dept/Sect Head</label>
                    <select class="form-select" name="DHdanSH" id="DHdanSH" required>
                        <option value="">-- Memuat Nama Atasan... --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">PJO</label>
                    <select class="form-select" name="PJO" id="PJO" required>
                        <option value="">-- Memuat Nama PJO... --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Departemen</label>
                    <input type="text" class="form-control" name="departemen" id="user_dept" value="{{ Auth::user()->departemen }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pembuat</label>
                    <input type="text" class="form-control" name="pembuat" value="{{ Auth::user()->nama }}" readonly>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
    const jenisDoc   = document.getElementById('jenis_doc');
    const noDocSelect = document.getElementById('no_dokumen');
    const judulInput  = document.getElementById('judul');

    // Listener saat Jenis Dokumen dipilih
    jenisDoc.addEventListener('change', function() {
        const jenis = this.value;
        noDocSelect.innerHTML = '<option value="">-- Memuat Nomor Dokumen... --</option>';
        noDocSelect.disabled = true;
        judulInput.value = ''; 

        if (jenis) {
            fetch(`/get-nomor-dokumen/${jenis}`)
                .then(response => response.json())
                .then(data => {
                    noDocSelect.innerHTML = '<option value="" selected disabled>-- Pilih No Dokumen --</option>';
                    window.currentDocData = data; // Simpan data sementara
                    
                    data.forEach(item => {
                        let option = document.createElement('option');
                        option.value = item.no_dokumen;
                        option.text  = item.no_dokumen;
                        noDocSelect.appendChild(option);
                    });
                    noDocSelect.disabled = false;
                })
                .catch(err => {
                    console.error(err);
                    noDocSelect.innerHTML = '<option value="">Data tidak ditemukan</option>';
                });
        }
    });

    // Listener saat Nomor Dokumen dipilih (Auto-fill Judul)
    noDocSelect.addEventListener('change', function() {
        const selectedNo = this.value;
        const matched = window.currentDocData.find(doc => doc.no_dokumen === selectedNo);
        if (matched) {
            judulInput.value = matched.judul;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const atasanSelect = document.getElementById('DHdanSH');

        fetch('/get-daftar-atasan')
            .then(response => response.json())
            .then(data => {
                atasanSelect.innerHTML = '<option value="" selected disabled>-- Pilih Atasan --</option>';
                data.forEach(user => {
                    let option = document.createElement('option');
                    // Kamu bisa simpan ID atau Nama (disini saya contohkan Nama sesuai input awalmu)
                    option.value = user.nama; 
                    option.text  = user.nama;
                    atasanSelect.appendChild(option);
                });
            })
            .catch(err => {
                console.error('Gagal memuat atasan:', err);
                atasanSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const atasanSelect = document.getElementById('PJO');

        fetch('/get-daftar-pjo')
            .then(response => response.json())
            .then(data => {
                atasanSelect.innerHTML = '<option value="" selected disabled>-- Pilih PJO --</option>';
                data.forEach(user => {
                    let option = document.createElement('option');
                    // Kamu bisa simpan ID atau Nama (disini saya contohkan Nama sesuai input awalmu)
                    option.value = user.nama; 
                    option.text  = user.nama;
                    atasanSelect.appendChild(option);
                });
            })
            .catch(err => {
                console.error('Gagal memuat atasan:', err);
                atasanSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    });
</script>
@endsection
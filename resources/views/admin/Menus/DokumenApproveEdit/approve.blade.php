@extends('layouts.main')
@section('container')

<style>
#pdf-wrapper {
    position: relative;
    display: inline-block;
}
#click-layer {
    position: absolute;
    top: 0;
    left: 0;
    cursor: crosshair;
}
#signature-preview {
    position: absolute;
    width: 170px;
    pointer-events: none;
    opacity: 0.8;
    display: none;
    transform: translate(-50%, -50%); /* KUNCI PUSAT */
}
</style>

<div class="card">
    <div class="card-header">Approve Dokumen</div>
    <div class="card-body">

        <div id="pdf-wrapper">
            <canvas id="pdf-canvas"></canvas>
            <canvas id="click-layer"></canvas>

            <img id="signature-preview"
                 src="{{ asset('storage/signature/'.Auth::user()->signature) }}">
        </div>

        <form action="{{ route('dokumen.approve.save', $dokumen->id) }}" method="POST">
            @csrf
            <input type="hidden" name="ratio_x" id="ratio_x">
            <input type="hidden" name="ratio_y" id="ratio_y">

            <button class="btn btn-success mt-3" id="btnSave" disabled>
                Simpan & Approve
            </button>
        </form>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
const url = "{{ asset('storage/newDokumen/'.$dokumen->file) }}";
const pdfCanvas = document.getElementById('pdf-canvas');
const clickLayer = document.getElementById('click-layer');
const preview = document.getElementById('signature-preview');
const btnSave = document.getElementById('btnSave');

let canvasWidth = 0;
let canvasHeight = 0;
let locked = false;

pdfjsLib.getDocument(url).promise.then(pdf => {
    pdf.getPage(1).then(page => {
        const viewport = page.getViewport({ scale: 1.5 });

        pdfCanvas.width = viewport.width;
        pdfCanvas.height = viewport.height;
        clickLayer.width = viewport.width;
        clickLayer.height = viewport.height;

        canvasWidth = viewport.width;
        canvasHeight = viewport.height;

        page.render({
            canvasContext: pdfCanvas.getContext('2d'),
            viewport: viewport
        });
    });
});

/* PREVIEW MENGIKUT KURSOR */
clickLayer.addEventListener('mousemove', e => {
    if (locked) return;

    preview.style.display = 'block';
    preview.style.left = e.offsetX + 'px';
    preview.style.top  = e.offsetY + 'px';
});

/* KLIK = KUNCI POSISI */
clickLayer.addEventListener('click', e => {
    const ratioX = e.offsetX / canvasWidth;
    const ratioY = e.offsetY / canvasHeight;

    document.getElementById('ratio_x').value = ratioX;
    document.getElementById('ratio_y').value = ratioY;

    locked = true;
    btnSave.disabled = false;

    alert('Posisi tanda tangan dikunci, klik Simpan & Approve');
});
</script>

@endsection

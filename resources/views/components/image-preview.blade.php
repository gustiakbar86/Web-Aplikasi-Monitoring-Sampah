@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showImage(e, imageUrl) {
        e.preventDefault();
        Swal.fire({
            imageUrl: imageUrl,
            imageAlt: 'Foto Sampah',
            width: '80%',
            imageWidth: 'auto',
            imageHeight: 'auto',
            showCloseButton: true,
            showConfirmButton: false
        });
    }
</script>
@endpush
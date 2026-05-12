@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formSampah');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah anda yakin ingin menyimpan data?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hapus event listener beforeunload sebelum submit
                        window.onbeforeunload = null;
                        form.submit();
                    }
                });
            });

            // Konfirmasi saat klik tombol kembali
            const backBtn = document.querySelector('.btn-secondary');
            if (backBtn) {
                backBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah anda yakin ingin kembali? Data yang telah diisi akan hilang.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Kembali',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.history.back();
                        }
                    });
                });
            }
        }
    });
</script>
@endpush
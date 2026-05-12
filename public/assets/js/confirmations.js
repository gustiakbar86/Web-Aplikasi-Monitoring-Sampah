// Function to show confirmation dialog
function showConfirmation(title, text, icon, confirmButtonText, callback) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#1E3F8C',
        cancelButtonColor: '#d33',
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// Add confirmation for all delete forms
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showConfirmation(
            'Konfirmasi Hapus',
            'Apakah Anda yakin ingin menghapus data ini?',
            'warning',
            'Ya, Hapus',
            () => this.submit()
        );
    });
});

// Add confirmation for all update forms
document.querySelectorAll('.update-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showConfirmation(
            'Konfirmasi Update',
            'Apakah data yang dimasukkan sudah benar?',
            'question',
            'Ya, Update',
            () => this.submit()
        );
    });
});

// Add confirmation for all add/create forms
document.querySelectorAll('.create-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showConfirmation(
            'Konfirmasi Tambah',
            'Apakah data yang dimasukkan sudah benar?',
            'question',
            'Ya, Tambah',
            () => this.submit()
        );
    });
});

// Add confirmation for export actions
document.querySelectorAll('.export-action').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showConfirmation(
            'Konfirmasi Export',
            'Apakah Anda ingin mengexport data ini?',
            'question',
            'Ya, Export',
            () => this.submit()
        );
    });
});

// Add confirmation for logout
document.querySelectorAll('.logout-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showConfirmation(
            'Konfirmasi Logout',
            'Apakah Anda yakin ingin keluar dari sistem?',
            'warning',
            'Ya, Keluar',
            () => this.submit()
        );
    });
});
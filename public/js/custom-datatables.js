// File: public/js/custom-datatables.js

$(document).ready(function() {
    // Inisialisasi DataTables untuk tabel Aturan Fuzzy
    if ($('#aturanFuzzyTable').length) { 
        $('#aturanFuzzyTable').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(difilter dari _MAX_ total entri)",
                "zeroRecords": "Tidak ada data yang cocok ditemukan",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": -1 } // Menargetkan kolom terakhir (Aksi)
            ],
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
        });
    }

    // Inisialisasi DataTables untuk tabel Tempat Bisnis
    if ($('#tempatBisnisTable').length) { 
        $('#tempatBisnisTable').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(difilter dari _MAX_ total entri)",
                "zeroRecords": "Tidak ada data yang cocok ditemukan",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            },
            "columnDefs": [
                // Kolom 'Aksi' di tempatBisnisTable adalah indeks ke-5 (0-indexed)
                // Jika Anda menambahkan kelas 'no-sort' ke th Aksi, Anda bisa pakai:
                // { "orderable": false, "targets": ".no-sort" }
                { "orderable": false, "targets": 5 } 
            ],
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
        });
    }

    // Inisialisasi DataTables untuk tabel Parameter
    if ($('#parametersTable').length) { 
        $('#parametersTable').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(difilter dari _MAX_ total entri)",
                "zeroRecords": "Tidak ada data yang cocok ditemukan",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            },
            "columnDefs": [
                // Kolom 'Detail Himpunan Fuzzy' (indeks 2) dan 'Aksi' (indeks 3) tidak bisa di-sort
                // Jika Anda menambahkan kelas 'no-sort' ke th Aksi, Anda bisa pakai:
                // { "orderable": false, "targets": [2, ".no-sort"] }
                { "orderable": false, "targets": [2, 3] } 
            ],
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
        });
    }
});

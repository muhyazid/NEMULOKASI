/**
 * Fuzzy Lokasi - Main JavaScript File
 * ---------------------------------
 * Script untuk menangani fungsionalitas utama aplikasi
 */

$(document).ready(function () {
    /**
     * Inisialisasi aplikasi
     */
    initializeApp();

    /**
     * Setup sidebar behavior
     */
    setupSidebar();

    /**
     * Setup event listeners
     */
    setupEventListeners();
});

/**
 * Inisialisasi aplikasi
 */
function initializeApp() {
    // Hilangkan preloader
    $(".preloader").fadeOut("slow");

    // Nonaktifkan auto-expand saat hover sidebar
    $("body").addClass("sidebar-no-expand");
}

/**
 * Setup sidebar behavior
 */
function setupSidebar() {
    // Otomatis tampilkan submenu jika parent menu aktif (hanya jika sidebar tidak collapsed)
    if (!$("body").hasClass("sidebar-collapse")) {
        $(".nav-item.menu-open > .nav-treeview").show();
        // Pastikan ikon panah bawah berubah saat dropdown terbuka
        $(".nav-sidebar .nav-item.menu-open > .nav-link .right").css(
            "transform",
            "rotate(-180deg)"
        );
    }
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Toggle submenu pada klik
    $(".nav-sidebar .nav-item > .nav-link").on("click", function (e) {
        // Hanya mencegah default jika link memiliki submenu
        if ($(this).next(".nav-treeview").length > 0) {
            e.preventDefault();
            var parent = $(this).parent();
            parent.toggleClass("menu-open");
            parent.find("> .nav-treeview").slideToggle();
        }
    });

    // Event listener untuk tombol toggle sidebar
    $('[data-widget="pushmenu"]').on("click", function () {
        // Menunggu animasi sidebar selesai
        setTimeout(function () {
            if ($("body").hasClass("sidebar-collapse")) {
                // Jika sidebar di-collapse, tutup semua dropdown
                $(".nav-item.menu-open").removeClass("menu-open");
                $(".nav-treeview").hide();
            } else {
                // Jika sidebar di-expand, buka kembali menu yang aktif
                var activeItems = $(
                    ".nav-sidebar .nav-item:has(.nav-link.active)"
                );
                activeItems.parents(".nav-item").addClass("menu-open");
                activeItems.parents(".nav-item").find("> .nav-treeview").show();
            }
        }, 300); // Tunggu 300ms untuk animasi selesai
    });

    // Handle toggle fullscreen
    $('.nav-link[data-widget="fullscreen"]').on("click", function () {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else {
            document.documentElement.requestFullscreen();
        }
    });
}

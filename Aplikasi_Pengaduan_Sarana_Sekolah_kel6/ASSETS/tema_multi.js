
/* =====================================================
   THEME SYSTEM
===================================================== */

// Ambil tema yang tersimpan
(function () {
    const savedTheme = localStorage.getItem("library-theme");

    if (savedTheme === "dark") {
        document.documentElement.classList.add("dark-mode");
    }
})();


document.addEventListener("DOMContentLoaded", function () {

    // Ambil semua tombol tema
    const buttons = document.querySelectorAll(".theme-toggle");

    // Fungsi untuk mengganti ikon tombol
    function updateThemeButton(button) {
        const isDark = document.body.classList.contains("dark-mode");

        if (isDark) {
            button.innerHTML = "☀️";
            button.title = "Ganti ke tema terang";
        } else {
            button.innerHTML = "🌙";
            button.title = "Ganti ke tema gelap";
        }

        button.setAttribute("aria-label", button.title);
    }

    // Fungsi untuk menerapkan tema
    function applyTheme(isDark) {
        document.body.classList.toggle("dark-mode", isDark);
        document.documentElement.classList.toggle("dark-mode", isDark);

        localStorage.setItem(
            "library-theme",
            isDark ? "dark" : "light"
        );

        buttons.forEach(function (button) {
            updateThemeButton(button);
        });
    }

    // Terapkan tema saat halaman dibuka
    const savedTheme = localStorage.getItem("library-theme");
    applyTheme(savedTheme === "dark");

    // Jalankan tombol tema
    buttons.forEach(function (button) {
        button.addEventListener("click", function () {
            const isDark = !document.body.classList.contains("dark-mode");
            applyTheme(isDark);
        });
    });

});

document.addEventListener("DOMContentLoaded", function () {
    const backArrow = document.querySelector('.back-arrow');

    backArrow.addEventListener('click', function (e) {
        e.preventDefault();
        window.history.back(); // Kembali ke halaman sebelumnya
    });
});
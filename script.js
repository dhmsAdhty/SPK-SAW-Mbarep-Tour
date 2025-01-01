// Menutup modal
function closeModal() {
    document.getElementById('errorModal').style.display = 'none';
}

// Menutup modal otomatis setelah 5 detik
setTimeout(function() {
    var modal = document.getElementById('errorModal');
    if (modal) {
        modal.style.display = 'none';
    }
}, 4000);  // 5000 ms = 5 detik

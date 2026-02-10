// ===============================
// LOADING SAAT SUBMIT FORM
// ===============================
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = 'Memproses...';
                btn.classList.add('opacity-70');
            }
        });
    });
});

// ===============================
// ANIMASI CARD DASHBOARD
// ===============================
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.dashboard-card');

    cards.forEach((card, index) => {
        card.style.opacity = 0;
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = 1;
            card.style.transform = 'translateY(0)';
        }, index * 120);
    });
});

// ===============================
// PREVENT DOUBLE CLICK BUTTON
// ===============================
function disableOnce(btn) {
    btn.disabled = true;
    btn.innerText = 'Processing...';
    btn.classList.add('opacity-60');
}

// ===============================
// AUTO HIDE ALERT (OPSIONAL)
// ===============================
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = 0;
        setTimeout(() => alert.remove(), 500);
    }
}, 3000);

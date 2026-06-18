import './stimulus_bootstrap.js';
import './styles/app.css';

/* ── Page index : date en direct ──────────────────────────── */
const topBarDateEl = document.getElementById('top-bar-date');
if (topBarDateEl) {
    topBarDateEl.textContent = new Date().toLocaleDateString('fr-FR', {
        weekday: 'long', day: 'numeric', month: 'long'
    });
}

/* ── Page PIN : pavé numérique ────────────────────────────── */
const pinForm = document.getElementById('pin-form');
if (pinForm) {
    let pin = '';

    function updatePinProgress() {
        document.querySelectorAll('.pin-progress__dot').forEach((dot, index) => {
            dot.classList.toggle('pin-progress__dot--filled', index < pin.length);
        });
    }

    window.addDigit = function(digit) {
        if (pin.length < 6) {
            pin += digit;
            updatePinProgress();
            if (pin.length === 6) {
                document.getElementById('pin-hidden-input').value = pin;
                pinForm.submit();
            }
        }
    };

    window.deleteDigit = function() {
        pin = pin.slice(0, -1);
        updatePinProgress();
    };
}

/* ── Page action : horloge en direct ─────────────────────── */
const liveTimeEl = document.getElementById('live-time');
if (liveTimeEl) {
    function updateClock() {
        const now = new Date();
        liveTimeEl.textContent = now.toLocaleTimeString('fr-FR', {
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
        document.getElementById('live-date').textContent = now.toLocaleDateString('fr-FR', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });
    }
    updateClock();
    setInterval(updateClock, 1000);
}

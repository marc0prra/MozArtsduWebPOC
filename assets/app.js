import './stimulus_bootstrap.js';
import './styles/app.css';

let clockInterval = null;

document.addEventListener('turbo:load', function () {

    /* ── Page index : affiche la date du jour dans l'en-tête ── */
    const topBarDateEl = document.getElementById('top-bar-date');
    if (topBarDateEl) {
        topBarDateEl.textContent = new Date().toLocaleDateString('fr-FR', {
            weekday: 'long', day: 'numeric', month: 'long'
        });
    }

    /*  Page PIN : gestion du pavé numérique */
    const pinForm = document.getElementById('pin-form');
    if (pinForm) {
        let pin = '';

        function updatePinProgress() {
            document.querySelectorAll('.pin-progress__dot').forEach((dot, index) => {
                dot.classList.toggle('pin-progress__dot--filled', index < pin.length);
            });
        }

        document.getElementById('number-pad').addEventListener('click', function (event) {
            const key = event.target.closest('.number-pad__key');
            if (!key) return;

            if (key.dataset.digit !== undefined) {
                if (pin.length < 6) {
                    pin += key.dataset.digit;
                    updatePinProgress();

                    if (pin.length === 6) {
                        document.getElementById('pin-hidden-input').value = pin;
                        pinForm.submit();
                    }
                }
            } else if (key.dataset.delete !== undefined) {
                pin = pin.slice(0, -1);
                updatePinProgress();
            }
        });
    }

    /*  Page action : horloge en direct */
    const liveTimeEl = document.getElementById('live-time');
    if (liveTimeEl) {
        clearInterval(clockInterval);

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
        clockInterval = setInterval(updateClock, 1000);
    }
});

import './stimulus_bootstrap.js';
import './styles/app.css';

/**
 * Initialise les comportements JavaScript selon la page affichée.
 *
 * Pourquoi turbo:load et pas DOMContentLoaded ?
 * Turbo (@hotwired/turbo) intercepte les clics sur les liens et remplace
 * le contenu de la page en AJAX sans recharger le script. DOMContentLoaded
 * ne se déclencherait qu'une seule fois (au premier chargement), donc les
 * fonctions du pavé numérique ne seraient jamais définies après navigation.
 * turbo:load se déclenche après chaque navigation Turbo, ce qui garantit
 * que window.addDigit et window.deleteDigit sont toujours disponibles.
 */
document.addEventListener('turbo:load', function () {

    /* ── Page index : affiche la date du jour dans l'en-tête ── */
    const topBarDateEl = document.getElementById('top-bar-date');
    if (topBarDateEl) {
        topBarDateEl.textContent = new Date().toLocaleDateString('fr-FR', {
            weekday: 'long', day: 'numeric', month: 'long'
        });
    }

    /* ── Page PIN : gestion du pavé numérique ─────────────────
       Les fonctions addDigit et deleteDigit sont exposées sur window
       pour être appelées par les attributs onclick des boutons du pavé.
       Le formulaire se soumet automatiquement quand les 6 chiffres sont saisis.
    ────────────────────────────────────────────────────────── */
    const pinForm = document.getElementById('pin-form');
    if (pinForm) {
        let pin = ''; // PIN saisi par l'utilisateur (jamais affiché, seulement compté)

        function updatePinProgress() {
            // Remplit les points en fonction du nombre de chiffres déjà saisis
            document.querySelectorAll('.pin-progress__dot').forEach((dot, index) => {
                dot.classList.toggle('pin-progress__dot--filled', index < pin.length);
            });
        }

        window.addDigit = function (digit) {
            if (pin.length < 6) {
                pin += digit;
                updatePinProgress();

                // Soumission automatique dès que les 6 chiffres sont saisis
                if (pin.length === 6) {
                    document.getElementById('pin-hidden-input').value = pin;
                    pinForm.submit();
                }
            }
        };

        window.deleteDigit = function () {
            pin = pin.slice(0, -1);
            updatePinProgress();
        };
    }

    /* ── Page action : horloge en direct ──────────────────────
       Met à jour l'heure et la date toutes les secondes.
    ────────────────────────────────────────────────────────── */
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
});

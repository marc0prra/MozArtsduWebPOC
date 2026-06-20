<?php

use App\Kernel;

// Définit le fuseau horaire de l'application (stockage et affichage cohérents)
date_default_timezone_set('Europe/Paris');

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return static function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};

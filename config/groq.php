<?php

return [
    /*
     * GROQ API Key
     * Esta clave se lee desde la variable de entorno GROQ_API_KEY.
     * Si Dokploy la inyecta al proceso PHP-FPM, env() la encontrará.
     * En caso contrario, el controlador la escribe en /tmp/.groq_key
     * usando el valor de esta configuración para que Python la pueda leer.
     */
    'api_key' => env('GROQ_API_KEY'),
];

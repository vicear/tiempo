<?php
/**
 * Funciones relacionadas con la API del tiempo
 */

// Función para obtener datos del tiempo desde OpenWeatherMap API
function getWeather($city) {
    // En una aplicación real, usarías tu propia clave API
    // Para este ejemplo, simularemos la respuesta de la API
    $apiKey = "YOUR_API_KEY"; // Reemplazar con una clave API real en producción
    
    // Para fines de demostración, devolveremos datos simulados si no hay clave API
    if ($apiKey == "YOUR_API_KEY") {
        // Datos simulados para demostración
        return [
            "city" => $city,
            "temperature" => rand(5, 35),
            "condition" => ["Despejado", "Nublado", "Lluvioso", "Soleado", "Tormentoso"][rand(0, 4)],
            "humidity" => rand(30, 90),
            "wind_speed" => rand(0, 30)
        ];
    }
    
    // En una aplicación real, harías una llamada a la API:
    $url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";
    $response = file_get_contents($url);
    
    if ($response === false) {
        throw new Exception("No se pudo conectar con la API del tiempo");
    }
    
    $data = json_decode($response, true);
    
    if (isset($data['cod']) && $data['cod'] != 200) {
        throw new Exception($data['message'] ?? "Error desconocido");
    }
    
    // Formatear los datos de la API
    return [
        "city" => $data['name'],
        "temperature" => round($data['main']['temp']),
        "condition" => $data['weather'][0]['main'],
        "humidity" => $data['main']['humidity'],
        "wind_speed" => round($data['wind']['speed'] * 3.6) // Convertir m/s a km/h
    ];
}
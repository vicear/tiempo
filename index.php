<?php
// Incluir archivos necesarios
require_once 'includes/weather.php';

// Procesar el formulario
$weather = null;
$error = null;
$city = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["city"])) {
    $city = htmlspecialchars($_POST["city"]);
    
    if (empty($city)) {
        $error = "Por favor, introduce una ciudad";
    } else {
        try {
            $weather = getWeather($city);
        } catch (Exception $e) {
            $error = "Error al obtener los datos del clima: " . $e->getMessage();
        }
    }
}

// Si no hay búsqueda, mostrar una ciudad por defecto
if (!$weather && !$error) {
    $defaultCities = ["Madrid", "Barcelona", "Valencia", "Sevilla", "Bilbao"];
    $randomCity = $defaultCities[array_rand($defaultCities)];
    try {
        $weather = getWeather($randomCity);
        $city = $randomCity;
    } catch (Exception $e) {
        // Silenciar error para ciudad por defecto
    }
}

// Incluir cabecera
include 'includes/header.php';
?>

<div class="hero">
    <div class="search-container">
        <h1>¿Cómo está el tiempo hoy?</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="search-form">
            <input type="text" name="city" placeholder="Introduce una ciudad..." value="<?php echo $city; ?>">
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
        </form>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <?php if ($weather): ?>
        <div class="weather-card">
            <div class="weather-header">
                <h2><?php echo $weather["city"]; ?></h2>
                <p class="date"><?php echo date('l, j \d\e F \d\e Y'); ?></p>
            </div>
            
            <div class="weather-body">
                <div class="weather-icon">
                    <?php 
                    $iconFile = 'sunny.png'; // Default
                    switch(strtolower($weather["condition"])) {
                        case 'despejado':
                        case 'clear':
                            $iconFile = 'clear.png';
                            break;
                        case 'nublado':
                        case 'cloudy':
                        case 'clouds':
                            $iconFile = 'cloudy.png';
                            break;
                        case 'lluvioso':
                        case 'rainy':
                        case 'rain':
                            $iconFile = 'rainy.png';
                            break;
                        case 'tormentoso':
                        case 'stormy':
                        case 'thunderstorm':
                            $iconFile = 'stormy.png';
                            break;
                        case 'soleado':
                        case 'sunny':
                            $iconFile = 'sunny.png';
                            break;
                    }
                    ?>
                    <img src="images/<?php echo $iconFile; ?>" alt="<?php echo $weather["condition"]; ?>">
                </div>
                <div class="weather-info">
                    <div class="temperature"><?php echo $weather["temperature"]; ?><span>°C</span></div>
                    <div class="condition"><?php echo $weather["condition"]; ?></div>
                </div>
            </div>
            
            <div class="weather-details">
                <div class="detail">
                    <div class="detail-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>
                    </div>
                    <div class="detail-info">
                        <h3>Humedad</h3>
                        <p><?php echo $weather["humidity"]; ?>%</p>
                    </div>
                </div>
                <div class="detail">
                    <div class="detail-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.7 7.7a2.5 2.5 0 1 1 1.8 4.3H2"></path><path d="M9.6 4.6A2 2 0 1 1 11 8H2"></path><path d="M12.6 19.4A2 2 0 1 0 14 16H2"></path></svg>
                    </div>
                    <div class="detail-info">
                        <h3>Viento</h3>
                        <p><?php echo $weather["wind_speed"]; ?> km/h</p>
                    </div>
                </div>
            </div>
            
            <div class="forecast">
                <h3>Pronóstico para los próximos días</h3>
                <div class="forecast-days">
                    <?php
                    // Generar pronóstico simulado para los próximos 5 días
                    $currentTemp = $weather["temperature"];
                    for ($i = 1; $i <= 5; $i++) {
                        $dayTemp = $currentTemp + rand(-3, 3);
                        $dayName = date('D', strtotime("+$i day"));
                        $conditions = ["Despejado", "Nublado", "Lluvioso", "Soleado"];
                        $dayCondition = $conditions[array_rand($conditions)];
                        
                        echo '<div class="forecast-day">';
                        echo '<span class="day-name">' . $dayName . '</span>';
                        echo '<span class="day-temp">' . $dayTemp . '°C</span>';
                        echo '<span class="day-condition">' . $dayCondition . '</span>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="other-cities">
            <h3>Otras ciudades populares</h3>
            <div class="cities-grid">
                <?php
                $popularCities = ["Madrid", "Barcelona", "Valencia", "Sevilla", "Bilbao", "Málaga"];
                // Eliminar la ciudad actual de la lista
                $popularCities = array_diff($popularCities, [$city]);
                // Tomar solo 4 ciudades
                $popularCities = array_slice($popularCities, 0, 4);
                
                foreach ($popularCities as $popularCity) {
                    try {
                        $cityWeather = getWeather($popularCity);
                        echo '<a href="?city=' . urlencode($popularCity) . '" class="city-card">';
                        echo '<h4>' . $popularCity . '</h4>';
                        echo '<div class="city-temp">' . $cityWeather["temperature"] . '°C</div>';
                        echo '<div class="city-condition">' . $cityWeather["condition"] . '</div>';
                        echo '</a>';
                    } catch (Exception $e) {
                        // Ignorar errores para ciudades populares
                    }
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
// Incluir pie de página
include 'includes/footer.php';
?>
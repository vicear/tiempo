// Script.js - Funcionalidad JavaScript opcional

document.addEventListener('DOMContentLoaded', function() {
    // Animación simple para la tarjeta del tiempo
    const weatherCard = document.querySelector('.weather-card');
    if (weatherCard) {
        weatherCard.style.opacity = '0';
        weatherCard.style.transition = 'opacity 0.5s ease-in-out';
        
        setTimeout(() => {
            weatherCard.style.opacity = '1';
        }, 300);
    }
    
    // Validación del formulario en el lado del cliente
    const weatherForm = document.querySelector('form');
    if (weatherForm) {
        weatherForm.addEventListener('submit', function(e) {
            const cityInput = this.querySelector('input[name="city"]');
            if (!cityInput.value.trim()) {
                e.preventDefault();
                
                // Crear mensaje de error si no existe
                let errorMsg = document.querySelector('.error');
                if (!errorMsg) {
                    errorMsg = document.createElement('p');
                    errorMsg.className = 'error';
                    weatherForm.after(errorMsg);
                }
                
                errorMsg.textContent = 'Por favor, introduce una ciudad';
                
                // Efecto de shake en el input
                cityInput.style.border = '1px solid #e74c3c';
                cityInput.classList.add('shake');
                
                setTimeout(() => {
                    cityInput.classList.remove('shake');
                }, 500);
            }
        });
        
        // Restablecer estilos al escribir
        const cityInput = weatherForm.querySelector('input[name="city"]');
        if (cityInput) {
            cityInput.addEventListener('input', function() {
                this.style.border = '1px solid #ddd';
            });
        }
    }
});

// Añadir clase CSS para la animación shake
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .shake {
        animation: shake 0.5s;
    }
`;
document.head.appendChild(style);
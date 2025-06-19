// Timer functionaliteit - verbeterde versie
function updateTimer() {
    // Haal het element op waar de timer in staat
    const timerElement = document.getElementById('timeRemaining');
    // Haal de huidige resterende tijd (in seconden) uit een custom attribute 'value'
    let timeLeft = parseInt(timerElement.getAttribute('value'));
    
    // Start een interval die elke seconde afloopt
    const timerInterval = setInterval(() => {
        timeLeft--;  // 1 seconde aftrekken
        timerElement.setAttribute('value', timeLeft);  // Update de opgeslagen tijd
        
        // Bereken minuten en seconden voor weergave
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = minutes + " minuten en " + seconds + " seconden";
        
        // Als minder dan 1 minuut over is, maak de timer visueel opvallend (rode achtergrond + animatie)
        if (timeLeft <= 60) {
            timerElement.style.background = 'rgba(255, 0, 0, 0.5)';
            timerElement.style.animation = 'pulse 0.5s infinite';  // pulse animatie (CSS moet dit definiëren)
        }
        
        // Bij minder dan 30 seconden wordt de achtergrond donkerder en het lettertype groter
        if (timeLeft <= 30) {
            timerElement.style.background = 'rgba(255, 0, 0, 0.8)';
            timerElement.style.fontSize = '22px';
        }
        
        // Als de tijd op is, stop de timer en stuur de gebruiker door naar 'lose.php' (verloren pagina)
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            window.location.href = 'lose.php';
            return;
        }
    }, 1000); // Elke 1000ms (1 seconde)
}


// Vraag 1: Verhaal onthullen als je erop klikt
function revealStory(element) {
    // Controleer of er nog tijd over is, anders direct verloren
    const timeLeft = parseInt(document.getElementById('timeRemaining').getAttribute('value'));
    if (timeLeft <= 0) {
        window.location.href = 'lose.php';
        return;
    }

    // Vind de verborgen tekst en het preview element binnen het aangeklikte element
    const storyContent = element.querySelector('.story-content');
    const storyPreview = element.querySelector('.story-preview');
    
    // Als het verhaal nog niet zichtbaar is, toon het en verberg de preview
    if (storyContent.style.display === 'none') {
        storyContent.style.display = 'block';
        storyPreview.style.display = 'none';
        
        // Als dit het juiste verhaal is, voeg dan een 'correct' class toe voor styling + animatie
        if (element.dataset.story === 'correct') {
            element.classList.add('correct');
            element.style.animation = 'correctAnswer 0.8s ease-in-out';  // CSS animatie (zelf definiëren)
        }
    }
}


// Vraag 2: Geheim verhaal onthullen na klik op afbeelding
function revealMystery() {
    // Check of er nog tijd is, anders verloren
    const timeLeft = parseInt(document.getElementById('timeRemaining').getAttribute('value'));
    if (timeLeft <= 0) {
        window.location.href = 'lose.php';
        return;
    }

    // Toon het verborgen geheim verhaal door een class toe te voegen
    const mysteryStory = document.getElementById('mysteryStory');
    mysteryStory.classList.add('show');  // CSS moet 'show' definiëren om zichtbaar te maken
}


// Vraag 3: Veldslag markeren
function highlightBattle(element) {
    // Check tijd
    const timeLeft = parseInt(document.getElementById('timeRemaining').getAttribute('value'));
    if (timeLeft <= 0) {
        window.location.href = 'lose.php';
        return;
    }

    // Verwijder 'highlight' class van alle veldslag kaarten
    document.querySelectorAll('.battle-card').forEach(card => {
        card.classList.remove('highlight');
    });
    
    // Voeg 'highlight' toe aan de aangeklikte kaart
    element.classList.add('highlight');
    
    // Extra effect als dit de juiste veldslag is (austerlitz): vergroot de kaart kort
    if (element.dataset.battle === 'austerlitz') {
        element.style.transform = 'scale(1.1)';
        setTimeout(() => {
            element.style.transform = 'scale(1.05)';
        }, 200);
    }
}


// Initialiseer alles als de pagina geladen is
document.addEventListener('DOMContentLoaded', function() {
    updateTimer();  // Start de timer
    
    // Voorkom dat formulieren verzonden worden als de tijd al om is
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const timeLeft = parseInt(document.getElementById('timeRemaining').getAttribute('value'));
            if (timeLeft <= 0) {
                e.preventDefault();  // Stop het verzenden
                window.location.href = 'lose.php';  // Redirect naar verloren pagina
            }
        });
    });
});


// Maak vallende deeltjes (visueel effect)
function createParticle() {
    // Check of er nog tijd is
    const timeLeft = parseInt(document.getElementById('timeRemaining').getAttribute('value'));
    if (timeLeft <= 0) return;

    // Maak een nieuw deeltje (div)
    const particle = document.createElement('div');
    particle.style.position = 'fixed';
    particle.style.width = '4px';
    particle.style.height = '4px';
    particle.style.background = '#f39c12';  // Oranje kleur
    particle.style.borderRadius = '50%';  // Rond
    particle.style.pointerEvents = 'none';  // Klikken gaat erdoorheen
    particle.style.zIndex = '1000';
    particle.style.left = Math.random() * window.innerWidth + 'px';  // Willekeurige horizontale positie
    particle.style.top = '-10px';  // Net boven het scherm
    particle.style.opacity = '0.7';
    
    document.body.appendChild(particle);
    
    // Laat het deeltje naar beneden vallen en vervagen
    let pos = -10;
    const fall = setInterval(() => {
        pos += 2;  // 2px per interval omlaag
        particle.style.top = pos + 'px';
        particle.style.opacity = (1 - pos / window.innerHeight);  // Wordt langzaam transparanter
        
        if (pos > window.innerHeight) {
            clearInterval(fall);  // Stop met vallen als het uit beeld is
            document.body.removeChild(particle);  // Verwijder het element uit DOM
        }
    }, 50);  // Elke 50ms
}

// Elke 2 seconden een nieuw deeltje laten vallen
setInterval(createParticle, 2000);

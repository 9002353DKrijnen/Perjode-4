//Ahmads js codes



        // Timer functionality - verbeterde versie
        function updateTimer() {
            const timerElement = document.getElementById('timeRemaining');
            let timeLeft = parseInt(timerElement.getAttribute('value'));
            
            const timerInterval = setInterval(() => {
                timeLeft--;
                timerElement.setAttribute('value', timeLeft);
                
                // Bereken minuten en seconden
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerElement.textContent = minutes + " minuten en " + seconds + " seconden";
                
                // Voeg waarschuwingseffect toe wanneer de tijd bijna op is
                if (timeLeft <= 60) {
                    timerElement.style.background = 'rgba(255, 0, 0, 0.5)';
                    timerElement.style.animation = 'pulse 0.5s infinite';
                }
                
                if (timeLeft <= 30) {
                    timerElement.style.background = 'rgba(255, 0, 0, 0.8)';
                    timerElement.style.fontSize = '22px';
                }
                
                // Stop timer en redirect bij 0
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    window.location.href = 'lose.php';
                    return;
                }
            }, 1000);
        }

        // Question 1: Story reveal functionality
        function revealStory(element) {
            const timeLeft = parseInt(document.getElementById('timeRemaining').getAttribute('value'));
            if (timeLeft <= 0) {
                window.location.href = 'lose.php';
                return;
            }

            const storyContent = element.querySelector('.story-content');
            const storyPreview = element.querySelector('.story-preview');
            
            if (storyContent.style.display === 'none') {
                storyContent.style.display = 'block';
                storyPreview.style.display = 'none';
                
                if (element.dataset.story === 'correct') {
                    element.classList.add('correct');
                    element.style.animation = 'correctAnswer 0.8s ease-in-out';
                }
            }
        }

        // Question 2: Mystery reveal
        function revealMystery() {
            const timeLeft = parseInt(document.getElementById('timeRemaining').getAttribute('value'));
            if (timeLeft <= 0) {
                window.location.href = 'lose.php';
                return;
            }

            const mysteryStory = document.getElementById('mysteryStory');
            mysteryStory.classList.add('show');
        }

        // Question 3: Battle highlight
        function highlightBattle(element) {
            const timeLeft = parseInt(document.getElementById('timeRemaining').getAttribute('value'));
            if (timeLeft <= 0) {
                window.location.href = 'lose.php';
                return;
            }

            document.querySelectorAll('.battle-card').forEach(card => {
                card.classList.remove('highlight');
            });
            
            element.classList.add('highlight');
            
            if (element.dataset.battle === 'austerlitz') {
                element.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    element.style.transform = 'scale(1.05)';
                }, 200);
            }
        }

        // Initialize timer
        document.addEventListener('DOMContentLoaded', function() {
            updateTimer();
            
            // Voorkom dat formulieren worden verzonden als de tijd om is
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const timeLeft = parseInt(document.getElementById('timeRemaining').getAttribute('value'));
                    if (timeLeft <= 0) {
                        e.preventDefault();
                        window.location.href = 'lose.php';
                    }
                });
            });
        });

        // Add some interactive particle effects
        function createParticle() {
            const timeLeft = parseInt(document.getElementById('timeRemaining').getAttribute('value'));
            if (timeLeft <= 0) return;

            const particle = document.createElement('div');
            particle.style.position = 'fixed';
            particle.style.width = '4px';
            particle.style.height = '4px';
            particle.style.background = '#f39c12';
            particle.style.borderRadius = '50%';
            particle.style.pointerEvents = 'none';
            particle.style.zIndex = '1000';
            particle.style.left = Math.random() * window.innerWidth + 'px';
            particle.style.top = '-10px';
            particle.style.opacity = '0.7';
            
            document.body.appendChild(particle);
            
            let pos = -10;
            const fall = setInterval(() => {
                pos += 2;
                particle.style.top = pos + 'px';
                particle.style.opacity = (1 - pos / window.innerHeight);
                
                if (pos > window.innerHeight) {
                    clearInterval(fall);
                    document.body.removeChild(particle);
                }
            }, 50);
        }

        // Create ambient particles
        setInterval(createParticle, 2000);
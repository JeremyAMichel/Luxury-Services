document.addEventListener('DOMContentLoaded', function() {
    const filterLinks = document.querySelectorAll('.portfolio-filter li a');
    const jobItems = document.querySelectorAll('.portfolio-item');

    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Si on veut une animation douce sans rechargement
            e.preventDefault();
            
            // Mise à jour de la classe active
            filterLinks.forEach(l => l.parentElement.classList.remove('active'));
            this.parentElement.classList.add('active');

            // Animation de fondu avant le changement de page
            document.querySelector('.portfolio').style.opacity = '0';
            
            // Redirection après l'animation
            setTimeout(() => {
                window.location.href = this.href;
            }, 300);
        });
    });

    // Animation d'apparition au chargement
    document.querySelector('.portfolio').style.opacity = '1';
});
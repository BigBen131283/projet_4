

const headerBg = document.getElementById('bg');
const menuToggle = document.querySelector('.menu_toggle');
const navigation = document.querySelector('.main_menu');
const bioToggle = document.querySelector('.bio_toggle');
const biography = document.querySelector('.displayed_bio')


// parallax accueil du site
window.addEventListener('scroll', goToMenu);

function goToMenu(){
    headerBg.style.opacity = 1 - +window.pageYOffset/400+'';
    headerBg.style.top = +window.pageYOffset+'px';
    headerBg.style.backgroundPositionY = - +window.pageYOffset/2+'px';
}

//main menu
menuToggle.onclick = function(){
    navigation.classList.toggle('active');
}

// Biographie de l'auteur
bioToggle.onclick = function(){
    biography.classList.toggle('active');
}
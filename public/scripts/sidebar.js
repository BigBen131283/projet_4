const menuToggle = document.querySelector('.sidebarToggle');
const navigation = document.querySelector('.side-navigation');
const list = document.querySelectorAll('.list');

menuToggle.onclick = function(){
    navigation.classList.toggle('open');
}

function activeLink(){
    list.forEach((item) =>
        item.classList.remove('active'));
    this.classList.add('active');
}

list.forEach((item) =>
    item.addEventListener('click', activeLink));
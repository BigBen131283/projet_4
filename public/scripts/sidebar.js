const menuToggle = document.querySelector('.sidebarToggle');
const navigation = document.querySelector('.side-navigation');
const list = document.querySelectorAll('.list');
const membersBox = document.getElementById('#members');
const moderationBox = document.getElementById('#moderate');
const chapterBox = document.getElementById('#chapter');

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
const menuToggle = document.querySelector('.sidebarToggle');
const navigation = document.querySelector('.side-navigation');
const list = document.querySelectorAll('.list');
const membersBox = document.querySelector('#members');
const moderationBox = document.querySelector('#moderate');
const chapterBox = document.querySelector('#chapters');
const writeBox = document.querySelector('#write');
const btnAll = document.querySelector('.btnAll');
const btnMembers = document.querySelector('.btnMembers');
const btnComments = document.querySelector('.btnComments');
const btnChapters = document.querySelector('.btnChapters');
const btnWrite = document.querySelector('.btnWrite');
const editButton = document.querySelector('.selectbillet');

btnAll.addEventListener('click', displayBox);
btnMembers.addEventListener('click', displayMembers);
btnComments.addEventListener('click', displayModerate);
btnChapters.addEventListener('click', displayChapters);
btnWrite.addEventListener('click', displayWrite);
editButton.addEventListener('click', displayWrite);
editButton.addEventListener('click', switchToEdit);

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

// display des parties selon le click

function displayBox(){
    if(membersBox.classList.contains('hidden')){
        membersBox.classList.remove('hidden');
    }
    if(moderationBox.classList.contains('hidden')){
        moderationBox.classList.remove('hidden');
    }
    if(chapterBox.classList.contains('hidden')){
        chapterBox.classList.remove('hidden');
    }
    if(writeBox.classList.contains('hidden')){
        writeBox.classList.remove('hidden');
    }
    if(membersBox.classList.contains('active')){
        membersBox.classList.remove('active');
    }
    if(moderationBox.classList.contains('active')){
        moderationBox.classList.remove('active');
    }
    if(chapterBox.classList.contains('active')){
        chapterBox.classList.remove('active');
    }
    if(writeBox.classList.contains('active')){
        writeBox.classList.remove('active');
    }
}

function displayMembers(){
    if(!membersBox.classList.contains('active'))
    {
        membersBox.classList.add('active');
    }
    if(membersBox.classList.contains('hidden')){
        membersBox.classList.remove('hidden');
    }
    if(moderationBox.classList.contains('active')){
        moderationBox.classList.remove('active');
    }
    if(chapterBox.classList.contains('active')){
        chapterBox.classList.remove('active');
    }
    if(writeBox.classList.contains('active')){
        writeBox.classList.remove('active');
    }
    moderationBox.classList.add('hidden');
    chapterBox.classList.add('hidden');
    writeBox.classList.add('hidden');
}

function displayModerate(){
    if(!moderationBox.classList.contains('active'))
    {
        moderationBox.classList.add('active');
    }
    if(moderationBox.classList.contains('hidden')){
        moderationBox.classList.remove('hidden');
    }
    if(membersBox.classList.contains('active')){
        membersBox.classList.remove('active');
    }
    if(chapterBox.classList.contains('active')){
        chapterBox.classList.remove('active');
    }
    if(writeBox.classList.contains('active')){
        writeBox.classList.remove('active');
    }
    membersBox.classList.add('hidden');
    chapterBox.classList.add('hidden');
    writeBox.classList.add('hidden');
}

function displayChapters(){
    if(!chapterBox.classList.contains('active'))
    {
        chapterBox.classList.add('active');
    }
    if(chapterBox.classList.contains('hidden')){
        chapterBox.classList.remove('hidden');
    }
    if(membersBox.classList.contains('active')){
        membersBox.classList.remove('active');
    }
    if(moderationBox.classList.contains('active')){
        moderationBox.classList.remove('active');
    }
    if(writeBox.classList.contains('active')){
        writeBox.classList.remove('active');
    }
    membersBox.classList.add('hidden');
    moderationBox.classList.add('hidden');
    writeBox.classList.add('hidden');
}

function displayWrite(){
    if(!writeBox.classList.contains('active'))
    {
        writeBox.classList.add('active');
    }
    if(writeBox.classList.contains('hidden')){
        writeBox.classList.remove('hidden');
    }
    if(membersBox.classList.contains('active')){
        membersBox.classList.remove('active');
    }
    if(moderationBox.classList.contains('active')){
        moderationBox.classList.remove('active');
    }
    if(chapterBox.classList.contains('active')){
        chapterBox.classList.remove('active');
    }
    membersBox.classList.add('hidden');
    moderationBox.classList.add('hidden');
    chapterBox.classList.add('hidden');
}

function switchToEdit(){
    btnChapters.classList.remove('active');
    btnWrite.classList.add('active');
}
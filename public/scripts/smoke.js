const tales = document.querySelector('.tales');
const abstract = document.querySelector('.abstract');
const allLetters = document.querySelectorAll('.abstract span')

// gestion de la partie Aventure de l'accueil


tales.onclick = function(){
    abstract.classList.add('active');
}

let styleIndex = 0;

for(let i=0; i<allLetters.length; i++){
    console.log(allLetters[i]);
    allLetters[i].style="--i:"+styleIndex++;    
}

tales.addEventListener('click', (e)=>{
    e.preventDefault();
    console.log("url clicked");
    setTimeout(() => {
        window.location.href = "/billets/chapterlist";
        console.log("timeout executed...");
    }, 5000);
})
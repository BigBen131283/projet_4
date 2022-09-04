const tales = document.querySelector('.tales');
const lastPostContent = document.querySelector('.last-post-content')
// const abstract = document.querySelector('.abstract');


// gestion de la partie Aventure de l'accueil


tales.onclick = function(){
    console.log('coucou');
    lastPostContent.classList.add('active');
}

// let styleIndex = 0;

// for(let i=0; i<abstract.length; i++){
//     console.log(abstract[i]);
//     abstract[i].style="--i:"+styleIndex++;    
// }

tales.addEventListener('click', (e)=>{
    e.preventDefault();
    console.log("url clicked");
    setTimeout(() => {
        window.location.href = "/billets/chapterlist";
        console.log("timeout executed...");
    }, 2100);
})
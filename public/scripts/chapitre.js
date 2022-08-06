$(document).ready( () => {

    $("#like").click(thumbsup);
    $("#dislike").click(thumbsdown);
    const billetId = $("#billetId").text();
    $("#billetId").hide();
    console.log(billetId);

    function thumbsup(){
        console.log("J'adore");
        fetch('/billets/jsonGetLikes/'+billetId, {method : 'GET', headers: {"Content-Type": "application/json"}})
        .then((res) => {
            if (res.ok) {
                // console.log(res.json());
                return res.json();
            }
        })
        .then((value) => {
            console.log(value);
        })
        .catch((err) => {
            console.log(err);
        })
    }

    function thumbsdown(){
        console.log("I fucking hate you");
    }

});
// console.log("*******");
// const thumbsup = document.getElementById('like');
// const thumbsdown = document.getElementById('dislike');

// thumbsup.onclick = function like(){
//     console.log("J'adore!");
// }

// thumbsdown.onclick = function dislike(){
//     console.log("I fuckin'hate it!");
// }


// /billets/likeit/<?= $billet->id?>/<?= $loggedUser->getId()?>
// /billets/dislikeit/<?= $billet->id?>/<?= $loggedUser->getId()?>
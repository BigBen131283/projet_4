$(document).ready( () => {

    $("#like").click(thumbsup);
    $("#dislike").click(thumbsdown);
    const billetId = $("#billetId").text();
    $("#billetId").hide();
    console.log(billetId);

    function thumbsup(){
        
        const params = 
            {
                "billetId": billetId,
                "userid": 36,
                "actionflag": 1
            };
        
        // let xhr = new XMLHttpRequest();
        // xhr.open("POST", "/billets/jsonPostUpdateCounter");
        
        // xhr.setRequestHeader("Accept", "application/json");
        // xhr.setRequestHeader("Content-Type", "application/json");

        // xhr.onload = () => console.log(xhr.responseText);
        // xhr.send(params);
        
        fetch("/billets/jsonPostUpdateCounter", {
        method: 'POST',
        headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
        },
        body: `{
            "billetId": billetId,
            "userid": 36,
            "actionflag": 1
        }`,
        })
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
            console.log(err)});

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
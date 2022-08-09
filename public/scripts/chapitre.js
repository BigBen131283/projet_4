$(document).ready( () => {
    setInterval(checkCounters, 30000);
    checkCounters();
    checkmyCounters();

    $("#like").click(thumbsup);
    $("#dislike").click(thumbsdown);
    // --------------------------------------------------------------------------
    function thumbsup(){
        console.log(`Thumbs up emitted by ${useremail} with ID ${userid} for billet ${billetid}`);
        const params = {
            "billetId": billetid,
            "userid": userid,
            "actionflag": 1
        };
        fetch("/billets/jsonPostUpdateCounter", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(params)
        })
        .then((res) => {
            if (res.ok) {
                return res.json();
            }
            else {
                console.log(res.status);
                return;
            }
        })
        .then((value) => {
            console.log(value);
            checkCounters();
            checkmyCounters();
        })
        .catch((err) => {
            console.log(`Request error **** : ${err}`)});
    }
    // ----------------------------------------------------------------------------------
    function thumbsdown(){
        console.log(`Thumbs down emitted by ${useremail} with ID ${userid} for billet ${billetid}`);
        const params = {
            "billetId": billetid,
            "userid": userid,
            "actionflag": 0
        };
        fetch("/billets/jsonPostUpdateCounter", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(params)
        })
        .then((res) => {
            if (res.ok) {
                return res.json();
            }
        })
        .then((value) => {
            console.log(value);
            checkCounters();
            checkmyCounters();
        })
        .catch((err) => {
            console.log(`Request error **** : ${err}`)
        });
    }

    function checkCounters(){
        fetch("/billets/jsonGetLikes/"+billetid, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then((res) => {
            if (res.ok) {
                return res.json();
            }
        })
        .then((value) => {
            console.log(value);
            $('.likescount').each((index, element) => {
                element.textContent = value.likes;
            })
            $('.dislikescount').each((index, element) => {
                element.textContent = value.dislikes;
            })
        })
        .catch((err) => {
            console.log(`Request error **** : ${err}`)
        });
    }

    function checkmyCounters(){
        fetch("/billets/jsonGetMyAdvice/"+userid+"/"+billetid, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then((res) => {
            if (res.ok) {
                return res.json();
            }
        })
        .then((value) => {
            console.log(value);
            if(value.status){
                if(value.result === "1"){
                    $("#iconlike").attr("name", "thumbs-up");
                    $("#icondislike").attr("name", "thumbs-down-outline");
                }
                else{
                    $("#icondislike").attr("name", "thumbs-down");
                    $("#iconlike").attr("name", "thumbs-up-outline");    
                }
            }
            else{
                $("#iconlike").attr("name", "thumbs-up-outline");
                $("#icondislike").attr("name", "thumbs-down-outline");
            }
        })
        .catch((err) => {
            console.log(`Request error **** : ${err}`)
        });
    }
});

$(document).ready( () => {
    
    $(".selectbillet").each((index, element) => {
        console.log(index +": " + element.id);
        // $(element).click(editbillet);
    })
    // $("#editbillet").click(editbillet);
    // $("#").click();
    // --------------------------------------------------------------------------
    function editbillet(){
        console.log($(this));
        // const params = {
        //     "billetId": billetid,
        //     "userid": userid,
        //     "actionflag": 1
        // };
        // fetch("/billets/jsonPostUpdateCounter", {
        //     method: 'POST',
        //     headers: {
        //         'Accept': 'application/json',
        //         'Content-Type': 'application/json'
        //     },
        //     body: JSON.stringify(params)
        // })
        // .then((res) => {
        //     if (res.ok) {
        //         return res.json();
        //     }
        //     else {
        //         console.log(res.status);
        //         return;
        //     }
        // })
        // .then((value) => {
        //     console.log(value);
        //     checkCounters();
        //     checkmyCounters();
        // })
        // .catch((err) => {
        //     console.log(`Request error **** : ${err}`)});
    }
    // ----------------------------------------------------------------------------------
    
});

let editmode = false;

$(document).ready( () => {
    
    $(".selectbillet").each((index, element) => {
        $(element).click( () => { actionRequest(element) ;} );
    })


    // $("#editbillet").click(editbillet);
    // $("#").click();
    // --------------------------------------------------------------------------
    function actionRequest(element){
        const idsplit = element.id.split('-');      // Check wich billet was selected and for which action 
                                                    // Look into admin.php to find out the possible actions
                                                    // currently editbillet or deletebillet
        const billetid = idsplit[1];
        const action = idsplit[0];
        const deleteaction = 'deletebillet';
        const editaction = 'editbillet';

        console.log(`You selected ${billetid} for ${action}`);

        switch (action) {
            case editaction:
                editbillet(billetid);
                break;
            case deleteaction:
                console.log('Delete ' + billetid);
                break;
            default:
                console.log('Unrecognized request ' + action);
                break;
        }

        function editbillet(billetid) {
            const url = '/billets/jsonGetBillet/' + billetid;
            console.log('Edit : call ' + url);

            fetch(url, {
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
               else {
                   console.log(res.status);
                   return;
               }
            })
            .then((databillet) => {     // Update the edit fields
               console.log(databillet);
               $('#write .inputBox input, #write .inputBox textarea').each( (index, element) => {
                    console.log(element.id);
                    switch (element.id) {
                        case 'titleid':
                            $('#' + element.id).val(databillet.title);
                            break;
                        case 'abstractid':
                            $('#' + element.id).val(databillet.abstract);
                            break;
                        case 'fileid':
                            console.log('Should load /images/chapter_pictures/' + databillet.chapter_picture + ' somewhere in the page');
                            break;
                        case 'chapterid':
                            $('#tinymce').val(databillet.chapter);
                            break;
                        case 'dateid':
                            $('#' + element.id).val(databillet.publish_at);
                            break;        
                    }
               });

               if(!editmode) { // Already changed the interface buttons ?
                    $('button .publish').html('Mettre à jour');
                    $('#write form').attr('action', '/billets/editbillet/'+ billetid);
                    let clearbutton = document.createElement("button");
                    clearbutton.textContent = "Annulation";
                    clearbutton.id = "clearbutton";
                    clearbutton.classList.add("publish");
                    
                    $('.controls').append(clearbutton);        // Add the clear button
                    $('#clearbutton').click( (event) => {
                        event.preventDefault();     // No propagation of event when just clearing fields
                        $('#write .inputBox input, #write .inputBox textarea').each( (index, element) => {
                            if(element.id !== 'fileid')
                                $(element).val(' ');
                        });
                        $('#clearbutton').remove();
                        $('button, .publish').html('Publier');
                        editmode = false;
                    });
                    editmode = true;
                }
            })
            .catch((e) => {
               console.log(e);
            })
        }
    }    
});

tinymce.init(
    {
        selector: '#chapterid, #content',
        plugins: 
        [
          'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
          'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
          'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
        ],
        toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
          'alignleft aligncenter alignright alignjustify | ' +
          'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
    });



console.log(window.location.pathname);
let urlParse = window.location.pathname.split('/');
let nbParse = urlParse.length;
let action = urlParse[nbParse - 1];
console.log(action);
let editmode = true

if(action === 'createbillet' || action === 'admin'){
    editmode = false;
}


$(document).ready( () => {

    if(isNaN(action)){
        displayButtons();
    }
    else
    {
        displayButtons(action);
    }
        
    
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
                            tinymce.get("chapterid").setContent(databillet.chapter);
                            break;
                        case 'dateid':
                            $('#' + element.id).val(databillet.publish_at);
                            break;        
                    }
               });
               editmode = true;
               clearErrorMessages();
               displayButtons(billetid);
            })
            .catch((e) => {
               console.log(e);
            })
        }
    }

    function displayButtons(billetid = ' '){
        if(editmode) { // Already changed the interface buttons ?
            
            $('#write form').attr('action', '/billets/editbillet/'+ billetid);
            $('#edit').addClass('active');
            $('#publish').removeClass('active');
            
            $('#clearbutton').click( (event) => {
                event.preventDefault();     // No propagation of event when just clearing fields
                $('#write .inputBox input, #write .inputBox textarea').each( (index, element) => {
                    if(element.id !== 'fileid')
                        $(element).val(' ');
                });
                clearErrorMessages();
                $('#write form').attr('action', '/billets/createbillet/');
                editmode = false;
                $('#publish').addClass('active');
                $('#edit').removeClass('active');
            });
        }
    }
    
    function clearErrorMessages(){
        $('.myerror').each( (index, element) => {
            $(element).text(' ');
        })
    }
});

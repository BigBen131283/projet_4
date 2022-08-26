
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
    initTinyMce('#abstractid', 200);
    initTinyMce('#chapterid', 400);
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

    // --------------------------------------------------------------------------
    function actionRequest(element){
        const idsplit = element.id.split('-');      // Check wich billet was selected and for which action 
                                                    // Look into admin.php to find out the possible actions
                                                    // currently editbillet or deletebillet
        const billetid = idsplit[1];
        const action = idsplit[0];
        const deleteaction = 'deletebillet';
        const editaction = 'editbillet';

        // console.log(`You selected ${billetid} for ${action}`);

        switch (action) {
            case editaction:
                editbillet(billetid);
                break;
            case deleteaction:
                deletebillet(billetid);
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
                $('.write .inputBox input, .write .inputBox textarea').each( (index, element) => {
                    console.log(element.id);
                    switch (element.id) {
                        case 'titleid':
                            $('#' + element.id).val(databillet.title);
                            break;
                        case 'abstractid':
                            //$('#' + element.id).html(databillet.abstract);
                            tinymce.get("abstractid").setContent(databillet.abstract);
                            break;
                        case 'fileid':
                            console.log('Should load /images/chapter_pictures/' + databillet.chapter_picture + ' somewhere in the page');
                            break;
                        case 'chapterid':
                            //$('#' + element.id).html(databillet.chapter);
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

        function deletebillet(billetId) {
            const params = {
                "billetId": billetId
            };
            console.clear();
            console.log('Deleting billet : ' + billetId);
            $.ajax(
                {
                    type: "POST",
                    url: "/billets/jsonDeleteBillet/",
                    dataType: "json",
                    async: false,
                    data: JSON.stringify(params),
                    success: function(data) {
                        console.log(data);
                        updateStats();
                    },
                    error: function(xhr, status, error) {
                        console.log(JSON.stringify(params));
                        console.log('DELETE KO : ' + xhr.status + " " + xhr.responseText);
                    }            
                }
            );
        }
    }

    function updateStats(){
        $.ajax(
            {
                type: "GET",
                url: "/admin/jsonGetStats/",
                dataType: "json",
                async: false,
                success: function(data) {
                    console.log(data);
                    $('#billets_stats .data').text(data.allCounters.publishedBillets);
                    $('#member_stats .data').text(data.allCounters.allUsers);
                    $('#comments_stats .data').text(data.allCounters.allComments);
                    $('#modo_stats .data').text(data.allCounters.allModerate);
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    console.log('Update Stats KO : ' + xhr.status + " " + xhr.responseText);
                }            
            }
        );
    }
    
    function initTinyMce(selector, theheight) {
        tinymce.init(
            {
                //selector: '#chapterid, #abstractid',
                selector: selector,
                placeholder: 'Là où la magie opère...',
                height: theheight,
                plugins: 
                [
                  'advlist','autolink', 'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
                  'fullscreen','insertdatetime','media','table','help','wordcount'
                ],
                toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
                  'alignleft aligncenter alignright alignjustify | bullist numlist checklist | help',
                  setup: (editor) => {
                    editor.on('init', (e) => {
                        // console.log('**** Init ' + e);
                    }),
                    editor.on('change', (e) => {
                        // console.log('**** Change ' + e);
                    }),
                    editor.on('click', (e) => {
                        // console.log('**** Click ' + e);
                    })
                  }
            });
    }
    function displayButtons(billetid = ' '){
        if(editmode) { // Already changed the interface buttons ?
            
            $('.write form').attr('action', '/billets/editbillet/'+ billetid);
            $('#edit').addClass('active');
            $('#publish').removeClass('active');
            
            $('#clearbutton').click( (event) => {
                event.preventDefault();     // No propagation of event when just clearing fields
                clearErrorMessages();
                clearFields();
                $('.write form').attr('action', '/billets/createbillet/');
                editmode = false;
                $('#publish').addClass('active');
                $('#edit').removeClass('active');
            });
        }
    }
    function clearFields() {
        $('.write .inputBox input, .write .inputBox textarea').each( (index, element) => {
            if(element.id !== 'fileid')
                $(element).val(' ');
        });
        tinymce.get("chapterid").setContent(' ');
        tinymce.get("abstractid").setContent(' ');
    }
    function clearErrorMessages(){
        $('.myerror').each( (index, element) => {
            $(element).text(' ');
        })
    }
});

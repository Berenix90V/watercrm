//---------------------------------DEFINIZIONE FUNZIONI--------------------------------------//
// ordine per status e data caratteristico di appuntamenti e scadenze
function home_table(){
    var status = $('th:contains("Status")').index();
    var data = $('th:contains("Data")').index();
    var table = $('.d_order').DataTable();
    table
        .order([ status, "asc" ], [ data, "asc"] )
        .draw();
}
// ordine per status e data caratteristico di lista
function registry_table(){
    var status = $('th:contains("Status")').index();
    var table = $('.r_order').DataTable();
    table
        .order([ status, "asc" ])
        .draw();
}

// funzione per collegare la select alla tabella home

// ----------------------------- FINE DEF FUNZIONI -------------------------------------------//

// -------------------------------FUNZIONI DA ESEGUIRE A DOC PRONTO-----------------------------//
$(document).ready(function() {
    if($('.d_order').length){
        home_table();
    }
    if($('.r_order').length){
        registry_table();
    }
      
});



// -----------------------------------------FUNZIONI DA ESEGUIRE IN ALTRE CONDIZIONI----------------------------------------//

// SELECT HOME
//filtro
/*$('select[name = "choose_table"]').on("change", function(){
    var choose_table = $(this).val();
    var path = site_url+'/Home/index';
    //console.log(warehouse1);
    $.ajax({
        url: path,
        data: {choose_table:choose_table},
        method: "POST",
        dataType:"html",
        success:function(result){
            $('table').remove();
            $('div.row').remove();
            $('div.dataTables_wrapper').remove();
            $('div.no-data').remove();
            $("div#table-responsive").append(result);
            home_table();
        }       
    });
});*/
/* CODICE DA IMPLEMENTARE PER CORREGGERE BUG
window.onpopstate = function(event) {
    alert("location: " + document.location + ", state: " + JSON.stringify(event.state));
};
history.pushState({page: 1}, "title 1", "?page=1");
history.pushState({page: 2}, "title 2", "?page=2");
history.back(); // alerts "location: http://example.com/example.html?page=1, state: {"page":1}"
history.back(); // alerts "location: http://example.com/example.html, state: null
*/
// FINE SELECT HOME


//FUNZIONI GESTITE DA ALBERTO

//aggiunge il simbolo del menu a tutti le viste in responsive
$('h1').append('<div id="menu"><i class="fas fa-bars"></i></div>');
$('#action-list').prepend('<span class = "main-option" id="close-menu-mobile"><a><i class = "far fa-window-close"></i> CHIUDI MENU</a></span>')
$('#menu').click(function() {
    $('#action-list').fadeIn(200);
});
$('#close-menu-mobile').click(() => {
    $('#action-list').fadeOut(200);
});
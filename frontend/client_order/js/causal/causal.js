$(document).ready(function(){
    loadTable();
    $('#table_data').DataTable();
});


/* CONFIGURA LOS HEADERS PARA PODER ENTENDER JSON LA RESPUESTA */

function getHeaders()
{
    return{
        'Accept':'application/json',
        'content-type': 'application/json; charset=UTF-8'
    };
}

/* consulta en la API los registros y los pinta en una tabla */

async function loadTable()
{
    $.ajax({
        url  : 'http://127.0.0.1:8000/api/causal',
        type :  'GET',
        headers : getHeaders(),
        success : function(data){
            //console.log(data);
            let html = '';
            for(let causal of data)
            {
                html += '<tr>' +
                            '<td>'+causal.id+'</td>' +
                            '<td>'+causal.description+'</td>' +
                            '<td>' +
                                '<a href="#" onclick="edit('+causal.id+')" title="editar" class="btn btn-primary btn-circle btn-sm">' +
                                    '<i class="far fa-edit"></i>' +
                                '</a>' +
                                '<a href="#" onclick="remove('+causal.id+')" title="eliminar" class="btn btn-danger btn-circle btn-sm" onclick="return remove();">' +
                                    '<i class="fas fa-trash"></i>' +
                                '</a>' +
                            '</td>' +
                        '</tr>';
            }
            document.querySelector('#table_data tbody'). outerHTML = html;

        },
        error: function(error){
            console.log(error);
            alert("No es posible completar la operacion")
        }
    });
}

/**
 * Elimina un registro conectandose a la API
 * @param {*} id 
 */
async  function remove(id)
{
    if(!confirm("¿Esta seguro de eliminar un registro?"))
    {
        return;
    }

    const  request = await fetch('http://127.0.0.1:8000/api/causal/' + id, {
        method: 'DELETE',
        headers: getHeaders()
    });

    location.reload();

}

/**
 * Redicciona al formulario de editar, enviado el id
 * del registro
 * @param {*} id 
 */
function edit(id)
{
    window.location.href='edit.html?id=' + id;
}





class corte {
    constructor(id, fh_inicio, fh_fin, total, usuario) {
        this.id = id
        this.fh_inicio = fh_inicio
        this.fh_fin = fh_fin
        this.total = total
        this.usuario = usuario
    }
}

var cortes = []
var cortes_mostrar = []

var agregar_corte = (id, fh_inicio, fh_fin, total, usuario) => {
    cortes.push(new corte(id, fh_inicio, fh_fin, total, usuario))
}

var actualizar_tabla = () => {
    var v = (val) => {
        if (val == undefined || val == null) return ""
        else return val
    }
    var v_n = (val) => {
        if (val == undefined || val == null) return 0
        else return val
    }
    var tabla = document.getElementById("tabla_cortes")
    tabla.innerHTML = ""
    var tb = document.createElement("tbody")

    var fila_enc = document.createElement("tr")
    var encabezados = ["ID", "Fecha/hora inicio", "Fecha/hora fin", "Total", "Usuario", ""]
    for (i = 0; i < encabezados.length; i++) {
        var celda = document.createElement("th")
        celda.innerHTML = encabezados[i]
        fila_enc.append(celda)
    }
    tb.append(fila_enc)

    for (i = 0; i < cortes_mostrar.length; i++) {
        var corte_act = cortes_mostrar[i]
        var corte_info = [corte_act.id, corte_act.fh_inicio, corte_act.fh_fin, "$ " + v_n(corte_act.total).toFixed(2), corte_act.usuario]
        var fila = document.createElement("tr")
        for (j = 0; j < corte_info.length + 1; j++) {
            var celda = document.createElement("td")
            if ( j == 0 ) celda.innerHTML = "<a href='./consultar_ventas.php?id_corte=" + corte_info[j] + "' >" + corte_info[j] + "</a>"
            else if ( j == corte_info.length ) celda.innerHTML = "<a href='./consultar_ventas.php?id_corte=" + corte_act.id + "' ><button>Ver ventas</button></a>"
            else celda.innerHTML = corte_info[j]
            fila.append(celda)
        }
        tb.append(fila)
    }
    if ( cortes_mostrar.length == 0 ) {
        var fila = document.createElement("tr")
        var celda = document.createElement("td")
        celda.setAttribute("colspan", encabezados.length)
        celda.innerHTML = "No se ha encontrado información"
        fila.append(celda)
        tb.append(fila)
    }

    tabla.append(tb)
}

function filtrar() {
    var id = document.getElementById("filtro_id").value
    //var fecha = document.getElementById("filtro_fecha").value
    var usuario = document.getElementById("filtro_usuario").value

    if (id == "" && /*fecha == "" &&*/ usuario == "" && cortes != cortes_mostrar) {
        cortes_mostrar = cortes
        actualizar_tabla()
        return false
    }

    cortes_mostrar = []

    cortes.forEach(cort => {
        if(cort.id.toString().includes(id) && cort.usuario.includes(usuario) ){
            cortes_mostrar.push(cort)
        }
    });

    actualizar_tabla()
    return false
}


const comp_texto = (a, b) => {
    if (a > b) return 1
    if (a < b) return -1
    return 0
}
const comp_id_asc = (a, b) => { return parseInt(a.id) - parseInt(b.id) }
const comp_id_desc = (a, b) => { return parseInt(b.id) - parseInt(a.id) }
/*const comp_fhinicio_asc = (a, b) => { return comp_texto(a.fh_inicio, b.fh_inicio) }
const comp_fhinicio_desc = (a, b) => { return comp_texto(b.fh_inicio, a.fh_inicio) }
const comp_fhfin_asc = (a, b) => { return comp_texto(a.fh_fin, b.fh_fin) }
const comp_fhfin_desc = (a, b) => { return comp_texto(b.fh_fin, a.fh_fin) }*/
const comp_total_asc = (a, b) => { return parseFloat(a.total) - parseFloat(b.total) }
const comp_total_desc = (a, b) => { return parseFloat(b.total) - parseFloat(a.total) }
const comp_usuario_asc = (a, b) => { return comp_texto(a.usuario, b.usuario) }
const comp_usuario_desc = (a, b) => { return comp_texto(b.usuario, a.usuario) }
const comparaciones = [ 
    [comp_id_asc, comp_id_desc],
    //[comp_fhinicio_asc, comp_fhinicio_desc],
    //[comp_fhfin_asc, comp_fhfin_desc],
    [comp_total_asc, comp_total_desc],
    [comp_usuario_asc, comp_usuario_desc]
]
const ordenar = () => {
    var id_orden = parseInt(document.getElementById("id_orden").value)
    var id_modo = parseInt(document.getElementById("id_modo").value)

    if ( !isNaN(id_orden) && id_orden >= 0 && id_orden <= 2 && !isNaN(id_modo) && id_modo >= 0 && id_modo <= 1 ) {
        cortes_mostrar.sort(comparaciones[id_orden][id_modo])
        actualizar_tabla()
    }
    return false
}
//const comp_fechaHora_desc = (a, b) => { return Date.parse(b.fecha_hora) - Date.parse(a.fecha_hora) }
class venta {
    constructor(id_compra, fecha_hora, total, devuelto, usuario) {
        this.id_compra = id_compra
        this.fecha_hora = fecha_hora
        this.total = total
        this.devuelto = devuelto
        this.usuario = usuario
    }
}

var ventas = []
var ventas_mostrar = []

var agregar_venta = (id_compra, fecha_hora, total, devuelto, usuario) => {
    ventas.push(new venta(id_compra, fecha_hora, total, devuelto, usuario))
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
    var tabla = document.getElementById("tabla_ventas")
    tabla.innerHTML = ""
    var tb = document.createElement("tbody")

    var fila_enc = document.createElement("tr")
    var encabezados = ["ID", "Fecha y hora", "Total", "Devuelto", "Usuario", ""]
    for (i = 0; i < encabezados.length; i++) {
        var celda = document.createElement("th")
        celda.innerHTML = encabezados[i]
        fila_enc.append(celda)
    }
    tb.append(fila_enc)

    for (i = 0; i < ventas_mostrar.length; i++) {
        var venta_act = ventas_mostrar[i]
        var venta_info = [venta_act.id_compra, venta_act.fecha_hora, "$ " + v_n(venta_act.total).toFixed(2), (venta_act.devuelto? "SI" : "-"), venta_act.usuario]
        var fila = document.createElement("tr")
        for (j = 0; j < venta_info.length + 1; j++) {
            var celda = document.createElement("td")
            if ( j == 0 ) celda.innerHTML = "<a href='./resumen_venta.php?id=" + venta_info[j] + "' >" + venta_info[j] + "</a>"
            else if ( j == venta_info.length ) celda.innerHTML = "<a href='./resumen_venta.php?id=" + venta_act.id_compra + "' ><button style='background-color: #3a7cf4; color: #fff;'>Más info.</button></a>"
            else celda.innerHTML = venta_info[j]
            fila.append(celda)
        }
        tb.append(fila)
    }
    if ( ventas_mostrar.length == 0 ) {
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
    var fecha = document.getElementById("filtro_fecha").value
    var usuario = document.getElementById("filtro_usuario").value

    if (id == "" && fecha == "" && usuario == "" && ventas != ventas_mostrar) {
        ventas_mostrar = ventas
        actualizar_tabla()
        return false
    }

    ventas_mostrar = []

    ventas.forEach(vent => {
        if(vent.id_compra.toString().includes(id) && vent.fecha_hora.includes(fecha) && vent.usuario.includes(usuario) ){
            ventas_mostrar.push(vent)
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
const comp_idCompra_asc = (a, b) => { return parseInt(a.id_compra) - parseInt(b.id_compra) }
const comp_idCompra_desc = (a, b) => { return parseInt(b.id_compra) - parseInt(a.id_compra) }
const comp_fechaHora_asc = (a, b) => { return comp_texto(a.fecha_hora, b.fecha_hora) }
const comp_fechaHora_desc = (a, b) => { return comp_texto(b.fecha_hora, a.fecha_hora) }
const comp_total_asc = (a, b) => { return parseFloat(a.total) - parseFloat(b.total) }
const comp_total_desc = (a, b) => { return parseFloat(b.total) - parseFloat(a.total) }
const comp_devuelto_asc = (a, b) => { return parseInt(a.devuelto) - parseInt(b.devuelto) }
const comp_devuelto_desc = (a, b) => { return parseInt(b.devuelto) - parseInt(a.devuelto) }
const comp_usuario_asc = (a, b) => { return comp_texto(a.usuario, b.usuario) }
const comp_usuario_desc = (a, b) => { return comp_texto(b.usuario, a.usuario) }
const comparaciones = [ 
    [comp_idCompra_asc, comp_idCompra_desc],
    [comp_fechaHora_asc, comp_fechaHora_desc],
    [comp_total_asc, comp_total_desc],
    [comp_devuelto_asc, comp_devuelto_desc],
    [comp_usuario_asc, comp_usuario_desc]
]
const ordenar = () => {
    var id_orden = parseInt(document.getElementById("id_orden").value)
    var id_modo = parseInt(document.getElementById("id_modo").value)

    if ( !isNaN(id_orden) && id_orden >= 0 && id_orden <= 4 && !isNaN(id_modo) && id_modo >=0 && id_modo <=1 ) {
        ventas_mostrar.sort(comparaciones[id_orden][id_modo])
        actualizar_tabla()
    }
    return false
}
//const comp_fechaHora_desc = (a, b) => { return Date.parse(b.fecha_hora) - Date.parse(a.fecha_hora) }
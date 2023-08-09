class producto {
    constructor(id, id_codigo, descripcion, cantidad, precio) {
        this.id = id
        this.id_codigo = id_codigo
        this.descripcion = descripcion
        this.cantidad = cantidad
        this.precio = precio
    }
}

var productos = []
var productos_mostrar = []

var agregar_producto = (id, id_codigo, descripcion, cantidad, precio) => {
    productos.push(new producto(id, id_codigo, descripcion, cantidad, precio))
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
    var tabla = document.getElementById("tabla_productos")
    tabla.innerHTML = ""
    var tb = document.createElement("tbody")

    var fila_enc = document.createElement("tr")
    var encabezados = ["ID", "Codigo barras", "Descripción", "Precio", "Cantidad", ""]
    for (i = 0; i < encabezados.length; i++) {
        var celda = document.createElement("th")
        celda.innerHTML = encabezados[i]
        fila_enc.append(celda)
    }
    tb.append(fila_enc)

    for (i = 0; i < productos_mostrar.length; i++) {
        var prod = productos_mostrar[i]
        var prod_info = [prod.id, prod.id_codigo, prod.descripcion, "$ " + v_n(prod.precio).toFixed(2),  prod.cantidad]
        var fila = document.createElement("tr")
        for (j = 0; j < prod_info.length + 1; j++) {
            var celda = document.createElement("td")
            if ( j == 0 ) {
                celda.innerHTML = "<a href='modificar_producto.php?id=" + prod.id +"'>" + prod_info[j] + "</a>"
            } else if ( j == prod_info.length ) {
                celda.innerHTML = "<a href='modificar_producto.php?id=" + prod.id +"'><button style='background-color: #3a7cf4; color: #fff;'>Modificar</button></a>"
            } else {
                celda.innerHTML = prod_info[j]
            }
            fila.append(celda)
        }
        tb.append(fila)
    }
    if ( productos_mostrar.length == 0 ) {
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
    var id_codigo = document.getElementById("filtro_cb").value
    var descr = document.getElementById("filtro_descr").value

    if (id == "" && id_codigo == "" && descr == "" && productos != productos_mostrar) {
        productos_mostrar = productos
        actualizar_tabla()
    }

    productos_mostrar = []

    productos.forEach(prod => {
        if(prod.id.toString().includes(id) && prod.id_codigo.includes(id_codigo) && prod.descripcion.includes(descr) ){
            productos_mostrar.push(prod)
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
const comp_cb_asc = (a, b) => { return comp_texto(a.id_codigo, b.id_codigo) }
const comp_cb_desc = (a, b) => { return comp_texto(b.id_codigo, a.id_codigo) }
const comp_desc_asc = (a, b) => { return comp_texto(a.descripcion, b.descripcion) }
const comp_desc_desc = (a, b) => { return comp_texto(b.descripcion, a.descripcion) }
const comp_precio_asc = (a, b) => { return parseFloat(a.precio) - parseFloat(b.precio) }
const comp_precio_desc = (a, b) => { return parseFloat(b.precio) - parseFloat(a.precio) }
const comp_cantidad_asc = (a, b) => { return parseInt(a.cantidad) - parseInt(b.cantidad) }
const comp_cantidad_desc = (a, b) => { return parseInt(b.cantidad) - parseInt(a.cantidad) }
const comparaciones = [ 
    [comp_id_asc, comp_id_desc],
    [comp_cb_asc, comp_cb_desc],
    [comp_desc_asc, comp_desc_desc],
    [comp_precio_asc, comp_precio_desc],
    [comp_cantidad_asc, comp_cantidad_desc]
]
const ordenar = () => {
    var id_orden = parseInt(document.getElementById("id_orden").value)
    var id_modo = parseInt(document.getElementById("id_modo").value)

    if ( !isNaN(id_orden) && id_orden >= 0 && id_orden <= 4 && !isNaN(id_modo) && id_modo >= 0 && id_modo <= 1 ) {
        productos_mostrar.sort(comparaciones[id_orden][id_modo])
        actualizar_tabla()
    }
    return false
}
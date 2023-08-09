class producto {
    constructor(id, id_codigo, descripcion, cantidad, precio, cantidad_act) {
        this.id = id
        this.id_codigo = id_codigo
        this.descripcion = descripcion
        this.cantidad = cantidad
        this.precio = precio
        this.cantidad_act = cantidad_act
    }
}

var productos = []
var productos_mostrar = []
var productos_carrito = []

var agregar_producto = (id, id_codigo, descripcion, cantidad, precio) => {
    productos.push(new producto(id, id_codigo, descripcion, cantidad, precio))
}

var agregar_carrito = (id_prod_actual) => {
    if ( productos_mostrar[id_prod_actual].cantidad <= 0 ) {
        alert("Cantidad no suficiente del producto")
        return
    }
    var index = productos_carrito.indexOf(productos_mostrar[id_prod_actual])
    if ( index == -1 ) {
        productos_mostrar[id_prod_actual].cantidad_act = 1
        productos_carrito.push(productos_mostrar[id_prod_actual])
    } else if ( productos_carrito[index].cantidad_act + 1 <= productos_carrito[index].cantidad ) {
        if ( confirm("El producto seleccionado ya se encuentra en el carrito\n¿Añadir pieza?") )
            productos_carrito[index].cantidad_act++
        else return
    } else {
        alert("El producto ya se encuentra en el carrito")
        return
    }

    actualizar_tabla_carrito()
}

var validar_cantidad = (id, inp_el) => {
    var cantidad = parseInt(inp_el.value)
    //alert("Producto: " + productos_carrito[id].descripcion + "  -  Cantidad: " + cantidad)
    if ( cantidad <= 0 && confirm("¿Deseas quitar el producto del carrito?") ) {
        productos_carrito.splice(id, 1)
    } else if ( cantidad > productos_carrito[id].cantidad ) {
        alert ("Introduce una cantidad válida y disponible")
    } else if ( cantidad > 0) {
        productos_carrito[id].cantidad_act = cantidad
        var subtotal = cantidad * productos_carrito[id].precio
        inp_el.parentElement.parentElement.lastElementChild.innerHTML = "$ " + subtotal.toFixed(2)
        actualizar_total()
        return
    }
    actualizar_tabla_carrito()
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
    var encabezados = ["ID", "Codigo barras", "Descripción", "Precio", "Cantidad", "Agregar"]
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
        for (j = 0; j <= prod_info.length; j++) {
            var celda = document.createElement("td")
            if (j < prod_info.length) { celda.innerHTML = prod_info[j] }
            else {
                if (prod.cantidad > 0) celda.innerHTML = "<button id='btn_agregar_" + i + "' onclick='agregar_carrito("+i+")' style='background-color: #3a7cf4; color: #fff;'>Agregar al carrito</button>" 
                else celda.innerHTML = "Sin existencias"
            }
            fila.append(celda)
        }
        tb.append(fila)
    }
    if (productos_mostrar.length == 0) {
        var fila = document.createElement("tr")
        var celda = document.createElement("td")
        celda.setAttribute("colspan", 6)
        celda.innerHTML = "No hay elementos"
        fila.append(celda)
        tb.append(fila)
    }

    tabla.append(tb)
}

var actualizar_total = () => {
    var total = 0

    productos_carrito.forEach(prod => {
        total += prod.cantidad_act * prod.precio
    });

    document.getElementById("txt_total").innerHTML = "Total: $ " + total.toFixed(2)
    return total
}

var actualizar_tabla_carrito = () => {
    var v = (val) => {
        if (val == undefined || val == null) return ""
        else return val
    }
    var v_n = (val) => {
        if (val == undefined || val == null) return 0
        else return val
    }
    var tabla = document.getElementById("tabla_carrito")
    tabla.innerHTML = ""
    var tb = document.createElement("tbody")

    var fila_enc = document.createElement("tr")
    var encabezados = ["ID", "Codigo barras", "Descripción", "Precio", "Cantidad", "Subtotal"]
    for (i = 0; i < encabezados.length; i++) {
        var celda = document.createElement("th")
        celda.innerHTML = encabezados[i]
        fila_enc.append(celda)
    }
    tb.append(fila_enc)

    for (i = 0; i < productos_carrito.length; i++) {
        var prod = productos_carrito[i]
        var subtotal = prod.precio*prod.cantidad_act
        var prod_info = [prod.id, prod.id_codigo, prod.descripcion, "$ " + v_n(prod.precio).toFixed(2),  prod.cantidad_act, "$ " + v_n(subtotal).toFixed(2)]
        var fila = document.createElement("tr")
        for (j = 0; j < prod_info.length; j++) {
            var celda = document.createElement("td")
            if (j == 4) {
                celda.innerHTML = "<input type='number' min=0 max=" + prod.cantidad + " value=" + prod_info[j] + " onchange='validar_cantidad("+i+", this)' />"
            } else {
                celda.innerHTML = prod_info[j]
            }
            fila.append(celda)
        }
        tb.append(fila)
    }
    if (productos_carrito.length == 0) {
        var fila = document.createElement("tr")
        var celda = document.createElement("td")
        celda.setAttribute("colspan", 6)
        celda.innerHTML = "No hay elementos"
        fila.append(celda)
        tb.append(fila)
    }
    tabla.append(tb)
    actualizar_total()
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

const validar_compra = () => {
    var valido = true
    var texto_salida = ""
    var total  = 0
    if ( productos_carrito.length <= 0 ) {
        alert("No hay ningún producto en el carrito")
        return false
    }
    for (i = 0; i < productos_carrito.length; i++) {
        var prod = productos_carrito[i];
        if ( prod.cantidad_act <= 0 || prod.cantidad_act > prod.cantidad ) {
            alert("Hay un error en las cantidades de algún producto (" + producto.descripcion + ")")
            valido = false
            break
        } else {
            texto_salida += prod.id + "::" + prod.cantidad_act + "::" + prod.precio
            if ( i + 1 < productos_carrito.length ) texto_salida += "__"
            total += prod.cantidad_act * prod.precio
        }
    }
    var ingresado = document.getElementById("ingreso_cliente").value

    if ( isNaN(ingresado) || ingresado < total ) {
        valido = false
        alert( "La cantidad ingresada por el usuario no es suficiente para cubrir la cuenta" )
    }
    if (valido) {
        document.getElementById("info_compra").value = texto_salida
        document.body.onbeforeunload = null
    }
    //alert("Texto de salida: " + document.getElementById("info_compra").value)
    return valido
}
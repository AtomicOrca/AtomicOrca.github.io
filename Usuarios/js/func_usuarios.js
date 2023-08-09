class usuario {
    constructor(id, nusuario, habilitado, nombre, correo, nivel) {
        this.id = id
        this.nusuario = nusuario
        this.habilitado = habilitado
        this.nombre = nombre
        this.correo = correo
        this.nivel = nivel
    }
}

var usuarios = []
var usuarios_mostrar = []

var agregar_usuario = (id, nusuario, habilitado, nombre, correo, nivel) => {
    usuarios.push(new usuario(id, nusuario, habilitado, nombre, correo, nivel))
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
    var tabla = document.getElementById("tabla_usuarios")
    tabla.innerHTML = ""
    var tb = document.createElement("tbody")

    var fila_enc = document.createElement("tr")
    var encabezados = ["ID", "Usuario", "Habilitado", "Nombre", "Correo", "Nivel", "", ""]
    for (i = 0; i < encabezados.length; i++) {
        var celda = document.createElement("th")
        celda.innerHTML = encabezados[i]
        fila_enc.append(celda)
    }
    tb.append(fila_enc)

    for (i = 0; i < usuarios_mostrar.length; i++) {
        var usuario_act = usuarios_mostrar[i]
        var usuario_info = [usuario_act.id, usuario_act.nusuario, (usuario_act.habilitado? "✓" : "-"), usuario_act.nombre, usuario_act.correo, usuario_act.nivel]
        var fila = document.createElement("tr")
        for (j = 0; j < usuario_info.length + 2; j++) {
            var celda = document.createElement("td")
            if ( j == 0 ) celda.innerHTML = "<a href='./modificar_usuario.php?id=" + usuario_act.id + "' >" + usuario_info[j] + "</a>"
            else if ( j == usuario_info.length ) celda.innerHTML = "<a href='./modificar_usuario.php?id=" + usuario_act.id + "' ><button style='background-color: #f4623a;'>Modificar</button></a>"
            else if ( j == usuario_info.length + 1 ) celda.innerHTML = "<a href='./modificar_contra.php" + ( usuario_actual != usuario_act.nusuario? "?id=" + usuario_act.id : "") + "' ><button style='background-color: #3a7cf4; color: #fff;'>Cambiar contra.</button></a>"
            else celda.innerHTML = usuario_info[j]
            fila.append(celda)
        }
        tb.append(fila)
    }
    if ( usuarios_mostrar.length == 0 ) {
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
    var nusuario = document.getElementById("filtro_usuario").value
    var nombre = document.getElementById("filtro_nombre").value
    var correo = document.getElementById("filtro_correo").value
    var nivel = document.getElementById("filtro_nivel").value
    var habilitado = document.getElementById("filtro_habilitado").value

    if ( id == "" && nusuario == "" && nombre == "" && correo == "" && nivel == "" && habilitado == "" && usuarios_mostrar != usuarios ) {
        usuarios_mostrar = usuarios
        actualizar_tabla()
        return false
    }

    usuarios_mostrar = []

    usuarios.forEach(usr => {
        if( usr.id.toString().includes(id) && usr.nusuario.includes(nusuario) && usr.nombre.includes(nombre) && usr.correo.includes(correo) && usr.nivel.includes(nivel) && usr.habilitado.toString().includes(habilitado) ){
            usuarios_mostrar.push(usr)
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
const comp_usr_asc = (a, b) => { return comp_texto(a.nusuario, b.nusuario) }
const comp_usr_desc = (a, b) => { return comp_texto(b.nusuario, a.nusuario) }
// Nombre, Correo, Nivel, Habilitado
const comp_nomb_asc = (a, b) => { return comp_texto(a.nombre, b.nombre) }
const comp_nomb_desc = (a, b) => { return comp_texto(b.nombre, a.nombre) }

const comp_correo_asc = (a, b) => { return comp_texto(a.correo, b.correo) }
const comp_correo_desc = (a, b) => { return comp_texto(b.correo, a.correo) }

const comp_nivel_asc = (a, b) => { return comp_texto(a.nivel, b.nivel) }
const comp_nivel_desc = (a, b) => { return comp_texto(b.nivel, a.nivel) }

const comp_habilitado_asc = (a, b) => { return parseInt(a.habilitado) - parseInt(b.habilitado) }
const comp_habilitado_desc = (a, b) => { return parseInt(b.habilitado) - parseInt(a.habilitado) }

const comparaciones = [ 
    [comp_id_asc, comp_id_desc],
    [comp_usr_asc, comp_usr_desc],
    [comp_nomb_asc, comp_nomb_desc],
    [comp_correo_asc, comp_correo_desc],
    [comp_nivel_asc, comp_nivel_desc],
    [comp_habilitado_asc, comp_habilitado_desc]
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
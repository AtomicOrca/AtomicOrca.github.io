const validar_campo = (elemento, min = 1, max = 30) => {
    return elemento.value != undefined && elemento.value != "" && elemento.value.length >= min && elemento.value.length <= max
}

const tipos_usuario = ["SuperAdmin", "Administrador", "Cajero", "Inventario"]
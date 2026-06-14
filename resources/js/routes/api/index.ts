import mobile from './mobile'
import params from './params'
import sunat from './sunat'
import personas from './personas'
import trabajadores from './trabajadores'
import usuarios from './usuarios'
import zonas from './zonas'
import entidades from './entidades'
import instituciones from './instituciones'

const api = {
    mobile: Object.assign(mobile, mobile),
    params: Object.assign(params, params),
    sunat: Object.assign(sunat, sunat),
    personas: Object.assign(personas, personas),
    trabajadores: Object.assign(trabajadores, trabajadores),
    usuarios: Object.assign(usuarios, usuarios),
    zonas: Object.assign(zonas, zonas),
    entidades: Object.assign(entidades, entidades),
    instituciones: Object.assign(instituciones, instituciones),
}

export default api
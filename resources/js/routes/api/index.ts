import params from './params'
import sunat from './sunat'
import personas from './personas'
import zonas from './zonas'
import entidades from './entidades'

const api = {
    params: Object.assign(params, params),
    sunat: Object.assign(sunat, sunat),
    personas: Object.assign(personas, personas),
    zonas: Object.assign(zonas, zonas),
    entidades: Object.assign(entidades, entidades),
}

export default api
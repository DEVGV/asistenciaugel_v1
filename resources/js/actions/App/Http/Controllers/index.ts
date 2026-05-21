import Entidad from './Entidad'
import Configuracion from './Configuracion'
import Persona from './Persona'
import Trabajador from './Trabajador'
import InstitucionEducativa from './InstitucionEducativa'
import Infraestructura from './Infraestructura'
import Settings from './Settings'
import Api from './Api'

const Controllers = {
    Entidad: Object.assign(Entidad, Entidad),
    Configuracion: Object.assign(Configuracion, Configuracion),
    Persona: Object.assign(Persona, Persona),
    Trabajador: Object.assign(Trabajador, Trabajador),
    InstitucionEducativa: Object.assign(InstitucionEducativa, InstitucionEducativa),
    Infraestructura: Object.assign(Infraestructura, Infraestructura),
    Settings: Object.assign(Settings, Settings),
    Api: Object.assign(Api, Api),
}

export default Controllers
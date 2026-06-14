import Auth from './Auth'
import Entidad from './Entidad'
import Configuracion from './Configuracion'
import Persona from './Persona'
import Trabajador from './Trabajador'
import InstitucionEducativa from './InstitucionEducativa'
import Infraestructura from './Infraestructura'
import Horario from './Horario'
import Tramite from './Tramite'
import Settings from './Settings'
import Api from './Api'

const Controllers = {
    Auth: Object.assign(Auth, Auth),
    Entidad: Object.assign(Entidad, Entidad),
    Configuracion: Object.assign(Configuracion, Configuracion),
    Persona: Object.assign(Persona, Persona),
    Trabajador: Object.assign(Trabajador, Trabajador),
    InstitucionEducativa: Object.assign(InstitucionEducativa, InstitucionEducativa),
    Infraestructura: Object.assign(Infraestructura, Infraestructura),
    Horario: Object.assign(Horario, Horario),
    Tramite: Object.assign(Tramite, Tramite),
    Settings: Object.assign(Settings, Settings),
    Api: Object.assign(Api, Api),
}

export default Controllers
import Auth from './Auth'
import DashboardController from './DashboardController'
import Entidad from './Entidad'
import Configuracion from './Configuracion'
import Trabajador from './Trabajador'
import Horario from './Horario'
import Persona from './Persona'
import InstitucionEducativa from './InstitucionEducativa'
import Asistencia from './Asistencia'
import Infraestructura from './Infraestructura'
import Marcacion from './Marcacion'
import Tramite from './Tramite'
import Settings from './Settings'
import Api from './Api'

const Controllers = {
    Auth: Object.assign(Auth, Auth),
    DashboardController: Object.assign(DashboardController, DashboardController),
    Entidad: Object.assign(Entidad, Entidad),
    Configuracion: Object.assign(Configuracion, Configuracion),
    Trabajador: Object.assign(Trabajador, Trabajador),
    Horario: Object.assign(Horario, Horario),
    Persona: Object.assign(Persona, Persona),
    InstitucionEducativa: Object.assign(InstitucionEducativa, InstitucionEducativa),
    Asistencia: Object.assign(Asistencia, Asistencia),
    Infraestructura: Object.assign(Infraestructura, Infraestructura),
    Marcacion: Object.assign(Marcacion, Marcacion),
    Tramite: Object.assign(Tramite, Tramite),
    Settings: Object.assign(Settings, Settings),
    Api: Object.assign(Api, Api),
}

export default Controllers
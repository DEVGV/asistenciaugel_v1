import AreaController from './AreaController'
import CargoController from './CargoController'
import CondicionLaboralController from './CondicionLaboralController'
import ZonaController from './ZonaController'
import UsuarioController from './UsuarioController'
import PerfilController from './PerfilController'
import UsuarioApiController from './UsuarioApiController'

const Configuracion = {
    AreaController: Object.assign(AreaController, AreaController),
    CargoController: Object.assign(CargoController, CargoController),
    CondicionLaboralController: Object.assign(CondicionLaboralController, CondicionLaboralController),
    ZonaController: Object.assign(ZonaController, ZonaController),
    UsuarioController: Object.assign(UsuarioController, UsuarioController),
    PerfilController: Object.assign(PerfilController, PerfilController),
    UsuarioApiController: Object.assign(UsuarioApiController, UsuarioApiController),
}

export default Configuracion
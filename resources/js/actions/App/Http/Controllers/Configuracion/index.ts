import AreaController from './AreaController'
import CargoController from './CargoController'
import CondicionLaboralController from './CondicionLaboralController'
import ZonaController from './ZonaController'

const Configuracion = {
    AreaController: Object.assign(AreaController, AreaController),
    CargoController: Object.assign(CargoController, CargoController),
    CondicionLaboralController: Object.assign(CondicionLaboralController, CondicionLaboralController),
    ZonaController: Object.assign(ZonaController, ZonaController),
}

export default Configuracion
import TrabajadorController from './TrabajadorController'
import RegistroTrabajadorController from './RegistroTrabajadorController'
import AltaTrabajadorController from './AltaTrabajadorController'

const Trabajador = {
    TrabajadorController: Object.assign(TrabajadorController, TrabajadorController),
    RegistroTrabajadorController: Object.assign(RegistroTrabajadorController, RegistroTrabajadorController),
    AltaTrabajadorController: Object.assign(AltaTrabajadorController, AltaTrabajadorController),
}

export default Trabajador
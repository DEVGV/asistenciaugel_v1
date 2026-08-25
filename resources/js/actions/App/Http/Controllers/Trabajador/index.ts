import TrabajadorController from './TrabajadorController'
import AltaTrabajadorController from './AltaTrabajadorController'
import MarcacionesTrabajadorController from './MarcacionesTrabajadorController'
import RegistroTrabajadorController from './RegistroTrabajadorController'

const Trabajador = {
    TrabajadorController: Object.assign(TrabajadorController, TrabajadorController),
    AltaTrabajadorController: Object.assign(AltaTrabajadorController, AltaTrabajadorController),
    MarcacionesTrabajadorController: Object.assign(MarcacionesTrabajadorController, MarcacionesTrabajadorController),
    RegistroTrabajadorController: Object.assign(RegistroTrabajadorController, RegistroTrabajadorController),
}

export default Trabajador
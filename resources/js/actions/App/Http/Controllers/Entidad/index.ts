import EntidadController from './EntidadController'
import EntidadMasivaController from './EntidadMasivaController'

const Entidad = {
    EntidadController: Object.assign(EntidadController, EntidadController),
    EntidadMasivaController: Object.assign(EntidadMasivaController, EntidadMasivaController),
}

export default Entidad
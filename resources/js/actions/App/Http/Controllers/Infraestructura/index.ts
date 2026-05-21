import LocalController from './LocalController'
import DispositivoMarcaController from './DispositivoMarcaController'
import LocalInstEducController from './LocalInstEducController'
import RelojController from './RelojController'
import LocalMarcacionController from './LocalMarcacionController'

const Infraestructura = {
    LocalController: Object.assign(LocalController, LocalController),
    DispositivoMarcaController: Object.assign(DispositivoMarcaController, DispositivoMarcaController),
    LocalInstEducController: Object.assign(LocalInstEducController, LocalInstEducController),
    RelojController: Object.assign(RelojController, RelojController),
    LocalMarcacionController: Object.assign(LocalMarcacionController, LocalMarcacionController),
}

export default Infraestructura
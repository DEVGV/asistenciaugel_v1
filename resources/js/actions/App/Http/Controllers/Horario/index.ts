import HorarioCursoController from './HorarioCursoController'
import HorarioMasivoController from './HorarioMasivoController'
import CargaHorariaController from './CargaHorariaController'
import HorarioTrabajadorController from './HorarioTrabajadorController'

const Horario = {
    HorarioCursoController: Object.assign(HorarioCursoController, HorarioCursoController),
    HorarioMasivoController: Object.assign(HorarioMasivoController, HorarioMasivoController),
    CargaHorariaController: Object.assign(CargaHorariaController, CargaHorariaController),
    HorarioTrabajadorController: Object.assign(HorarioTrabajadorController, HorarioTrabajadorController),
}

export default Horario
import HorarioTrabajadorController from './HorarioTrabajadorController'
import HorarioCursoController from './HorarioCursoController'
import HorarioMasivoController from './HorarioMasivoController'
import CargaHorariaController from './CargaHorariaController'

const Horario = {
    HorarioTrabajadorController: Object.assign(HorarioTrabajadorController, HorarioTrabajadorController),
    HorarioCursoController: Object.assign(HorarioCursoController, HorarioCursoController),
    HorarioMasivoController: Object.assign(HorarioMasivoController, HorarioMasivoController),
    CargaHorariaController: Object.assign(CargaHorariaController, CargaHorariaController),
}

export default Horario
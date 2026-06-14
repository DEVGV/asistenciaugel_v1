import type { SeccionIE, CursoIE } from './institucion-educativa';
import type { ParamSimple } from './params';
import type { Trabajador, AltaTrabajador } from './trabajador';

export interface HorarioCurso {
    id: number;
    anio: number;
    seccion_id: number;
    curso_id: number;
    diaSemana: string;
    nroDia: number;
    turno_id: number | null;
    nombreTurno: string | null;
    horaInicio: string;
    horaFin: string;
    minAcum: number | null;
    created_by: number | null;
    // Relations
    curso?: CursoIE;
    seccion?: SeccionIE;
    cargas?: CargaHoraria[];
}

export interface CargaHoraria {
    id: number;
    horarioCurso_id: number;
    trabajador_id: number;
    altaTrabajador_id: number | null;
    fechaInicio: string | null;
    fechaFin: string | null;
    titularSuplencia: string | null;
    created_by: number | null;
    // Relations
    horario_curso?: HorarioCurso;
    trabajador?: Trabajador;
    alta_trabajador?: AltaTrabajador;
}

export interface HorarioTrabajador {
    id: number;
    codigo: string | null;
    anio: number;
    institucionEduc_id: number;
    trabajador_id: number;
    altaTrabajador_id: number | null;
    tipoHorario: string | null;
    nombre: string | null;
    fechaInicio: string | null;
    fechaFin: string | null;
    created_by: number | null;
    archivado: boolean;
    activo: boolean;
    // Relations
    trabajador?: Trabajador;
    detalles?: DetalleHorario[];
}

export interface DetalleHorario {
    id: number;
    horarioTrabajador_id: number;
    turno_id: number | null;
    nombreTurno: string | null;
    nroTurno: number | null;
    diaSemana: string | null;
    nroDia: number | null;
    horarioCursoIni_id: number | null;
    entDiaInicio: number | null;
    entDiaFin: number | null;
    entHoraInicio: string | null;
    entHoraFin: string | null;
    entTolerancia: number | null;
    horarioCursoFin_id: number | null;
    salDiaInicio: number | null;
    salDiaFin: number | null;
    salHoraInicio: string | null;
    salHoraFin: string | null;
    salTolerancia: number | null;
    horaAcumula: number | null;
    aplicar: boolean;
    created_by: number | null;
    // Relations
    turno?: ParamSimple;
    horario_curso_ini?: HorarioCurso;
}

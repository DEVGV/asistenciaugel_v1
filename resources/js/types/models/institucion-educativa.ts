import type { LocalInstEduc } from './infraestructura';
import type { ParamSimple } from './params';
import type { Telefono, Email, Domicilio } from './persona';

export interface Entidad {
    id: number;
    razonSocial: string;
    razonComercial: string | null;
    ruc: string;
    activo: boolean;
    tipoEntidad?: ParamSimple;
}

export interface InstitucionEducativa {
    id: number;
    entidadUgel_id: number | null;
    entidadAdmin_id: number | null;
    codigoInstitucion: string;
    codigoModular: string | null;
    nombreLegal: string;
    regimenEduc_id: number | null;
    tipoInstEduc_id: number | null;
    modalidadFormativa_id: number | null;
    nivelCiclo_id: number | null;
    fechaInicio: string | null;
    fechaFin: string | null;
    created_by: number;
    // Relations
    regimen_educ?: ParamSimple;
    tipo_inst_educ?: ParamSimple;
    modalidad_formativa?: ParamSimple;
    nivel_ciclo?: ParamSimple;
    entidad_ugel?: Entidad;
    entidad_admin?: Entidad;
    cursos?: CursoIE[];
    grados?: GradoIE[];
    locales_inst_educ?: LocalInstEduc[];
    dias_no_laborables?: DiasNoLaborable[];
    telefonos?: Telefono[];
    emails?: Email[];
    domicilios?: Domicilio[];
}

export interface CursoIE {
    id: number;
    institucionEduc_id: number;
    codigo: string | null;
    nombre: string;
    sigla: string | null;
    created_by: number;
    activo: boolean;
}

export interface GradoIE {
    id: number;
    institucionEduc_id: number;
    codigo: string | null;
    nombre: string;
    sigla: string | null;
    created_by: number;
    activo: boolean;
    // Relations
    secciones?: SeccionIE[];
}

export interface SeccionIE {
    id: number;
    grado_id: number;
    codigo: string | null;
    nombre: string;
    sigla: string | null;
    created_by: number;
    activo: boolean;
    // Relations
    grado?: GradoIE;
}

export interface DiasNoLaborable {
    id: number;
    institucionEduc_id: number;
    feriado_id: number | null;
    fecha: string;
    observacion: string | null;
    nacionalLocal: 'N' | 'L' | null;
    recuperable: 'S' | 'N' | null;
    created_by: number | null;
    activo: boolean;
    // Relations
    feriado?: {
        id: number;
        descripcion: string | null;
        diaMesDefault: string | null;
    };
}

export interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

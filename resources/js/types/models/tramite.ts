import type { ParamSimple } from './params';

export interface DocumentoTram {
    id: number;
    expediente_id: number;
    documento_id: number | null;
    nroDoc: string | null;
    fechaDoc: string | null;
    trabajadorDoc_id: number | null;
    rutaDoc: string | null;
    observacion: string | null;
    // Relaciones
    documento?: ParamSimple;
}

export interface DetallePermiso {
    id: number;
    trabajador_id: number;
    altaTrabajador_id: number | null;
    turno: number | null;
    fechaInicio: string | null;
    fechaFin: string | null;
    marcaApli: string | null;
    expediente_id: number | null;
    observacion: string | null;
    // Relaciones
    alta_trabajador?: {
        id: number;
        institucionEducativa_id: number;
        institucion_educativa?: {
            id: number;
            nombreLegal: string | null;
            codigoInstitucion: string | null;
        };
        cargo?: { id: number; nombre: string | null };
    };
}

export interface ExpedientePermiso {
    id: number;
    codigo: string | null;
    anio: number | null;
    trabajador_id: number | null;
    asunto: string | null;
    fecha: string | null;
    observacion: string | null;
    estado_id: number | null;
    created_at: string | null;
    // Relaciones
    estado?: ParamSimple;
    trabajador?: {
        id: number;
        persona?: {
            id: number;
            paterno: string | null;
            materno: string | null;
            nombre: string | null;
            docIdentidad: string | null;
        };
    };
    documentos?: DocumentoTram[];
    justificaciones?: DetallePermiso[];
    exoneraciones?: DetallePermiso[];
}

export interface AltaPermisoOption {
    id: number;
    trabajador_id: number;
    label: string;
    docIdentidad?: string | null;
    cargo?: string | null;
}

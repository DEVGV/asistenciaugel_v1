import type { ParamSimple } from './params';

// ── Enums ────────────────────────────────────────────────────────────────────

export type TipoExpediente = 'J' | 'S' | 'E' | 'I';

export const TIPO_EXPEDIENTE_LABELS: Record<TipoExpediente, string> = {
    J: 'Justificación',
    S: 'Suspensión',
    E: 'Exoneración',
    I: 'Incapacidad',
};

/** Nombres de estado para el frontend (overrides del nombre de BD) */
export const ESTADO_LABELS: Record<string, string> = {
    '1': 'Registrado',
    '2': 'Por Autorizar',
    '3': 'Rechazado',
    '4': 'Autorizado',
    '5': 'Anulado',
};

/** Devuelve el label de estado para mostrar en el frontend */
export function estadoLabel(exp: Expediente): string {
    const codigo = exp.estado?.codigo ?? '1';
    return ESTADO_LABELS[codigo] ?? exp.estado?.nombre ?? 'Registrado';
}

// ── Modelos ──────────────────────────────────────────────────────────────────

export interface DocumentoTram {
    id: number;
    expediente_id: number | null;
    documento_id: number | null;
    nroDoc: string | null;
    fechaDoc: string | null;
    trabajadorDoc_id: number | null;
    rutaDoc: string | null;
    observacion: string | null;
    created_by: number | null;
    // Relaciones
    documento?: ParamSimple;
}

export interface Expediente {
    id: number;
    codigo: string | null;
    tipoExpediente: TipoExpediente | null;
    anio: number | null;
    trabajador_id: number | null;
    altaTrabajador_id: number | null;
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
    alta?: {
        id: number;
        institucionEducativa?: {
            id: number;
            nombreLegal: string | null;
            codigoModular: string | null;
        } | null;
    } | null;
    documentos?: DocumentoTram[];
    // Relaciones CUD
    suspension?: Record<string, unknown> | null;
    justificacion?: Record<string, unknown> | null;
    incapacidad?: Record<string, unknown> | null;
    exoneracion?: Record<string, unknown> | null;
    // Permisos de resolución (desde detalleJson)
    puedeResolver?: boolean;
    puedeRegistrarCud?: boolean;
    tieneDetalle?: boolean;
}

// ── Motivo de Suspensión Laboral ─────────────────────────────────────────────

export interface MotivoSuspLab {
    id: number;
    codigo: string | null;
    tipoSuspensionLaboral_id: number | null;
    descripcion: string | null;
    abreviatura: string | null;
    conGoceHaber: boolean | null;
    codigoProg: string | null;
    autorizadoPor: 'D' | 'U';
    activo: boolean | null;
    tipo_suspension_laboral?: { id: number; descripcion: string | null } | null;
}

// ── Formularios ──────────────────────────────────────────────────────────────

export interface DocumentoTramForm {
    documento_id: number | null;
    nroDoc: string;
    fechaDoc: string;
    observacion: string;
    archivo: File | null;
}

export interface PersonalActivoOption {
    alta_id: number;
    trabajador_id: number;
    label: string;
    docIdentidad: string | null;
    cargo: string | null;
}

export interface ExpedienteForm {
    tipoExpediente: TipoExpediente | '';
    trabajador_id: number | null;
    altaTrabajador_id: number | null;
    asunto: string;
    fecha: string;
    observacion: string;
    documentos: DocumentoTramForm[];
}

// ── Formularios CUD post-aprobación ─────────────────────────────────────────

export interface SuspensionForm {
    motivoSuspLab_id: number | null;
    fechaHoraInicio: string;
    fechaHoraFin: string;
    totalDias: number | null;
    totalHoras: number | null;
    observacion: string;
}

export interface JustificacionForm {
    motivoSuspLab_id: number | null;
    turno: number | null;
    fechaInicio: string;
    fechaFin: string;
    observacion: string;
}

export interface IncapacidadForm {
    motivoSuspLab_id: number | null;
    condicionSubsidio: string;
    fechaInicio: string;
    fechaFin: string;
    nroDias: number | null;
    nroCertificado: string;
    observacion: string;
}

export interface ExoneracionForm {
    motivoSuspLab_id: number | null;
    fechaInicio: string;
    fechaFin: string;
    observacion: string;
}

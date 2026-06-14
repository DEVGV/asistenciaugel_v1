import type { Persona } from './persona';
import type { PaginatedResponse } from './persona';

export interface Trabajador {
    id: number;
    codigo: string;
    persona_id: number;
    created_by: number;
    activo: boolean;
    // Relations
    persona?: Persona;
    altas?: AltaTrabajador[];
}

export interface AltaTrabajador {
    id: number;
    trabajador_id: number;
    institucionEducativa_id: number;
    condicionLaboral_id: number;
    tipoContrato_id: number;
    rolTrabajador_id: number;
    situacionLaboral_id: number;
    area_id: number | null;
    cargo_id: number | null;
    codigoAirsp: string | null;
    fechaInicio: string;
    fechaFin: string | null;
    fechaAlta: string | null;
    fechaBaja: string | null;
    motivoBaja_id: number | null;
    observacion: string | null;
    created_by: number | null;
    // Relations
    trabajador?: Trabajador;
    institucionEducativa?: {
        id: number;
        nombreLegal: string | null;
        codigoInstitucion: string | null;
    };
    institucion_educativa?: {
        id: number;
        nombreLegal: string | null;
        codigoInstitucion: string | null;
    };
    condicionLaboral?: {
        id: number;
        nombre: string | null;
        abreviatura: string | null;
    };
    condicion_laboral?: {
        id: number;
        nombre: string | null;
        abreviatura: string | null;
    };
    tipoContrato?: { id: number; nombre: string | null };
    tipo_contrato?: { id: number; nombre: string | null };
    rolTrabajador?: { id: number; nombre: string | null };
    rol_trabajador?: { id: number; nombre: string | null };
    situacionLaboral?: { id: number; nombre: string | null };
    situacion_laboral?: { id: number; nombre: string | null };
    area?: { id: number; nombre: string | null };
    cargo?: { id: number; nombre: string | null };
    motivoBaja?: { id: number; nombre: string | null };
    motivo_baja?: { id: number; nombre: string | null };
    perfil_ie?: {
        id: number;
        user_id: number;
        perfil_id: number;
        institucionEducativa_id: number;
        activo: boolean;
        perfil?: {
            id: number;
            nombre: string;
            descripcion: string | null;
        };
    } | null;
}

export type { PaginatedResponse };

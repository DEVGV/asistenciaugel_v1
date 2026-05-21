import type { ParamSimple } from './params';

export interface Persona {
    id: number;
    tipoDocIdentidad_id: number;
    docIdentidad: string;
    paterno: string;
    materno: string;
    nombre: string;
    sexo_id: number;
    fechaNac: string | null;
    pais_id: number | null;
    ubigeo: string | null;
    foto: string | null;
    created_by: number;
    activo: boolean;
    // Computed
    nombre_completo?: string;
    // Relations
    tipoDocIdentidad?: ParamSimple;
    sexo?: ParamSimple;
    pais?: ParamSimple;
    telefonos?: Telefono[];
    emails?: Email[];
    domicilios?: Domicilio[];
}

export interface Telefono {
    id: number;
    persona_id: number;
    entidad_id: number | null;
    institucionEduc_id: number | null;
    operador_id: number | null;
    movilFijo: 'M' | 'F';
    codigoPais: string | null;
    numero: string;
    imei: string | null;
    fechaInicio: string | null;
    fechaFin: string | null;
    created_by: number;
    // Relations
    operador?: ParamSimple;
}

export interface Email {
    id: number;
    persona_id: number;
    entidad_id: number | null;
    institucionEduc_id: number | null;
    email: string;
    personalInst: 'P' | 'I';
    fechaInicio: string | null;
    fechaFin: string | null;
    created_by: number;
}

export interface Domicilio {
    id: number;
    persona_id: number;
    entidad_id: number | null;
    institucionEduc_id: number | null;
    domicilio: string;
    zona_id: number | null;
    ubigeo: string | null;
    fechaInicio: string | null;
    fechaFin: string | null;
    created_by: number;
    // Relations
    zona?: { id: number; nombre: string };
}

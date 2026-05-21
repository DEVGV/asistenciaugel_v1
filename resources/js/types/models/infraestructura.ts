import type { ParamSimple } from './params';

export interface Local {
    id: number;
    nombre: string | null;
    domicilio: string | null;
    zona_id: number | null;
    ubigeo: string | null;
    utm_huso: number | null;
    utm_banda: string | null;
    utm_x_este: number | null;
    utm_y_norte: number | null;
    activo: boolean | null;
    created_at: string | null;
    // Relations
    zona?: ParamSimple;
}

export interface LocalInstEduc {
    id: number;
    local_id: number | null;
    entidad_id: number | null;
    institucionEduc_id: number | null;
    fechaInicio: string | null;
    fechaFin: string | null;
    // Relations
    local?: Local;
    relojes?: Reloj[];
    locales_marcacion?: LocalMarcacion[];
}

export interface LocalMarcacion {
    id: number;
    trabajador_id: number;
    altaTrabajador_id: number | null;
    localInstEduc_id: number | null;
    fechaInicio: string | null;
    fechaFin: string | null;
    // Relations
    trabajador?: {
        id: number;
        codigo: string;
        persona?: {
            paterno: string;
            materno: string;
            nombre: string | null;
        };
    };
}

export interface Reloj {
    id: number;
    nombre: string | null;
    dreccionIP: string | null;
    direccionMac: string | null;
    puerto: number | null;
    serie: string | null;
    localInstEduc_id: number | null;
    idBiometrico: number | null;
    activo: boolean | null;
    created_at: string | null;
}

export interface DispositivoMarca {
    id: number;
    telefonoMovil_id: number;
    fechaInicio: string | null;
    fechaFin: string | null;
    created_at: string | null;
    // Relations
    telefono_movil?: {
        id: number;
        numero: string;
    };
}

import type { ParamSimple } from './params';

export interface Entidad {
    id: number;
    tipoEntidad_id: number;
    ruc: string;
    razonSocial: string;
    razonComercial: string | null;
    personaRepLegal_id: number | null;
    created_by: number;
    activo: boolean;
    // Relations
    tipo_entidad?: ParamSimple;
}

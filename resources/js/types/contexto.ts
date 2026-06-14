export interface ContextoIeOpcion {
    id: number;
    nombre: string;
    codigoModular: string | null;
    perfil: string | null;
}

export interface ContextoUgelOpcion {
    id: number;
    nombre: string;
    esAdmin: boolean;
    ies: ContextoIeOpcion[];
}

export interface ContextoActivo {
    ugel: { id: number; nombre: string } | null;
    ie: { id: number; nombre: string; codigoModular: string | null } | null;
    multiple: boolean;
}

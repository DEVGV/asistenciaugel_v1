export interface Area {
    id: number;
    codigo: string | null;
    nombre: string | null;
    sigla: string | null;
    descripcion: string | null;
    rolTrabajador_id: number | null;
    activo: boolean;
}

export interface Cargo {
    id: number;
    codigo: string | null;
    nombre: string | null;
    abreviatura: string | null;
    rolTrabajador_id: number | null;
    created_by: number | null;
    activo: boolean;
    // Relaciones
    rol_trabajador?: { id: number; nombre: string | null };
}

export interface CondicionLaboral {
    id: number;
    codigo: string | null;
    regimenLaboral_id: number;
    tipoTrabajador_id: number;
    nombre: string | null;
    abreviatura: string | null;
    descripcion: string | null;
    created_by: number | null;
    // Relations
    regimenLaboral?: { id: number; nombre: string | null };
    tipoTrabajador?: { id: number; nombre: string | null };
}

export interface Permiso {
    id: number;
    codigo: string;
    modulo: string;
    descripcion: string | null;
    activo: boolean;
}

export interface Perfil {
    id: number;
    nombre: string;
    descripcion: string | null;
    activo: boolean;
    permisos_count?: number;
    permisos?: Permiso[];
}

export interface UsuarioPerfilIe {
    id: number;
    perfil_id: number;
    perfil: Perfil;
    entidadUgel_id: number | null;
    entidadUgel: {
        id: number;
        razonSocial: string;
    } | null;
    institucionEducativa_id: number | null;
    institucionEducativa: {
        id: number;
        nombreLegal: string;
        codigoModular: string | null;
    } | null;
    activo: boolean;
}

export interface Usuario {
    id: number;
    login: string;
    activo: boolean;
    trabajador?: {
        id: number;
        persona?: {
            nombre: string | null;
            paterno: string | null;
            materno: string | null;
        };
    };
    perfiles_ie?: UsuarioPerfilIe[];
}

export interface Zona {
    id: number;
    tipoZona_id: number;
    distrito_id: number | null;
    nombre: string | null;
    abreviatura: string | null;
    created_by: number | null;
    activo: boolean;
    // Relations
    tipoZona?: {
        id: number;
        nombre: string | null;
        abreviatura: string | null;
    };
    distrito?: {
        id: number;
        nombre: string | null;
        provincia?: {
            id: number;
            nombre: string | null;
            departamento?: {
                id: number;
                nombre: string | null;
            };
        };
    };
}

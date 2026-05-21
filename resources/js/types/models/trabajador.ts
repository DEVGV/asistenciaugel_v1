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
}

export type { PaginatedResponse };

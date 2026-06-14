import type { Persona } from './models/persona';

export interface Trabajador {
    id: number;
    codigo: string;
    persona_id: number;
    activo: boolean;
    persona?: Persona;
}

export type User = {
    id: number;
    login: string;
    trabajador_id: number | null;
    activo: boolean;
    created_at: string;
    updated_at: string;
    // Relations
    trabajador?: Trabajador;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};

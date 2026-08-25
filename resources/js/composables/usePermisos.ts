import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

/**
 * Composable para verificar permisos del usuario en el contexto activo.
 *
 * Uso:
 *   const { can, canAny, perfilActivo, esAdmin, esDirector, esDocente } = usePermisos();
 *
 *   if (can('trabajadores.crear')) { ... }
 *   if (canAny(['trabajadores.crear', 'trabajadores.editar'])) { ... }
 *   if (esDirector.value) { ... }
 */
export function usePermisos() {
    const page = usePage();

    const permisos = computed<string[]>(() => (page.props.permisos as string[]) ?? []);

    const perfilActivo = computed<string | null>(() => (page.props.perfilActivo as string | null) ?? null);

    /** Verifica si el usuario tiene un permiso específico. */
    function can(permiso: string): boolean {
        return permisos.value.includes(permiso);
    }

    /** Verifica si el usuario tiene al menos uno de los permisos indicados. */
    function canAny(codigos: string[]): boolean {
        return codigos.some((c) => permisos.value.includes(c));
    }

    /** Verifica si el usuario tiene todos los permisos indicados. */
    function canAll(codigos: string[]): boolean {
        return codigos.every((c) => permisos.value.includes(c));
    }

    // Atajos por perfil
    const esAdmin = computed(() => perfilActivo.value === 'Admin UGEL');
    const esDirector = computed(() => perfilActivo.value === 'Director IE');
    const esDocente = computed(() => perfilActivo.value === 'Docente');

    return {
        permisos,
        perfilActivo,
        can,
        canAny,
        canAll,
        esAdmin,
        esDirector,
        esDocente,
    };
}

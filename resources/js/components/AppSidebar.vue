<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    LayoutGrid,
    UserCheck,
    User,
    School,
    Building2,
    Settings,
    FileText,
} from 'lucide-vue-next';
import AreaController from '@/actions/App/Http/Controllers/Configuracion/AreaController';
import CargoController from '@/actions/App/Http/Controllers/Configuracion/CargoController';
import CondicionLaboralController from '@/actions/App/Http/Controllers/Configuracion/CondicionLaboralController';
import PerfilController from '@/actions/App/Http/Controllers/Configuracion/PerfilController';
import UsuarioController from '@/actions/App/Http/Controllers/Configuracion/UsuarioController';
import ZonaController from '@/actions/App/Http/Controllers/Configuracion/ZonaController';
import EntidadController from '@/actions/App/Http/Controllers/Entidad/EntidadController';
import InstitucionEducativaController from '@/actions/App/Http/Controllers/InstitucionEducativa/InstitucionEducativaController';
import TrabajadorController from '@/actions/App/Http/Controllers/Trabajador/TrabajadorController';
import ExpedienteController from '@/actions/App/Http/Controllers/Tramite/ExpedienteController';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { usePermisos } from '@/composables/usePermisos';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';
import { computed } from 'vue';

const { canAny, esAdmin, esDirector } = usePermisos();
const page = usePage();
const authUser = computed(() => page.props.auth?.user);

/**
 * Todos los items del sidebar con sus permisos requeridos.
 * Si `requiere` está vacío o ausente, el item siempre se muestra.
 * Para sub-items, se filtra individualmente.
 */
const allNavItems: NavItem[] = [
    {
        title: 'Panel de Control',
        href: dashboard(),
        icon: LayoutGrid,
        requiere: ['dashboard.ver', 'dashboard.admin'],
    },
    {
        title: 'Trabajadores',
        href: TrabajadorController.index().url,
        icon: UserCheck,
        requiere: ['trabajadores.ver'],
    },
    {
        title: 'Entidades',
        href: EntidadController.index().url,
        icon: Building2,
        requiere: ['entidades.ver'],
    },
    {
        title: 'Instituciones Educativas',
        href: InstitucionEducativaController.index().url,
        icon: School,
        requiere: ['instituciones.ver'],
    },
    {
        title: 'Configuración',
        href: '#',
        icon: Settings,
        requiere: ['configuracion.ver', 'configuracion.editar', 'usuarios.gestionar'],
        items: [
            { title: 'Áreas', href: AreaController.index().url, requiere: ['configuracion.ver'] },
            { title: 'Cargos', href: CargoController.index().url, requiere: ['configuracion.ver'] },
            {
                title: 'Cond. Laborales',
                href: CondicionLaboralController.index().url,
                requiere: ['configuracion.ver'],
            },
            { title: 'Zonas', href: ZonaController.index().url, requiere: ['configuracion.ver'] },
            { title: 'Usuarios', href: UsuarioController.index().url, requiere: ['usuarios.gestionar'] },
            { title: 'Perfiles', href: PerfilController.index().url, requiere: ['usuarios.gestionar'] },
        ],
    },
    {
        title: 'Trámites',
        href: '#',
        icon: FileText,
        requiere: ['tramites.ver'],
        items: [
            { title: 'Expedientes', href: ExpedienteController.index().url, requiere: ['tramites.ver'] },
        ],
    },
];

/**
 * Filtra items y sub-items según los permisos del usuario.
 * - Si un item no tiene `requiere`, siempre se muestra.
 * - Para items con sub-items, se filtran los hijos y si no queda ninguno, se oculta el padre.
 *
 * Para trabajadores normales (ni Admin UGEL ni Director IE):
 * el sidebar solo muestra un enlace con su nombre
 * que lleva a su propia ficha de trabajador.
 */
const mainNavItems = computed<NavItem[]>(() => {
    // ── Trabajador normal: solo su nombre → su ficha ──
    if (!esAdmin.value && !esDirector.value) {
        const trabajador = authUser.value?.trabajador;
        const persona = trabajador?.persona;
        if (trabajador && persona) {
            const primerNombre = (persona.nombre ?? '').split(' ')[0] || 'Mi Perfil';
            const doc = persona.docIdentidad ? ` - ${persona.docIdentidad}` : '';
            return [{
                title: `${primerNombre}${doc}`,
                href: TrabajadorController.show({ trabajador: trabajador.id }).url,
                icon: User,
            }];
        }
        return [];
    }

    // ── Otros perfiles: filtrado estándar ──
    return allNavItems.reduce<NavItem[]>((acc, item) => {
        // Verificar permiso del item padre
        if (item.requiere && item.requiere.length > 0 && !canAny(item.requiere)) {
            return acc;
        }

        // Si tiene sub-items, filtrarlos
        if (item.items && item.items.length > 0) {
            const filteredItems = item.items.filter((sub) => {
                if (!sub.requiere || sub.requiere.length === 0) return true;
                return canAny(sub.requiere);
            });

            // Si no queda ningún sub-item visible, no mostrar el padre
            if (filteredItems.length === 0) return acc;

            acc.push({ ...item, items: filteredItems });
        } else {
            acc.push(item);
        }

        return acc;
    }, []);
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

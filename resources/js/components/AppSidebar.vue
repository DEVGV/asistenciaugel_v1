<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    LayoutGrid,
    Users,
    UserCheck,
    School,
    Building2,
    Settings,
    CalendarCheck,
    FileText,
} from 'lucide-vue-next';
import AreaController from '@/actions/App/Http/Controllers/Configuracion/AreaController';
import CargoController from '@/actions/App/Http/Controllers/Configuracion/CargoController';
import CondicionLaboralController from '@/actions/App/Http/Controllers/Configuracion/CondicionLaboralController';
import ZonaController from '@/actions/App/Http/Controllers/Configuracion/ZonaController';
import EntidadController from '@/actions/App/Http/Controllers/Entidad/EntidadController';
import InstitucionEducativaController from '@/actions/App/Http/Controllers/InstitucionEducativa/InstitucionEducativaController';
import PersonaController from '@/actions/App/Http/Controllers/Persona/PersonaController';
import TrabajadorController from '@/actions/App/Http/Controllers/Trabajador/TrabajadorController';
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
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Panel de Control',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Personas',
        href: PersonaController.index().url,
        icon: Users,
    },
    {
        title: 'Trabajadores',
        href: TrabajadorController.index().url,
        icon: UserCheck,
    },
    {
        title: 'Instituciones Educativas',
        href: InstitucionEducativaController.index().url,
        icon: School,
    },
    {
        title: 'Entidades',
        href: EntidadController.index().url,
        icon: Building2,
    },
    {
        title: 'Configuración',
        href: '#',
        icon: Settings,
        items: [
            { title: 'Áreas', href: AreaController.index().url },
            { title: 'Cargos', href: CargoController.index().url },
            {
                title: 'Cond. Laborales',
                href: CondicionLaboralController.index().url,
            },
            { title: 'Zonas', href: ZonaController.index().url },
        ],
    },
    {
        title: 'Asistencia',
        href: '#',
        icon: CalendarCheck,
        items: [
            { title: 'Marcaciones', href: '#' },
            { title: 'Mensual', href: '#' },
            { title: 'Consolidación', href: '#' },
        ],
    },
    {
        title: 'Trámites',
        href: '#',
        icon: FileText,
        items: [
            { title: 'Expedientes', href: '#' },
            { title: 'Justificaciones', href: '#' },
            { title: 'Suspensiones', href: '#' },
        ],
    },
];

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

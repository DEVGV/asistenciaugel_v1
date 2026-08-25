<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import {
    Users,
    School,
    CalendarCheck,
    Clock,
    FileText,
    AlertTriangle,
    Building2,
    BarChart3,
    UserCheck,
    ClipboardList,
} from 'lucide-vue-next';
import { usePermisos } from '@/composables/usePermisos';
import { dashboard } from '@/routes';
import { computed } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Panel de Control',
                href: dashboard(),
            },
        ],
    },
});

const { can, canAny, esAdmin, esDirector, esDocente, perfilActivo } = usePermisos();
const page = usePage();

const contexto = computed(() => page.props.contexto);
const userName = computed(() => {
    const user = page.props.auth?.user;
    const persona = user?.trabajador?.persona;
    if (persona) {
        return `${persona.nombre} ${persona.paterno}`.trim();
    }
    return user?.login ?? 'Usuario';
});
</script>

<template>
    <Head title="Panel de Control" />

    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
        <!-- Saludo y contexto -->
        <div>
            <h1 class="text-2xl font-semibold text-foreground">
                Bienvenido, {{ userName }}
            </h1>
            <p class="mt-1 text-sm text-muted-foreground">
                <span v-if="perfilActivo" class="inline-flex items-center gap-1.5">
                    <span
                        class="inline-block h-2 w-2 rounded-full"
                        :class="{
                            'bg-purple-500': esAdmin,
                            'bg-teal-500': esDirector,
                            'bg-amber-500': esDocente,
                            'bg-gray-400': !esAdmin && !esDirector && !esDocente,
                        }"
                    />
                    {{ perfilActivo }}
                </span>
                <span v-if="contexto?.ugel"> &middot; {{ contexto.ugel.nombre }}</span>
                <span v-if="contexto?.ie"> &middot; {{ contexto.ie.nombre }}</span>
            </p>
        </div>

        <!-- ════════════════════════════════════════════════════════════════════
             DASHBOARD: Admin UGEL
             ════════════════════════════════════════════════════════════════════ -->
        <template v-if="esAdmin">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30">
                            <School class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Instituciones</p>
                            <p class="text-2xl font-semibold text-foreground">—</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                            <Users class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Trabajadores</p>
                            <p class="text-2xl font-semibold text-foreground">—</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-100 dark:bg-teal-900/30">
                            <Building2 class="h-5 w-5 text-teal-600 dark:text-teal-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Entidades</p>
                            <p class="text-2xl font-semibold text-foreground">—</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                            <AlertTriangle class="h-5 w-5 text-amber-600 dark:text-amber-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Alertas</p>
                            <p class="text-2xl font-semibold text-foreground">—</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <h3 class="mb-3 text-sm font-medium text-muted-foreground">Resumen de asistencia global</h3>
                    <div class="flex h-48 items-center justify-center text-muted-foreground/50">
                        <BarChart3 class="mr-2 h-5 w-5" /> Datos de asistencia próximamente
                    </div>
                </div>
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <h3 class="mb-3 text-sm font-medium text-muted-foreground">Trámites pendientes</h3>
                    <div class="flex h-48 items-center justify-center text-muted-foreground/50">
                        <FileText class="mr-2 h-5 w-5" /> Sin trámites pendientes
                    </div>
                </div>
            </div>
        </template>

        <!-- ════════════════════════════════════════════════════════════════════
             DASHBOARD: Director IE
             ════════════════════════════════════════════════════════════════════ -->
        <template v-else-if="esDirector">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-100 dark:bg-teal-900/30">
                            <UserCheck class="h-5 w-5 text-teal-600 dark:text-teal-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Docentes en la IE</p>
                            <p class="text-2xl font-semibold text-foreground">—</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30">
                            <CalendarCheck class="h-5 w-5 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Presentes hoy</p>
                            <p class="text-2xl font-semibold text-foreground">—</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/30">
                            <AlertTriangle class="h-5 w-5 text-red-600 dark:text-red-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Ausentes hoy</p>
                            <p class="text-2xl font-semibold text-foreground">—</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                            <ClipboardList class="h-5 w-5 text-amber-600 dark:text-amber-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Trámites pendientes</p>
                            <p class="text-2xl font-semibold text-foreground">—</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                <h3 class="mb-3 text-sm font-medium text-muted-foreground">Asistencia de hoy — {{ contexto?.ie?.nombre }}</h3>
                <div class="flex h-48 items-center justify-center text-muted-foreground/50">
                    <CalendarCheck class="mr-2 h-5 w-5" /> Resumen de marcaciones próximamente
                </div>
            </div>
        </template>

        <!-- ════════════════════════════════════════════════════════════════════
             DASHBOARD: Docente
             ════════════════════════════════════════════════════════════════════ -->
        <template v-else-if="esDocente">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                            <Clock class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Mi horario de hoy</p>
                            <p class="text-lg font-semibold text-foreground">Sin clases programadas</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30">
                            <CalendarCheck class="h-5 w-5 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Mi asistencia del mes</p>
                            <p class="text-lg font-semibold text-foreground">—</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                            <FileText class="h-5 w-5 text-amber-600 dark:text-amber-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Mis trámites</p>
                            <p class="text-lg font-semibold text-foreground">Sin pendientes</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border">
                <h3 class="mb-3 text-sm font-medium text-muted-foreground">Mis últimas marcaciones</h3>
                <div class="flex h-32 items-center justify-center text-muted-foreground/50">
                    <Clock class="mr-2 h-5 w-5" /> Historial de marcaciones próximamente
                </div>
            </div>
        </template>

        <!-- ════════════════════════════════════════════════════════════════════
             DASHBOARD: Fallback (perfil desconocido o sin perfil)
             ════════════════════════════════════════════════════════════════════ -->
        <template v-else>
            <div class="rounded-xl border border-sidebar-border/70 bg-card p-8 text-center dark:border-sidebar-border">
                <AlertTriangle class="mx-auto mb-3 h-8 w-8 text-muted-foreground/50" />
                <p class="text-muted-foreground">
                    No se ha detectado un perfil activo para este contexto.
                    Contacta al administrador si necesitas acceso.
                </p>
            </div>
        </template>
    </div>
</template>

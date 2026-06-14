<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Building2, Calendar, Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import TrabajadorController from '@/actions/App/Http/Controllers/Trabajador/TrabajadorController';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import altasRoutes from '@/routes/altas';
import type {
    AltaTrabajador,
    PaginatedResponse,
} from '@/types/models/trabajador';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Trabajadores', href: TrabajadorController.index().url },
            { title: 'Altas', href: altasRoutes.index().url },
        ],
    },
});

const props = defineProps<{
    altas: PaginatedResponse<AltaTrabajador>;
    filters: { search?: string; anio?: string; solo_activas?: boolean };
}>();

// ─── Filtros ───
const search = ref(props.filters.search || '');
const anio = ref(props.filters.anio || new Date().getFullYear().toString());
const soloActivas = ref(props.filters.solo_activas ?? false);

let searchTimeout: ReturnType<typeof setTimeout>;

function applyFilters() {
    router.get(
        altasRoutes.index().url,
        {
            search: search.value || undefined,
            anio: anio.value || undefined,
            solo_activas: soloActivas.value ? '1' : undefined,
        },
        { preserveState: true, replace: true },
    );
}

watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 350);
});

watch([anio, soloActivas], applyFilters);

function estaActiva(alta: AltaTrabajador): boolean {
    return !alta.fechaBaja;
}
</script>

<template>
    <Head title="Altas de Trabajadores" />

    <div class="flex flex-col gap-4 p-4">
        <!-- Encabezado -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">
                    Altas de Trabajadores
                </h1>
                <p class="text-sm text-muted-foreground">
                    Listado global de vinculaciones laborales por institución
                    educativa.
                </p>
            </div>
        </div>

        <!-- Filtros -->
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <Search
                    class="absolute top-2.5 left-3 h-4 w-4 text-muted-foreground"
                />
                <Input
                    v-model="search"
                    placeholder="Buscar por trabajador, DNI..."
                    class="pl-9"
                />
            </div>
            <div class="flex items-center gap-2">
                <Calendar class="h-4 w-4 text-muted-foreground" />
                <Input
                    v-model="anio"
                    type="number"
                    min="2000"
                    max="2100"
                    class="w-24"
                    placeholder="Año"
                />
            </div>
            <label class="flex cursor-pointer items-center gap-2 text-sm">
                <input
                    v-model="soloActivas"
                    type="checkbox"
                    class="size-4 rounded border-input accent-primary"
                />
                Solo activas
            </label>
        </div>

        <!-- Tabla -->
        <div class="overflow-hidden rounded-md border bg-card shadow-xs">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Trabajador</TableHead>
                        <TableHead>
                            <div class="flex items-center gap-1">
                                <Building2 class="h-3.5 w-3.5" />
                                Institución
                            </div>
                        </TableHead>
                        <TableHead>Condición / Rol</TableHead>
                        <TableHead class="w-[110px]">Inicio</TableHead>
                        <TableHead class="w-[110px]">Fin / Baja</TableHead>
                        <TableHead class="w-[80px]">Estado</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="alta in props.altas.data" :key="alta.id">
                        <TableCell>
                            <Link
                                :href="
                                    TrabajadorController.show({
                                        trabajador: alta.trabajador_id,
                                    }).url
                                "
                                class="group"
                            >
                                <div
                                    class="text-sm font-medium transition-colors group-hover:text-primary"
                                >
                                    {{ alta.trabajador?.persona?.paterno }}
                                    {{ alta.trabajador?.persona?.materno }}
                                </div>
                                <div class="text-xs text-muted-foreground">
                                    {{ alta.trabajador?.codigo }} ·
                                    {{ alta.trabajador?.persona?.docIdentidad }}
                                </div>
                            </Link>
                        </TableCell>
                        <TableCell>
                            <div class="text-sm font-medium">
                                {{
                                    (
                                        alta.institucion_educativa ||
                                        alta.institucionEducativa
                                    )?.nombreLegal || '-'
                                }}
                            </div>
                            <div
                                v-if="alta.codigoAirsp"
                                class="text-xs text-muted-foreground"
                            >
                                AIRSP: {{ alta.codigoAirsp }}
                            </div>
                        </TableCell>
                        <TableCell>
                            <div class="text-xs font-medium">
                                {{
                                    (
                                        alta.condicion_laboral ||
                                        alta.condicionLaboral
                                    )?.abreviatura ||
                                    (
                                        alta.condicion_laboral ||
                                        alta.condicionLaboral
                                    )?.nombre ||
                                    '-'
                                }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{
                                    (alta.rol_trabajador || alta.rolTrabajador)
                                        ?.nombre || '-'
                                }}
                            </div>
                            <div v-if="alta.perfil_ie?.perfil" class="mt-1">
                                <span
                                    class="inline-flex items-center rounded bg-blue-50 px-1.5 py-0.5 text-[10px] font-semibold text-blue-700 ring-1 ring-blue-700/10 dark:bg-blue-950/30 dark:text-blue-400 dark:ring-blue-400/20"
                                >
                                    Perfil: {{ alta.perfil_ie.perfil.nombre }}
                                </span>
                            </div>
                        </TableCell>
                        <TableCell class="text-xs">{{
                            alta.fechaInicio
                        }}</TableCell>
                        <TableCell class="text-xs">
                            <div v-if="alta.fechaBaja" class="text-destructive">
                                {{ alta.fechaBaja }}
                            </div>
                            <span
                                v-else-if="alta.fechaFin"
                                class="text-muted-foreground"
                                >{{ alta.fechaFin }}</span
                            >
                            <span v-else class="text-muted-foreground">—</span>
                        </TableCell>
                        <TableCell>
                            <StatusBadge
                                :active="estaActiva(alta)"
                                label-active="Activo"
                                label-inactive="Baja"
                            />
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="!props.altas.data.length">
                        <TableCell
                            colspan="6"
                            class="h-24 text-center text-muted-foreground"
                        >
                            No se encontraron altas con los filtros aplicados.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Paginación -->
            <div
                v-if="props.altas.total > 0"
                class="flex items-center justify-between border-t px-4 py-3"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.altas.current_page }} de
                    {{ props.altas.last_page }} ({{ props.altas.total }}
                    registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.altas.current_page <= 1"
                        @click="
                            router.get(
                                altasRoutes.index().url,
                                {
                                    page: props.altas.current_page - 1,
                                    search: search || undefined,
                                    anio: anio || undefined,
                                },
                                { preserveState: true },
                            )
                        "
                    >
                        Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.altas.current_page >= props.altas.last_page
                        "
                        @click="
                            router.get(
                                altasRoutes.index().url,
                                {
                                    page: props.altas.current_page + 1,
                                    search: search || undefined,
                                    anio: anio || undefined,
                                },
                                { preserveState: true },
                            )
                        "
                    >
                        Siguiente
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

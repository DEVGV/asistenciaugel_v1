<script setup lang="ts">
import { useDebounceFn } from '@vueuse/core';
import {
    AlertCircle,
    ChevronLeft,
    ChevronRight,
    ClipboardCheck,
    Eye,
    FileCheck,
    Plus,
    RefreshCw,
    Search,
} from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';
import ExpedienteDetailModal from '@/components/tramite/ExpedienteDetailModal.vue';
import ExpedienteFormModal from '@/components/tramite/ExpedienteFormModal.vue';
import RegistroDirectoModal from '@/components/tramite/RegistroDirectoModal.vue';
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
import { usePermisos } from '@/composables/usePermisos';
import { TIPO_EXPEDIENTE_LABELS, estadoLabel, type Expediente } from '@/types/models/tramite';

const props = defineProps<{
    institucionId: number;
}>();

const { can, esAdmin, esDirector } = usePermisos();

const expedientes = ref<Expediente[]>([]);
const loading = ref(false);
const error = ref(false);
const search = ref('');
const page = ref(1);
const lastPage = ref(1);
const total = ref(0);
const showModal = ref(false);
const showDirectoModal = ref(false);

// Modal de detalle
const showDetailModal = ref(false);
const selectedExpedienteId = ref<number | null>(null);

function openDetail(exp: Expediente) {
    selectedExpedienteId.value = exp.id;
    showDetailModal.value = true;
}

const ESTADO_CLASES: Record<string, string> = {
    '1': 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
    '2': 'bg-sky-100 text-sky-700 ring-1 ring-sky-200',
    '3': 'bg-red-100 text-red-700 ring-1 ring-red-200',
    '4': 'bg-blue-100 text-blue-700 ring-1 ring-blue-200',
    '5': 'bg-muted text-muted-foreground ring-1 ring-border',
};

function nombreTrabajador(exp: Expediente): string {
    const p = exp.trabajador?.persona;
    if (!p) return '—';
    return [p.paterno, p.materno, p.nombre].filter(Boolean).join(' ');
}

async function loadExpedientes() {
    loading.value = true;
    error.value = false;

    try {
        const params = new URLSearchParams({ page: String(page.value) });
        if (search.value) params.set('search', search.value);

        const res = await fetch(
            `/api/instituciones/${props.institucionId}/expedientes?${params}`,
            { headers: { Accept: 'application/json' } },
        );

        if (!res.ok) throw new Error();

        const result = await res.json();
        expedientes.value = result.data ?? [];
        lastPage.value = result.last_page ?? 1;
        total.value = result.total ?? result.data?.length ?? 0;
    } catch {
        error.value = true;
    } finally {
        loading.value = false;
    }
}

onMounted(() => loadExpedientes());
watch(page, () => loadExpedientes());

const debouncedSearch = useDebounceFn(() => {
    page.value = 1;
    loadExpedientes();
}, 350);

watch(search, () => debouncedSearch());
</script>

<template>
    <div class="flex flex-col gap-5">
        <!-- Cabecera -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <ClipboardCheck class="h-5 w-5 text-primary" />
                <h2 class="text-base font-bold">Expedientes</h2>
                <span
                    v-if="!loading && !error"
                    class="rounded-full bg-muted px-2 py-0.5 text-xs font-medium text-muted-foreground"
                >
                    {{ total }}
                </span>
            </div>

            <div class="flex items-center gap-2">
                <Button
                    variant="ghost"
                    size="icon"
                    class="h-8 w-8"
                    :disabled="loading"
                    @click="loadExpedientes"
                >
                    <RefreshCw class="h-4 w-4" :class="{ 'animate-spin': loading }" />
                </Button>
                <Button v-if="esDirector || esAdmin" size="sm" variant="outline" class="h-8 text-xs" @click="showDirectoModal = true">
                    <FileCheck class="mr-1 h-3.5 w-3.5" />
                    Registrar permiso
                </Button>
                <Button v-if="can('tramites.crear')" size="sm" class="h-8 text-xs" @click="showModal = true">
                    <Plus class="mr-1 h-3.5 w-3.5" />
                    Nuevo expediente
                </Button>
            </div>
        </div>

        <!-- Búsqueda -->
        <div class="relative w-full sm:w-64">
            <Search class="absolute top-2.5 left-2.5 h-3.5 w-3.5 text-muted-foreground" />
            <Input v-model="search" placeholder="Buscar trabajador…" class="h-8 pl-8 text-xs" />
        </div>

        <!-- Error -->
        <div
            v-if="error"
            class="flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
        >
            <AlertCircle class="h-4 w-4 shrink-0" />
            Error al cargar los expedientes. Intente nuevamente.
        </div>

        <!-- Skeleton -->
        <div v-else-if="loading" class="rounded-lg border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Código</TableHead>
                        <TableHead>Trabajador</TableHead>
                        <TableHead>Tipo</TableHead>
                        <TableHead>Asunto</TableHead>
                        <TableHead>Fecha</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="w-16" />
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="i in 3" :key="i">
                        <TableCell><div class="h-4 w-16 animate-pulse rounded bg-muted" /></TableCell>
                        <TableCell><div class="h-4 w-32 animate-pulse rounded bg-muted" /></TableCell>
                        <TableCell><div class="h-4 w-20 animate-pulse rounded bg-muted" /></TableCell>
                        <TableCell><div class="h-4 w-40 animate-pulse rounded bg-muted" /></TableCell>
                        <TableCell><div class="h-4 w-24 animate-pulse rounded bg-muted" /></TableCell>
                        <TableCell><div class="h-6 w-20 animate-pulse rounded bg-muted" /></TableCell>
                        <TableCell><div class="h-8 w-8 animate-pulse rounded bg-muted" /></TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Vacío -->
        <div
            v-else-if="expedientes.length === 0"
            class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed py-14 text-center bg-card"
        >
            <ClipboardCheck class="mb-3 h-10 w-10 text-muted-foreground/40" />
            <p class="text-sm font-medium text-muted-foreground">Sin expedientes para los filtros seleccionados.</p>
            <Button v-if="can('tramites.crear')" size="sm" variant="outline" class="mt-4" @click="showModal = true">
                <Plus class="mr-1.5 h-3.5 w-3.5" />
                Nuevo expediente
            </Button>
        </div>

        <!-- Lista -->
        <template v-else>
            <div class="rounded-lg border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Código</TableHead>
                            <TableHead>Trabajador</TableHead>
                            <TableHead>Tipo</TableHead>
                            <TableHead>Asunto</TableHead>
                            <TableHead>Fecha</TableHead>
                            <TableHead>Estado</TableHead>
                            <TableHead class="w-16" />
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="exp in expedientes" :key="exp.id">
                            <TableCell class="font-mono text-xs font-semibold text-primary">
                                {{ exp.codigo ?? `EXP-${exp.id}` }}
                            </TableCell>
                            <TableCell class="text-sm">
                                {{ nombreTrabajador(exp) }}
                            </TableCell>
                            <TableCell class="text-sm">
                                {{ exp.tipoExpediente ? TIPO_EXPEDIENTE_LABELS[exp.tipoExpediente] : '—' }}
                            </TableCell>
                            <TableCell class="max-w-xs truncate text-sm">
                                {{ exp.asunto }}
                            </TableCell>
                            <TableCell class="text-sm">
                                {{ exp.fecha }}
                            </TableCell>
                            <TableCell>
                                <span
                                    class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                    :class="ESTADO_CLASES[exp.estado?.codigo ?? ''] ?? ESTADO_CLASES['1']"
                                >
                                    {{ estadoLabel(exp) }}
                                </span>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button variant="ghost" size="sm" @click="openDetail(exp)" title="Ver detalle">
                                    <Eye class="h-4 w-4" />
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <!-- Paginación -->
            <div v-if="lastPage > 1" class="flex items-center justify-center gap-3">
                <Button
                    variant="outline"
                    size="sm"
                    class="h-8"
                    :disabled="page <= 1 || loading"
                    @click="page--"
                >
                    <ChevronLeft class="h-4 w-4" />
                </Button>
                <span class="text-xs text-muted-foreground">Página {{ page }} de {{ lastPage }}</span>
                <Button
                    variant="outline"
                    size="sm"
                    class="h-8"
                    :disabled="page >= lastPage || loading"
                    @click="page++"
                >
                    <ChevronRight class="h-4 w-4" />
                </Button>
            </div>
        </template>
    </div>

    <!-- Modal crear expediente -->
    <ExpedienteFormModal
        v-model:show="showModal"
        :institucion-id="props.institucionId"
        @success="loadExpedientes"
    />

    <!-- Modal detalle expediente -->
    <ExpedienteDetailModal
        v-model:show="showDetailModal"
        :expediente-id="selectedExpedienteId"
    />

    <!-- Modal registro directo -->
    <RegistroDirectoModal
        v-model:show="showDirectoModal"
        :institucion-id="props.institucionId"
        @success="loadExpedientes"
    />
</template>

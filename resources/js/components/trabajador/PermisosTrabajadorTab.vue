<script setup lang="ts">
import { AlertCircle, ClipboardCheck, Eye, FileCheck, Plus, RefreshCw } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { usePermisos } from '@/composables/usePermisos';
import ExpedienteDetailModal from '@/components/tramite/ExpedienteDetailModal.vue';
import ExpedienteFormModal from '@/components/tramite/ExpedienteFormModal.vue';
import RegistroDirectoModal from '@/components/tramite/RegistroDirectoModal.vue';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { TIPO_EXPEDIENTE_LABELS, estadoLabel, type Expediente } from '@/types/models/tramite';
import type { Trabajador } from '@/types/models/trabajador';

const props = defineProps<{
    trabajador: Trabajador;
}>();

const { can, esAdmin, esDirector } = usePermisos();

const expedientes = ref<Expediente[]>([]);
const loading = ref(false);
const error = ref(false);
const showModal = ref(false);
const showDirectoModal = ref(false);
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

async function loadExpedientes() {
    loading.value = true;
    error.value = false;

    try {
        const res = await fetch(
            `/api/trabajadores/${props.trabajador.id}/expedientes`,
            { headers: { Accept: 'application/json' } },
        );

        if (!res.ok) throw new Error();

        const result = await res.json();
        expedientes.value = result.data ?? [];
    } catch {
        error.value = true;
    } finally {
        loading.value = false;
    }
}

onMounted(() => loadExpedientes());
</script>

<template>
    <div class="flex flex-col gap-5">
        <!-- Cabecera -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <ClipboardCheck class="h-5 w-5 text-primary" />
                <h2 class="text-base font-bold">Expedientes</h2>
            </div>

            <div class="flex items-center gap-2">
                <Button
                    variant="ghost"
                    size="icon"
                    class="h-8 w-8"
                    title="Recargar"
                    :disabled="loading"
                    @click="loadExpedientes"
                >
                    <RefreshCw class="h-4 w-4" :class="{ 'animate-spin': loading }" />
                </Button>
                <Button v-if="can('tramites.crear') && (esDirector || esAdmin)" size="sm" variant="outline" class="h-8 text-xs" @click="showDirectoModal = true">
                    <FileCheck class="mr-1 h-3.5 w-3.5" />
                    Registrar permiso
                </Button>
                <Button v-if="can('tramites.crear')" size="sm" class="h-8 text-xs" @click="showModal = true">
                    <Plus class="mr-1 h-3.5 w-3.5" />
                    Nuevo expediente
                </Button>
            </div>
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
                        <TableHead>Tipo</TableHead>
                        <TableHead>Asunto</TableHead>
                        <TableHead>Fecha</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="w-16" />
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="i in 2" :key="i">
                        <TableCell><div class="h-4 w-16 animate-pulse rounded bg-muted" /></TableCell>
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
            <p class="text-sm font-medium text-muted-foreground">Sin expedientes registrados.</p>
            <Button v-if="can('tramites.crear')" size="sm" variant="outline" class="mt-4" @click="showModal = true">
                <Plus class="mr-1.5 h-3.5 w-3.5" />
                Nuevo expediente
            </Button>
        </div>

        <!-- Lista -->
        <div v-else class="rounded-lg border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Código</TableHead>
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
    </div>

    <!-- Modal crear expediente -->
    <ExpedienteFormModal
        v-model:show="showModal"
        :trabajador-id="props.trabajador.id"
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
        :trabajador-id="props.trabajador.id"
        @success="loadExpedientes"
    />
</template>

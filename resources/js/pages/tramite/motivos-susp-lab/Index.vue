<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { MotivoSuspLab } from '@/types/models/tramite';

interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Trámites', href: '#' },
            { title: 'Motivos de Suspensión', href: '/motivos-susp-lab' },
        ],
    },
});

const props = defineProps<{
    motivos: PaginatedResponse<MotivoSuspLab>;
    filters: { search?: string };
}>();

const search = ref(props.filters.search ?? '');
let searchTimeout: ReturnType<typeof setTimeout>;

function onSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get('/motivos-susp-lab', { search: search.value || undefined }, { preserveState: true, replace: true });
    }, 300);
}

// ── Edición inline de autorizadoPor ────────────────────────────────────────────

const updatingId = ref<number | null>(null);

function updateAutorizadoPor(motivo: MotivoSuspLab, value: string) {
    updatingId.value = motivo.id;
    router.put(
        `/motivos-susp-lab/${motivo.id}`,
        { autorizadoPor: value },
        {
            preserveScroll: true,
            onFinish: () => {
                updatingId.value = null;
            },
        },
    );
}
</script>

<template>
    <Head title="Motivos de Suspensión Laboral" />
    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Motivos de Suspensión Laboral</h1>
        </div>

        <p class="text-sm text-muted-foreground">
            En esta pantalla puede definir quién resuelve cada motivo de suspensión: el Director de la IE o la UGEL.
            Los demás campos son de solo lectura.
        </p>

        <div class="max-w-sm">
            <Input
                v-model="search"
                placeholder="Buscar por descripción o código…"
                @input="onSearch"
            />
        </div>

        <div class="rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Código</TableHead>
                        <TableHead>Descripción</TableHead>
                        <TableHead>Abreviatura</TableHead>
                        <TableHead>Tipo Suspensión</TableHead>
                        <TableHead>Con Goce</TableHead>
                        <TableHead>Cód. Prog.</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead>Resuelve</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="motivo in props.motivos.data" :key="motivo.id">
                        <TableCell class="font-mono text-sm">{{ motivo.codigo || '-' }}</TableCell>
                        <TableCell class="font-medium">{{ motivo.descripcion }}</TableCell>
                        <TableCell class="text-sm">{{ motivo.abreviatura || '-' }}</TableCell>
                        <TableCell class="text-sm text-muted-foreground">
                            {{ motivo.tipo_suspension_laboral?.descripcion || '-' }}
                        </TableCell>
                        <TableCell class="text-sm">
                            {{ motivo.conGoceHaber ? 'Sí' : 'No' }}
                        </TableCell>
                        <TableCell class="font-mono text-sm">{{ motivo.codigoProg || '-' }}</TableCell>
                        <TableCell>
                            <StatusBadge :active="motivo.activo ?? true" />
                        </TableCell>
                        <TableCell>
                            <select
                                :value="motivo.autorizadoPor"
                                :disabled="updatingId === motivo.id"
                                class="flex h-8 w-28 rounded-md border border-input bg-background px-2 py-1 text-sm shadow-xs focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:opacity-50"
                                @change="updateAutorizadoPor(motivo, ($event.target as HTMLSelectElement).value)"
                            >
                                <option value="D">Director</option>
                                <option value="U">UGEL</option>
                            </select>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.motivos.data.length === 0">
                        <TableCell colspan="8" class="h-24 text-center">
                            No se encontraron motivos.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <div
                class="flex items-center justify-between border-t px-4 py-3"
                v-if="props.motivos.total > 0"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.motivos.current_page }} de {{ props.motivos.last_page }}
                    ({{ props.motivos.total }} registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.motivos.current_page <= 1"
                        @click="router.get('/motivos-susp-lab', { page: props.motivos.current_page - 1 })"
                    >
                        Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.motivos.current_page >= props.motivos.last_page"
                        @click="router.get('/motivos-susp-lab', { page: props.motivos.current_page + 1 })"
                    >
                        Siguiente
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

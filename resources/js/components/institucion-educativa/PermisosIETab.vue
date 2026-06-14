<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import {
    AlertCircle,
    ChevronLeft,
    ChevronRight,
    ClipboardCheck,
    Plus,
    RefreshCw,
    Search,
} from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import PermisoFormModal from '@/components/tramite/PermisoFormModal.vue';
import PermisosList from '@/components/tramite/PermisosList.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type {
    AltaPermisoOption,
    ExpedientePermiso,
} from '@/types/models/tramite';

const props = defineProps<{
    institucionId: number;
}>();

const ESTADO_FILTROS = [
    { value: '', label: 'Todos' },
    { value: 'PEN', label: 'Pendientes' },
    { value: 'VAL', label: 'Validados' },
    { value: 'REC', label: 'Rechazados' },
] as const;

const permisos = ref<ExpedientePermiso[]>([]);
const loading = ref(false);
const error = ref(false);
const estadoFiltro = ref<string>('');
const search = ref('');
const page = ref(1);
const lastPage = ref(1);
const total = ref(0);

async function loadPermisos() {
    loading.value = true;
    error.value = false;

    try {
        const params = new URLSearchParams({
            page: String(page.value),
        });

        if (estadoFiltro.value) {
            params.set('estado', estadoFiltro.value);
        }

        if (search.value) {
            params.set('search', search.value);
        }

        const res = await fetch(
            `/instituciones/${props.institucionId}/permisos?${params}`,
            { headers: { Accept: 'application/json' } },
        );

        if (!res.ok) {
            throw new Error();
        }

        const result = await res.json();
        permisos.value = result.data;
        lastPage.value = result.last_page ?? 1;
        total.value = result.total ?? result.data.length;
    } catch {
        error.value = true;
    } finally {
        loading.value = false;
    }
}

onMounted(() => loadPermisos());

watch(estadoFiltro, () => {
    page.value = 1;
    loadPermisos();
});

watch(page, () => loadPermisos());

const debouncedSearch = useDebounceFn(() => {
    page.value = 1;
    loadPermisos();
}, 350);

watch(search, () => debouncedSearch());

// ── Crear ─────────────────────────────────────────────────────────────────────
const showFormModal = ref(false);
const altasOptions = ref<AltaPermisoOption[]>([]);
const altasLoaded = ref(false);

async function openCreate() {
    if (!altasLoaded.value) {
        try {
            const res = await fetch(
                `/instituciones/${props.institucionId}/permisos-altas`,
                { headers: { Accept: 'application/json' } },
            );
            const result = await res.json();
            altasOptions.value = result.data;
            altasLoaded.value = true;
        } catch {
            altasOptions.value = [];
        }
    }

    showFormModal.value = true;
}

// ── Validar ───────────────────────────────────────────────────────────────────
const validarPermiso = ref<ExpedientePermiso | null>(null);
const validando = ref(false);

function confirmarValidar() {
    if (!validarPermiso.value) {
        return;
    }

    validando.value = true;

    router.post(
        `/permisos/${validarPermiso.value.id}/validar`,
        { estado: 'VAL' },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                validarPermiso.value = null;
                loadPermisos();
            },
            onFinish: () => {
                validando.value = false;
            },
        },
    );
}

// ── Rechazar ──────────────────────────────────────────────────────────────────
const showRechazoModal = ref(false);
const rechazoPermiso = ref<ExpedientePermiso | null>(null);

const rechazoForm = useForm({
    estado: 'REC',
    observacion: '',
});

function openRechazo(permiso: ExpedientePermiso) {
    rechazoPermiso.value = permiso;
    rechazoForm.reset();
    rechazoForm.clearErrors();
    showRechazoModal.value = true;
}

function submitRechazo() {
    if (!rechazoPermiso.value) {
        return;
    }

    rechazoForm.post(`/permisos/${rechazoPermiso.value.id}/validar`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showRechazoModal.value = false;
            rechazoPermiso.value = null;
            loadPermisos();
        },
    });
}
</script>

<template>
    <div class="flex flex-col gap-5">
        <!-- Cabecera -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <ClipboardCheck class="h-5 w-5 text-primary" />
                <h2 class="text-base font-bold">Permisos del Personal</h2>
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
                    title="Recargar"
                    :disabled="loading"
                    @click="loadPermisos"
                >
                    <RefreshCw
                        class="h-4 w-4"
                        :class="{ 'animate-spin': loading }"
                    />
                </Button>
                <Button size="sm" class="h-8 text-xs" @click="openCreate">
                    <Plus class="mr-1 h-3.5 w-3.5" />
                    Nueva Solicitud
                </Button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex gap-1 rounded-lg border bg-muted/30 p-1">
                <button
                    v-for="filtro in ESTADO_FILTROS"
                    :key="filtro.value"
                    type="button"
                    class="rounded-md px-3 py-1 text-xs font-medium transition-colors"
                    :class="
                        estadoFiltro === filtro.value
                            ? 'bg-background text-foreground shadow-xs'
                            : 'text-muted-foreground hover:text-foreground'
                    "
                    @click="estadoFiltro = filtro.value"
                >
                    {{ filtro.label }}
                </button>
            </div>

            <div class="relative">
                <Search
                    class="absolute top-1/2 left-2.5 h-3.5 w-3.5 -translate-y-1/2 text-muted-foreground"
                />
                <Input
                    v-model="search"
                    placeholder="Buscar trabajador…"
                    class="h-8 w-56 pl-8 text-xs"
                />
            </div>
        </div>

        <!-- Error -->
        <div
            v-if="error"
            class="flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/20 dark:text-red-300"
        >
            <AlertCircle class="h-4 w-4 shrink-0" />
            Error al cargar las solicitudes. Intente nuevamente.
        </div>

        <!-- Skeleton -->
        <div v-else-if="loading" class="space-y-3">
            <div
                v-for="i in 3"
                :key="i"
                class="h-28 animate-pulse rounded-xl border bg-muted/30"
            />
        </div>

        <!-- Vacío -->
        <div
            v-else-if="permisos.length === 0"
            class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed py-14 text-center"
        >
            <ClipboardCheck class="mb-3 h-10 w-10 text-muted-foreground/40" />
            <p class="text-sm font-medium text-muted-foreground">
                Sin solicitudes de permiso para los filtros seleccionados.
            </p>
        </div>

        <!-- Lista -->
        <template v-else>
            <PermisosList
                :permisos="permisos"
                show-trabajador
                can-validate
                @validar="validarPermiso = $event"
                @rechazar="openRechazo"
            />

            <!-- Paginación -->
            <div
                v-if="lastPage > 1"
                class="flex items-center justify-center gap-3"
            >
                <Button
                    variant="outline"
                    size="sm"
                    class="h-8"
                    :disabled="page <= 1 || loading"
                    @click="page--"
                >
                    <ChevronLeft class="h-4 w-4" />
                </Button>
                <span class="text-xs text-muted-foreground">
                    Página {{ page }} de {{ lastPage }}
                </span>
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

    <!-- Modal crear -->
    <PermisoFormModal
        v-model:show="showFormModal"
        :altas="altasOptions"
        alta-label="Trabajador"
        @success="loadPermisos"
    />

    <!-- Confirmar validación -->
    <ConfirmModal
        :show="validarPermiso !== null"
        title="Validar Solicitud"
        :description="`¿Validar la solicitud ${validarPermiso?.codigo ?? ''}? El permiso quedará aprobado.`"
        confirm-text="Validar"
        :processing="validando"
        @update:show="validarPermiso = $event ? validarPermiso : null"
        @confirm="confirmarValidar"
    />

    <!-- Modal rechazo -->
    <FormModal
        v-model:show="showRechazoModal"
        title="Rechazar Solicitud"
        :description="`Indique el motivo del rechazo de ${rechazoPermiso?.codigo ?? 'la solicitud'}.`"
        :processing="rechazoForm.processing"
        submit-text="Rechazar"
        @submit="submitRechazo"
    >
        <div class="grid gap-2">
            <Label>Motivo del rechazo *</Label>
            <Input
                v-model="rechazoForm.observacion"
                placeholder="Ej.: Sustento ilegible, fechas incorrectas…"
                :class="{
                    'border-destructive': rechazoForm.errors.observacion,
                }"
            />
            <p
                v-if="rechazoForm.errors.observacion"
                class="text-sm text-destructive"
            >
                {{ rechazoForm.errors.observacion }}
            </p>
        </div>
    </FormModal>
</template>

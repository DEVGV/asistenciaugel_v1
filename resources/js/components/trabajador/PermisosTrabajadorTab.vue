<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import {
    AlertCircle,
    ClipboardCheck,
    Plus,
    RefreshCw,
} from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import PermisoFormModal from '@/components/tramite/PermisoFormModal.vue';
import PermisosList from '@/components/tramite/PermisosList.vue';
import { Button } from '@/components/ui/button';
import type { Trabajador } from '@/types/models/trabajador';
import type {
    AltaPermisoOption,
    ExpedientePermiso,
} from '@/types/models/tramite';

const props = defineProps<{
    trabajador: Trabajador;
}>();

const permisos = ref<ExpedientePermiso[]>([]);
const loading = ref(false);
const error = ref(false);

// Altas activas del trabajador como opciones del formulario (etiqueta = IE)
const altasOptions = computed<AltaPermisoOption[]>(() =>
    (props.trabajador.altas ?? [])
        .filter((alta) => !alta.fechaBaja)
        .map((alta) => ({
            id: alta.id,
            trabajador_id: props.trabajador.id,
            label:
                alta.institucion_educativa?.nombreLegal ??
                alta.institucionEducativa?.nombreLegal ??
                `Alta #${alta.id}`,
            cargo: alta.cargo?.nombre ?? undefined,
        })),
);

async function loadPermisos() {
    loading.value = true;
    error.value = false;

    try {
        const res = await fetch(
            `/trabajadores/${props.trabajador.id}/permisos`,
            { headers: { Accept: 'application/json' } },
        );

        if (!res.ok) {
            throw new Error();
        }

        const result = await res.json();
        permisos.value = result.data;
    } catch {
        error.value = true;
    } finally {
        loading.value = false;
    }
}

onMounted(() => loadPermisos());

// ── Crear ─────────────────────────────────────────────────────────────────────
const showFormModal = ref(false);

// ── Anular ────────────────────────────────────────────────────────────────────
const anularPermiso = ref<ExpedientePermiso | null>(null);
const anulando = ref(false);

function confirmarAnular() {
    if (!anularPermiso.value) {
        return;
    }

    anulando.value = true;

    router.post(
        `/permisos/${anularPermiso.value.id}/anular`,
        {},
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                anularPermiso.value = null;
                loadPermisos();
            },
            onFinish: () => {
                anulando.value = false;
            },
        },
    );
}
</script>

<template>
    <div class="flex flex-col gap-5">
        <!-- Cabecera -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <ClipboardCheck class="h-5 w-5 text-primary" />
                <h2 class="text-base font-bold">Solicitudes de Permiso</h2>
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
                <Button
                    size="sm"
                    class="h-8 text-xs"
                    :disabled="altasOptions.length === 0"
                    @click="showFormModal = true"
                >
                    <Plus class="mr-1 h-3.5 w-3.5" />
                    Nueva Solicitud
                </Button>
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
                v-for="i in 2"
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
                Sin solicitudes de permiso registradas.
            </p>
            <p class="mt-1 text-xs text-muted-foreground/70">
                Registre una solicitud adjuntando su documento de sustento.
            </p>
        </div>

        <!-- Lista -->
        <PermisosList
            v-else
            :permisos="permisos"
            show-institucion
            can-anular
            @anular="anularPermiso = $event"
        />
    </div>

    <!-- Modal crear -->
    <PermisoFormModal
        v-model:show="showFormModal"
        :altas="altasOptions"
        alta-label="Institución Educativa (vínculo laboral)"
        @success="loadPermisos"
    />

    <!-- Confirmar anulación -->
    <ConfirmModal
        :show="anularPermiso !== null"
        title="Anular Solicitud"
        :description="`¿Anular la solicitud ${anularPermiso?.codigo ?? ''}? Esta acción no se puede deshacer.`"
        confirm-text="Anular"
        destructive
        :processing="anulando"
        @update:show="anularPermiso = $event ? anularPermiso : null"
        @confirm="confirmarAnular"
    />
</template>

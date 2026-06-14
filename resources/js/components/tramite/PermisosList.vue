<script setup lang="ts">
import {
    CalendarRange,
    CheckCircle2,
    Download,
    FileText,
    School,
    XCircle,
    Ban,
    User,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import type { DetallePermiso, ExpedientePermiso } from '@/types/models/tramite';

const props = defineProps<{
    permisos: ExpedientePermiso[];
    /** Muestra el nombre del trabajador (contexto IE). */
    showTrabajador?: boolean;
    /** Muestra la IE del detalle (contexto trabajador). */
    showInstitucion?: boolean;
    /** Habilita acciones Validar / Rechazar (pestaña IE). */
    canValidate?: boolean;
    /** Habilita acción Anular sobre pendientes (pestaña trabajador). */
    canAnular?: boolean;
}>();

const emit = defineEmits<{
    (e: 'validar', permiso: ExpedientePermiso): void;
    (e: 'rechazar', permiso: ExpedientePermiso): void;
    (e: 'anular', permiso: ExpedientePermiso): void;
}>();

const ESTADO_STYLES: Record<string, string> = {
    PEN: 'bg-amber-100 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:ring-amber-900/50',
    VAL: 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:ring-emerald-900/50',
    REC: 'bg-red-100 text-red-700 ring-1 ring-red-200 dark:bg-red-950/30 dark:text-red-300 dark:ring-red-900/50',
    ANU: 'bg-muted text-muted-foreground ring-1 ring-border',
};

const MARCA_LABELS: Record<string, string> = {
    E: 'Entrada',
    S: 'Salida',
    ES: 'Entrada y Salida',
};

function estadoClass(permiso: ExpedientePermiso): string {
    return (
        ESTADO_STYLES[permiso.estado?.codigo ?? ''] ?? ESTADO_STYLES['PEN']
    );
}

function detalle(permiso: ExpedientePermiso): DetallePermiso | null {
    return permiso.justificaciones?.[0] ?? permiso.exoneraciones?.[0] ?? null;
}

function esExoneracion(permiso: ExpedientePermiso): boolean {
    return (permiso.exoneraciones?.length ?? 0) > 0;
}

function tipoLabel(permiso: ExpedientePermiso): string {
    if (!esExoneracion(permiso)) {
        return 'Permiso por día(s)';
    }

    const marca = detalle(permiso)?.marcaApli ?? '';

    return `Exoneración de marcación${MARCA_LABELS[marca] ? ` (${MARCA_LABELS[marca]})` : ''}`;
}

function nombreTrabajador(permiso: ExpedientePermiso): string {
    const p = permiso.trabajador?.persona;

    if (!p) {
        return `Trabajador #${permiso.trabajador_id}`;
    }

    return [p.paterno, p.materno, p.nombre].filter(Boolean).join(' ');
}

function esPendiente(permiso: ExpedientePermiso): boolean {
    return permiso.estado?.codigo === 'PEN';
}

function rangoFechas(permiso: ExpedientePermiso): string {
    const d = detalle(permiso);

    if (!d?.fechaInicio) {
        return '—';
    }

    return d.fechaInicio === d.fechaFin || !d.fechaFin
        ? d.fechaInicio
        : `${d.fechaInicio} → ${d.fechaFin}`;
}
</script>

<template>
    <div class="space-y-3">
        <div
            v-for="permiso in props.permisos"
            :key="permiso.id"
            class="overflow-hidden rounded-xl border bg-card shadow-xs"
        >
            <!-- Cabecera -->
            <div
                class="flex flex-wrap items-center justify-between gap-2 border-b bg-muted/20 px-4 py-2.5"
            >
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <FileText class="h-4 w-4 text-primary" />
                    <span class="font-mono text-xs font-semibold text-primary">
                        {{ permiso.codigo ?? `EXP-${permiso.id}` }}
                    </span>
                    <span class="text-xs text-muted-foreground">
                        · {{ permiso.fecha }}
                    </span>
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold"
                        :class="estadoClass(permiso)"
                    >
                        {{ permiso.estado?.nombre ?? 'Pendiente' }}
                    </span>
                </div>

                <!-- Acciones -->
                <div class="flex items-center gap-1.5">
                    <template v-if="props.canValidate && esPendiente(permiso)">
                        <Button
                            size="sm"
                            class="h-7 bg-emerald-600 text-xs hover:bg-emerald-700"
                            @click="emit('validar', permiso)"
                        >
                            <CheckCircle2 class="mr-1 h-3.5 w-3.5" />
                            Validar
                        </Button>
                        <Button
                            size="sm"
                            variant="destructive"
                            class="h-7 text-xs"
                            @click="emit('rechazar', permiso)"
                        >
                            <XCircle class="mr-1 h-3.5 w-3.5" />
                            Rechazar
                        </Button>
                    </template>
                    <Button
                        v-if="props.canAnular && esPendiente(permiso)"
                        size="sm"
                        variant="outline"
                        class="h-7 text-xs"
                        @click="emit('anular', permiso)"
                    >
                        <Ban class="mr-1 h-3.5 w-3.5" />
                        Anular
                    </Button>
                </div>
            </div>

            <!-- Cuerpo -->
            <div class="space-y-2 px-4 py-3 text-sm">
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                    <span
                        v-if="props.showTrabajador"
                        class="flex items-center gap-1.5 font-semibold"
                    >
                        <User class="h-3.5 w-3.5 text-muted-foreground" />
                        {{ nombreTrabajador(permiso) }}
                    </span>
                    <span
                        v-if="
                            props.showInstitucion &&
                            detalle(permiso)?.alta_trabajador
                                ?.institucion_educativa
                        "
                        class="flex items-center gap-1.5 text-muted-foreground"
                    >
                        <School class="h-3.5 w-3.5" />
                        {{
                            detalle(permiso)?.alta_trabajador
                                ?.institucion_educativa?.nombreLegal
                        }}
                    </span>
                </div>

                <p class="font-medium">{{ permiso.asunto }}</p>

                <div
                    class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-muted-foreground"
                >
                    <span>{{ tipoLabel(permiso) }}</span>
                    <span class="flex items-center gap-1">
                        <CalendarRange class="h-3.5 w-3.5" />
                        {{ rangoFechas(permiso) }}
                    </span>
                    <span v-if="permiso.observacion">
                        · Obs.: {{ permiso.observacion }}
                    </span>
                </div>

                <!-- Sustentos -->
                <div
                    v-if="permiso.documentos?.length"
                    class="flex flex-wrap gap-2 pt-1"
                >
                    <a
                        v-for="doc in permiso.documentos"
                        :key="doc.id"
                        :href="`/documentos-tram/${doc.id}/descargar`"
                        target="_blank"
                        class="inline-flex items-center gap-1.5 rounded-md border bg-background px-2.5 py-1 text-xs font-medium text-primary transition-colors hover:bg-primary/5"
                    >
                        <Download class="h-3.5 w-3.5" />
                        {{ doc.documento?.nombre ?? 'Sustento'
                        }}{{ doc.nroDoc ? ` N° ${doc.nroDoc}` : '' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Paperclip, Plus, Trash2, X } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import SearchSelect from '@/components/shared/SearchSelect.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    TIPO_EXPEDIENTE_LABELS,
    type DocumentoTramForm,
    type ExpedienteForm,
    type PersonalActivoOption,
    type TipoExpediente,
} from '@/types/models/tramite';

const props = defineProps<{
    form: ExpedienteForm & { errors: Record<string, string>; processing: boolean };
    /** Cuando viene pre-cargado desde el tab de un trabajador, oculta el select de trabajador */
    trabajadorFijo?: boolean;
    /** Id del trabajador pre-fijado (para cargar sus IEs activas) */
    trabajadorId?: number | null;
    /** IE del contexto — carga el personal activo de esa IE */
    institucionId?: number | null;
}>();

const emit = defineEmits<{
    (e: 'submit'): void;
}>();

const TIPOS = (Object.keys(TIPO_EXPEDIENTE_LABELS) as TipoExpediente[]).map((k) => ({
    value: k,
    label: TIPO_EXPEDIENTE_LABELS[k],
}));

// ── Altas activas del trabajador (modo "trabajadorFijo") ──────────────────────
interface AltaItem { id: number; label: string; sublabel?: string }
const altasActivas = ref<AltaItem[]>([]);
const loadingAltas = ref(false);

async function cargarAltasActivas() {
    if (!props.trabajadorId) return;
    loadingAltas.value = true;
    try {
        const res = await fetch(`/api/trabajadores/${props.trabajadorId}/altas-activas`, {
            headers: { Accept: 'application/json' },
        });
        if (!res.ok) throw new Error();
        const result = await res.json();
        altasActivas.value = (result.data ?? []).map((a: any) => ({
            id: a.alta_id,
            label: a.label,
            sublabel: a.sublabel ?? undefined,
        }));
    } catch {
        altasActivas.value = [];
    } finally {
        loadingAltas.value = false;
    }
}

// ── Personal activo de la IE ──────────────────────────────────────────────────
const personal = ref<PersonalActivoOption[]>([]);
const loadingPersonal = ref(false);

const personalItems = computed<AltaItem[]>(() =>
    personal.value.map((p) => ({
        id: p.alta_id,
        label: p.label,
        sublabel: p.cargo ?? undefined,
    })),
);

async function cargarPersonal() {
    if (!props.institucionId) return;
    loadingPersonal.value = true;
    try {
        const res = await fetch(`/api/instituciones/${props.institucionId}/personal-activo`, {
            headers: { Accept: 'application/json' },
        });
        if (!res.ok) throw new Error();
        const result = await res.json();
        personal.value = result.data ?? [];
    } catch {
        personal.value = [];
    } finally {
        loadingPersonal.value = false;
    }
}

onMounted(() => {
    if (props.trabajadorFijo && props.trabajadorId) cargarAltasActivas();
    if (props.institucionId) cargarPersonal();
});

watch(() => props.trabajadorId, (id) => { if (id) cargarAltasActivas(); });
watch(() => props.institucionId, (id) => { if (id) cargarPersonal(); });

/** Al seleccionar una IE/alta (modo trabajadorFijo): fija altaTrabajador_id */
function onAltaSeleccionada(altaId: number | string | null) {
    props.form.altaTrabajador_id = altaId != null ? Number(altaId) : null;
}

/** Al seleccionar del personal activo (modo IE): fija trabajador_id + altaTrabajador_id */
function onPersonalSeleccionado(altaId: number | string | null) {
    const opcion = personal.value.find((p) => String(p.alta_id) === String(altaId));
    if (opcion) {
        props.form.trabajador_id = opcion.trabajador_id;
        props.form.altaTrabajador_id = opcion.alta_id;
    } else {
        props.form.altaTrabajador_id = null;
    }
}

// ── Documentos ────────────────────────────────────────────────────────────────
function docVacio(): DocumentoTramForm {
    return { documento_id: null, nroDoc: '', fechaDoc: '', observacion: '', archivo: null };
}

function agregarDocumento() { props.form.documentos.push(docVacio()); }
function eliminarDocumento(index: number) { props.form.documentos.splice(index, 1); }
function docError(index: number, campo: string) { return props.form.errors[`documentos.${index}.${campo}`]; }

function onArchivoChange(event: Event, index: number) {
    const target = event.target as HTMLInputElement;
    props.form.documentos[index].archivo = target.files?.[0] ?? null;
}

function limpiarArchivo(index: number) {
    props.form.documentos[index].archivo = null;
    const input = document.querySelector(`[data-file-index="${index}"]`) as HTMLInputElement | null;
    if (input) input.value = '';
}
</script>

<template>
    <div class="space-y-5">
        <!-- Tipo + Fecha -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="grid gap-2">
                <Label>Tipo de expediente *</Label>
                <select
                    v-model="props.form.tipoExpediente"
                    class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                    :class="{ 'border-destructive': props.form.errors.tipoExpediente }"
                >
                    <option value="" disabled>Seleccione…</option>
                    <option v-for="t in TIPOS" :key="t.value" :value="t.value">
                        {{ t.label }}
                    </option>
                </select>
                <p v-if="props.form.errors.tipoExpediente" class="text-sm text-destructive">
                    {{ props.form.errors.tipoExpediente }}
                </p>
            </div>

            <div class="grid gap-2">
                <Label>Fecha *</Label>
                <Input
                    v-model="props.form.fecha"
                    type="date"
                    :class="{ 'border-destructive': props.form.errors.fecha }"
                />
                <p v-if="props.form.errors.fecha" class="text-sm text-destructive">
                    {{ props.form.errors.fecha }}
                </p>
            </div>
        </div>

        <!--
            Selector de trabajador / IE:
            · Caso A: trabajadorFijo=true  →  mostrar select de IE (altas activas del trabajador)
            · Caso B: institucionId set    →  mostrar select de personal activo de la IE
            · Caso C: genérico             →  ParamSelect trabajadores (sin alta)
        -->

        <!-- A: Trabajador fijo — elegir IE donde tiene alta activa -->
        <div v-if="props.trabajadorFijo" class="grid gap-2">
            <Label>Institución Educativa *</Label>
            <SearchSelect
                :model-value="props.form.altaTrabajador_id"
                :items="altasActivas"
                :loading="loadingAltas"
                placeholder="Seleccione la IE…"
                clearable
                @update:model-value="onAltaSeleccionada"
            />
            <p v-if="props.form.errors.altaTrabajador_id" class="text-sm text-destructive">
                {{ props.form.errors.altaTrabajador_id }}
            </p>
        </div>

        <!-- B: Contexto IE — elegir trabajador del personal activo -->
        <div v-else-if="props.institucionId" class="grid gap-2">
            <Label>Trabajador *</Label>
            <SearchSelect
                :model-value="props.form.altaTrabajador_id"
                :items="personalItems"
                :loading="loadingPersonal"
                placeholder="Seleccione trabajador…"
                clearable
                @update:model-value="onPersonalSeleccionado"
            />
            <p v-if="props.form.errors.trabajador_id" class="text-sm text-destructive">
                {{ props.form.errors.trabajador_id }}
            </p>
            <p v-if="props.form.errors.altaTrabajador_id" class="text-sm text-destructive">
                {{ props.form.errors.altaTrabajador_id }}
            </p>
        </div>

        <!-- C: Genérico — ParamSelect -->
        <div v-else class="grid gap-2">
            <ParamSelect
                v-model="props.form.trabajador_id"
                type="trabajadores"
                label="Trabajador *"
                placeholder="Seleccione un trabajador…"
                :error="props.form.errors.trabajador_id"
            />
        </div>

        <!-- Asunto -->
        <div class="grid gap-2">
            <Label>Asunto *</Label>
            <Input
                v-model="props.form.asunto"
                placeholder="Descripción breve del expediente"
                :class="{ 'border-destructive': props.form.errors.asunto }"
            />
            <p v-if="props.form.errors.asunto" class="text-sm text-destructive">
                {{ props.form.errors.asunto }}
            </p>
        </div>

        <!-- Observación -->
        <div class="grid gap-2">
            <Label>Observación</Label>
            <Input
                v-model="props.form.observacion"
                placeholder="Opcional"
                :class="{ 'border-destructive': props.form.errors.observacion }"
            />
            <p v-if="props.form.errors.observacion" class="text-sm text-destructive">
                {{ props.form.errors.observacion }}
            </p>
        </div>

        <!-- Documentos -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold">Documentos</h3>
                <Button type="button" variant="outline" size="sm" @click="agregarDocumento">
                    <Plus class="mr-1.5 h-3.5 w-3.5" />
                    Agregar documento
                </Button>
            </div>

            <p v-if="props.form.errors.documentos" class="text-sm text-destructive">
                {{ props.form.errors.documentos }}
            </p>

            <div
                v-for="(doc, index) in props.form.documentos"
                :key="index"
                class="rounded-lg border bg-muted/20 p-4 space-y-3"
            >
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">
                        Documento {{ index + 1 }}
                    </span>
                    <Button
                        v-if="props.form.documentos.length > 1"
                        type="button"
                        variant="ghost"
                        size="sm"
                        class="h-7 w-7 p-0 text-destructive hover:text-destructive"
                        @click="eliminarDocumento(index)"
                    >
                        <Trash2 class="h-3.5 w-3.5" />
                    </Button>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <ParamSelect
                            v-model="doc.documento_id"
                            type="documentos"
                            label="Tipo de documento *"
                            placeholder="Seleccione…"
                            :error="docError(index, 'documento_id')"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label>N° de documento</Label>
                        <Input
                            v-model="doc.nroDoc"
                            placeholder="Opcional"
                            :class="{ 'border-destructive': docError(index, 'nroDoc') }"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <Label>Fecha del documento</Label>
                        <Input
                            v-model="doc.fechaDoc"
                            type="date"
                            :class="{ 'border-destructive': docError(index, 'fechaDoc') }"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label>Observación</Label>
                        <Input
                            v-model="doc.observacion"
                            placeholder="Opcional"
                            :class="{ 'border-destructive': docError(index, 'observacion') }"
                        />
                    </div>
                </div>

                <!-- Archivo adjunto -->
                <div class="grid gap-2">
                    <Label class="flex items-center gap-1.5">
                        <Paperclip class="h-3.5 w-3.5 text-muted-foreground" />
                        Archivo adjunto
                        <span class="text-xs font-normal text-muted-foreground">(PDF, JPG, PNG, DOC — máx. 10 MB)</span>
                    </Label>

                    <div v-if="doc.archivo" class="flex items-center gap-2 rounded-md border border-primary/30 bg-primary/5 px-3 py-2 text-sm">
                        <Paperclip class="h-4 w-4 shrink-0 text-primary" />
                        <span class="min-w-0 flex-1 truncate text-xs font-medium">{{ doc.archivo.name }}</span>
                        <span class="shrink-0 text-xs text-muted-foreground">
                            {{ (doc.archivo.size / 1024 / 1024).toFixed(1) }} MB
                        </span>
                        <button
                            type="button"
                            class="shrink-0 text-muted-foreground hover:text-destructive"
                            @click="limpiarArchivo(index)"
                        >
                            <X class="h-3.5 w-3.5" />
                        </button>
                    </div>

                    <input
                        v-else
                        :data-file-index="index"
                        type="file"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                        class="flex w-full cursor-pointer rounded-md border border-input bg-background px-3 py-1.5 text-sm shadow-xs file:mr-3 file:rounded file:border-0 file:bg-primary/10 file:px-2 file:py-1 file:text-xs file:font-medium file:text-primary"
                        :class="{ 'border-destructive': docError(index, 'archivo') }"
                        @change="onArchivoChange($event, index)"
                    />
                    <p v-if="docError(index, 'archivo')" class="text-sm text-destructive">
                        {{ docError(index, 'archivo') }}
                    </p>
                </div>
            </div>

            <Button
                v-if="props.form.documentos.length === 0"
                type="button"
                variant="outline"
                class="w-full border-dashed"
                @click="agregarDocumento"
            >
                <Plus class="mr-2 h-4 w-4" />
                Agregar primer documento
            </Button>
        </div>
    </div>
</template>

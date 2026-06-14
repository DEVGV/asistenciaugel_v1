<script setup lang="ts">
/**
 * GradosMasivaGrid — Editor tipo spreadsheet para crear grados y secciones masivamente.
 *
 * Diseño de la grilla:
 *  | # | Grado (nombre) | Sigla | Sección 1 | Sección 2 | … | Sección N | ✕ |
 *
 * - La primera columna es el nombre del grado (obligatorio).
 * - La segunda es la sigla del grado (opcional).
 * - Las columnas siguientes son las secciones: cada celda es el nombre de una sección.
 *   El usuario puede agregar/quitar columnas de sección dinámicamente.
 * - Validación visual antes de enviar.
 * - El backend recibe: { filas: [{ grado_nombre, grado_sigla, secciones: string[] }] }
 */
import { ref, computed, nextTick } from 'vue';
import {
    Plus,
    Trash2,
    Loader2,
    CheckCircle2,
    AlertCircle,
    X,
    Columns,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

// ─── Props / Emits ────────────────────────────────────────────────────────────
const props = defineProps<{
    institucionId: number;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'success', gradosCreados: number, seccionesCreadas: number): void;
}>();

// ─── Tipos ────────────────────────────────────────────────────────────────────
interface FilaGrado {
    _id: number;
    grado_nombre: string;
    grado_sigla: string;
    secciones: string[]; // índice = columna de sección
    _errors: Record<string, string>;
}

// ─── Columnas de sección (cantidad dinámica) ──────────────────────────────────
const numSecciones = ref(5); // columnas de sección visibles

function agregarColumna() {
    numSecciones.value++;
    // Extender todas las filas con celda vacía
    filas.value.forEach((f) => {
        while (f.secciones.length < numSecciones.value) f.secciones.push('');
    });
}

function quitarColumna() {
    if (numSecciones.value <= 1) return;
    numSecciones.value--;
    filas.value.forEach((f) => {
        f.secciones = f.secciones.slice(0, numSecciones.value);
    });
}

// ─── Filas ────────────────────────────────────────────────────────────────────
let nextId = 0;

function nuevaFila(): FilaGrado {
    return {
        _id: nextId++,
        grado_nombre: '',
        grado_sigla: '',
        secciones: Array(numSecciones.value).fill(''),
        _errors: {},
    };
}

const filas = ref<FilaGrado[]>([
    nuevaFila(),
    nuevaFila(),
    nuevaFila(),
    nuevaFila(),
    nuevaFila(),
]);

function agregarFila() {
    filas.value.push(nuevaFila());
    nextTick(() => {
        const el = document.getElementById('grados-grid-scroll');
        if (el) el.scrollTop = el.scrollHeight;
    });
}

function agregarFilas(n: number) {
    for (let i = 0; i < n; i++) filas.value.push(nuevaFila());
}

function eliminarFila(id: number) {
    filas.value = filas.value.filter((f) => f._id !== id);
    if (filas.value.length === 0) filas.value.push(nuevaFila());
}

function limpiarFilasVacias() {
    const llenas = filas.value.filter((f) => f.grado_nombre.trim() !== '');
    filas.value = llenas.length ? llenas : [nuevaFila()];
}

// ─── Filas con datos ──────────────────────────────────────────────────────────
const filasConDatos = computed(() =>
    filas.value.filter((f) => f.grado_nombre.trim() !== ''),
);

// ─── Validación ───────────────────────────────────────────────────────────────
function validar(): boolean {
    let ok = true;
    filas.value.forEach((fila) => {
        fila._errors = {};
        // Ignorar filas completamente vacías
        if (
            fila.grado_nombre.trim() === '' &&
            fila.secciones.every((s) => s.trim() === '')
        )
            return;
        if (fila.grado_nombre.trim() === '') {
            fila._errors['grado_nombre'] = 'Requerido';
            ok = false;
        }
    });
    return ok;
}

// ─── Envío ────────────────────────────────────────────────────────────────────
const enviando = ref(false);
const resultado = ref<{
    grados_creados: number;
    secciones_creadas: number;
    errores: Record<number, string>;
} | null>(null);
const errorGeneral = ref('');

async function enviar() {
    if (!validar()) return;
    const filasFiltradas = filasConDatos.value;
    if (!filasFiltradas.length) {
        errorGeneral.value = 'No hay filas con datos para enviar.';
        return;
    }
    errorGeneral.value = '';
    enviando.value = true;
    resultado.value = null;

    const payload = filasFiltradas.map((f) => ({
        grado_nombre: f.grado_nombre.trim(),
        grado_sigla: f.grado_sigla.trim() || null,
        secciones: f.secciones.map((s) => s.trim()).filter((s) => s !== ''),
    }));

    try {
        const res = await fetch(
            `/instituciones/${props.institucionId}/grados-masivos`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN':
                        (
                            document.querySelector(
                                'meta[name="csrf-token"]',
                            ) as HTMLMetaElement
                        )?.content ?? '',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ filas: payload }),
            },
        );
        const data = await res.json();
        if (!res.ok) {
            errorGeneral.value = data.message ?? 'Error del servidor.';
        } else {
            resultado.value = data;
            if (data.grados_creados > 0) {
                emit('success', data.grados_creados, data.secciones_creadas);
            }
        }
    } catch {
        errorGeneral.value = 'Error de conexión al servidor.';
    } finally {
        enviando.value = false;
    }
}

function resetGrid() {
    numSecciones.value = 5;
    filas.value = Array.from({ length: 5 }, () => nuevaFila());
    resultado.value = null;
    errorGeneral.value = '';
}

// Encabezados de columna de sección
function secLabel(i: number) {
    return `Sección ${i + 1}`;
}
</script>

<template>
    <Teleport to="body">
        <div
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-0"
            @mousedown.self="emit('close')"
        >
            <div
                class="flex h-screen w-screen flex-col bg-background shadow-2xl"
                style="max-height: 100dvh"
            >
                <!-- ── Header ── -->
                <div
                    class="flex shrink-0 items-center justify-between border-b px-6 py-3"
                >
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-semibold">
                            Carga Masiva de Grados y Secciones
                        </h2>
                        <span class="text-xs text-muted-foreground">
                            1ª columna = grado · siguientes = secciones de ese
                            grado. Presiona
                            <strong>Guardar</strong> cuando termines.
                        </span>
                        <span
                            v-if="filasConDatos.length"
                            class="rounded-full bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary"
                        >
                            {{ filasConDatos.length }} grado{{
                                filasConDatos.length !== 1 ? 's' : ''
                            }}
                            con datos
                        </span>
                    </div>
                    <button
                        @click="emit('close')"
                        class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <!-- ── Grid ── -->
                <div class="flex flex-1 flex-col overflow-hidden">
                    <!-- Resultado -->
                    <div
                        v-if="resultado"
                        class="shrink-0 space-y-1 border-b px-5 py-3"
                    >
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-emerald-700 dark:text-emerald-400"
                        >
                            <CheckCircle2 class="h-4 w-4 shrink-0" />
                            {{ resultado.grados_creados }} grado{{
                                resultado.grados_creados !== 1 ? 's' : ''
                            }}
                            y {{ resultado.secciones_creadas }} sección{{
                                resultado.secciones_creadas !== 1 ? 'es' : ''
                            }}
                            creados correctamente.
                        </div>
                        <div
                            v-if="Object.keys(resultado.errores).length"
                            class="text-xs text-amber-700 dark:text-amber-400"
                        >
                            <span class="font-semibold"
                                >Filas con errores:</span
                            >
                            <span
                                v-for="(msg, row) in resultado.errores"
                                :key="row"
                                class="ml-2"
                                >fila {{ row }}: {{ msg }};</span
                            >
                        </div>
                    </div>

                    <!-- Error general -->
                    <div
                        v-if="errorGeneral"
                        class="flex shrink-0 items-center gap-2 border-b bg-destructive/5 px-5 py-2 text-xs text-destructive"
                    >
                        <AlertCircle class="h-4 w-4 shrink-0" />
                        {{ errorGeneral }}
                    </div>

                    <!-- Tabla scrollable -->
                    <div id="grados-grid-scroll" class="flex-1 overflow-auto">
                        <table
                            class="w-max min-w-full border-separate border-spacing-0 text-xs"
                        >
                            <!-- Cabecera fija -->
                            <thead
                                class="sticky top-0 z-20 bg-muted/90 backdrop-blur-sm"
                            >
                                <tr>
                                    <!-- N° -->
                                    <th
                                        class="w-8 border-r border-b px-2 py-2 text-center text-muted-foreground"
                                    >
                                        #
                                    </th>
                                    <!-- Grado -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 180px"
                                    >
                                        Grado
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <!-- Sigla grado -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold whitespace-nowrap"
                                        style="min-width: 90px"
                                    >
                                        Sigla
                                    </th>
                                    <!-- Secciones dinámicas -->
                                    <th
                                        v-for="i in numSecciones"
                                        :key="i"
                                        class="border-r border-b px-2 py-2 text-left font-semibold whitespace-nowrap"
                                        style="min-width: 110px"
                                    >
                                        {{ secLabel(i - 1) }}
                                    </th>
                                    <!-- Acciones cabecera: +/- columnas -->
                                    <th
                                        class="border-b px-2 py-1.5 text-center"
                                        style="min-width: 60px"
                                    >
                                        <div
                                            class="flex items-center justify-center gap-0.5"
                                        >
                                            <button
                                                type="button"
                                                @click="quitarColumna"
                                                :disabled="numSecciones <= 1"
                                                class="rounded p-0.5 text-muted-foreground transition hover:bg-muted hover:text-foreground disabled:opacity-30"
                                                title="Quitar columna de sección"
                                            >
                                                <X class="h-3.5 w-3.5" />
                                            </button>
                                            <button
                                                type="button"
                                                @click="agregarColumna"
                                                class="rounded p-0.5 text-muted-foreground transition hover:bg-muted hover:text-foreground"
                                                title="Agregar columna de sección"
                                            >
                                                <Columns class="h-3.5 w-3.5" />
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="(fila, idx) in filas"
                                    :key="fila._id"
                                    class="group transition-colors hover:bg-muted/30"
                                    :class="{
                                        'bg-destructive/5': Object.keys(
                                            fila._errors,
                                        ).length,
                                    }"
                                >
                                    <!-- N° -->
                                    <td
                                        class="border-r border-b px-2 py-1 text-center text-muted-foreground select-none"
                                    >
                                        {{ idx + 1 }}
                                    </td>

                                    <!-- Grado nombre -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.grado_nombre"
                                            @input="
                                                fila._errors['grado_nombre'] =
                                                    ''
                                            "
                                            placeholder="Ej: Primero, 1°, Inicial 3 años…"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/50"
                                            :class="
                                                fila._errors['grado_nombre']
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/30'
                                            "
                                        />
                                    </td>

                                    <!-- Grado sigla -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.grado_sigla"
                                            maxlength="20"
                                            placeholder="Ej: 1°"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/40 hover:bg-muted/30"
                                        />
                                    </td>

                                    <!-- Secciones -->
                                    <td
                                        v-for="i in numSecciones"
                                        :key="i"
                                        class="border-r border-b p-0"
                                    >
                                        <input
                                            type="text"
                                            v-model="fila.secciones[i - 1]"
                                            maxlength="100"
                                            :placeholder="'Ej: A, B, Única…'"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/40 hover:bg-muted/30"
                                        />
                                    </td>

                                    <!-- Eliminar fila -->
                                    <td class="border-b px-1 py-1 text-center">
                                        <button
                                            type="button"
                                            @click="eliminarFila(fila._id)"
                                            class="rounded p-1 text-muted-foreground opacity-0 transition-opacity group-hover:opacity-100 hover:bg-destructive/10 hover:text-destructive"
                                            title="Eliminar fila"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ── Acciones de fila ── -->
                    <div
                        class="flex shrink-0 items-center gap-2 border-t bg-muted/20 px-4 py-2.5"
                    >
                        <button
                            type="button"
                            @click="agregarFila"
                            class="flex items-center gap-1.5 rounded-md border border-dashed px-3 py-1.5 text-xs text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary"
                        >
                            <Plus class="h-3.5 w-3.5" /> Agregar fila
                        </button>
                        <button
                            type="button"
                            @click="agregarFilas(10)"
                            class="flex items-center gap-1.5 rounded-md border border-dashed px-3 py-1.5 text-xs text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary"
                        >
                            <Plus class="h-3.5 w-3.5" /> +10 filas
                        </button>
                        <button
                            type="button"
                            @click="limpiarFilasVacias"
                            class="flex items-center gap-1.5 rounded-md border border-dashed px-3 py-1.5 text-xs text-muted-foreground transition-colors hover:border-amber-500/50 hover:text-amber-600"
                        >
                            Limpiar vacías
                        </button>
                        <div
                            class="ml-auto flex items-center gap-1 text-xs text-muted-foreground"
                        >
                            <Columns class="h-3.5 w-3.5" />
                            <span
                                >{{ numSecciones }} col{{
                                    numSecciones !== 1 ? 's' : ''
                                }}
                                de sección</span
                            >
                            <button
                                type="button"
                                @click="quitarColumna"
                                :disabled="numSecciones <= 1"
                                class="ml-1 rounded border border-dashed px-1.5 py-0.5 text-muted-foreground transition hover:border-rose-400/60 hover:text-rose-500 disabled:opacity-30"
                            >
                                −
                            </button>
                            <button
                                type="button"
                                @click="agregarColumna"
                                class="rounded border border-dashed px-1.5 py-0.5 text-muted-foreground transition hover:border-primary/50 hover:text-primary"
                            >
                                +
                            </button>
                        </div>
                    </div>

                    <!-- ── Footer ── -->
                    <div
                        class="flex shrink-0 items-center justify-between border-t bg-background px-5 py-3.5"
                    >
                        <div class="flex items-center gap-3">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="emit('close')"
                                :disabled="enviando"
                            >
                                Cerrar
                            </Button>
                            <Button
                                v-if="resultado"
                                variant="outline"
                                size="sm"
                                @click="resetGrid"
                            >
                                Nueva carga
                            </Button>
                        </div>

                        <div class="flex items-center gap-3">
                            <span
                                v-if="filasConDatos.length"
                                class="text-xs text-muted-foreground"
                            >
                                {{ filasConDatos.length }} grado{{
                                    filasConDatos.length !== 1 ? 's' : ''
                                }}
                                a crear
                            </span>
                            <Button
                                size="sm"
                                @click="enviar"
                                :disabled="
                                    enviando ||
                                    !filasConDatos.length ||
                                    !!resultado
                                "
                                class="min-w-[150px]"
                            >
                                <Loader2
                                    v-if="enviando"
                                    class="mr-2 h-4 w-4 animate-spin"
                                />
                                {{
                                    enviando
                                        ? 'Guardando...'
                                        : `Guardar ${filasConDatos.length || ''} Grado${filasConDatos.length !== 1 ? 's' : ''}`
                                }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

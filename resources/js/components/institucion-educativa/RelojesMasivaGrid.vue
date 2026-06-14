<script setup lang="ts">
/**
 * RelojesMasivaGrid — Grilla masiva para crear relojes en un local de IE.
 *
 * Columnas: # | Nombre | IP | MAC | Puerto | Serie | ID Biométrico | ✕
 *
 * El prop `localInstEducId` indica a qué local pertenecen todos los relojes de esta carga.
 */
import { ref, computed, nextTick } from 'vue';
import {
    Plus,
    Trash2,
    Loader2,
    CheckCircle2,
    AlertCircle,
    X,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    localInstEducId: number;
    localNombre?: string;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'success', count: number): void;
}>();

// ─── Tipos ────────────────────────────────────────────────────────────────────
interface FilaReloj {
    _id: number;
    nombre: string;
    dreccionIP: string;
    direccionMac: string;
    puerto: string;
    serie: string;
    idBiometrico: string;
    _errors: Record<string, string>;
}

// ─── Filas ────────────────────────────────────────────────────────────────────
let nextId = 0;

function nuevaFila(): FilaReloj {
    return {
        _id: nextId++,
        nombre: '',
        dreccionIP: '',
        direccionMac: '',
        puerto: '',
        serie: '',
        idBiometrico: '',
        _errors: {},
    };
}

const filas = ref<FilaReloj[]>(Array.from({ length: 5 }, () => nuevaFila()));

function agregarFila() {
    filas.value.push(nuevaFila());
    nextTick(() => {
        const el = document.getElementById('relojes-grid-scroll');
        if (el) el.scrollTop = el.scrollHeight;
    });
}

function agregarFilas(n: number) {
    for (let i = 0; i < n; i++) filas.value.push(nuevaFila());
}

function eliminarFila(id: number) {
    filas.value = filas.value.filter((f) => f._id !== id);
    if (!filas.value.length) filas.value.push(nuevaFila());
}

function limpiarFilasVacias() {
    const llenas = filas.value.filter(
        (f) =>
            String(f.nombre ?? '').trim() !== '' ||
            String(f.dreccionIP ?? '').trim() !== '',
    );
    filas.value = llenas.length ? llenas : [nuevaFila()];
}

const filasConDatos = computed(() =>
    filas.value.filter(
        (f) =>
            String(f.nombre ?? '').trim() !== '' ||
            String(f.dreccionIP ?? '').trim() !== '',
    ),
);

// ─── Validación ───────────────────────────────────────────────────────────────
function validar(): boolean {
    let ok = true;
    filas.value.forEach((fila) => {
        fila._errors = {};
        const tieneAlgo =
            String(fila.nombre ?? '').trim() ||
            String(fila.dreccionIP ?? '').trim() ||
            String(fila.direccionMac ?? '').trim() ||
            String(fila.puerto ?? '').trim() ||
            String(fila.serie ?? '').trim() ||
            String(fila.idBiometrico ?? '').trim();
        if (!tieneAlgo) return;
        if (
            !String(fila.nombre ?? '').trim() &&
            !String(fila.dreccionIP ?? '').trim()
        ) {
            fila._errors['nombre'] = 'Nombre o IP requerido';
            ok = false;
        }
        const puertoStr = String(fila.puerto ?? '').trim();
        if (
            puertoStr &&
            (isNaN(Number(puertoStr)) ||
                Number(puertoStr) < 1 ||
                Number(puertoStr) > 65535)
        ) {
            fila._errors['puerto'] = 'Puerto inválido (1-65535)';
            ok = false;
        }
        const bioStr = String(fila.idBiometrico ?? '').trim();
        if (bioStr && isNaN(Number(bioStr))) {
            fila._errors['idBiometrico'] = 'Debe ser número';
            ok = false;
        }
    });
    return ok;
}

// ─── Envío ────────────────────────────────────────────────────────────────────
const enviando = ref(false);
const resultado = ref<{
    creados: number;
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
        nombre: String(f.nombre ?? '').trim() || null,
        dreccionIP: String(f.dreccionIP ?? '').trim() || null,
        direccionMac: String(f.direccionMac ?? '').trim() || null,
        puerto: String(f.puerto ?? '').trim() ? Number(f.puerto) : null,
        serie: String(f.serie ?? '').trim() || null,
        idBiometrico: String(f.idBiometrico ?? '').trim()
            ? Number(f.idBiometrico)
            : null,
    }));

    try {
        const res = await fetch(
            `/locales-ie/${props.localInstEducId}/relojes-masivos`,
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
            if (data.creados > 0) emit('success', data.creados);
        }
    } catch {
        errorGeneral.value = 'Error de conexión al servidor.';
    } finally {
        enviando.value = false;
    }
}

function resetGrid() {
    filas.value = Array.from({ length: 5 }, () => nuevaFila());
    resultado.value = null;
    errorGeneral.value = '';
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
                <!-- Header -->
                <div
                    class="flex shrink-0 items-center justify-between border-b px-6 py-3"
                >
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-semibold">
                            Carga Masiva de Relojes
                        </h2>
                        <span
                            v-if="props.localNombre"
                            class="rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground"
                        >
                            Local: <strong>{{ props.localNombre }}</strong>
                        </span>
                        <span class="text-xs text-muted-foreground">
                            Completa la grilla y presiona
                            <strong>Guardar</strong>. Necesitas al menos nombre
                            o IP por fila.
                        </span>
                        <span
                            v-if="filasConDatos.length"
                            class="rounded-full bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary"
                        >
                            {{ filasConDatos.length }} reloj{{
                                filasConDatos.length !== 1 ? 'es' : ''
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

                <!-- Grid -->
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
                            {{ resultado.creados }} reloj{{
                                resultado.creados !== 1 ? 'es' : ''
                            }}
                            creado{{ resultado.creados !== 1 ? 's' : '' }}
                            correctamente.
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

                    <!-- Tabla -->
                    <div id="relojes-grid-scroll" class="flex-1 overflow-auto">
                        <table
                            class="w-max min-w-full border-separate border-spacing-0 text-xs"
                        >
                            <thead
                                class="sticky top-0 z-20 bg-muted/90 backdrop-blur-sm"
                            >
                                <tr>
                                    <th
                                        class="w-8 border-r border-b px-2 py-2 text-center text-muted-foreground"
                                    >
                                        #
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 180px"
                                    >
                                        Nombre
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 140px"
                                    >
                                        Dirección IP
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 160px"
                                    >
                                        Dirección MAC
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 90px"
                                    >
                                        Puerto
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 130px"
                                    >
                                        Serie
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 110px"
                                    >
                                        ID Biométrico
                                    </th>
                                    <th class="w-8 border-b px-2 py-2"></th>
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
                                    <td
                                        class="border-r border-b px-2 py-1 text-center text-muted-foreground select-none"
                                    >
                                        {{ idx + 1 }}
                                    </td>

                                    <!-- Nombre -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.nombre"
                                            @input="fila._errors['nombre'] = ''"
                                            placeholder="Ej: Reloj Entrada"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/50"
                                            :class="
                                                fila._errors['nombre']
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/30'
                                            "
                                        />
                                    </td>

                                    <!-- IP -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.dreccionIP"
                                            maxlength="30"
                                            placeholder="192.168.1.10"
                                            class="w-full bg-transparent px-2.5 py-1.5 font-mono text-xs outline-none placeholder:text-muted-foreground/40 hover:bg-muted/30"
                                        />
                                    </td>

                                    <!-- MAC -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.direccionMac"
                                            maxlength="50"
                                            placeholder="AA:BB:CC:DD:EE:FF"
                                            class="w-full bg-transparent px-2.5 py-1.5 font-mono text-xs outline-none placeholder:text-muted-foreground/40 hover:bg-muted/30"
                                        />
                                    </td>

                                    <!-- Puerto -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="number"
                                            v-model="fila.puerto"
                                            @input="fila._errors['puerto'] = ''"
                                            min="1"
                                            max="65535"
                                            placeholder="4370"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/40"
                                            :class="
                                                fila._errors['puerto']
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/30'
                                            "
                                        />
                                    </td>

                                    <!-- Serie -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.serie"
                                            maxlength="100"
                                            placeholder="Opcional"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/40 hover:bg-muted/30"
                                        />
                                    </td>

                                    <!-- ID Biométrico -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="number"
                                            v-model="fila.idBiometrico"
                                            @input="
                                                fila._errors['idBiometrico'] =
                                                    ''
                                            "
                                            placeholder="Opcional"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/40"
                                            :class="
                                                fila._errors['idBiometrico']
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/30'
                                            "
                                        />
                                    </td>

                                    <!-- Eliminar -->
                                    <td class="border-b px-1 py-1 text-center">
                                        <button
                                            type="button"
                                            @click="eliminarFila(fila._id)"
                                            class="rounded p-1 text-muted-foreground opacity-0 transition-opacity group-hover:opacity-100 hover:bg-destructive/10 hover:text-destructive"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Acciones de fila -->
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
                    </div>

                    <!-- Footer -->
                    <div
                        class="flex shrink-0 items-center justify-between border-t bg-background px-5 py-3.5"
                    >
                        <div class="flex items-center gap-3">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="emit('close')"
                                :disabled="enviando"
                                >Cerrar</Button
                            >
                            <Button
                                v-if="resultado"
                                variant="outline"
                                size="sm"
                                @click="resetGrid"
                                >Nueva carga</Button
                            >
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                v-if="filasConDatos.length"
                                class="text-xs text-muted-foreground"
                            >
                                {{ filasConDatos.length }} reloj{{
                                    filasConDatos.length !== 1 ? 'es' : ''
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
                                        : `Guardar ${filasConDatos.length || ''} Reloj${filasConDatos.length !== 1 ? 'es' : ''}`
                                }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

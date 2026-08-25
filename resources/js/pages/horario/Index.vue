<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Plus,
    Upload,
    User,
    Trash2,
    Clock,
    UserCheck,
    ArrowRight,
    Sparkles,
    ChevronRight,
    BookOpen,
} from 'lucide-vue-next';
import { ref, computed, watch, onMounted } from 'vue';
import { toast } from 'vue-sonner';
import DetalleHorarioGrid from '@/components/horario/DetalleHorarioGrid.vue';
import HorarioForm from '@/components/horario/HorarioForm.vue';
import HorarioMasivoGrid from '@/components/horario/HorarioMasivoGrid.vue';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import SearchSelect from '@/components/shared/SearchSelect.vue';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Label } from '@/components/ui/label';

const props = defineProps<{
    instituciones: any[];
    anioActual: number;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Gestión de Horarios', href: '#' }],
    },
});

// ── Filtros ──────────────────────────────────────────────────────────────────
const selectedAnio = ref<string>(String(props.anioActual));
const selectedIE = ref<string>('');
const selectedGrado = ref<string>('');
const selectedSeccion = ref<string>('');

const ieDetails = ref<any>(null);
const loadingIE = ref(false);

const grados = computed(() => ieDetails.value?.grados || []);
const cursos = computed(() => ieDetails.value?.cursos || []);

const secciones = computed(() => {
    if (!selectedGrado.value) return [];
    const g = grados.value.find(
        (x: any) => x.id === Number(selectedGrado.value),
    );
    return g?.secciones || [];
});

// Etiquetas informativas
const selectedIELabel = computed(
    () =>
        props.instituciones.find((ie) => String(ie.id) === selectedIE.value)
            ?.nombreLegal ?? '—',
);
const selectedGradoLabel = computed(
    () =>
        grados.value.find((g: any) => String(g.id) === selectedGrado.value)
            ?.nombre ?? '—',
);
const selectedSeccionLabel = computed(
    () =>
        secciones.value.find((s: any) => String(s.id) === selectedSeccion.value)
            ?.nombre ?? '—',
);

// SearchSelect items
const gradoItems = computed(() =>
    grados.value.map((g: any) => ({ id: String(g.id), label: g.nombre })),
);
const seccionItems = computed(() =>
    secciones.value.map((s: any) => ({ id: String(s.id), label: s.nombre })),
);

// ── Horarios de curso ─────────────────────────────────────────────────────────
const horarios = ref<any[]>([]);
const loadingHorarios = ref(false);

// ── Modal Carga Masiva de Horarios ────────────────────────────────────────────
const showMasivoModal = ref(false);

function onMasivoSuccess() {
    loadHorarios();
}

// ── Modal Crear / Editar HorarioCurso (unificado con asignación docente) ──────
const showHorarioModal = ref(false);
const editingHorario = ref<any>(null);
const submittingHorario = ref(false);
const horarioFormRef = ref<any>(null);

// ── Modal Confirmación Eliminar ───────────────────────────────────────────────
const showDeleteConfirm = ref(false);
const deletingHorario = ref<any>(null);
const submittingDelete = ref(false);

const DAYS_LABEL: Record<number, string> = {
    1: 'Lunes',
    2: 'Martes',
    3: 'Miércoles',
    4: 'Jueves',
    5: 'Viernes',
    6: 'Sábado',
    7: 'Domingo',
};

// ── Watchers ──────────────────────────────────────────────────────────────────
let isInitializing = false;

watch(selectedIE, () => {
    if (!isInitializing) loadIEDetails(false);
});

watch([selectedSeccion, selectedAnio], () => loadHorarios());

// ── Lifecycle ─────────────────────────────────────────────────────────────────
onMounted(async () => {
    const params = new URLSearchParams(window.location.search);
    const ieId = params.get('ie_id');
    const gradoId = params.get('grado_id');
    const seccionId = params.get('seccion_id');
    const anio = params.get('anio');

    if (anio) selectedAnio.value = anio;

    const resolvedIeId =
        ieId ??
        (props.instituciones.length === 1
            ? String(props.instituciones[0].id)
            : null);

    if (resolvedIeId) {
        isInitializing = true;
        selectedIE.value = resolvedIeId;
        await loadIEDetails(true);

        if (gradoId) {
            selectedGrado.value = gradoId;
            if (seccionId) selectedSeccion.value = seccionId;
        }

        isInitializing = false;
    }
});

// ── API helpers ───────────────────────────────────────────────────────────────
function csrfToken(): string {
    return (
        (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)
            ?.content ?? ''
    );
}

async function loadIEDetails(keepSelections = false) {
    if (!selectedIE.value) {
        ieDetails.value = null;
        selectedGrado.value = '';
        selectedSeccion.value = '';
        horarios.value = [];
        return;
    }

    loadingIE.value = true;

    try {
        const res = await fetch(
            `/api/instituciones/${selectedIE.value}/detalles`,
        );
        const result = await res.json();
        ieDetails.value = result.data;

        if (!keepSelections) {
            selectedGrado.value = '';
            selectedSeccion.value = '';
            horarios.value = [];
        }
    } catch (e) {
        console.error(e);
        toast.error('Error al cargar la Institución Educativa.');
    } finally {
        loadingIE.value = false;
    }
}

async function loadHorarios() {
    if (!selectedSeccion.value || !selectedAnio.value) {
        horarios.value = [];
        return;
    }

    loadingHorarios.value = true;

    try {
        const res = await fetch(
            `/horarios-cursos?seccion_id=${selectedSeccion.value}&anio=${selectedAnio.value}`,
        );
        const result = await res.json();
        horarios.value = result.data;
    } catch (e) {
        console.error(e);
        toast.error('Error al cargar la grilla de horarios.');
    } finally {
        loadingHorarios.value = false;
    }
}

// ── HorarioCurso ──────────────────────────────────────────────────────────────
function openCreateHorario() {
    editingHorario.value = null;
    showHorarioModal.value = true;
}

function openEditHorario(horario: any) {
    editingHorario.value = horario;
    showHorarioModal.value = true;
}

async function handleHorarioSubmit(data: any) {
    const isEdit = !!data.id;
    const url = isEdit ? `/horarios-cursos/${data.id}` : '/horarios-cursos';
    const method = isEdit ? 'PUT' : 'POST';

    submittingHorario.value = true;

    try {
        // 1. Guardar el horario de curso
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
            body: JSON.stringify(data),
        });
        const result = await res.json();

        if (!res.ok) {
            toast.error(result.message || 'Error al procesar el formulario.');
            return;
        }

        const horarioId = result.data?.id ?? data.id;

        // 2. Si se eligió un docente, asignarlo al horario recién creado/editado
        if (data.trabajador_id && horarioId) {
            const cargaExistente = editingHorario.value?.cargas?.[0];
            const cargaUrl = cargaExistente
                ? `/cargas/${cargaExistente.id}`
                : `/horarios-cursos/${horarioId}/cargas`;
            const cargaMethod = cargaExistente ? 'PUT' : 'POST';

            const cargaRes = await fetch(cargaUrl, {
                method: cargaMethod,
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                },
                body: JSON.stringify({
                    horarioCurso_id: horarioId,
                    trabajador_id: data.trabajador_id,
                    altaTrabajador_id: data.altaTrabajador_id ?? null,
                    titularSuplencia: data.titularSuplencia ?? 'T',
                    fechaInicio: data.fechaInicioDocente ?? null,
                    fechaFin: data.fechaFinDocente ?? null,
                    turno_id: data.turno_id ?? null,
                }),
            });

            if (!cargaRes.ok) {
                const cargaErr = await cargaRes.json();
                toast.warning(
                    'Horario guardado, pero error al asignar docente: ' +
                        (cargaErr.message ?? ''),
                );
                showHorarioModal.value = false;
                loadHorarios();
                return;
            }

            toast.success(
                isEdit
                    ? 'Horario y docente actualizados.'
                    : 'Horario creado y docente asignado.',
            );
        } else {
            toast.success(
                isEdit ? 'Horario actualizado.' : 'Horario registrado.',
            );
        }

        showHorarioModal.value = false;
        loadHorarios();
    } catch (e) {
        console.error(e);
        toast.error('Error de conexión.');
    } finally {
        submittingHorario.value = false;
    }
}

function confirmDeleteHorario(horario: any) {
    deletingHorario.value = horario;
    showDeleteConfirm.value = true;
}

async function executeDeleteHorario() {
    if (!deletingHorario.value) return;

    submittingDelete.value = true;

    try {
        const res = await fetch(
            `/horarios-cursos/${deletingHorario.value.id}`,
            {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                },
            },
        );

        if (res.ok) {
            toast.success('Horario eliminado.');
            showDeleteConfirm.value = false;
            deletingHorario.value = null;
            loadHorarios();
        } else {
            const err = await res.json();
            toast.error(err.message || 'Error al eliminar.');
        }
    } catch (e) {
        console.error(e);
        toast.error('Error de conexión.');
    } finally {
        submittingDelete.value = false;
    }
}

// ── Asignación Docente — se abre el modal unificado ───────────────────────────
function openAssignTeacher(horario: any) {
    editingHorario.value = horario;
    showHorarioModal.value = true;
}

async function removeAssign(cargaId: number) {
    if (
        !confirm(
            '¿Desasignar al docente de este curso? Se recalculará su horario.',
        )
    )
        return;

    try {
        const res = await fetch(`/horarios-cursos/cargas/${cargaId}`, {
            method: 'DELETE',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
        });

        if (res.ok) {
            toast.success('Docente desasignado y horario recalculado.');
            loadHorarios();
        } else {
            const err = await res.json();
            toast.error(err.message || 'Error al desasignar.');
        }
    } catch (e) {
        console.error(e);
        toast.error('Error de conexión.');
    }
}

function formatWorkerName(worker: any) {
    if (!worker?.persona) return '';
    return `${worker.persona.paterno} ${worker.persona.materno}, ${worker.persona.nombre}`;
}
</script>

<template>
    <Head title="Gestión de Horarios" />

    <div class="flex flex-col gap-6 p-4">
        <!-- ── Encabezado ──────────────────────────────────────────────────── -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-foreground">
                    Planificación de Horarios
                </h1>
                <p class="mt-0.5 text-sm text-muted-foreground">
                    Gestione los horarios escolares por sección y asigne
                    docentes a cada curso.
                </p>
            </div>

            <div v-if="selectedSeccion" class="flex items-center gap-2">
                <Button
                    variant="outline"
                    @click="showMasivoModal = true"
                    class="gap-1.5 border-indigo-200 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 dark:border-indigo-900/50 dark:text-indigo-400 dark:hover:bg-indigo-950/20"
                >
                    <Upload class="h-4 w-4" />
                    Carga Masiva
                </Button>
                <Button @click="openCreateHorario">
                    <Plus class="mr-2 h-4 w-4" /> Agregar Horario
                </Button>
            </div>
        </div>

        <!-- ── Panel de filtros ───────────────────────────────────────────── -->
        <div class="flex flex-col gap-3 border-b border-border pb-5">
            <div
                class="flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end"
            >
                <!-- Año -->
                <div class="w-full space-y-1.5 sm:w-28">
                    <Label
                        class="text-xs font-semibold tracking-wider text-muted-foreground uppercase"
                    >
                        Año Lectivo
                    </Label>
                    <Select v-model="selectedAnio">
                        <SelectTrigger class="h-9">
                            <SelectValue placeholder="Año" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="2026">2026</SelectItem>
                            <SelectItem value="2025">2025</SelectItem>
                            <SelectItem value="2024">2024</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <!-- IE — solo lectura -->
                <div class="w-full space-y-1.5 sm:min-w-[300px] sm:flex-1">
                    <Label
                        class="text-xs font-semibold tracking-wider text-muted-foreground uppercase"
                    >
                        Institución Educativa
                    </Label>
                    <div
                        class="flex h-9 items-center text-sm font-semibold text-foreground"
                    >
                        {{
                            selectedIELabel !== '—'
                                ? selectedIELabel
                                : 'Sin IE seleccionada'
                        }}
                    </div>
                </div>

                <!-- Grado -->
                <div class="w-full space-y-1.5 sm:w-52">
                    <Label
                        class="text-xs font-semibold tracking-wider text-muted-foreground uppercase"
                    >
                        Grado
                    </Label>
                    <SearchSelect
                        v-model="selectedGrado"
                        :items="gradoItems"
                        placeholder="Seleccione Grado..."
                        :disabled="!selectedIE || loadingIE"
                        :loading="loadingIE"
                        clearable
                    />
                </div>

                <!-- Sección -->
                <div class="w-full space-y-1.5 sm:w-44">
                    <Label
                        class="text-xs font-semibold tracking-wider text-muted-foreground uppercase"
                    >
                        Sección
                    </Label>
                    <SearchSelect
                        v-model="selectedSeccion"
                        :items="seccionItems"
                        placeholder="Seleccione Sección..."
                        :disabled="!selectedGrado"
                        clearable
                    />
                </div>
            </div>

            <!-- Breadcrumb informativo -->
            <div
                v-if="selectedSeccion"
                class="mt-1 flex items-center gap-1.5 text-xs text-muted-foreground"
            >
                <span class="font-medium text-foreground">{{
                    selectedIELabel
                }}</span>
                <ChevronRight class="h-3 w-3" />
                <span>{{ selectedGradoLabel }}</span>
                <ChevronRight class="h-3 w-3" />
                <span class="font-semibold text-primary"
                    >Sección {{ selectedSeccionLabel }}</span
                >
                <span
                    class="ml-1 rounded-full bg-muted px-2 py-0.5 font-medium"
                    >{{ selectedAnio }}</span
                >
            </div>
        </div>

        <!-- ── Cuerpo ──────────────────────────────────────────────────────── -->
        <template v-if="selectedSeccion">
            <!-- Grilla horaria -->
            <div class="relative">
                <div
                    v-if="loadingHorarios"
                    class="absolute inset-0 z-10 flex items-center justify-center rounded-xl bg-background/60 backdrop-blur-xs"
                >
                    <div class="flex flex-col items-center gap-2">
                        <div
                            class="h-8 w-8 animate-spin rounded-full border-b-2 border-primary"
                        />
                        <span class="text-sm font-medium text-muted-foreground"
                            >Cargando grilla...</span
                        >
                    </div>
                </div>

                <DetalleHorarioGrid
                    :horarios="horarios"
                    @edit="openEditHorario"
                    @delete="confirmDeleteHorario"
                    @assign="openAssignTeacher"
                    @select="openAssignTeacher"
                />
            </div>

            <!-- Tabla de docentes asignados -->
            <div class="overflow-hidden rounded-xl border bg-card shadow-sm">
                <div
                    class="flex items-center gap-2 border-b bg-muted/20 px-5 py-3.5"
                >
                    <UserCheck class="h-4 w-4 text-primary" />
                    <h3 class="text-sm font-semibold">
                        Carga Horaria y Docentes Asignados
                    </h3>
                </div>

                <div
                    v-if="horarios.length === 0"
                    class="py-10 text-center text-sm text-muted-foreground"
                >
                    No hay horarios de cursos registrados en esta sección.
                </div>

                <Table v-else>
                    <TableHeader>
                        <TableRow>
                            <TableHead>
                                <span class="flex items-center gap-1.5">
                                    <BookOpen class="h-3.5 w-3.5" /> Curso
                                </span>
                            </TableHead>
                            <TableHead>Día</TableHead>
                            <TableHead>Horario</TableHead>
                            <TableHead>
                                <span class="flex items-center gap-1.5">
                                    <User class="h-3.5 w-3.5" /> Docente
                                    Asignado
                                </span>
                            </TableHead>
                            <TableHead class="w-[160px] text-right"
                                >Acciones</TableHead
                            >
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="horario in horarios" :key="horario.id">
                            <TableCell class="font-medium">
                                {{ horario.curso?.nombre ?? '—' }}
                            </TableCell>
                            <TableCell class="text-sm text-muted-foreground">
                                {{ DAYS_LABEL[horario.nroDia] ?? '—' }}
                            </TableCell>
                            <TableCell>
                                <span
                                    class="flex items-center gap-1 font-mono text-xs"
                                >
                                    <Clock
                                        class="h-3 w-3 text-muted-foreground"
                                    />
                                    {{ horario.horaInicio?.substring(0, 5) }} –
                                    {{ horario.horaFin?.substring(0, 5) }}
                                </span>
                            </TableCell>
                            <TableCell>
                                <template
                                    v-if="
                                        horario.cargas &&
                                        horario.cargas.length > 0
                                    "
                                >
                                    <span class="text-sm font-medium">
                                        {{
                                            formatWorkerName(
                                                horario.cargas[0].trabajador,
                                            )
                                        }}
                                    </span>
                                </template>
                                <span
                                    v-else
                                    class="text-xs text-muted-foreground italic"
                                    >Sin asignar</span
                                >
                            </TableCell>
                            <TableCell class="text-right">
                                <div
                                    class="flex items-center justify-end gap-1"
                                >
                                    <template
                                        v-if="
                                            horario.cargas &&
                                            horario.cargas.length > 0
                                        "
                                    >
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            class="h-7 text-xs"
                                            as-child
                                        >
                                            <Link
                                                :href="`/horarios-trabajador/${horario.cargas[0].trabajador_id}?anio=${selectedAnio}`"
                                            >
                                                Ver horario
                                                <ArrowRight
                                                    class="ml-1 h-3 w-3"
                                                />
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-7 w-7 text-red-500 hover:bg-red-50 hover:text-red-700"
                                            title="Quitar asignación"
                                            @click="
                                                removeAssign(
                                                    horario.cargas[0].id,
                                                )
                                            "
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </Button>
                                    </template>
                                    <Button
                                        v-else
                                        variant="outline"
                                        size="sm"
                                        class="h-7 text-xs"
                                        @click="openAssignTeacher(horario)"
                                    >
                                        Asignar
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </template>

        <!-- Estado vacío -->
        <div
            v-else
            class="flex flex-col items-center justify-center space-y-4 rounded-2xl border-2 border-dashed bg-muted/10 p-12 text-center"
        >
            <div class="rounded-full bg-primary/5 p-4 text-primary">
                <Sparkles class="h-10 w-10 animate-pulse" />
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-foreground">
                    Seleccione una Sección
                </h3>
                <p class="mx-auto max-w-sm text-sm text-muted-foreground">
                    Elija el año lectivo, la Institución Educativa, el Grado y
                    la Sección para visualizar y programar los horarios.
                </p>
            </div>
        </div>
    </div>

    <!-- ── Modal: Crear / Editar HorarioCurso (con asignación de docente) ───── -->
    <FormModal
        v-model:show="showHorarioModal"
        :title="
            editingHorario
                ? 'Editar Horario de Curso'
                : 'Agregar Horario de Curso'
        "
        :description="
            editingHorario
                ? 'Modifique los datos del horario seleccionado.'
                : `Nuevo horario — Sección ${selectedSeccionLabel} · ${selectedGradoLabel} · ${selectedAnio}`
        "
        maxWidth="2xl"
        :submitText="editingHorario ? 'Actualizar' : 'Crear Horario'"
        :processing="submittingHorario"
        @submit="horarioFormRef?.handleSubmit()"
        @close="showHorarioModal = false"
    >
        <HorarioForm
            ref="horarioFormRef"
            :seccionId="Number(selectedSeccion)"
            :anio="Number(selectedAnio)"
            :cursos="cursos"
            :horarioCurso="editingHorario"
            :ieId="Number(selectedIE) || null"
            @submit="handleHorarioSubmit"
            @cancel="showHorarioModal = false"
        />
    </FormModal>

    <!-- ── Modal: Confirmar Eliminación ───────────────────────────────────── -->
    <ConfirmModal
        v-model:show="showDeleteConfirm"
        title="Eliminar Horario de Curso"
        description="¿Está seguro? Se eliminará la asignación docente y se recalculará el horario consolidado del trabajador."
        confirm-text="Sí, eliminar"
        destructive
        :processing="submittingDelete"
        @confirm="executeDeleteHorario"
        @cancel="showDeleteConfirm = false"
    />

    <!-- ── Modal: Carga Masiva de Horarios ───────────────────────────────── -->
    <HorarioMasivoGrid
        v-if="showMasivoModal"
        :seccion-id="Number(selectedSeccion)"
        :anio="Number(selectedAnio)"
        :cursos="cursos"
        :ie-id="Number(selectedIE) || null"
        :horarios-existentes="horarios"
        @close="showMasivoModal = false"
        @success="onMasivoSuccess"
    />
</template>

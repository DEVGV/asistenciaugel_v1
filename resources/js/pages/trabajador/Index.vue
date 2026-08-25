<script setup lang="ts">
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import {
    Plus,
    Pencil,
    Trash2,
    Eye,
    ChevronDown,
    ChevronRight,
    UserCheck,
    Search,
    Loader2,
    CheckCircle2,
    UserPlus,
    Upload,
} from 'lucide-vue-next';
import { ref, watch, computed } from 'vue';
import TrabajadorController from '@/actions/App/Http/Controllers/Trabajador/TrabajadorController';
import { usePermisos } from '@/composables/usePermisos';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import LocalMarcacionSelect from '@/components/shared/LocalMarcacionSelect.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import { Button } from '@/components/ui/button';
import TrabajadorMasivoGrid from '@/components/trabajador/TrabajadorMasivoGrid.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { Trabajador, PaginatedResponse } from '@/types/models/trabajador';
import type { ParamSimple } from '@/types/models/params';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Trabajadores', href: TrabajadorController.index().url },
        ],
    },
});

const props = defineProps<{
    trabajadores: PaginatedResponse<Trabajador>;
    filters: { search?: string };
    perfiles: { id: number; nombre: string; descripcion: string | null }[];
}>();

const { can } = usePermisos();

// ─── Búsqueda en Vivo en el Index ───
const search = ref(props.filters.search || '');
let searchTimeout: any = null;

watch(search, (val) => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    searchTimeout = setTimeout(() => {
        router.get(
            TrabajadorController.index().url,
            { search: val || undefined },
            {
                preserveState: true,
                replace: true,
            },
        );
    }, 300);
});

// ─── Modal Confirmar Desactivación ───
const showDeleteModal = ref(false);
const trabajadorToDelete = ref<Trabajador | null>(null);
const isDeleting = ref(false);

function confirmDelete(trabajador: Trabajador) {
    trabajadorToDelete.value = trabajador;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!trabajadorToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(
        TrabajadorController.destroy({
            trabajador: trabajadorToDelete.value.id,
        }).url,
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                trabajadorToDelete.value = null;
            },
            onFinish: () => {
                isDeleting.value = false;
            },
        },
    );
}

// ─── Modal de Registro de Trabajador ───
const showRegisterModal = ref(false);
const showAlta = ref(false);

// Form
const form = useForm({
    // Persona
    tipoDocIdentidad_id: null as number | null,
    docIdentidad: '',
    paterno: '',
    materno: '',
    nombre: '',
    sexo_id: null as number | null,
    fechaNac: '',
    pais_id: null as number | null,
    ubigeo: '',

    // Alta (opcional)
    incluir_alta: false,
    institucionEducativa_id: null as number | null,
    condicionLaboral_id: null as number | null,
    tipoContrato_id: null as number | null,
    rolTrabajador_id: null as number | null,
    situacionLaboral_id: null as number | null,
    area_id: null as number | null,
    cargo_id: null as number | null,
    codigoAirsp: '',
    fechaInicio: '',
    fechaFin: '',
    fechaAlta: '',
    observacion: '',

    // Local de marcación (opcional, depende de la IE)
    localInstEduc_id: null as number | null,

    // Perfil
    perfil_id: null as number | null,
});

// Sync showAlta con form.incluir_alta
watch(showAlta, (v) => {
    form.incluir_alta = v;
});

// ─── RENIEC Auto-fill ───
const selectedDocTypeAbrev = ref<string | null>(null);
const isSearchingReniec = ref(false);
const searchError = ref<string | null>(null);

function onDocTypeChange(item: ParamSimple | null) {
    selectedDocTypeAbrev.value = item?.abreviatura || null;
}

async function buscarReniec() {
    if (!form.docIdentidad || form.docIdentidad.length !== 8) return;
    if (selectedDocTypeAbrev.value !== 'DNI') return;

    isSearchingReniec.value = true;
    searchError.value = null;

    try {
        const response = await fetch('/api/sunat/dni', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            body: JSON.stringify({ dni: form.docIdentidad }),
        });
        const result = await response.json();
        if (!response.ok)
            throw new Error(result.message || 'Error al buscar el DNI');
        if (result.data) {
            form.nombre = result.data.nombres || '';
            form.paterno = result.data.apellido_paterno || '';
            form.materno = result.data.apellido_materno || '';
            form.clearErrors('nombre', 'paterno', 'materno');
        }
    } catch (e: any) {
        searchError.value = e.message;
    } finally {
        isSearchingReniec.value = false;
    }
}

watch(
    () => form.docIdentidad,
    (newVal) => {
        if (newVal?.length === 8 && selectedDocTypeAbrev.value === 'DNI') {
            buscarReniec();
        }
    },
);

// ─── IE Search ───
const ieQuery = ref('');
const ieOptions = ref<{ id: number; nombre: string }[]>([]);
const ieLoading = ref(false);
let ieTimeout: ReturnType<typeof setTimeout>;

watch(ieQuery, (q) => {
    clearTimeout(ieTimeout);
    ieTimeout = setTimeout(async () => {
        if (!q) return;
        ieLoading.value = true;
        try {
            const res = await fetch(
                `/api/instituciones/search?search=${encodeURIComponent(q)}&per_page=30`,
            );
            const data = await res.json();
            ieOptions.value = (data.data || []).map((ie: any) => ({
                id: ie.id,
                nombre: ie.nombreLegal ?? ie.codigoInstitucion ?? String(ie.id),
            }));
        } catch {
            ieOptions.value = [];
        } finally {
            ieLoading.value = false;
        }
    }, 300);
});

// ─── Resumen usuario ───
const loginPreview = computed(() => form.docIdentidad || '(documento)');

function openRegisterModal() {
    showRegisterModal.value = true;
    showAlta.value = false;
    selectedDocTypeAbrev.value = null;
    searchError.value = null;
    ieQuery.value = '';
    ieOptions.value = [];
    form.reset();
    form.clearErrors();
}

function submit() {
    form.post('/registro-trabajador', {
        onSuccess: () => {
            showRegisterModal.value = false;
            form.reset();
        },
    });
}

// ─── Carga Masiva Grid ─────────────────────────────────────────────────────
const showMasivoModal = ref(false);

function onMasivoSuccess(count: number) {
    showMasivoModal.value = false;
    router.reload({ only: ['trabajadores'] });
}
</script>

<template>
    <Head title="Trabajadores" />

    <div class="flex flex-col gap-4 p-4">
        <!-- Encabezado -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    Trabajadores
                </h1>
                <p class="text-sm text-muted-foreground">
                    Gestión del personal registrado y sus códigos de trabajador
                    asignados.
                </p>
            </div>
            <div v-if="can('trabajadores.crear')" class="flex items-center gap-2">
                <Button variant="outline" @click="showMasivoModal = true">
                    <Upload class="mr-2 h-4 w-4" />
                    Carga Masiva
                </Button>
                <Button @click="openRegisterModal">
                    <Plus class="mr-2 h-4 w-4" />
                    Nuevo Trabajador
                </Button>
            </div>
        </div>

        <!-- Barra de Búsqueda -->
        <div class="flex items-center gap-2">
            <input
                v-model="search"
                type="text"
                placeholder="Buscar por código de trabajador, DNI o nombres..."
                class="flex h-9 w-full max-w-md rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs placeholder:text-muted-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none"
            />
        </div>

        <!-- Tabla Principal -->
        <div class="overflow-hidden rounded-md border bg-card shadow-xs">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[180px]"
                            >Código Trabajador</TableHead
                        >
                        <TableHead>Persona</TableHead>
                        <TableHead>Documento</TableHead>
                        <TableHead>Sexo</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="trabajador in props.trabajadores.data"
                        :key="trabajador.id"
                    >
                        <TableCell class="font-semibold text-primary">
                            <div class="flex items-center gap-1.5">
                                <UserCheck
                                    class="h-4 w-4 shrink-0 text-muted-foreground"
                                />
                                {{ trabajador.codigo }}
                            </div>
                        </TableCell>
                        <TableCell>
                            <div class="text-sm font-medium">
                                {{ trabajador.persona?.paterno }}
                                {{ trabajador.persona?.materno }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{ trabajador.persona?.nombre }}
                            </div>
                        </TableCell>
                        <TableCell>
                            <div class="text-xs text-muted-foreground">
                                {{
                                    trabajador.persona?.tipoDocIdentidad
                                        ?.abreviatura ||
                                    trabajador.persona?.tipoDocIdentidad?.nombre
                                }}
                            </div>
                            <div class="text-xs font-medium">
                                {{ trabajador.persona?.docIdentidad }}
                            </div>
                        </TableCell>
                        <TableCell class="text-xs">
                            {{ trabajador.persona?.sexo?.nombre || '-' }}
                        </TableCell>
                        <TableCell>
                            <StatusBadge :active="trabajador.activo" />
                        </TableCell>
                        <TableCell class="text-right">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        class="h-8 data-[state=open]:bg-muted"
                                    >
                                        Acciones
                                        <ChevronDown
                                            class="ml-2 h-4 w-4 text-muted-foreground"
                                        />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuLabel
                                        >Acciones</DropdownMenuLabel
                                    >
                                    <DropdownMenuItem as-child>
                                        <Link
                                            :href="
                                                TrabajadorController.show({
                                                    trabajador: trabajador.id,
                                                }).url
                                            "
                                            class="flex items-center"
                                        >
                                            <Eye class="mr-2 h-4 w-4" />
                                            <span>Ver Detalles</span>
                                        </Link>
                                    </DropdownMenuItem>
                                    <template v-if="can('trabajadores.eliminar')">
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem
                                            @click="confirmDelete(trabajador)"
                                            class="cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive"
                                        >
                                            <Trash2 class="mr-2 h-4 w-4" />
                                            <span>Desactivar</span>
                                        </DropdownMenuItem>
                                    </template>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.trabajadores.data.length === 0">
                        <TableCell
                            colspan="6"
                            class="h-24 text-center text-muted-foreground"
                        >
                            No hay trabajadores registrados.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Paginación -->
            <div
                class="flex items-center justify-between border-t px-4 py-3"
                v-if="props.trabajadores.total > 0"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.trabajadores.current_page }} de
                    {{ props.trabajadores.last_page }} ({{
                        props.trabajadores.total
                    }}
                    registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.trabajadores.current_page <= 1"
                        @click="
                            router.get(TrabajadorController.index().url, {
                                page: props.trabajadores.current_page - 1,
                                search: search || undefined,
                            })
                        "
                    >
                        Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.trabajadores.current_page >=
                            props.trabajadores.last_page
                        "
                        @click="
                            router.get(TrabajadorController.index().url, {
                                page: props.trabajadores.current_page + 1,
                                search: search || undefined,
                            })
                        "
                    >
                        Siguiente
                    </Button>
                </div>
            </div>
        </div>

        <!-- Modal Confirmación de Desactivación -->
        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Desactivar Trabajador"
            :description="`¿Estás seguro de que deseas desactivar el trabajador con código ${trabajadorToDelete?.codigo}?`"
            confirm-text="Desactivar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="trabajadorToDelete = null"
        />

        <!-- Modal de Registro de Trabajador -->
        <FormModal
            v-model:show="showRegisterModal"
            title="Registrar Trabajador"
            description="Formulario unificado: persona + trabajador + usuario + alta laboral."
            max-width="5xl"
            @submit="submit"
            :processing="form.processing"
        >
            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
                <!-- Columna Izquierda: Persona + Usuario -->
                <div class="space-y-6">
                    <!-- SECCIÓN 1: DATOS DE PERSONA -->
                    <div class="rounded-xl border bg-card p-6 shadow-xs">
                        <h2 class="mb-4 text-lg font-semibold text-foreground">
                            Datos de Persona
                        </h2>

                        <div class="grid gap-5">
                            <!-- Tipo Doc + Nro Documento -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <ParamSelect
                                    v-model="form.tipoDocIdentidad_id"
                                    type="tipos-doc-identidad"
                                    label="Tipo Doc. *"
                                    :error="form.errors.tipoDocIdentidad_id"
                                    default-codigo="01"
                                    @update:item="onDocTypeChange"
                                />
                                <div class="grid gap-2">
                                    <Label for="docIdentidad"
                                        >N° Documento *</Label
                                    >
                                    <div class="relative flex items-center">
                                        <Input
                                            id="docIdentidad"
                                            v-model="form.docIdentidad"
                                            placeholder="N° documento"
                                            maxlength="20"
                                            :class="{
                                                'pr-10':
                                                    selectedDocTypeAbrev ===
                                                    'DNI',
                                            }"
                                        />
                                        <Button
                                            v-if="
                                                selectedDocTypeAbrev === 'DNI'
                                            "
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            class="absolute right-0 h-full px-3 py-2 hover:bg-transparent"
                                            @click="buscarReniec"
                                            :disabled="
                                                isSearchingReniec ||
                                                form.docIdentidad?.length !== 8
                                            "
                                        >
                                            <Loader2
                                                v-if="isSearchingReniec"
                                                class="h-4 w-4 animate-spin text-muted-foreground"
                                            />
                                            <Search
                                                v-else
                                                class="h-4 w-4 text-muted-foreground"
                                            />
                                        </Button>
                                    </div>
                                    <p
                                        v-if="searchError"
                                        class="text-sm text-amber-500"
                                    >
                                        {{ searchError }}
                                    </p>
                                    <p
                                        v-if="form.errors.docIdentidad"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.docIdentidad }}
                                    </p>
                                </div>
                            </div>

                            <!-- Apellidos -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="grid gap-2">
                                    <Label for="paterno">Ap. Paterno *</Label>
                                    <Input
                                        id="paterno"
                                        v-model="form.paterno"
                                        placeholder="Apellido paterno"
                                    />
                                    <p
                                        v-if="form.errors.paterno"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.paterno }}
                                    </p>
                                </div>
                                <div class="grid gap-2">
                                    <Label for="materno">Ap. Materno *</Label>
                                    <Input
                                        id="materno"
                                        v-model="form.materno"
                                        placeholder="Apellido materno"
                                    />
                                    <p
                                        v-if="form.errors.materno"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.materno }}
                                    </p>
                                </div>
                            </div>

                            <!-- Nombres -->
                            <div class="grid gap-2">
                                <Label for="nombre">Nombres *</Label>
                                <Input
                                    id="nombre"
                                    v-model="form.nombre"
                                    placeholder="Nombres completos"
                                />
                                <p
                                    v-if="form.errors.nombre"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.nombre }}
                                </p>
                            </div>

                            <!-- Sexo + País -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <ParamSelect
                                    v-model="form.sexo_id"
                                    type="sexos"
                                    label="Sexo *"
                                    :error="form.errors.sexo_id"
                                />
                                <ParamSelect
                                    v-model="form.pais_id"
                                    type="paises"
                                    label="País *"
                                    :error="form.errors.pais_id"
                                    default-codigo="9589"
                                />
                            </div>

                            <!-- Fecha Nacimiento -->
                            <div class="grid gap-2">
                                <Label for="fechaNac">Fecha Nacimiento</Label>
                                <Input
                                    id="fechaNac"
                                    v-model="form.fechaNac"
                                    type="date"
                                />
                                <p
                                    v-if="form.errors.fechaNac"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.fechaNac }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: ACCESO AL SISTEMA (Compacto y Destacado) -->
                    <div
                        class="rounded-xl border border-blue-200/50 bg-blue-50/50 p-5 text-blue-900 shadow-xs dark:border-blue-900/50 dark:bg-blue-950/20 dark:text-blue-300"
                    >
                        <p
                            class="flex items-center gap-2 text-sm font-semibold"
                        >
                            <span
                                class="inline-block h-2.5 w-2.5 animate-pulse rounded-full bg-blue-500"
                            ></span>
                            Acceso al Sistema
                        </p>
                        <p
                            class="mt-2 text-xs leading-relaxed text-muted-foreground"
                        >
                            Se creará automáticamente una cuenta de usuario
                            asignada a este trabajador. Su
                            <strong>nombre de usuario</strong> y
                            <strong>contraseña inicial</strong> serán el número
                            de documento:
                            <code
                                class="ml-1 rounded bg-blue-100 px-2 py-0.5 font-mono font-bold text-blue-800 dark:bg-blue-900/60 dark:text-blue-200"
                                >{{ loginPreview }}</code
                            >.
                        </p>
                    </div>
                </div>

                <!-- Columna Derecha: Alta Laboral -->
                <div class="rounded-xl border bg-card p-6 shadow-xs">
                    <div
                        class="mb-4 flex items-center justify-between border-b pb-2"
                    >
                        <h2 class="text-lg font-semibold text-foreground">
                            Alta Laboral
                        </h2>
                        <div class="flex items-center space-x-2">
                            <input
                                id="incluir_alta"
                                type="checkbox"
                                v-model="showAlta"
                                class="h-4 w-4 cursor-pointer rounded border-input text-primary accent-primary focus:ring-primary"
                            />
                            <label
                                for="incluir_alta"
                                class="cursor-pointer text-sm font-medium text-foreground select-none"
                            >
                                Registrar Alta Laboral
                            </label>
                        </div>
                    </div>

                    <div
                        v-if="showAlta"
                        class="animate-in space-y-4 duration-200 fade-in-50"
                    >
                        <!-- IE -->
                        <div class="grid gap-2">
                            <Label>Institución Educativa *</Label>
                            <div class="relative">
                                <input
                                    v-model="ieQuery"
                                    type="text"
                                    placeholder="Buscar institución..."
                                    class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs placeholder:text-muted-foreground focus:ring-2 focus:ring-primary focus:outline-none"
                                />
                            </div>
                            <div
                                v-if="form.institucionEducativa_id"
                                class="text-xs text-muted-foreground"
                            >
                                IE seleccionada ID:
                                {{ form.institucionEducativa_id }}
                            </div>
                            <div
                                v-if="ieOptions.length && ieQuery"
                                class="max-h-48 animate-in overflow-y-auto rounded-md border bg-background shadow-lg duration-200 fade-in-50"
                            >
                                <button
                                    v-for="ie in ieOptions"
                                    :key="ie.id"
                                    type="button"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-accent hover:text-accent-foreground"
                                    :class="{
                                        'bg-primary/10 font-medium text-primary':
                                            form.institucionEducativa_id ===
                                            ie.id,
                                    }"
                                    @click="
                                        form.institucionEducativa_id = ie.id;
                                        ieQuery = ie.nombre;
                                        ieOptions = [];
                                    "
                                >
                                    {{ ie.nombre }}
                                </button>
                            </div>
                            <p
                                v-if="form.errors.institucionEducativa_id"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.institucionEducativa_id }}
                            </p>
                        </div>

                        <!-- Local de marcación (depende de la IE) -->
                        <div class="grid gap-2">
                            <LocalMarcacionSelect
                                label="Local de Marcación"
                                placeholder="Sin local de marcación"
                                :institucion-id="form.institucionEducativa_id"
                                v-model="form.localInstEduc_id"
                                :error="form.errors.localInstEduc_id"
                            />
                            <p class="text-xs text-muted-foreground">
                                Solo se listan los locales de la IE
                                seleccionada.
                            </p>
                        </div>

                        <!-- Código AIRSP + Condición Laboral -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="grid gap-2">
                                <Label>Código AIRSP</Label>
                                <Input
                                    v-model="form.codigoAirsp"
                                    placeholder="Ej: 28001234"
                                />
                                <p
                                    v-if="form.errors.codigoAirsp"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.codigoAirsp }}
                                </p>
                            </div>
                            <ParamSelect
                                type="condiciones-laborales"
                                label="Condición Laboral *"
                                v-model="form.condicionLaboral_id"
                                :error="form.errors.condicionLaboral_id"
                            />
                        </div>

                        <!-- Tipo de Contrato + Situación Laboral -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <ParamSelect
                                type="tipos-contrato"
                                label="Tipo de Contrato *"
                                v-model="form.tipoContrato_id"
                                :error="form.errors.tipoContrato_id"
                            />
                            <ParamSelect
                                type="situaciones-laborales"
                                label="Situación Laboral *"
                                v-model="form.situacionLaboral_id"
                                :error="form.errors.situacionLaboral_id"
                            />
                        </div>

                        <!-- Rol del Trabajador + Área -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <ParamSelect
                                type="roles-trabajador"
                                label="Rol del Trabajador"
                                v-model="form.rolTrabajador_id"
                                :error="form.errors.rolTrabajador_id"
                            />
                            <ParamSelect
                                type="areas"
                                label="Área *"
                                v-model="form.area_id"
                                :error="form.errors.area_id"
                            />
                        </div>

                        <!-- Cargo -->
                        <ParamSelect
                            type="cargos"
                            label="Cargo *"
                            v-model="form.cargo_id"
                            :error="form.errors.cargo_id"
                        />

                        <!-- Fechas (Inicio, Fin, Alta) -->
                        <div
                            class="grid grid-cols-1 gap-4 border-t pt-4 sm:grid-cols-3"
                        >
                            <div class="grid gap-2">
                                <Label>Fecha Inicio *</Label>
                                <Input
                                    v-model="form.fechaInicio"
                                    type="date"
                                    :class="{
                                        'border-destructive':
                                            form.errors.fechaInicio,
                                    }"
                                />
                                <p
                                    v-if="form.errors.fechaInicio"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.fechaInicio }}
                                </p>
                            </div>
                            <div class="grid gap-2">
                                <Label>Fecha Fin</Label>
                                <Input v-model="form.fechaFin" type="date" />
                                <p
                                    v-if="form.errors.fechaFin"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.fechaFin }}
                                </p>
                            </div>
                            <div class="grid gap-2">
                                <Label>Fecha Alta</Label>
                                <Input v-model="form.fechaAlta" type="date" />
                                <p
                                    v-if="form.errors.fechaAlta"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.fechaAlta }}
                                </p>
                            </div>
                        </div>

                        <!-- Observación -->
                        <div class="grid gap-2">
                            <Label>Observación</Label>
                            <textarea
                                v-model="form.observacion"
                                rows="2"
                                placeholder="Observaciones adicionales..."
                                class="flex min-h-[60px] w-full resize-none rounded-md border border-input bg-background px-3 py-2 text-sm shadow-xs placeholder:text-muted-foreground focus:ring-2 focus:ring-primary focus:outline-none"
                            />
                            <p
                                v-if="form.errors.observacion"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.observacion }}
                            </p>
                        </div>

                        <!-- Perfil -->
                        <div
                            class="rounded-lg border border-dashed border-primary/30 bg-primary/5 p-4"
                        >
                            <h3
                                class="mb-2 text-sm font-semibold text-foreground"
                            >
                                Perfil de Usuario en esta IE
                            </h3>
                            <p
                                class="mb-3 text-xs leading-relaxed text-muted-foreground"
                            >
                                Asigne el rol y permisos que tendrá el usuario
                                en esta institución educativa.
                            </p>
                            <div class="grid gap-2">
                                <Label>Perfil</Label>
                                <ParamSelect
                                    type="perfiles"
                                    v-model="form.perfil_id"
                                    placeholder="Seleccionar perfil..."
                                    :error="form.errors.perfil_id"
                                />
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-center rounded-lg border border-dashed bg-muted/20 py-16 text-center"
                    >
                        <p class="text-sm font-medium text-muted-foreground">
                            Alta laboral omitida
                        </p>
                        <p
                            class="mt-1 max-w-[240px] text-xs text-muted-foreground/80"
                        >
                            El trabajador se creará sin vinculación inicial a
                            una Institución Educativa.
                        </p>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex w-full justify-end gap-2">
                    <Button
                        type="button"
                        variant="outline"
                        @click="showRegisterModal = false"
                    >
                        Cancelar
                    </Button>

                    <Button type="submit" :disabled="form.processing">
                        <UserPlus class="mr-2 h-4 w-4" />
                        {{
                            form.processing
                                ? 'Registrando...'
                                : 'Registrar Trabajador'
                        }}
                    </Button>
                </div>
            </template>
        </FormModal>

        <!-- Modal Carga Masiva Grid -->
        <TrabajadorMasivoGrid
            v-if="showMasivoModal"
            @close="showMasivoModal = false"
            @success="onMasivoSuccess"
        />
    </div>
</template>

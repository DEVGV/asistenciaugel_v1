<script setup lang="ts">
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Plus,
    Pencil,
    Trash2,
    School,
    BookOpen,
    GraduationCap,
    LayoutGrid,
    ChevronDown,
    MapPin,
    Clock,
    Smartphone,
    UserPlus,
    Calendar,
    Users,
    Upload,
    Search,
    ShieldCheck,
    CalendarOff,
    ClipboardCheck,
    Sparkles,
    Loader2,
} from 'lucide-vue-next';
import { ref, watch, computed } from 'vue';
import HorarioTrabajadorController from '@/actions/App/Http/Controllers/Horario/HorarioTrabajadorController';
import LocalController from '@/actions/App/Http/Controllers/Infraestructura/LocalController';
import LocalInstEducController from '@/actions/App/Http/Controllers/Infraestructura/LocalInstEducController';
import LocalMarcacionController from '@/actions/App/Http/Controllers/Infraestructura/LocalMarcacionController';
import RelojController from '@/actions/App/Http/Controllers/Infraestructura/RelojController';
import CursoIEController from '@/actions/App/Http/Controllers/InstitucionEducativa/CursoIEController';
import DiasNoLaborablesController from '@/actions/App/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController';
import GradoIEController from '@/actions/App/Http/Controllers/InstitucionEducativa/GradoIEController';
import InstitucionEducativaController from '@/actions/App/Http/Controllers/InstitucionEducativa/InstitucionEducativaController';
import SeccionIEController from '@/actions/App/Http/Controllers/InstitucionEducativa/SeccionIEController';
import AltaMasivaGrid from '@/components/institucion-educativa/AltaMasivaGrid.vue';
import CursosMasivaGrid from '@/components/institucion-educativa/CursosMasivaGrid.vue';
import GradosMasivaGrid from '@/components/institucion-educativa/GradosMasivaGrid.vue';
import PermisosIETab from '@/components/institucion-educativa/PermisosIETab.vue';
import RelojesMasivaGrid from '@/components/institucion-educativa/RelojesMasivaGrid.vue';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import GestionUsuarioModal from '@/components/shared/GestionUsuarioModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import ZonaSelect from '@/components/shared/ZonaSelect.vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
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
import type { LocalInstEduc, Reloj } from '@/types/models/infraestructura';
import type {
    InstitucionEducativa,
    CursoIE,
    DiasNoLaborable,
    GradoIE,
    SeccionIE,
} from '@/types/models/institucion-educativa';
import type {
    AltaTrabajador,
    PaginatedResponse,
} from '@/types/models/trabajador';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Instituciones Educativas',
                href: InstitucionEducativaController.index().url,
            },
            { title: 'Detalle', href: '#' },
        ],
    },
});

const props = defineProps<{
    institucion: InstitucionEducativa;
    docentes?: PaginatedResponse<AltaTrabajador>;
    docentesFiltros?: {
        search?: string;
        solo_activas?: boolean;
        condicion_id?: number;
    };
    activeTab?: string;
}>();

const activeTab = ref<
    'datos' | 'cursos' | 'grados' | 'locales' | 'relojes' | 'docentes' | 'diasNoLaborables' | 'permisos'
>((props.activeTab as any) ?? 'datos');

// ─── Sub-recurso Modal (compartido para cursos, grados, secciones) ───
const showSubModal = ref(false);
const subModalType = ref<'curso' | 'grado' | 'seccion'>('curso');
const subIsEditing = ref(false);
const subEditingId = ref<number | null>(null);
const subParentId = ref<number | null>(null);

const subForm = useForm({ nombre: '', sigla: '', activo: true });

watch(showSubModal, (val) => {
    if (!val) {
        subForm.reset();
        subForm.clearErrors();
    }
});

function openSubCreate(type: 'curso' | 'grado' | 'seccion', parentId?: number) {
    subModalType.value = type;
    subIsEditing.value = false;
    subEditingId.value = null;
    subParentId.value = parentId ?? null;
    subForm.reset();
    subForm.clearErrors();
    showSubModal.value = true;
}

function openSubEdit(
    type: 'curso' | 'grado' | 'seccion',
    item: CursoIE | GradoIE | SeccionIE,
) {
    subModalType.value = type;
    subIsEditing.value = true;
    subEditingId.value = item.id;
    subForm.clearErrors();
    subForm.nombre = item.nombre;
    subForm.sigla = item.sigla || '';
    subForm.activo = item.activo;
    showSubModal.value = true;
}

function submitSub() {
    const ieId = props.institucion.id;
    let targetUrl = '';

    if (subIsEditing.value && subEditingId.value) {
        if (subModalType.value === 'curso') {
            targetUrl = CursoIEController.update({
                curso: subEditingId.value,
            }).url;
        } else if (subModalType.value === 'grado') {
            targetUrl = GradoIEController.update({
                grado: subEditingId.value,
            }).url;
        } else {
            targetUrl = SeccionIEController.update({
                seccione: subEditingId.value,
            }).url;
        }

        subForm.put(targetUrl, {
            onSuccess: () => {
                showSubModal.value = false;
            },
        });
    } else {
        if (subModalType.value === 'curso') {
            targetUrl = CursoIEController.store({ institucione: ieId }).url;
        } else if (subModalType.value === 'grado') {
            targetUrl = GradoIEController.store({ institucione: ieId }).url;
        } else {
            targetUrl = SeccionIEController.store({
                grado: subParentId.value!,
            }).url;
        }

        subForm.post(targetUrl, {
            onSuccess: () => {
                showSubModal.value = false;
            },
        });
    }
}

// ─── Eliminar sub-recurso ───
const showDeleteSub = ref(false);
const deleteSubType = ref<'curso' | 'grado' | 'seccion'>('curso');
const deleteSubId = ref<number | null>(null);
const deleteSubName = ref('');
const isDeletingSub = ref(false);

function confirmDeleteSub(
    type: 'curso' | 'grado' | 'seccion',
    id: number,
    name: string,
) {
    deleteSubType.value = type;
    deleteSubId.value = id;
    deleteSubName.value = name;
    showDeleteSub.value = true;
}

function executeDeleteSub() {
    if (!deleteSubId.value) {
        return;
    }

    isDeletingSub.value = true;
    let targetUrl = '';

    if (deleteSubType.value === 'curso') {
        targetUrl = CursoIEController.destroy({ curso: deleteSubId.value }).url;
    } else if (deleteSubType.value === 'grado') {
        targetUrl = GradoIEController.destroy({ grado: deleteSubId.value }).url;
    } else {
        targetUrl = SeccionIEController.destroy({
            seccione: deleteSubId.value,
        }).url;
    }

    router.delete(targetUrl, {
        onSuccess: () => {
            showDeleteSub.value = false;
            deleteSubId.value = null;
        },
        onFinish: () => {
            isDeletingSub.value = false;
        },
    });
}

const subModalTitles: Record<string, Record<string, string>> = {
    curso: { create: 'Nuevo Curso', edit: 'Editar Curso' },
    grado: { create: 'Nuevo Grado', edit: 'Editar Grado' },
    seccion: { create: 'Nueva Sección', edit: 'Editar Sección' },
};

// ─── Infraestructura: Crear Local y asignar a IE ───
const showLocalModal = ref(false);
const isEditingLocal = ref(false);
const editingLocalId = ref<number | null>(null);
const localSelectedDepartamento = ref<number | null>(null);
const localSelectedProvincia = ref<number | null>(null);
const localSelectedDistrito = ref<number | null>(null);
const isInitializingLocal = ref(false);

const localForm = useForm({
    nombre: '',
    domicilio: '',
    zona_id: null as number | null,
    ubigeo: '',
    utm_huso: '' as string | number,
    utm_banda: '',
    utm_x_este: '' as string | number,
    utm_y_norte: '' as string | number,
    fechaInicio: '',
    fechaFin: '',
});

watch(showLocalModal, (val) => {
    if (!val) {
        localForm.reset();
        localForm.clearErrors();
        localSelectedDepartamento.value = null;
        localSelectedProvincia.value = null;
        localSelectedDistrito.value = null;
    }
});

function openLocalCreate() {
    isInitializingLocal.value = false;
    isEditingLocal.value = false;
    editingLocalId.value = null;
    localForm.reset();
    localForm.clearErrors();
    localSelectedDepartamento.value = null;
    localSelectedProvincia.value = null;
    localSelectedDistrito.value = null;
    showLocalModal.value = true;
}

function openLocalEdit(lie: any) {
    isInitializingLocal.value = true;
    isEditingLocal.value = true;
    editingLocalId.value = lie.local.id;
    localForm.clearErrors();
    localForm.nombre = lie.local.nombre || '';
    localForm.domicilio = lie.local.domicilio || '';
    localForm.zona_id = lie.local.zona_id || null;
    localForm.ubigeo = lie.local.ubigeo || '';
    localForm.utm_huso = lie.local.utm_huso || '';
    localForm.utm_banda = lie.local.utm_banda || '';
    localForm.utm_x_este = lie.local.utm_x_este || '';
    localForm.utm_y_norte = lie.local.utm_y_norte || '';
    localForm.fechaInicio = lie.fechaInicio || '';
    localForm.fechaFin = lie.fechaFin || '';

    localSelectedDepartamento.value = null;
    localSelectedProvincia.value = null;
    localSelectedDistrito.value = null;

    if (localForm.ubigeo) {
        fetch(`/api/params/ubigeo/${localForm.ubigeo}`)
            .then((res) => res.json())
            .then((data) => {
                if (
                    data.departamento_id &&
                    data.provincia_id &&
                    data.distrito_id
                ) {
                    localSelectedDepartamento.value = data.departamento_id;
                    localSelectedProvincia.value = data.provincia_id;
                    localSelectedDistrito.value = data.distrito_id;

                    // Restauramos zona_id después de que carguen los combos para no ser pisados
                    setTimeout(() => {
                        localForm.zona_id = lie.local.zona_id || null;
                        isInitializingLocal.value = false;
                    }, 150);
                } else {
                    isInitializingLocal.value = false;
                }
            })
            .catch((err) => {
                console.error('Error fetching reverse ubigeo:', err);
                isInitializingLocal.value = false;
            });
    } else {
        isInitializingLocal.value = false;
    }

    showLocalModal.value = true;
}

function submitLocal() {
    if (isEditingLocal.value && editingLocalId.value) {
        localForm.put(
            LocalController.update({ local: editingLocalId.value }).url,
            {
                onSuccess: () => {
                    showLocalModal.value = false;
                },
            },
        );
    } else {
        localForm.post(
            LocalInstEducController.store({
                institucione: props.institucion.id,
            }).url,
            {
                onSuccess: () => {
                    showLocalModal.value = false;
                },
            },
        );
    }
}

// ─── Infraestructura: Desasignar Local ───
const showDeleteLocal = ref(false);
const deleteLocalId = ref<number | null>(null);
const deleteLocalName = ref('');
const isDeletingLocal = ref(false);

function confirmDeleteLocal(localIe: LocalInstEduc) {
    deleteLocalId.value = localIe.id;
    deleteLocalName.value = localIe.local?.nombre || 'Local';
    showDeleteLocal.value = true;
}

function executeDeleteLocal() {
    if (!deleteLocalId.value) {
        return;
    }

    isDeletingLocal.value = true;
    router.delete(
        LocalInstEducController.destroy({ localesIe: deleteLocalId.value }).url,
        {
            onSuccess: () => {
                showDeleteLocal.value = false;
                deleteLocalId.value = null;
            },
            onFinish: () => {
                isDeletingLocal.value = false;
            },
        },
    );
}

// ─── Infraestructura: Relojes ───
const showRelojModal = ref(false);
const relojIsEditing = ref(false);
const relojEditingId = ref<number | null>(null);
const relojParentId = ref<number | null>(null);

const relojForm = useForm({
    nombre: '',
    dreccionIP: '',
    direccionMac: '',
    puerto: '' as string | number,
    serie: '',
    idBiometrico: '' as string | number,
    activo: true,
});

watch(showRelojModal, (val) => {
    if (!val) {
        relojForm.reset();
        relojForm.clearErrors();
    }
});

function openRelojCreate(localInstEducId: number) {
    relojIsEditing.value = false;
    relojEditingId.value = null;
    relojParentId.value = localInstEducId;
    relojForm.reset();
    relojForm.clearErrors();
    showRelojModal.value = true;
}

function openRelojEdit(reloj: Reloj) {
    relojIsEditing.value = true;
    relojEditingId.value = reloj.id;
    relojForm.clearErrors();
    relojForm.nombre = reloj.nombre || '';
    relojForm.dreccionIP = reloj.dreccionIP || '';
    relojForm.direccionMac = reloj.direccionMac || '';
    relojForm.puerto = reloj.puerto || '';
    relojForm.serie = reloj.serie || '';
    relojForm.idBiometrico = reloj.idBiometrico || '';
    relojForm.activo = reloj.activo ?? true;
    showRelojModal.value = true;
}

function submitReloj() {
    const submitter = relojForm.transform((data) => ({
        ...data,
        puerto: data.puerto === '' ? null : data.puerto,
        idBiometrico: data.idBiometrico === '' ? null : data.idBiometrico,
    }));

    if (relojIsEditing.value && relojEditingId.value) {
        submitter.put(
            RelojController.update({ reloje: relojEditingId.value }).url,
            {
                onSuccess: () => {
                    showRelojModal.value = false;
                },
            },
        );
    } else {
        submitter.post(
            RelojController.store({ localesIe: relojParentId.value! }).url,
            {
                onSuccess: () => {
                    showRelojModal.value = false;
                },
            },
        );
    }
}

// ─── Eliminar Reloj ───
const showDeleteReloj = ref(false);
const deleteRelojId = ref<number | null>(null);
const deleteRelojName = ref('');
const isDeletingReloj = ref(false);

function confirmDeleteReloj(reloj: Reloj) {
    deleteRelojId.value = reloj.id;
    deleteRelojName.value = reloj.nombre || 'Reloj';
    showDeleteReloj.value = true;
}

function executeDeleteReloj() {
    if (!deleteRelojId.value) {
        return;
    }

    isDeletingReloj.value = true;
    router.delete(
        RelojController.destroy({ reloje: deleteRelojId.value }).url,
        {
            onSuccess: () => {
                showDeleteReloj.value = false;
                deleteRelojId.value = null;
            },
            onFinish: () => {
                isDeletingReloj.value = false;
            },
        },
    );
}

// ─── Tab Docentes / Personal ───────────────────────────────────────────────
const docenteSearch = ref(props.docentesFiltros?.search || '');
const docenteSoloActivas = ref(props.docentesFiltros?.solo_activas ?? false);
let docenteTimeout: ReturnType<typeof setTimeout>;

const docentesUrl = computed(
    () => `/instituciones/${props.institucion.id}/docentes`,
);

function cargarDocentes(extraParams: Record<string, any> = {}) {
    router.get(
        docentesUrl.value,
        {
            search: docenteSearch.value || undefined,
            solo_activas: docenteSoloActivas.value ? '1' : undefined,
            ...extraParams,
        },
        { preserveState: true, replace: true },
    );
}

watch(docenteSearch, () => {
    clearTimeout(docenteTimeout);
    docenteTimeout = setTimeout(() => cargarDocentes(), 350);
});

watch(docenteSoloActivas, () => cargarDocentes());

function switchToDocentes() {
    activeTab.value = 'docentes';

    if (!props.docentes) {
        cargarDocentes();
    }
}

// ─── Carga Masiva Grid (Altas) ────────────────────────────────────────────────
const showMasivaModal = ref(false);

function onMasivaSuccess(_count: number) {
    showMasivaModal.value = false;
    setTimeout(() => cargarDocentes(), 400);
}

// ─── Modal Gestión Usuario ────────────────────────────────────────────────────
const showUsuarioModal = ref(false);
const usuarioModalTrabajadorId = ref<number | null>(null);
const usuarioModalNombre = ref('');

function openUsuarioModal(trabajadorId: number, nombre: string) {
    usuarioModalTrabajadorId.value = trabajadorId;
    usuarioModalNombre.value = nombre;
    showUsuarioModal.value = true;
}

// ─── Carga Masiva Grados y Secciones ─────────────────────────────────────────
const showGradosMasivaModal = ref(false);

function onGradosMasivaSuccess(_grados: number, _secciones: number) {
    showGradosMasivaModal.value = false;
    router.reload({ only: ['institucion'] });
}

// ─── Carga Masiva Cursos ──────────────────────────────────────────────────────
const showCursosMasivaModal = ref(false);

function onCursosMasivaSuccess(_count: number) {
    showCursosMasivaModal.value = false;
    router.reload({ only: ['institucion'] });
}

// ─── Carga Masiva Relojes ─────────────────────────────────────────────────────
const relojesMasivaLocalId = ref<number | null>(null);
const relojesMasivaLocalNombre = ref<string>('');

function openRelojesMasiva(localId: number, localNombre: string) {
    relojesMasivaLocalId.value = localId;
    relojesMasivaLocalNombre.value = localNombre;
}

function onRelojesMasivaSuccess(_count: number) {
    relojesMasivaLocalId.value = null;
    router.reload({ only: ['institucion'] });
}

const tabs = [
    { key: 'datos', label: 'Datos Generales', icon: School },
    { key: 'cursos', label: 'Cursos', icon: BookOpen },
    { key: 'grados', label: 'Grados y Secciones', icon: GraduationCap },
    { key: 'locales', label: 'Locales', icon: MapPin },
    { key: 'relojes', label: 'Relojes', icon: Clock },
    { key: 'docentes', label: 'Docentes / Personal', icon: Users },
    { key: 'diasNoLaborables', label: 'Días No Laborables', icon: CalendarOff },
    { key: 'permisos', label: 'Permisos', icon: ClipboardCheck },
] as const;

// ─── Días No Laborables ────────────────────────────────────────────────────────
const showDiasModal = ref(false);
const diasIsEditing = ref(false);
const diasEditingId = ref<number | null>(null);
const diasIsGenerating = ref(false);
const diasGenerarAnio = ref(new Date().getFullYear());
const diasGenerarMsg = ref('');

const diasForm = useForm({
    institucionEduc_id: props.institucion.id,
    fecha: '',
    feriado_id: null as number | null,
    observacion: '',
    nacionalLocal: 'N' as 'N' | 'L',
    recuperable: 'N' as 'S' | 'N',
});

watch(showDiasModal, (val) => {
    if (!val) {
        diasForm.reset();
        diasForm.clearErrors();
        diasForm.institucionEduc_id = props.institucion.id;
        diasIsEditing.value = false;
        diasEditingId.value = null;
    }
});

function openDiasCreate() {
    diasIsEditing.value = false;
    diasEditingId.value = null;
    diasForm.reset();
    diasForm.clearErrors();
    diasForm.institucionEduc_id = props.institucion.id;
    diasForm.nacionalLocal = 'N';
    diasForm.recuperable = 'N';
    showDiasModal.value = true;
}

function openDiasEdit(dia: DiasNoLaborable) {
    diasIsEditing.value = true;
    diasEditingId.value = dia.id;
    diasForm.clearErrors();
    diasForm.institucionEduc_id = props.institucion.id;
    diasForm.fecha = dia.fecha;
    diasForm.feriado_id = dia.feriado_id ?? null;
    diasForm.observacion = dia.observacion ?? '';
    diasForm.nacionalLocal = (dia.nacionalLocal as 'N' | 'L') ?? 'N';
    diasForm.recuperable = (dia.recuperable as 'S' | 'N') ?? 'N';
    showDiasModal.value = true;
}

function submitDias() {
    if (diasIsEditing.value && diasEditingId.value) {
        diasForm.put(
            DiasNoLaborablesController.update({ diasNoLaborable: diasEditingId.value }).url,
            { onSuccess: () => {
 showDiasModal.value = false; 
} },
        );
    } else {
        diasForm.post(
            DiasNoLaborablesController.store({ institucione: props.institucion.id }).url,
            { onSuccess: () => {
 showDiasModal.value = false; 
} },
        );
    }
}

const showDeleteDias = ref(false);
const deleteDiasId = ref<number | null>(null);
const deleteDiasLabel = ref('');
const isDeletingDias = ref(false);

function confirmDeleteDias(dia: DiasNoLaborable) {
    deleteDiasId.value = dia.id;
    deleteDiasLabel.value = dia.fecha;
    showDeleteDias.value = true;
}

function executeDeleteDias() {
    if (!deleteDiasId.value) {
return;
}

    isDeletingDias.value = true;
    router.delete(
        DiasNoLaborablesController.destroy({ diasNoLaborable: deleteDiasId.value }).url,
        {
            onSuccess: () => {
 showDeleteDias.value = false; deleteDiasId.value = null; 
},
            onFinish: () => {
 isDeletingDias.value = false; 
},
        },
    );
}

async function generarFeriados() {
    diasIsGenerating.value = true;
    diasGenerarMsg.value = '';

    try {
        const url = DiasNoLaborablesController.generarFeriados(
            { institucione: props.institucion.id },
            { query: { anio: diasGenerarAnio.value } },
        ).url;
        const res = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        });
        const data = await res.json();
        diasGenerarMsg.value = data.message ?? '';
        router.reload({ only: ['institucion'] });
    } finally {
        diasIsGenerating.value = false;
    }
}
</script>

<template>
    <Head :title="`IE: ${props.institucion.nombreLegal}`" />

    <div class="flex flex-col gap-4 p-4">
        <!-- Encabezado -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    {{ props.institucion.nombreLegal }}
                </h1>
                <p class="text-sm text-muted-foreground">
                    Código:
                    <span class="font-semibold text-primary">{{
                        props.institucion.codigoInstitucion
                    }}</span>
                    <span v-if="props.institucion.codigoModular" class="ml-2">
                        · Modular:
                        <span class="font-semibold">{{
                            props.institucion.codigoModular
                        }}</span>
                    </span>
                </p>
            </div>
            <Button variant="outline" as-child size="sm">
                <Link :href="InstitucionEducativaController.index().url">
                    <ArrowLeft class="mr-2 h-4 w-4" /> Volver
                </Link>
            </Button>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap gap-1 border-b">
            <button
                v-for="tab in tabs"
                :key="tab.key"
                @click="
                    tab.key === 'docentes'
                        ? switchToDocentes()
                        : (activeTab = (tab.key as typeof activeTab.value))
                "
                :class="[
                    '-mb-px flex items-center gap-2 border-b-2 px-4 py-2.5 text-sm font-medium transition-colors',
                    activeTab === tab.key
                        ? 'border-primary text-primary'
                        : 'border-transparent text-muted-foreground hover:border-muted-foreground/50 hover:text-foreground',
                ]"
            >
                <component :is="tab.icon" class="h-4 w-4" />
                {{ tab.label }}
            </button>
        </div>

        <!-- Tab: Datos Generales -->
        <div
            v-if="activeTab === 'datos'"
            class="rounded-xl border bg-card p-6 shadow-xs"
        >
            <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
                <div>
                    <span class="text-muted-foreground">Tipo Institución:</span>
                    <p class="font-medium">
                        {{ props.institucion.tipo_inst_educ?.nombre || '-' }}
                    </p>
                </div>
                <div>
                    <span class="text-muted-foreground"
                        >Régimen Educativo:</span
                    >
                    <p class="font-medium">
                        {{ props.institucion.regimen_educ?.nombre || '-' }}
                    </p>
                </div>
                <div>
                    <span class="text-muted-foreground"
                        >Modalidad Formativa:</span
                    >
                    <p class="font-medium">
                        {{
                            props.institucion.modalidad_formativa?.nombre || '-'
                        }}
                    </p>
                </div>
                <div>
                    <span class="text-muted-foreground">Nivel / Ciclo:</span>
                    <p class="font-medium">
                        {{ props.institucion.nivel_ciclo?.nombre || '-' }}
                    </p>
                </div>
                <div>
                    <span class="text-muted-foreground">UGEL:</span>
                    <p class="font-medium">
                        {{ props.institucion.entidad_ugel?.razonSocial || '-' }}
                    </p>
                </div>
                <div>
                    <span class="text-muted-foreground">Entidad Admin:</span>
                    <p class="font-medium">
                        {{
                            props.institucion.entidad_admin?.razonSocial || '-'
                        }}
                    </p>
                </div>
                <div>
                    <span class="text-muted-foreground">Fecha Inicio:</span>
                    <p class="font-medium">
                        {{ props.institucion.fechaInicio || '-' }}
                    </p>
                </div>
                <div>
                    <span class="text-muted-foreground">Fecha Fin:</span>
                    <p class="font-medium">
                        {{ props.institucion.fechaFin || '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Tab: Cursos -->
        <div v-if="activeTab === 'cursos'" class="space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Cursos</h2>
                <div class="flex items-center gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="showCursosMasivaModal = true"
                        class="gap-1.5 border-indigo-200 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 dark:border-indigo-900/50 dark:text-indigo-400 dark:hover:bg-indigo-950/20"
                    >
                        <Upload class="h-4 w-4" />
                        Carga Masiva
                    </Button>
                    <Button size="sm" @click="openSubCreate('curso')"
                        ><Plus class="mr-2 h-4 w-4" /> Nuevo Curso</Button
                    >
                </div>
            </div>
            <div class="overflow-hidden rounded-md border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">Código</TableHead>
                            <TableHead>Nombre</TableHead>
                            <TableHead class="w-[100px]">Sigla</TableHead>
                            <TableHead class="w-[80px]">Estado</TableHead>
                            <TableHead class="w-[100px] text-right"
                                >Acciones</TableHead
                            >
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="curso in props.institucion.cursos || []"
                            :key="curso.id"
                        >
                            <TableCell class="text-xs font-medium">{{
                                curso.codigo || '-'
                            }}</TableCell>
                            <TableCell class="text-sm">{{
                                curso.nombre
                            }}</TableCell>
                            <TableCell class="text-xs">{{
                                curso.sigla || '-'
                            }}</TableCell>
                            <TableCell
                                ><StatusBadge :active="curso.activo"
                            /></TableCell>
                            <TableCell class="text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child
                                        ><Button
                                            variant="ghost"
                                            size="sm"
                                            class="h-7"
                                            ><ChevronDown
                                                class="h-4 w-4" /></Button
                                    ></DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem
                                            @click="openSubEdit('curso', curso)"
                                            ><Pencil class="mr-2 h-4 w-4" />
                                            Editar</DropdownMenuItem
                                        >
                                        <DropdownMenuItem
                                            @click="
                                                confirmDeleteSub(
                                                    'curso',
                                                    curso.id,
                                                    curso.nombre,
                                                )
                                            "
                                            class="text-destructive"
                                            ><Trash2 class="mr-2 h-4 w-4" />
                                            Desactivar</DropdownMenuItem
                                        >
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!props.institucion.cursos?.length"
                            ><TableCell
                                colspan="5"
                                class="h-20 text-center text-muted-foreground"
                                >No hay cursos registrados.</TableCell
                            ></TableRow
                        >
                    </TableBody>
                </Table>
            </div>
        </div>

        <!-- Tab: Grados y Secciones -->
        <div v-if="activeTab === 'grados'" class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Grados y Secciones</h2>
                <div class="flex items-center gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="showGradosMasivaModal = true"
                        class="gap-1.5 border-indigo-200 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 dark:border-indigo-900/50 dark:text-indigo-400 dark:hover:bg-indigo-950/20"
                    >
                        <Upload class="h-4 w-4" />
                        Carga Masiva
                    </Button>
                    <Button size="sm" @click="openSubCreate('grado')"
                        ><Plus class="mr-2 h-4 w-4" /> Nuevo Grado</Button
                    >
                </div>
            </div>
            <div
                v-for="grado in props.institucion.grados || []"
                :key="grado.id"
                class="overflow-hidden rounded-lg border bg-card"
            >
                <div
                    class="flex items-center justify-between border-b bg-muted/30 px-4 py-2.5"
                >
                    <div class="flex items-center gap-2">
                        <GraduationCap class="h-4.5 w-4.5 text-primary" />
                        <span class="text-sm font-semibold text-foreground">{{
                            grado.nombre
                        }}</span>
                        <span
                            v-if="grado.codigo"
                            class="text-xs text-muted-foreground"
                            >({{ grado.codigo }})</span
                        >
                        <StatusBadge :active="grado.activo" />
                    </div>
                    <div class="flex items-center gap-1.5">
                        <Button
                            variant="outline"
                            size="sm"
                            class="h-7 border-emerald-200 bg-emerald-50/30 text-xs text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-950/20 dark:text-emerald-400"
                            @click="openSubCreate('seccion', grado.id)"
                        >
                            <Plus class="mr-1 h-3.5 w-3.5 text-emerald-500" />
                            <span>Sección</span>
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            class="h-7 w-7 p-0 text-amber-500 hover:bg-amber-50 hover:text-amber-600 dark:text-amber-400 dark:hover:bg-amber-950/20"
                            @click="openSubEdit('grado', grado)"
                            title="Editar Grado"
                        >
                            <Pencil class="h-3.5 w-3.5" />
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            class="h-7 w-7 p-0 text-rose-500 hover:bg-rose-50 hover:text-rose-600 dark:text-rose-400 dark:hover:bg-rose-950/20"
                            @click="
                                confirmDeleteSub(
                                    'grado',
                                    grado.id,
                                    grado.nombre,
                                )
                            "
                            title="Eliminar Grado"
                        >
                            <Trash2 class="h-3.5 w-3.5" />
                        </Button>
                    </div>
                </div>
                <div
                    v-if="grado.secciones?.length"
                    class="border-t bg-muted/5 p-4"
                >
                    <div
                        class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3"
                    >
                        <div
                            v-for="sec in grado.secciones"
                            :key="sec.id"
                            class="flex items-center justify-between gap-4 rounded-lg border bg-background p-2.5 shadow-xs transition-all hover:shadow-md dark:border-muted/30"
                        >
                            <div class="flex min-w-0 items-center gap-2.5">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400"
                                >
                                    <LayoutGrid class="h-4 w-4" />
                                </div>
                                <div class="truncate">
                                    <p
                                        class="truncate text-sm font-bold text-foreground"
                                    >
                                        Sección {{ sec.nombre }}
                                    </p>
                                    <p
                                        v-if="sec.sigla"
                                        class="truncate text-[11px] text-muted-foreground"
                                    >
                                        Sigla: {{ sec.sigla }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-1.5">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    class="h-8 gap-1.5 border-blue-100 text-xs text-blue-600 hover:bg-blue-50/50 hover:text-blue-700 dark:border-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-950/20 dark:hover:text-blue-300"
                                    as-child
                                >
                                    <Link
                                        :href="
                                            HorarioTrabajadorController.index({
                                                query: {
                                                    ie_id: props.institucion.id,
                                                    grado_id: grado.id,
                                                    seccion_id: sec.id,
                                                },
                                            }).url
                                        "
                                    >
                                        <Calendar
                                            class="h-3.5 w-3.5 text-blue-500 dark:text-blue-400"
                                        />
                                        <span>Ver Horario</span>
                                    </Link>
                                </Button>

                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="h-8 w-8 text-amber-500 hover:bg-amber-50 hover:text-amber-600 dark:text-amber-400 dark:hover:bg-amber-950/20"
                                    @click="openSubEdit('seccion', sec)"
                                    title="Editar"
                                >
                                    <Pencil class="h-3.5 w-3.5" />
                                </Button>

                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="h-8 w-8 text-rose-500 hover:bg-rose-50 hover:text-rose-600 dark:text-rose-400 dark:hover:bg-rose-950/20"
                                    @click="
                                        confirmDeleteSub(
                                            'seccion',
                                            sec.id,
                                            sec.nombre,
                                        )
                                    "
                                    title="Eliminar"
                                >
                                    <Trash2 class="h-3.5 w-3.5" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-else
                    class="bg-muted/5 p-4 text-center text-xs text-muted-foreground"
                >
                    Sin secciones registradas.
                </div>
            </div>
            <div
                v-if="!props.institucion.grados?.length"
                class="rounded-md border p-8 text-center text-muted-foreground"
            >
                No hay grados registrados.
            </div>
        </div>

        <!-- Tab: Locales -->
        <div v-if="activeTab === 'locales'" class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Locales Asignados</h2>
                <Button size="sm" @click="openLocalCreate()"
                    ><Plus class="mr-2 h-4 w-4" /> Asignar Local</Button
                >
            </div>

            <div
                v-for="lie in props.institucion.locales_inst_educ || []"
                :key="lie.id"
                class="overflow-hidden rounded-lg border bg-card"
            >
                <div
                    class="flex items-center justify-between border-b bg-muted/30 px-4 py-2.5"
                >
                    <div class="flex items-center gap-2">
                        <MapPin class="h-4 w-4 text-primary" />
                        <span class="text-sm font-semibold">{{
                            lie.local?.nombre || 'Sin nombre'
                        }}</span>
                        <span
                            v-if="lie.local?.zona"
                            class="text-xs text-muted-foreground"
                            >· {{ lie.local.zona.nombre }}</span
                        >
                        <span
                            v-if="lie.fechaInicio"
                            class="text-xs text-muted-foreground"
                            >· Desde: {{ lie.fechaInicio }}</span
                        >
                    </div>
                    <div class="flex gap-1">
                        <Button
                            variant="ghost"
                            size="sm"
                            class="h-7"
                            @click="openLocalEdit(lie)"
                        >
                            <Pencil class="h-3.5 w-3.5" />
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            class="h-7 text-destructive"
                            @click="confirmDeleteLocal(lie)"
                        >
                            <Trash2 class="h-3.5 w-3.5" />
                        </Button>
                    </div>
                </div>
                <div class="space-y-3 p-4">
                    <div
                        v-if="lie.local?.domicilio"
                        class="text-sm text-muted-foreground"
                    >
                        <span class="font-medium text-foreground"
                            >Domicilio:</span
                        >
                        {{ lie.local.domicilio }}
                    </div>

                    <!-- Trabajadores de Marcación -->
                    <div v-if="lie.locales_marcacion?.length" class="space-y-1">
                        <p
                            class="text-xs font-semibold tracking-wide text-muted-foreground uppercase"
                        >
                            Trabajadores de Marcación
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <div
                                v-for="lm in lie.locales_marcacion"
                                :key="lm.id"
                                class="flex items-center gap-2 rounded-md border bg-background px-3 py-1.5 text-sm"
                            >
                                <UserPlus
                                    class="h-3.5 w-3.5 text-muted-foreground"
                                />
                                <span class="font-medium">
                                    {{ lm.trabajador?.persona?.paterno }}
                                    {{ lm.trabajador?.persona?.materno }},
                                    {{ lm.trabajador?.persona?.nombre }}
                                </span>
                                <span
                                    v-if="lm.fechaInicio"
                                    class="text-xs text-muted-foreground"
                                    >({{ lm.fechaInicio }})</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Relojes del Local -->
                    <div v-if="lie.relojes?.length" class="space-y-1">
                        <p
                            class="text-xs font-semibold tracking-wide text-muted-foreground uppercase"
                        >
                            Relojes Biométricos
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <div
                                v-for="reloj in lie.relojes"
                                :key="reloj.id"
                                class="flex items-center gap-2 rounded-md border bg-background px-3 py-1.5 text-sm"
                            >
                                <Clock
                                    class="h-3.5 w-3.5 text-muted-foreground"
                                />
                                <span class="font-medium">{{
                                    reloj.nombre || 'Sin nombre'
                                }}</span>
                                <span
                                    v-if="reloj.dreccionIP"
                                    class="text-xs text-muted-foreground"
                                    >{{ reloj.dreccionIP }}</span
                                >
                                <StatusBadge :active="reloj.activo ?? false" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="!props.institucion.locales_inst_educ?.length"
                class="rounded-md border p-8 text-center text-muted-foreground"
            >
                No hay locales asignados a esta institución.
            </div>
        </div>

        <!-- Tab: Relojes -->
        <div v-if="activeTab === 'relojes'" class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Relojes por Local</h2>
            </div>

            <div
                v-for="lie in props.institucion.locales_inst_educ || []"
                :key="lie.id"
                class="space-y-2"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <MapPin class="h-4 w-4 text-primary" />
                        <span class="text-sm font-semibold">{{
                            lie.local?.nombre || 'Sin nombre'
                        }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <Button
                            size="sm"
                            variant="outline"
                            @click="
                                openRelojesMasiva(
                                    lie.id,
                                    lie.local?.nombre || 'Sin nombre',
                                )
                            "
                            class="gap-1.5 border-indigo-200 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 dark:border-indigo-900/50 dark:text-indigo-400 dark:hover:bg-indigo-950/20"
                        >
                            <Upload class="h-4 w-4" />
                            Carga Masiva
                        </Button>
                        <Button
                            size="sm"
                            variant="outline"
                            @click="openRelojCreate(lie.id)"
                        >
                            <Plus class="mr-2 h-4 w-4" /> Nuevo Reloj
                        </Button>
                    </div>
                </div>

                <div class="overflow-hidden rounded-md border bg-card">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nombre</TableHead>
                                <TableHead>IP</TableHead>
                                <TableHead>MAC</TableHead>
                                <TableHead class="w-[80px]">Puerto</TableHead>
                                <TableHead>Serie</TableHead>
                                <TableHead class="w-[80px]">Estado</TableHead>
                                <TableHead class="w-[100px] text-right"
                                    >Acciones</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="reloj in lie.relojes || []"
                                :key="reloj.id"
                            >
                                <TableCell class="text-sm font-medium">{{
                                    reloj.nombre || '-'
                                }}</TableCell>
                                <TableCell class="font-mono text-xs">{{
                                    reloj.dreccionIP || '-'
                                }}</TableCell>
                                <TableCell class="font-mono text-xs">{{
                                    reloj.direccionMac || '-'
                                }}</TableCell>
                                <TableCell class="text-xs">{{
                                    reloj.puerto || '-'
                                }}</TableCell>
                                <TableCell class="text-xs">{{
                                    reloj.serie || '-'
                                }}</TableCell>
                                <TableCell
                                    ><StatusBadge
                                        :active="reloj.activo ?? false"
                                /></TableCell>
                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child
                                            ><Button
                                                variant="ghost"
                                                size="sm"
                                                class="h-7"
                                                ><ChevronDown
                                                    class="h-4 w-4" /></Button
                                        ></DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem
                                                @click="openRelojEdit(reloj)"
                                                ><Pencil class="mr-2 h-4 w-4" />
                                                Editar</DropdownMenuItem
                                            >
                                            <DropdownMenuItem
                                                @click="
                                                    confirmDeleteReloj(reloj)
                                                "
                                                class="text-destructive"
                                                ><Trash2 class="mr-2 h-4 w-4" />
                                                Desactivar</DropdownMenuItem
                                            >
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="!lie.relojes?.length">
                                <TableCell
                                    colspan="7"
                                    class="h-16 text-center text-muted-foreground"
                                    >Sin relojes registrados en este
                                    local.</TableCell
                                >
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>

            <div
                v-if="!props.institucion.locales_inst_educ?.length"
                class="rounded-md border p-8 text-center text-muted-foreground"
            >
                Debe asignar locales primero para gestionar relojes.
            </div>
        </div>

        <!-- Tab: Docentes / Personal -->
        <div v-if="activeTab === 'docentes'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-semibold">Docentes / Personal</h2>
                <div class="flex items-center gap-2">
                    <Button
                        size="sm"
                        variant="outline"
                        @click="showMasivaModal = true"
                    >
                        <Upload class="mr-2 h-4 w-4" /> Carga Masiva
                    </Button>
                </div>
            </div>

            <!-- Filtros -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative min-w-[200px] flex-1">
                    <Search
                        class="absolute top-2.5 left-3 h-4 w-4 text-muted-foreground"
                    />
                    <Input
                        v-model="docenteSearch"
                        placeholder="Buscar por nombre, DNI, código AIRSP..."
                        class="pl-9"
                    />
                </div>
                <label class="flex cursor-pointer items-center gap-2 text-sm">
                    <input
                        v-model="docenteSoloActivas"
                        type="checkbox"
                        class="size-4 rounded border-input accent-primary"
                    />
                    Solo activos
                </label>
            </div>

            <!-- Tabla -->
            <div class="overflow-hidden rounded-md border bg-card shadow-xs">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Trabajador</TableHead>
                            <TableHead>Condición / Rol</TableHead>
                            <TableHead class="w-[130px]"
                                >Área / Cargo</TableHead
                            >
                            <TableHead class="w-[100px]">Inicio</TableHead>
                            <TableHead class="w-[100px]">Fin / Baja</TableHead>
                            <TableHead class="w-[75px]">Estado</TableHead>
                            <TableHead class="w-[50px]"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template
                            v-if="props.docentes && props.docentes.data.length"
                        >
                            <TableRow
                                v-for="alta in props.docentes.data"
                                :key="alta.id"
                            >
                                <TableCell>
                                    <Link
                                        :href="`/trabajadores/${alta.trabajador_id}`"
                                        class="group"
                                    >
                                        <div
                                            class="text-sm font-medium transition-colors group-hover:text-primary"
                                        >
                                            {{
                                                alta.trabajador?.persona
                                                    ?.paterno
                                            }}
                                            {{
                                                alta.trabajador?.persona
                                                    ?.materno
                                            }},
                                            {{
                                                alta.trabajador?.persona?.nombre
                                            }}
                                        </div>
                                        <div
                                            class="text-xs text-muted-foreground"
                                        >
                                            {{ alta.trabajador?.codigo }}
                                            <span
                                                v-if="
                                                    alta.trabajador?.persona
                                                        ?.docIdentidad
                                                "
                                            >
                                                ·
                                                {{
                                                    alta.trabajador?.persona
                                                        ?.docIdentidad
                                                }}
                                            </span>
                                            <span
                                                v-if="alta.codigoAirsp"
                                                class="ml-1 text-primary"
                                            >
                                                · AIRSP: {{ alta.codigoAirsp }}
                                            </span>
                                        </div>
                                    </Link>
                                </TableCell>
                                <TableCell>
                                    <div class="text-xs font-medium">
                                        {{
                                            (
                                                alta.condicion_laboral ||
                                                alta.condicionLaboral
                                            )?.abreviatura ||
                                            (
                                                alta.condicion_laboral ||
                                                alta.condicionLaboral
                                            )?.nombre ||
                                            '-'
                                        }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        {{
                                            (
                                                alta.rol_trabajador ||
                                                alta.rolTrabajador
                                            )?.nombre || '-'
                                        }}
                                    </div>
                                    <div
                                        v-if="alta.perfil_ie?.perfil"
                                        class="mt-1"
                                    >
                                        <span
                                            class="inline-flex items-center rounded bg-blue-50 px-1.5 py-0.5 text-[10px] font-semibold text-blue-700 ring-1 ring-blue-700/10 dark:bg-blue-950/30 dark:text-blue-400 dark:ring-blue-400/20"
                                        >
                                            Perfil:
                                            {{ alta.perfil_ie.perfil.nombre }}
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell class="text-xs">
                                    <div>{{ alta.area?.nombre || '-' }}</div>
                                    <div class="text-muted-foreground">
                                        {{ alta.cargo?.nombre || '-' }}
                                    </div>
                                </TableCell>
                                <TableCell class="text-xs">{{
                                    alta.fechaInicio
                                }}</TableCell>
                                <TableCell class="text-xs">
                                    <span
                                        v-if="alta.fechaBaja"
                                        class="text-destructive"
                                        >{{ alta.fechaBaja }}</span
                                    >
                                    <span
                                        v-else-if="alta.fechaFin"
                                        class="text-muted-foreground"
                                        >{{ alta.fechaFin }}</span
                                    >
                                    <span v-else class="text-muted-foreground"
                                        >—</span
                                    >
                                </TableCell>
                                <TableCell>
                                    <StatusBadge
                                        :active="!alta.fechaBaja"
                                        label-active="Activo"
                                        label-inactive="Baja"
                                    />
                                </TableCell>
                                <TableCell>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        class="h-7 w-7 p-0"
                                        :title="`Gestionar usuario de ${alta.trabajador?.persona?.paterno}`"
                                        @click="
                                            openUsuarioModal(
                                                alta.trabajador_id,
                                                [
                                                    alta.trabajador?.persona
                                                        ?.paterno,
                                                    alta.trabajador?.persona
                                                        ?.materno,
                                                    alta.trabajador?.persona
                                                        ?.nombre,
                                                ]
                                                    .filter(Boolean)
                                                    .join(' '),
                                            )
                                        "
                                    >
                                        <ShieldCheck
                                            class="h-4 w-4 text-muted-foreground hover:text-primary"
                                        />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </template>
                        <TableRow
                            v-else-if="
                                props.docentes && !props.docentes.data.length
                            "
                        >
                            <TableCell
                                colspan="7"
                                class="h-24 text-center text-muted-foreground"
                            >
                                No se encontraron docentes/personal con los
                                filtros aplicados.
                            </TableCell>
                        </TableRow>
                        <TableRow v-else>
                            <TableCell
                                colspan="7"
                                class="h-16 text-center text-sm text-muted-foreground"
                            >
                                Cargando datos...
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <!-- Paginación -->
                <div
                    v-if="props.docentes && props.docentes.total > 0"
                    class="flex items-center justify-between border-t px-4 py-3"
                >
                    <span class="text-sm text-muted-foreground">
                        Página {{ props.docentes.current_page }} de
                        {{ props.docentes.last_page }} ({{
                            props.docentes.total
                        }}
                        registros)
                    </span>
                    <div class="flex gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="props.docentes.current_page <= 1"
                            @click="
                                cargarDocentes({
                                    page: props.docentes!.current_page - 1,
                                })
                            "
                            >Anterior</Button
                        >
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="
                                props.docentes.current_page >=
                                props.docentes.last_page
                            "
                            @click="
                                cargarDocentes({
                                    page: props.docentes!.current_page + 1,
                                })
                            "
                            >Siguiente</Button
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Días No Laborables -->
        <div v-if="activeTab === 'diasNoLaborables'" class="space-y-4">
            <!-- Encabezado -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-semibold">Días No Laborables</h2>
                <div class="flex items-center gap-2">
                    <!-- Generar feriados por defecto -->
                    <div class="flex items-center gap-1.5 rounded-md border px-2 py-1">
                        <label class="text-xs text-muted-foreground">Año:</label>
                        <Input
                            v-model.number="diasGenerarAnio"
                            type="number"
                            class="h-7 w-20 text-xs"
                            :min="2020"
                            :max="2099"
                        />
                        <Button
                            size="sm"
                            variant="outline"
                            class="h-7 gap-1.5 border-amber-200 text-amber-600 hover:bg-amber-50 hover:text-amber-700 dark:border-amber-900/50 dark:text-amber-400 dark:hover:bg-amber-950/20"
                            :disabled="diasIsGenerating"
                            @click="generarFeriados"
                        >
                            <Loader2 v-if="diasIsGenerating" class="h-3.5 w-3.5 animate-spin" />
                            <Sparkles v-else class="h-3.5 w-3.5" />
                            Generar Feriados
                        </Button>
                    </div>
                    <Button size="sm" @click="openDiasCreate">
                        <Plus class="mr-2 h-4 w-4" /> Nuevo Día
                    </Button>
                </div>
            </div>

            <!-- Mensaje de generación -->
            <p v-if="diasGenerarMsg" class="text-sm text-muted-foreground italic">
                {{ diasGenerarMsg }}
            </p>

            <!-- Tabla -->
            <div class="overflow-hidden rounded-md border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[130px]">Fecha</TableHead>
                            <TableHead>Descripción / Feriado</TableHead>
                            <TableHead class="w-[100px]">Ámbito</TableHead>
                            <TableHead class="w-[100px]">Recuperable</TableHead>
                            <TableHead class="w-[100px] text-right">Acciones</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="dia in props.institucion.dias_no_laborables || []"
                            :key="dia.id"
                        >
                            <TableCell class="font-mono text-sm font-medium">
                                {{ dia.fecha }}
                            </TableCell>
                            <TableCell class="text-sm">
                                <span v-if="dia.feriado">
                                    <span class="font-medium">{{ dia.feriado.descripcion }}</span>
                                </span>
                                <span v-if="dia.observacion" class="block text-xs text-muted-foreground">
                                    {{ dia.observacion }}
                                </span>
                                <span v-if="!dia.feriado && !dia.observacion" class="text-muted-foreground">—</span>
                            </TableCell>
                            <TableCell class="text-xs">
                                <span
                                    :class="dia.nacionalLocal === 'N'
                                        ? 'inline-flex rounded bg-blue-50 px-1.5 py-0.5 text-xs font-semibold text-blue-700 ring-1 ring-blue-700/10 dark:bg-blue-950/30 dark:text-blue-400 dark:ring-blue-400/20'
                                        : 'inline-flex rounded bg-purple-50 px-1.5 py-0.5 text-xs font-semibold text-purple-700 ring-1 ring-purple-700/10 dark:bg-purple-950/30 dark:text-purple-400 dark:ring-purple-400/20'"
                                >
                                    {{ dia.nacionalLocal === 'N' ? 'Nacional' : 'Local' }}
                                </span>
                            </TableCell>
                            <TableCell class="text-xs">
                                <span
                                    :class="dia.recuperable === 'S'
                                        ? 'inline-flex rounded bg-emerald-50 px-1.5 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-700/10 dark:bg-emerald-950/30 dark:text-emerald-400 dark:ring-emerald-400/20'
                                        : 'inline-flex rounded bg-muted px-1.5 py-0.5 text-xs font-semibold text-muted-foreground ring-1 ring-muted-foreground/20'"
                                >
                                    {{ dia.recuperable === 'S' ? 'Sí' : 'No' }}
                                </span>
                            </TableCell>
                            <TableCell class="text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="sm" class="h-7">
                                            <ChevronDown class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem @click="openDiasEdit(dia)">
                                            <Pencil class="mr-2 h-4 w-4" /> Editar
                                        </DropdownMenuItem>
                                        <DropdownMenuItem
                                            @click="confirmDeleteDias(dia)"
                                            class="text-destructive"
                                        >
                                            <Trash2 class="mr-2 h-4 w-4" /> Eliminar
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!props.institucion.dias_no_laborables?.length">
                            <TableCell colspan="5" class="h-20 text-center text-muted-foreground">
                                No hay días no laborables registrados para esta IE.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>

        <!-- TAB Permisos -->
        <div v-if="activeTab === 'permisos'">
            <PermisosIETab :institucion-id="props.institucion.id" />
        </div>

        <!-- Modal Carga Masiva Grid (Altas) -->
        <AltaMasivaGrid
            v-if="showMasivaModal"
            :institucion-id="props.institucion.id"
            @close="showMasivaModal = false"
            @success="onMasivaSuccess"
        />

        <!-- Modal Carga Masiva Grados y Secciones -->
        <GradosMasivaGrid
            v-if="showGradosMasivaModal"
            :institucion-id="props.institucion.id"
            @close="showGradosMasivaModal = false"
            @success="onGradosMasivaSuccess"
        />

        <!-- Modal Carga Masiva Cursos -->
        <CursosMasivaGrid
            v-if="showCursosMasivaModal"
            :institucion-id="props.institucion.id"
            @close="showCursosMasivaModal = false"
            @success="onCursosMasivaSuccess"
        />

        <!-- Modal Carga Masiva Relojes (por local) -->
        <RelojesMasivaGrid
            v-if="relojesMasivaLocalId !== null"
            :local-inst-educ-id="relojesMasivaLocalId"
            :local-nombre="relojesMasivaLocalNombre"
            @close="relojesMasivaLocalId = null"
            @success="onRelojesMasivaSuccess"
        />

        <!-- Modal Sub-recurso (Curso/Grado/Sección) -->
        <FormModal
            v-model:show="showSubModal"
            :title="
                subModalTitles[subModalType][subIsEditing ? 'edit' : 'create']
            "
            :processing="subForm.processing"
            @submit="submitSub"
        >
            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label>Nombre *</Label>
                    <Input
                        v-model="subForm.nombre"
                        placeholder="Nombre del recurso"
                        :class="{ 'border-destructive': subForm.errors.nombre }"
                    />
                    <p
                        v-if="subForm.errors.nombre"
                        class="text-sm text-destructive"
                    >
                        {{ subForm.errors.nombre }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label>Sigla</Label>
                    <Input v-model="subForm.sigla" placeholder="Opcional" />
                </div>
            </div>
        </FormModal>

        <!-- Modal Eliminar Sub-recurso -->
        <ConfirmModal
            v-model:show="showDeleteSub"
            title="Desactivar Recurso"
            :description="`¿Desactivar '${deleteSubName}'?`"
            confirm-text="Desactivar"
            destructive
            :processing="isDeletingSub"
            @confirm="executeDeleteSub"
            @cancel="deleteSubId = null"
        />

        <!-- Modal Crear Local -->
        <FormModal
            v-model:show="showLocalModal"
            title="Nuevo Local"
            description="El local se creará y asignará automáticamente a esta institución."
            max-width="4xl"
            :processing="localForm.processing"
            @submit="submitLocal"
        >
            <div class="grid gap-6">
                <!-- Fila 1: Nombre y Domicilio -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label>Nombre del local *</Label>
                        <Input
                            v-model="localForm.nombre"
                            placeholder="Ej: Local Principal, Anexo 01..."
                            :class="{
                                'border-destructive': localForm.errors.nombre,
                            }"
                        />
                        <p
                            v-if="localForm.errors.nombre"
                            class="text-sm text-destructive"
                        >
                            {{ localForm.errors.nombre }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <Label>Domicilio / Dirección</Label>
                        <Input
                            v-model="localForm.domicilio"
                            placeholder="Av. / Jr. / Calle..."
                        />
                    </div>
                </div>

                <!-- Fila 2: Departamento y Provincia -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="grid gap-2">
                        <ParamSelect
                            type="departamentos"
                            label="Departamento"
                            v-model="localSelectedDepartamento"
                            placeholder="Seleccionar..."
                            @update:modelValue="
                                () => {
                                    if (!isInitializingLocal) {
                                        localSelectedProvincia = null;
                                        localSelectedDistrito = null;
                                        localForm.ubigeo = '';
                                        localForm.zona_id = null;
                                    }
                                }
                            "
                        />
                    </div>
                    <div class="grid gap-2">
                        <ParamSelect
                            type="provincias"
                            label="Provincia"
                            :parent-id="localSelectedDepartamento"
                            v-model="localSelectedProvincia"
                            placeholder="Seleccionar..."
                            :disabled="!localSelectedDepartamento"
                            @update:modelValue="
                                () => {
                                    if (!isInitializingLocal) {
                                        localSelectedDistrito = null;
                                        localForm.ubigeo = '';
                                        localForm.zona_id = null;
                                    }
                                }
                            "
                        />
                    </div>
                    <div class="grid gap-2">
                        <ParamSelect
                            type="distritos"
                            label="Distrito"
                            :parent-id="localSelectedProvincia"
                            v-model="localSelectedDistrito"
                            placeholder="Seleccionar..."
                            :disabled="!localSelectedProvincia"
                            @update:item="
                                (item: any) => {
                                    if (!isInitializingLocal) {
                                        localForm.ubigeo = item
                                            ? item.codigo || ''
                                            : '';
                                        localForm.zona_id = null;
                                    }
                                }
                            "
                        />
                    </div>
                </div>

                <!-- Fila 3: Zona y Ubigeo -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="grid gap-2 md:col-span-2">
                        <ZonaSelect
                            v-model="localForm.zona_id"
                            :distrito-id="localSelectedDistrito"
                            label="Zona (opcional)"
                            placeholder="Seleccionar zona..."
                            :disabled="!localSelectedDistrito"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label
                            class="text-xs font-bold text-muted-foreground uppercase"
                            >Ubigeo</Label
                        >
                        <Input
                            v-model="localForm.ubigeo"
                            placeholder="000000"
                            maxlength="6"
                            class="h-9 bg-muted/30 font-mono font-bold"
                            readonly
                        />
                    </div>
                </div>

                <!-- Fila 4: Coordenadas UTM -->
                <div
                    class="grid grid-cols-2 gap-4 border-t pt-4 md:grid-cols-4"
                >
                    <div class="grid gap-2">
                        <Label class="text-xs">UTM Huso</Label>
                        <Input
                            v-model="localForm.utm_huso"
                            type="number"
                            step="0.0001"
                            placeholder="Huso"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label class="text-xs">UTM Banda</Label>
                        <Input
                            v-model="localForm.utm_banda"
                            placeholder="Banda"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label class="text-xs">UTM X (Este)</Label>
                        <Input
                            v-model="localForm.utm_x_este"
                            type="number"
                            step="0.0001"
                            placeholder="Este"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label class="text-xs">UTM Y (Norte)</Label>
                        <Input
                            v-model="localForm.utm_y_norte"
                            type="number"
                            step="0.0001"
                            placeholder="Norte"
                        />
                    </div>
                </div>

                <!-- Fila 5: Fechas -->
                <div class="grid grid-cols-2 gap-4 border-t pt-4">
                    <div class="grid gap-2">
                        <Label>Fecha Inicio</Label>
                        <Input v-model="localForm.fechaInicio" type="date" />
                    </div>
                    <div class="grid gap-2">
                        <Label>Fecha Fin</Label>
                        <Input v-model="localForm.fechaFin" type="date" />
                    </div>
                </div>
            </div>
        </FormModal>

        <!-- Modal Eliminar Local -->
        <ConfirmModal
            v-model:show="showDeleteLocal"
            title="Desasignar Local"
            :description="`¿Desasignar '${deleteLocalName}' de esta institución?`"
            confirm-text="Desasignar"
            destructive
            :processing="isDeletingLocal"
            @confirm="executeDeleteLocal"
            @cancel="deleteLocalId = null"
        />

        <!-- Modal Reloj -->
        <FormModal
            v-model:show="showRelojModal"
            :title="relojIsEditing ? 'Editar Reloj' : 'Nuevo Reloj'"
            :processing="relojForm.processing"
            @submit="submitReloj"
        >
            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label>Nombre</Label>
                    <Input
                        v-model="relojForm.nombre"
                        placeholder="Nombre del reloj"
                        :class="{
                            'border-destructive': relojForm.errors.nombre,
                        }"
                    />
                    <p
                        v-if="relojForm.errors.nombre"
                        class="text-sm text-destructive"
                    >
                        {{ relojForm.errors.nombre }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>Dirección IP</Label>
                        <Input
                            v-model="relojForm.dreccionIP"
                            placeholder="192.168.1.100"
                        />
                        <p
                            v-if="relojForm.errors.dreccionIP"
                            class="text-sm text-destructive"
                        >
                            {{ relojForm.errors.dreccionIP }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <Label>Dirección MAC</Label>
                        <Input
                            v-model="relojForm.direccionMac"
                            placeholder="AA:BB:CC:DD:EE:FF"
                        />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>Puerto</Label>
                        <Input
                            v-model="relojForm.puerto"
                            type="number"
                            placeholder="4370"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label>Serie</Label>
                        <Input
                            v-model="relojForm.serie"
                            placeholder="Número de serie"
                        />
                    </div>
                </div>
                <div class="grid gap-2">
                    <Label>ID Biométrico</Label>
                    <Input
                        v-model="relojForm.idBiometrico"
                        type="number"
                        placeholder="Opcional"
                    />
                </div>
            </div>
        </FormModal>

        <!-- Modal Eliminar Reloj -->
        <ConfirmModal
            v-model:show="showDeleteReloj"
            title="Desactivar Reloj"
            :description="`¿Desactivar reloj '${deleteRelojName}'?`"
            confirm-text="Desactivar"
            destructive
            :processing="isDeletingReloj"
            @confirm="executeDeleteReloj"
            @cancel="deleteRelojId = null"
        />

        <GestionUsuarioModal
            v-if="usuarioModalTrabajadorId !== null"
            v-model:show="showUsuarioModal"
            :trabajador-id="usuarioModalTrabajadorId"
            :trabajador-nombre="usuarioModalNombre"
            @updated="cargarDocentes()"
        />

        <!-- Modal Crear / Editar Día No Laborable -->
        <FormModal
            v-model:show="showDiasModal"
            :title="diasIsEditing ? 'Editar Día No Laborable' : 'Nuevo Día No Laborable'"
            :processing="diasForm.processing"
            @submit="submitDias"
        >
            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label>Fecha *</Label>
                    <Input
                        v-model="diasForm.fecha"
                        type="date"
                        :class="{ 'border-destructive': diasForm.errors.fecha }"
                    />
                    <p v-if="diasForm.errors.fecha" class="text-sm text-destructive">
                        {{ diasForm.errors.fecha }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label>Descripción / Observación</Label>
                    <Input
                        v-model="diasForm.observacion"
                        placeholder="Ej: Aniversario de la IE, día cívico..."
                        :class="{ 'border-destructive': diasForm.errors.observacion }"
                    />
                    <p v-if="diasForm.errors.observacion" class="text-sm text-destructive">
                        {{ diasForm.errors.observacion }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>Ámbito</Label>
                        <select
                            v-model="diasForm.nacionalLocal"
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                        >
                            <option value="N">Nacional</option>
                            <option value="L">Local</option>
                        </select>
                    </div>
                    <div class="grid gap-2">
                        <Label>Recuperable</Label>
                        <select
                            v-model="diasForm.recuperable"
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                        >
                            <option value="N">No</option>
                            <option value="S">Sí</option>
                        </select>
                    </div>
                </div>
            </div>
        </FormModal>

        <!-- Modal Confirmar Eliminar Día No Laborable -->
        <ConfirmModal
            v-model:show="showDeleteDias"
            title="Eliminar Día No Laborable"
            :description="`¿Eliminar el día no laborable del ${deleteDiasLabel}?`"
            confirm-text="Eliminar"
            destructive
            :processing="isDeletingDias"
            @confirm="executeDeleteDias"
            @cancel="deleteDiasId = null"
        />
    </div>
</template>

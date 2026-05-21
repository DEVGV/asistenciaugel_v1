<script setup lang="ts">
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import {
    ArrowLeft, Plus, Pencil, Trash2, School, BookOpen, GraduationCap,
    LayoutGrid, ChevronDown, MapPin, Clock, Smartphone, UserPlus,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import InstitucionEducativaController from '@/actions/App/Http/Controllers/InstitucionEducativa/InstitucionEducativaController';
import CursoIEController from '@/actions/App/Http/Controllers/InstitucionEducativa/CursoIEController';
import GradoIEController from '@/actions/App/Http/Controllers/InstitucionEducativa/GradoIEController';
import SeccionIEController from '@/actions/App/Http/Controllers/InstitucionEducativa/SeccionIEController';
import LocalInstEducController from '@/actions/App/Http/Controllers/Infraestructura/LocalInstEducController';
import LocalController from '@/actions/App/Http/Controllers/Infraestructura/LocalController';
import RelojController from '@/actions/App/Http/Controllers/Infraestructura/RelojController';
import LocalMarcacionController from '@/actions/App/Http/Controllers/Infraestructura/LocalMarcacionController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import ZonaSelect from '@/components/shared/ZonaSelect.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import type { InstitucionEducativa, CursoIE, GradoIE, SeccionIE } from '@/types/models/institucion-educativa';
import type { LocalInstEduc, Reloj } from '@/types/models/infraestructura';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Instituciones Educativas', href: InstitucionEducativaController.index().url },
            { title: 'Detalle', href: '#' },
        ],
    },
});

const props = defineProps<{ institucion: InstitucionEducativa }>();

const activeTab = ref<'datos' | 'cursos' | 'grados' | 'locales' | 'relojes'>('datos');

// ─── Sub-recurso Modal (compartido para cursos, grados, secciones) ───
const showSubModal = ref(false);
const subModalType = ref<'curso' | 'grado' | 'seccion'>('curso');
const subIsEditing = ref(false);
const subEditingId = ref<number | null>(null);
const subParentId = ref<number | null>(null);

const subForm = useForm({ nombre: '', sigla: '', activo: true });

watch(showSubModal, (val) => { if (!val) { subForm.reset(); subForm.clearErrors(); } });

function openSubCreate(type: 'curso' | 'grado' | 'seccion', parentId?: number) {
    subModalType.value = type;
    subIsEditing.value = false;
    subEditingId.value = null;
    subParentId.value = parentId ?? null;
    subForm.reset();
    subForm.clearErrors();
    showSubModal.value = true;
}

function openSubEdit(type: 'curso' | 'grado' | 'seccion', item: CursoIE | GradoIE | SeccionIE) {
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
            targetUrl = CursoIEController.update({ curso: subEditingId.value }).url;
        } else if (subModalType.value === 'grado') {
            targetUrl = GradoIEController.update({ grado: subEditingId.value }).url;
        } else {
            targetUrl = SeccionIEController.update({ seccione: subEditingId.value }).url;
        }
        subForm.put(targetUrl, { onSuccess: () => { showSubModal.value = false; } });
    } else {
        if (subModalType.value === 'curso') {
            targetUrl = CursoIEController.store({ institucione: ieId }).url;
        } else if (subModalType.value === 'grado') {
            targetUrl = GradoIEController.store({ institucione: ieId }).url;
        } else {
            targetUrl = SeccionIEController.store({ grado: subParentId.value! }).url;
        }
        subForm.post(targetUrl, { onSuccess: () => { showSubModal.value = false; } });
    }
}

// ─── Eliminar sub-recurso ───
const showDeleteSub = ref(false);
const deleteSubType = ref<'curso' | 'grado' | 'seccion'>('curso');
const deleteSubId = ref<number | null>(null);
const deleteSubName = ref('');
const isDeletingSub = ref(false);

function confirmDeleteSub(type: 'curso' | 'grado' | 'seccion', id: number, name: string) {
    deleteSubType.value = type;
    deleteSubId.value = id;
    deleteSubName.value = name;
    showDeleteSub.value = true;
}

function executeDeleteSub() {
    if (!deleteSubId.value) return;
    isDeletingSub.value = true;
    let targetUrl = '';

    if (deleteSubType.value === 'curso') {
        targetUrl = CursoIEController.destroy({ curso: deleteSubId.value }).url;
    } else if (deleteSubType.value === 'grado') {
        targetUrl = GradoIEController.destroy({ grado: deleteSubId.value }).url;
    } else {
        targetUrl = SeccionIEController.destroy({ seccione: deleteSubId.value }).url;
    }

    router.delete(targetUrl, {
        onSuccess: () => { showDeleteSub.value = false; deleteSubId.value = null; },
        onFinish: () => { isDeletingSub.value = false; },
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
    
    showLocalModal.value = true;
}

function submitLocal() {
    if (isEditingLocal.value && editingLocalId.value) {
        localForm.put(LocalController.update({ locale: editingLocalId.value }).url, {
            onSuccess: () => { showLocalModal.value = false; },
        });
    } else {
        localForm.post(
            LocalInstEducController.store({ institucione: props.institucion.id }).url,
            { onSuccess: () => { showLocalModal.value = false; } },
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
    if (!deleteLocalId.value) return;
    isDeletingLocal.value = true;
    router.delete(LocalInstEducController.destroy({ localesIe: deleteLocalId.value }).url, {
        onSuccess: () => { showDeleteLocal.value = false; deleteLocalId.value = null; },
        onFinish: () => { isDeletingLocal.value = false; },
    });
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

watch(showRelojModal, (val) => { if (!val) { relojForm.reset(); relojForm.clearErrors(); } });

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
        submitter.put(RelojController.update({ reloje: relojEditingId.value }).url, {
            onSuccess: () => { showRelojModal.value = false; },
        });
    } else {
        submitter.post(RelojController.store({ localesIe: relojParentId.value! }).url, {
            onSuccess: () => { showRelojModal.value = false; },
        });
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
    if (!deleteRelojId.value) return;
    isDeletingReloj.value = true;
    router.delete(RelojController.destroy({ reloje: deleteRelojId.value }).url, {
        onSuccess: () => { showDeleteReloj.value = false; deleteRelojId.value = null; },
        onFinish: () => { isDeletingReloj.value = false; },
    });
}

const tabs = [
    { key: 'datos', label: 'Datos Generales', icon: School },
    { key: 'cursos', label: 'Cursos', icon: BookOpen },
    { key: 'grados', label: 'Grados y Secciones', icon: GraduationCap },
    { key: 'locales', label: 'Locales', icon: MapPin },
    { key: 'relojes', label: 'Relojes', icon: Clock },
] as const;
</script>

<template>
    <Head :title="`IE: ${props.institucion.nombreLegal}`" />

    <div class="flex flex-col gap-4 p-4">
        <!-- Encabezado -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">{{ props.institucion.nombreLegal }}</h1>
                <p class="text-sm text-muted-foreground">
                    Código: <span class="font-semibold text-primary">{{ props.institucion.codigoInstitucion }}</span>
                    <span v-if="props.institucion.codigoModular" class="ml-2">
                        · Modular: <span class="font-semibold">{{ props.institucion.codigoModular }}</span>
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
                v-for="tab in tabs" :key="tab.key"
                @click="activeTab = tab.key"
                :class="[
                    'flex items-center gap-2 px-4 py-2.5 text-sm font-medium transition-colors border-b-2 -mb-px',
                    activeTab === tab.key
                        ? 'border-primary text-primary'
                        : 'border-transparent text-muted-foreground hover:text-foreground hover:border-muted-foreground/50',
                ]"
            >
                <component :is="tab.icon" class="h-4 w-4" />
                {{ tab.label }}
            </button>
        </div>

        <!-- Tab: Datos Generales -->
        <div v-if="activeTab === 'datos'" class="rounded-xl border bg-card p-6 shadow-xs">
            <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
                <div><span class="text-muted-foreground">Tipo Institución:</span><p class="font-medium">{{ props.institucion.tipo_inst_educ?.nombre || '-' }}</p></div>
                <div><span class="text-muted-foreground">Régimen Educativo:</span><p class="font-medium">{{ props.institucion.regimen_educ?.nombre || '-' }}</p></div>
                <div><span class="text-muted-foreground">Modalidad Formativa:</span><p class="font-medium">{{ props.institucion.modalidad_formativa?.nombre || '-' }}</p></div>
                <div><span class="text-muted-foreground">Nivel / Ciclo:</span><p class="font-medium">{{ props.institucion.nivel_ciclo?.nombre || '-' }}</p></div>
                <div><span class="text-muted-foreground">UGEL:</span><p class="font-medium">{{ props.institucion.entidad_ugel?.razonSocial || '-' }}</p></div>
                <div><span class="text-muted-foreground">Entidad Admin:</span><p class="font-medium">{{ props.institucion.entidad_admin?.razonSocial || '-' }}</p></div>
                <div><span class="text-muted-foreground">Fecha Inicio:</span><p class="font-medium">{{ props.institucion.fechaInicio || '-' }}</p></div>
                <div><span class="text-muted-foreground">Fecha Fin:</span><p class="font-medium">{{ props.institucion.fechaFin || '-' }}</p></div>
            </div>
        </div>

        <!-- Tab: Cursos -->
        <div v-if="activeTab === 'cursos'" class="space-y-3">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">Cursos</h2>
                <Button size="sm" @click="openSubCreate('curso')"><Plus class="mr-2 h-4 w-4" /> Nuevo Curso</Button>
            </div>
            <div class="rounded-md border bg-card overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">Código</TableHead>
                            <TableHead>Nombre</TableHead>
                            <TableHead class="w-[100px]">Sigla</TableHead>
                            <TableHead class="w-[80px]">Estado</TableHead>
                            <TableHead class="w-[100px] text-right">Acciones</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="curso in props.institucion.cursos || []" :key="curso.id">
                            <TableCell class="font-medium text-xs">{{ curso.codigo || '-' }}</TableCell>
                            <TableCell class="text-sm">{{ curso.nombre }}</TableCell>
                            <TableCell class="text-xs">{{ curso.sigla || '-' }}</TableCell>
                            <TableCell><StatusBadge :active="curso.activo" /></TableCell>
                            <TableCell class="text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child><Button variant="ghost" size="sm" class="h-7"><ChevronDown class="h-4 w-4" /></Button></DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem @click="openSubEdit('curso', curso)"><Pencil class="mr-2 h-4 w-4" /> Editar</DropdownMenuItem>
                                        <DropdownMenuItem @click="confirmDeleteSub('curso', curso.id, curso.nombre)" class="text-destructive"><Trash2 class="mr-2 h-4 w-4" /> Desactivar</DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!props.institucion.cursos?.length"><TableCell colspan="5" class="h-20 text-center text-muted-foreground">No hay cursos registrados.</TableCell></TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>

        <!-- Tab: Grados y Secciones -->
        <div v-if="activeTab === 'grados'" class="space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">Grados y Secciones</h2>
                <Button size="sm" @click="openSubCreate('grado')"><Plus class="mr-2 h-4 w-4" /> Nuevo Grado</Button>
            </div>
            <div v-for="grado in props.institucion.grados || []" :key="grado.id" class="rounded-lg border bg-card overflow-hidden">
                <div class="flex items-center justify-between border-b bg-muted/30 px-4 py-2.5">
                    <div class="flex items-center gap-2">
                        <GraduationCap class="h-4 w-4 text-primary" />
                        <span class="text-sm font-semibold">{{ grado.nombre }}</span>
                        <span v-if="grado.codigo" class="text-xs text-muted-foreground">({{ grado.codigo }})</span>
                        <StatusBadge :active="grado.activo" />
                    </div>
                    <div class="flex gap-1">
                        <Button variant="ghost" size="sm" class="h-7 text-xs" @click="openSubCreate('seccion', grado.id)"><Plus class="mr-1 h-3 w-3" /> Sección</Button>
                        <Button variant="ghost" size="sm" class="h-7" @click="openSubEdit('grado', grado)"><Pencil class="h-3.5 w-3.5" /></Button>
                        <Button variant="ghost" size="sm" class="h-7 text-destructive" @click="confirmDeleteSub('grado', grado.id, grado.nombre)"><Trash2 class="h-3.5 w-3.5" /></Button>
                    </div>
                </div>
                <div v-if="grado.secciones?.length" class="p-3">
                    <div class="flex flex-wrap gap-2">
                        <div v-for="sec in grado.secciones" :key="sec.id"
                            class="flex items-center gap-2 rounded-md border bg-background px-3 py-1.5 text-sm">
                            <LayoutGrid class="h-3.5 w-3.5 text-muted-foreground" />
                            <span class="font-medium">{{ sec.nombre }}</span>
                            <span v-if="sec.sigla" class="text-xs text-muted-foreground">({{ sec.sigla }})</span>
                            <button @click="openSubEdit('seccion', sec)" class="ml-1 text-muted-foreground hover:text-foreground"><Pencil class="h-3 w-3" /></button>
                            <button @click="confirmDeleteSub('seccion', sec.id, sec.nombre)" class="text-muted-foreground hover:text-destructive"><Trash2 class="h-3 w-3" /></button>
                        </div>
                    </div>
                </div>
                <div v-else class="p-4 text-center text-xs text-muted-foreground">Sin secciones registradas.</div>
            </div>
            <div v-if="!props.institucion.grados?.length" class="rounded-md border p-8 text-center text-muted-foreground">No hay grados registrados.</div>
        </div>

        <!-- Tab: Locales -->
        <div v-if="activeTab === 'locales'" class="space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">Locales Asignados</h2>
                <Button size="sm" @click="openLocalCreate()"><Plus class="mr-2 h-4 w-4" /> Asignar Local</Button>
            </div>

            <div v-for="lie in props.institucion.locales_inst_educ || []" :key="lie.id" class="rounded-lg border bg-card overflow-hidden">
                <div class="flex items-center justify-between border-b bg-muted/30 px-4 py-2.5">
                    <div class="flex items-center gap-2">
                        <MapPin class="h-4 w-4 text-primary" />
                        <span class="text-sm font-semibold">{{ lie.local?.nombre || 'Sin nombre' }}</span>
                        <span v-if="lie.local?.zona" class="text-xs text-muted-foreground">· {{ lie.local.zona.nombre }}</span>
                        <span v-if="lie.fechaInicio" class="text-xs text-muted-foreground">· Desde: {{ lie.fechaInicio }}</span>
                    </div>
                    <div class="flex gap-1">
                        <Button variant="ghost" size="sm" class="h-7" @click="openLocalEdit(lie)">
                            <Pencil class="h-3.5 w-3.5" />
                        </Button>
                        <Button variant="ghost" size="sm" class="h-7 text-destructive" @click="confirmDeleteLocal(lie)">
                            <Trash2 class="h-3.5 w-3.5" />
                        </Button>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    <div v-if="lie.local?.domicilio" class="text-sm text-muted-foreground">
                        <span class="font-medium text-foreground">Domicilio:</span> {{ lie.local.domicilio }}
                    </div>

                    <!-- Trabajadores de Marcación -->
                    <div v-if="lie.locales_marcacion?.length" class="space-y-1">
                        <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Trabajadores de Marcación</p>
                        <div class="flex flex-wrap gap-2">
                            <div v-for="lm in lie.locales_marcacion" :key="lm.id"
                                class="flex items-center gap-2 rounded-md border bg-background px-3 py-1.5 text-sm">
                                <UserPlus class="h-3.5 w-3.5 text-muted-foreground" />
                                <span class="font-medium">
                                    {{ lm.trabajador?.persona?.paterno }} {{ lm.trabajador?.persona?.materno }}, {{ lm.trabajador?.persona?.nombre }}
                                </span>
                                <span v-if="lm.fechaInicio" class="text-xs text-muted-foreground">({{ lm.fechaInicio }})</span>
                            </div>
                        </div>
                    </div>

                    <!-- Relojes del Local -->
                    <div v-if="lie.relojes?.length" class="space-y-1">
                        <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Relojes Biométricos</p>
                        <div class="flex flex-wrap gap-2">
                            <div v-for="reloj in lie.relojes" :key="reloj.id"
                                class="flex items-center gap-2 rounded-md border bg-background px-3 py-1.5 text-sm">
                                <Clock class="h-3.5 w-3.5 text-muted-foreground" />
                                <span class="font-medium">{{ reloj.nombre || 'Sin nombre' }}</span>
                                <span v-if="reloj.dreccionIP" class="text-xs text-muted-foreground">{{ reloj.dreccionIP }}</span>
                                <StatusBadge :active="reloj.activo ?? false" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!props.institucion.locales_inst_educ?.length" class="rounded-md border p-8 text-center text-muted-foreground">
                No hay locales asignados a esta institución.
            </div>
        </div>

        <!-- Tab: Relojes -->
        <div v-if="activeTab === 'relojes'" class="space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">Relojes por Local</h2>
            </div>

            <div v-for="lie in props.institucion.locales_inst_educ || []" :key="lie.id" class="space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <MapPin class="h-4 w-4 text-primary" />
                        <span class="text-sm font-semibold">{{ lie.local?.nombre || 'Sin nombre' }}</span>
                    </div>
                    <Button size="sm" variant="outline" @click="openRelojCreate(lie.id)">
                        <Plus class="mr-2 h-4 w-4" /> Nuevo Reloj
                    </Button>
                </div>

                <div class="rounded-md border bg-card overflow-hidden">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nombre</TableHead>
                                <TableHead>IP</TableHead>
                                <TableHead>MAC</TableHead>
                                <TableHead class="w-[80px]">Puerto</TableHead>
                                <TableHead>Serie</TableHead>
                                <TableHead class="w-[80px]">Estado</TableHead>
                                <TableHead class="w-[100px] text-right">Acciones</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="reloj in lie.relojes || []" :key="reloj.id">
                                <TableCell class="text-sm font-medium">{{ reloj.nombre || '-' }}</TableCell>
                                <TableCell class="text-xs font-mono">{{ reloj.dreccionIP || '-' }}</TableCell>
                                <TableCell class="text-xs font-mono">{{ reloj.direccionMac || '-' }}</TableCell>
                                <TableCell class="text-xs">{{ reloj.puerto || '-' }}</TableCell>
                                <TableCell class="text-xs">{{ reloj.serie || '-' }}</TableCell>
                                <TableCell><StatusBadge :active="reloj.activo ?? false" /></TableCell>
                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child><Button variant="ghost" size="sm" class="h-7"><ChevronDown class="h-4 w-4" /></Button></DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem @click="openRelojEdit(reloj)"><Pencil class="mr-2 h-4 w-4" /> Editar</DropdownMenuItem>
                                            <DropdownMenuItem @click="confirmDeleteReloj(reloj)" class="text-destructive"><Trash2 class="mr-2 h-4 w-4" /> Desactivar</DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="!lie.relojes?.length">
                                <TableCell colspan="7" class="h-16 text-center text-muted-foreground">Sin relojes registrados en este local.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>

            <div v-if="!props.institucion.locales_inst_educ?.length" class="rounded-md border p-8 text-center text-muted-foreground">
                Debe asignar locales primero para gestionar relojes.
            </div>
        </div>

        <!-- Modal Sub-recurso (Curso/Grado/Sección) -->
        <FormModal
            v-model:show="showSubModal"
            :title="subModalTitles[subModalType][subIsEditing ? 'edit' : 'create']"
            :processing="subForm.processing"
            @submit="submitSub"
        >
            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label>Nombre *</Label>
                    <Input v-model="subForm.nombre" placeholder="Nombre del recurso" :class="{ 'border-destructive': subForm.errors.nombre }" />
                    <p v-if="subForm.errors.nombre" class="text-sm text-destructive">{{ subForm.errors.nombre }}</p>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>Nombre del local *</Label>
                        <Input v-model="localForm.nombre" placeholder="Ej: Local Principal, Anexo 01..." :class="{ 'border-destructive': localForm.errors.nombre }" />
                        <p v-if="localForm.errors.nombre" class="text-sm text-destructive">{{ localForm.errors.nombre }}</p>
                    </div>
                    <div class="grid gap-2">
                        <Label>Domicilio / Dirección</Label>
                        <Input v-model="localForm.domicilio" placeholder="Av. / Jr. / Calle..." />
                    </div>
                </div>

                <!-- Fila 2: Departamento y Provincia -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="grid gap-2">
                        <ParamSelect
                            type="departamentos"
                            label="Departamento"
                            v-model="localSelectedDepartamento"
                            placeholder="Seleccionar..."
                            @update:modelValue="() => {
                                localSelectedProvincia = null;
                                localSelectedDistrito = null;
                                localForm.ubigeo = '';
                                localForm.zona_id = null;
                            }"
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
                            @update:modelValue="() => {
                                localSelectedDistrito = null;
                                localForm.ubigeo = '';
                                localForm.zona_id = null;
                            }"
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
                            @update:item="(item: any) => {
                                localForm.ubigeo = item ? (item.codigo || '') : '';
                                localForm.zona_id = null;
                            }"
                        />
                    </div>
                </div>

                <!-- Fila 3: Zona y Ubigeo -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2 grid gap-2">
                        <ZonaSelect
                            v-model="localForm.zona_id"
                            :distrito-id="localSelectedDistrito"
                            label="Zona (opcional)"
                            placeholder="Seleccionar zona..."
                            :disabled="!localSelectedDistrito"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label class="text-xs font-bold text-muted-foreground uppercase">Ubigeo</Label>
                        <Input
                            v-model="localForm.ubigeo"
                            placeholder="000000"
                            maxlength="6"
                            class="h-9 font-mono font-bold bg-muted/30"
                            readonly
                        />
                    </div>
                </div>

                <!-- Fila 4: Coordenadas UTM -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t pt-4">
                    <div class="grid gap-2">
                        <Label class="text-xs">UTM Huso</Label>
                        <Input v-model="localForm.utm_huso" type="number" step="0.0001" placeholder="Huso" />
                    </div>
                    <div class="grid gap-2">
                        <Label class="text-xs">UTM Banda</Label>
                        <Input v-model="localForm.utm_banda" placeholder="Banda" />
                    </div>
                    <div class="grid gap-2">
                        <Label class="text-xs">UTM X (Este)</Label>
                        <Input v-model="localForm.utm_x_este" type="number" step="0.0001" placeholder="Este" />
                    </div>
                    <div class="grid gap-2">
                        <Label class="text-xs">UTM Y (Norte)</Label>
                        <Input v-model="localForm.utm_y_norte" type="number" step="0.0001" placeholder="Norte" />
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
                    <Input v-model="relojForm.nombre" placeholder="Nombre del reloj" :class="{ 'border-destructive': relojForm.errors.nombre }" />
                    <p v-if="relojForm.errors.nombre" class="text-sm text-destructive">{{ relojForm.errors.nombre }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>Dirección IP</Label>
                        <Input v-model="relojForm.dreccionIP" placeholder="192.168.1.100" />
                        <p v-if="relojForm.errors.dreccionIP" class="text-sm text-destructive">{{ relojForm.errors.dreccionIP }}</p>
                    </div>
                    <div class="grid gap-2">
                        <Label>Dirección MAC</Label>
                        <Input v-model="relojForm.direccionMac" placeholder="AA:BB:CC:DD:EE:FF" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>Puerto</Label>
                        <Input v-model="relojForm.puerto" type="number" placeholder="4370" />
                    </div>
                    <div class="grid gap-2">
                        <Label>Serie</Label>
                        <Input v-model="relojForm.serie" placeholder="Número de serie" />
                    </div>
                </div>
                <div class="grid gap-2">
                    <Label>ID Biométrico</Label>
                    <Input v-model="relojForm.idBiometrico" type="number" placeholder="Opcional" />
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
    </div>
</template>

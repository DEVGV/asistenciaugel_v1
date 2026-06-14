<script setup lang="ts">
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import {
    Plus,
    Pencil,
    Trash2,
    Eye,
    ChevronDown,
    School,
    Search,
    Upload,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import InstitucionEducativaController from '@/actions/App/Http/Controllers/InstitucionEducativa/InstitucionEducativaController';
import InstitucionMasivaGrid from '@/components/institucion-educativa/InstitucionMasivaGrid.vue';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import EntidadSelect from '@/components/shared/EntidadSelect.vue';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import { Button } from '@/components/ui/button';
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
import type {
    InstitucionEducativa,
    PaginatedResponse,
} from '@/types/models/institucion-educativa';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Instituciones Educativas',
                href: InstitucionEducativaController.index().url,
            },
        ],
    },
});

const props = defineProps<{
    instituciones: PaginatedResponse<InstitucionEducativa>;
    filters: { search?: string };
}>();

// ─── Búsqueda en Vivo ───
const search = ref(props.filters.search || '');
let searchTimeout: any = null;

watch(search, (val) => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    searchTimeout = setTimeout(() => {
        router.get(
            InstitucionEducativaController.index().url,
            { search: val || undefined },
            {
                preserveState: true,
                replace: true,
            },
        );
    }, 300);
});

// ─── Modal Crear/Editar ───
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    codigoInstitucion: '',
    codigoModular: '',
    nombreLegal: '',
    entidadUgel_id: null as number | null,
    entidadAdmin_id: null as number | null,
    regimenEduc_id: null as number | null,
    tipoInstEduc_id: null as number | null,
    modalidadFormativa_id: null as number | null,
    nivelCiclo_id: null as number | null,
    fechaInicio: '',
    fechaFin: '',
});

watch(showModal, (val) => {
    if (!val) {
        form.reset();
        form.clearErrors();
    }
});

function openCreateModal() {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
}

function openEditModal(ie: InstitucionEducativa) {
    isEditing.value = true;
    editingId.value = ie.id;
    form.clearErrors();
    form.codigoInstitucion = ie.codigoInstitucion;
    form.codigoModular = ie.codigoModular || '';
    form.nombreLegal = ie.nombreLegal;
    form.entidadUgel_id = ie.entidadUgel_id;
    form.entidadAdmin_id = ie.entidadAdmin_id;
    form.regimenEduc_id = ie.regimenEduc_id;
    form.tipoInstEduc_id = ie.tipoInstEduc_id;
    form.modalidadFormativa_id = ie.modalidadFormativa_id;
    form.nivelCiclo_id = ie.nivelCiclo_id;
    form.fechaInicio = ie.fechaInicio || '';
    form.fechaFin = ie.fechaFin || '';
    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(
            InstitucionEducativaController.update({
                institucione: editingId.value,
            }).url,
            {
                onSuccess: () => {
                    showModal.value = false;
                    form.reset();
                },
            },
        );
    } else {
        form.post(InstitucionEducativaController.store().url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
}

// ─── Modal Eliminar ───
const showDeleteModal = ref(false);
const ieToDelete = ref<InstitucionEducativa | null>(null);
const isDeleting = ref(false);

function confirmDelete(ie: InstitucionEducativa) {
    ieToDelete.value = ie;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!ieToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(
        InstitucionEducativaController.destroy({
            institucione: ieToDelete.value.id,
        }).url,
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                ieToDelete.value = null;
            },
            onFinish: () => {
                isDeleting.value = false;
            },
        },
    );
}
// ─── Modal Carga Masiva ───
const showMasivoModal = ref(false);

function onMasivoSuccess() {
    router.reload({ only: ['instituciones'] });
}
</script>

<template>
    <Head title="Instituciones Educativas" />

    <div class="flex flex-col gap-4 p-4">
        <!-- Encabezado -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    Instituciones Educativas
                </h1>
                <p class="text-sm text-muted-foreground">
                    Gestión de instituciones educativas, sus cursos, grados y
                    secciones.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Button variant="outline" @click="showMasivoModal = true">
                    <Upload class="mr-2 h-4 w-4" />
                    Carga Masiva
                </Button>
                <Button @click="openCreateModal">
                    <Plus class="mr-2 h-4 w-4" />
                    Nueva Institución
                </Button>
            </div>
        </div>

        <!-- Barra de Búsqueda -->
        <div class="flex items-center gap-2">
            <div class="relative w-full max-w-md">
                <Search
                    class="absolute top-2.5 left-3 h-4 w-4 text-muted-foreground"
                />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Buscar por código, código modular o nombre..."
                    class="flex h-9 w-full rounded-md border border-input bg-transparent py-1 pr-3 pl-9 text-sm shadow-xs placeholder:text-muted-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none"
                />
            </div>
        </div>

        <!-- Tabla Principal -->
        <div class="overflow-hidden rounded-md border bg-card shadow-xs">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[140px]">Código</TableHead>
                        <TableHead class="w-[130px]">Cód. Modular</TableHead>
                        <TableHead>Nombre Legal</TableHead>
                        <TableHead>Tipo</TableHead>
                        <TableHead>Nivel/Ciclo</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="ie in props.instituciones.data"
                        :key="ie.id"
                    >
                        <TableCell class="font-semibold text-primary">
                            <div class="flex items-center gap-1.5">
                                <School
                                    class="h-4 w-4 shrink-0 text-muted-foreground"
                                />
                                {{ ie.codigoInstitucion }}
                            </div>
                        </TableCell>
                        <TableCell class="text-xs font-medium">
                            {{ ie.codigoModular || '-' }}
                        </TableCell>
                        <TableCell>
                            <div class="text-sm font-medium">
                                {{ ie.nombreLegal }}
                            </div>
                            <div
                                v-if="ie.entidad_admin"
                                class="inline-flex items-center rounded-md border border-muted bg-muted/50 px-2 py-0.5 text-xs font-medium text-muted-foreground"
                            >
                                {{ ie.entidad_admin.razonSocial }}
                            </div>
                        </TableCell>
                        <TableCell>
                            {{ ie.tipo_inst_educ?.nombre || '-' }}
                        </TableCell>
                        <TableCell>
                            {{ ie.nivel_ciclo?.nombre || '-' }}
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
                                                InstitucionEducativaController.show(
                                                    {
                                                        institucione: ie.id,
                                                    },
                                                ).url
                                            "
                                            class="flex items-center"
                                        >
                                            <Eye class="mr-2 h-4 w-4" />
                                            <span>Ver Detalles</span>
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem
                                        @click="openEditModal(ie)"
                                    >
                                        <Pencil class="mr-2 h-4 w-4" />
                                        <span>Editar</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        @click="confirmDelete(ie)"
                                        class="cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive"
                                    >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Eliminar</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.instituciones.data.length === 0">
                        <TableCell
                            colspan="6"
                            class="h-24 text-center text-muted-foreground"
                        >
                            No hay instituciones educativas registradas.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Paginación -->
            <div
                class="flex items-center justify-between border-t px-4 py-3"
                v-if="props.instituciones.total > 0"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.instituciones.current_page }} de
                    {{ props.instituciones.last_page }} ({{
                        props.instituciones.total
                    }}
                    registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.instituciones.current_page <= 1"
                        @click="
                            router.get(
                                InstitucionEducativaController.index().url,
                                {
                                    page: props.instituciones.current_page - 1,
                                    search: search || undefined,
                                },
                            )
                        "
                    >
                        Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.instituciones.current_page >=
                            props.instituciones.last_page
                        "
                        @click="
                            router.get(
                                InstitucionEducativaController.index().url,
                                {
                                    page: props.instituciones.current_page + 1,
                                    search: search || undefined,
                                },
                            )
                        "
                    >
                        Siguiente
                    </Button>
                </div>
            </div>
        </div>

        <!-- Modal Crear/Editar -->
        <FormModal
            v-model:show="showModal"
            :title="
                isEditing
                    ? 'Editar Institución Educativa'
                    : 'Nueva Institución Educativa'
            "
            max-width="3xl"
            :processing="form.processing"
            @submit="submitForm"
        >
            <div class="grid grid-cols-2 gap-4">
                <div class="grid gap-2">
                    <Label>Código Institución *</Label>
                    <Input
                        v-model="form.codigoInstitucion"
                        placeholder="Ej: 0123456"
                        :class="{
                            'border-destructive': form.errors.codigoInstitucion,
                        }"
                    />
                    <p
                        v-if="form.errors.codigoInstitucion"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.codigoInstitucion }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label>Código Modular</Label>
                    <Input
                        v-model="form.codigoModular"
                        placeholder="Ej: 0654321"
                    />
                </div>
                <div class="col-span-2 grid gap-2">
                    <Label>Nombre Legal *</Label>
                    <Input
                        v-model="form.nombreLegal"
                        placeholder="Nombre legal de la institución"
                        :class="{
                            'border-destructive': form.errors.nombreLegal,
                        }"
                    />
                    <p
                        v-if="form.errors.nombreLegal"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.nombreLegal }}
                    </p>
                </div>
                <EntidadSelect
                    tipoEntidadCodigo="UGEL"
                    v-model="form.entidadUgel_id"
                    label="UGEL"
                    placeholder="Seleccionar UGEL..."
                    :error="form.errors.entidadUgel_id"
                />
                <EntidadSelect
                    tipoEntidadCodigo="IE"
                    v-model="form.entidadAdmin_id"
                    label="Entidad Admin"
                    placeholder="Seleccionar Entidad Administradora..."
                    :error="form.errors.entidadAdmin_id"
                />
                <ParamSelect
                    type="tipo-inst-educ"
                    v-model="form.tipoInstEduc_id"
                    label="Tipo de Institución"
                    placeholder="Seleccionar tipo..."
                    :error="form.errors.tipoInstEduc_id"
                />
                <ParamSelect
                    type="regimen-educ"
                    v-model="form.regimenEduc_id"
                    label="Régimen Educativo"
                    placeholder="Seleccionar régimen..."
                    :error="form.errors.regimenEduc_id"
                />
                <ParamSelect
                    type="modalidades-form"
                    v-model="form.modalidadFormativa_id"
                    label="Modalidad Formativa *"
                    placeholder="Seleccionar modalidad..."
                    :error="form.errors.modalidadFormativa_id"
                />
                <ParamSelect
                    type="niveles-ciclo"
                    v-model="form.nivelCiclo_id"
                    label="Nivel / Ciclo *"
                    placeholder="Seleccionar nivel..."
                    :error="form.errors.nivelCiclo_id"
                />
                <div class="grid gap-2">
                    <Label>Fecha Inicio</Label>
                    <Input v-model="form.fechaInicio" type="date" />
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
            </div>
        </FormModal>

        <!-- Modal Eliminar -->
        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Eliminar Institución"
            :description="`¿Estás seguro de que deseas eliminar la institución ${ieToDelete?.nombreLegal}? Esta acción no se puede deshacer.`"
            confirm-text="Eliminar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="ieToDelete = null"
        />
        <!-- Modal Carga Masiva -->
        <InstitucionMasivaGrid
            v-if="showMasivoModal"
            @close="showMasivoModal = false"
            @success="onMasivoSuccess"
        />
    </div>
</template>

<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import {
    Plus,
    Pencil,
    Trash2,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import LocalController from '@/actions/App/Http/Controllers/Infraestructura/LocalController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
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
import type { Local } from '@/types/models/infraestructura';

export interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Infraestructura', href: '#' },
            { title: 'Locales', href: LocalController.index().url },
        ],
    },
});

const props = defineProps<{
    locales: PaginatedResponse<Local>;
    filters: { search?: string };
}>();

// ─── Búsqueda ───
const searchQuery = ref(props.filters.search || '');
let searchTimeout: ReturnType<typeof setTimeout>;

watch(searchQuery, (val) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            LocalController.index().url,
            { search: val || undefined },
            { preserveState: true, replace: true },
        );
    }, 300);
});

// ─── Modal crear/editar ───
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    nombre: '',
    domicilio: '',
    zona_id: '' as string | number,
    ubigeo: '',
    utm_huso: '' as string | number,
    utm_banda: '',
    utm_x_este: '' as string | number,
    utm_y_norte: '' as string | number,
    activo: true,
});

watch(showModal, (val) => {
    if (!val) {
        form.reset();
        form.clearErrors();
    }
});

function openCreate() {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
}

function openEdit(local: Local) {
    isEditing.value = true;
    editingId.value = local.id;
    form.clearErrors();
    form.nombre = local.nombre || '';
    form.domicilio = local.domicilio || '';
    form.zona_id = local.zona_id || '';
    form.ubigeo = local.ubigeo || '';
    form.utm_huso = local.utm_huso || '';
    form.utm_banda = local.utm_banda || '';
    form.utm_x_este = local.utm_x_este || '';
    form.utm_y_norte = local.utm_y_norte || '';
    form.activo = local.activo ?? true;
    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(LocalController.update({ local: editingId.value }).url, {
            onSuccess: () => {
                showModal.value = false;
            },
        });
    } else {
        form.post(LocalController.store().url, {
            onSuccess: () => {
                showModal.value = false;
            },
        });
    }
}

// ─── Eliminar ───
const showDelete = ref(false);
const deleteId = ref<number | null>(null);
const deleteName = ref('');
const isDeleting = ref(false);

function confirmDelete(local: Local) {
    deleteId.value = local.id;
    deleteName.value = local.nombre || 'Local';
    showDelete.value = true;
}

function executeDelete() {
    if (!deleteId.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(LocalController.destroy({ local: deleteId.value }).url, {
        onSuccess: () => {
            showDelete.value = false;
            deleteId.value = null;
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
}
</script>

<template>
    <Head title="Locales" />

    <div class="flex flex-col gap-4 p-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    Locales
                </h1>
                <p class="text-sm text-muted-foreground">
                    Gestión de locales físicos del sistema.
                </p>
            </div>
            <Button size="sm" @click="openCreate()"
                ><Plus class="mr-2 h-4 w-4" /> Nuevo Local</Button
            >
        </div>

        <!-- Búsqueda -->
        <div class="flex items-center gap-2">
            <Input
                v-model="searchQuery"
                placeholder="Buscar por nombre o domicilio..."
                class="max-w-sm"
            />
        </div>

        <!-- Tabla -->
        <div class="overflow-hidden rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Nombre</TableHead>
                        <TableHead>Domicilio</TableHead>
                        <TableHead>Zona</TableHead>
                        <TableHead class="w-[100px]">Ubigeo</TableHead>
                        <TableHead class="w-[80px]">Estado</TableHead>
                        <TableHead class="w-[100px] text-right"
                            >Acciones</TableHead
                        >
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="local in props.locales.data"
                        :key="local.id"
                    >
                        <TableCell class="text-sm font-medium">{{
                            local.nombre || '-'
                        }}</TableCell>
                        <TableCell class="text-sm">{{
                            local.domicilio || '-'
                        }}</TableCell>
                        <TableCell class="text-sm">{{
                            local.zona?.nombre || '-'
                        }}</TableCell>
                        <TableCell class="text-xs">{{
                            local.ubigeo || '-'
                        }}</TableCell>
                        <TableCell
                            ><StatusBadge :active="local.activo ?? false"
                        /></TableCell>
                        <TableCell class="text-right">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child
                                    ><Button
                                        variant="ghost"
                                        size="sm"
                                        class="h-7"
                                        ><ChevronDown class="h-4 w-4" /></Button
                                ></DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuItem @click="openEdit(local)"
                                        ><Pencil class="mr-2 h-4 w-4" />
                                        Editar</DropdownMenuItem
                                    >
                                    <DropdownMenuItem
                                        @click="confirmDelete(local)"
                                        class="text-destructive"
                                        ><Trash2 class="mr-2 h-4 w-4" />
                                        Desactivar</DropdownMenuItem
                                    >
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="!props.locales.data.length">
                        <TableCell
                            colspan="6"
                            class="h-20 text-center text-muted-foreground"
                            >No se encontraron locales.</TableCell
                        >
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Paginación -->
        <div
            v-if="props.locales.last_page > 1"
            class="flex items-center justify-between"
        >
            <p class="text-sm text-muted-foreground">
                Página {{ props.locales.current_page }} de
                {{ props.locales.last_page }} ({{ props.locales.total }}
                registros)
            </p>
            <div class="flex gap-2">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="props.locales.current_page <= 1"
                    @click="
                        router.get(
                            LocalController.index().url,
                            {
                                page: props.locales.current_page - 1,
                                search: searchQuery || undefined,
                            },
                            { preserveState: true },
                        )
                    "
                >
                    <ChevronLeft class="h-4 w-4" /> Anterior
                </Button>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="
                        props.locales.current_page >= props.locales.last_page
                    "
                    @click="
                        router.get(
                            LocalController.index().url,
                            {
                                page: props.locales.current_page + 1,
                                search: searchQuery || undefined,
                            },
                            { preserveState: true },
                        )
                    "
                >
                    Siguiente <ChevronRight class="h-4 w-4" />
                </Button>
            </div>
        </div>

        <!-- Modal Crear/Editar -->
        <FormModal
            v-model:show="showModal"
            :title="isEditing ? 'Editar Local' : 'Nuevo Local'"
            :processing="form.processing"
            @submit="submitForm"
        >
            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label>Nombre *</Label>
                    <Input
                        v-model="form.nombre"
                        placeholder="Nombre del local"
                        :class="{ 'border-destructive': form.errors.nombre }"
                    />
                    <p
                        v-if="form.errors.nombre"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.nombre }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label>Domicilio</Label>
                    <Input
                        v-model="form.domicilio"
                        placeholder="Dirección del local"
                    />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>Zona ID</Label>
                        <Input
                            v-model="form.zona_id"
                            type="number"
                            placeholder="ID de zona"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label>Ubigeo</Label>
                        <Input
                            v-model="form.ubigeo"
                            placeholder="Código ubigeo"
                        />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>UTM Huso</Label>
                        <Input
                            v-model="form.utm_huso"
                            type="number"
                            step="0.0001"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label>UTM Banda</Label>
                        <Input
                            v-model="form.utm_banda"
                            placeholder="Banda UTM"
                        />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>UTM X (Este)</Label>
                        <Input
                            v-model="form.utm_x_este"
                            type="number"
                            step="0.0001"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label>UTM Y (Norte)</Label>
                        <Input
                            v-model="form.utm_y_norte"
                            type="number"
                            step="0.0001"
                        />
                    </div>
                </div>
            </div>
        </FormModal>

        <!-- Modal Eliminar -->
        <ConfirmModal
            v-model:show="showDelete"
            title="Desactivar Local"
            :description="`¿Desactivar '${deleteName}'?`"
            confirm-text="Desactivar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="deleteId = null"
        />
    </div>
</template>

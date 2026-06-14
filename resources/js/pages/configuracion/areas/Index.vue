<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, ChevronDown } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import AreaController from '@/actions/App/Http/Controllers/Configuracion/AreaController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
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
import type { Area } from '@/types/models/configuracion';

interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Configuración', href: '#' },
            { title: 'Áreas', href: AreaController.index().url },
        ],
    },
});

const props = defineProps<{
    areas: PaginatedResponse<Area>;
    filters: { search?: string };
}>();

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    nombre: '',
    sigla: '',
    descripcion: '',
    rolTrabajador_id: null as number | null,
    activo: true,
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

function openEditModal(area: Area) {
    isEditing.value = true;
    editingId.value = area.id;
    form.clearErrors();
    form.nombre = area.nombre ?? '';
    form.sigla = area.sigla ?? '';
    form.descripcion = area.descripcion ?? '';
    form.rolTrabajador_id = area.rolTrabajador_id;
    form.activo = area.activo;
    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(AreaController.update({ area: editingId.value }).url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    } else {
        form.post(AreaController.store().url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
}

const showDeleteModal = ref(false);
const areaToDelete = ref<Area | null>(null);
const isDeleting = ref(false);

function confirmDelete(area: Area) {
    areaToDelete.value = area;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!areaToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(AreaController.destroy({ area: areaToDelete.value.id }).url, {
        onSuccess: () => {
            showDeleteModal.value = false;
            areaToDelete.value = null;
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
}
</script>

<template>
    <Head title="Áreas" />

    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Áreas</h1>
            <Button @click="openCreateModal">
                <Plus class="mr-2 h-4 w-4" />
                Nueva Área
            </Button>
        </div>

        <div class="rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Código</TableHead>
                        <TableHead>Nombre</TableHead>
                        <TableHead>Sigla</TableHead>
                        <TableHead>Descripción</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="area in props.areas.data" :key="area.id">
                        <TableCell class="font-mono text-sm">{{
                            area.codigo || '-'
                        }}</TableCell>
                        <TableCell class="font-medium">{{
                            area.nombre
                        }}</TableCell>
                        <TableCell>{{ area.sigla || '-' }}</TableCell>
                        <TableCell
                            class="max-w-xs truncate text-muted-foreground"
                            >{{ area.descripcion || '-' }}</TableCell
                        >
                        <TableCell>
                            <StatusBadge :active="area.activo" />
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
                                    <DropdownMenuItem
                                        @click="openEditModal(area)"
                                    >
                                        <Pencil class="mr-2 h-4 w-4" />
                                        <span>Editar</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        @click="confirmDelete(area)"
                                        class="cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive"
                                    >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Eliminar</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.areas.data.length === 0">
                        <TableCell colspan="6" class="h-24 text-center">
                            No hay áreas registradas.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div
                class="flex items-center justify-between border-t px-4 py-3"
                v-if="props.areas.total > 0"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.areas.current_page }} de
                    {{ props.areas.last_page }} ({{ props.areas.total }}
                    registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.areas.current_page <= 1"
                        @click="
                            router.get(AreaController.index().url, {
                                page: props.areas.current_page - 1,
                            })
                        "
                    >
                        Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.areas.current_page >= props.areas.last_page
                        "
                        @click="
                            router.get(AreaController.index().url, {
                                page: props.areas.current_page + 1,
                            })
                        "
                    >
                        Siguiente
                    </Button>
                </div>
            </div>
        </div>

        <FormModal
            v-model:show="showModal"
            :title="isEditing ? 'Editar Área' : 'Nueva Área'"
            :processing="form.processing"
            @submit="submitForm"
        >
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="nombre">Nombre *</Label>
                    <Input
                        id="nombre"
                        v-model="form.nombre"
                        placeholder="Nombre del área"
                    />
                    <p
                        v-if="form.errors.nombre"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.nombre }}
                    </p>
                </div>

                <div class="grid gap-2">
                    <Label for="sigla">Sigla</Label>
                    <Input
                        id="sigla"
                        v-model="form.sigla"
                        placeholder="Ej: TI"
                    />
                    <p
                        v-if="form.errors.sigla"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.sigla }}
                    </p>
                </div>

                <div class="grid gap-2">
                    <Label for="descripcion">Descripción</Label>
                    <Input
                        id="descripcion"
                        v-model="form.descripcion"
                        placeholder="Descripción opcional"
                    />
                    <p
                        v-if="form.errors.descripcion"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.descripcion }}
                    </p>
                </div>

                <div class="mt-2 flex items-center space-x-2">
                    <input
                        id="activo"
                        type="checkbox"
                        :checked="!!form.activo"
                        @change="
                            (e) =>
                                (form.activo = (
                                    e.target as HTMLInputElement
                                ).checked)
                        "
                        class="size-4 cursor-pointer rounded border-input accent-primary"
                    />
                    <Label for="activo" class="cursor-pointer"
                        >Área Activa</Label
                    >
                </div>
                <p v-if="form.errors.activo" class="text-sm text-destructive">
                    {{ form.errors.activo }}
                </p>
            </div>
        </FormModal>

        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Desactivar Área"
            :description="`¿Estás seguro de que deseas desactivar el área '${areaToDelete?.nombre}'?`"
            confirm-text="Desactivar"
            cancel-text="Cancelar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="areaToDelete = null"
        />
    </div>
</template>

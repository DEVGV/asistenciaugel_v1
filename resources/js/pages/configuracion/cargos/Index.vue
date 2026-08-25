<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, ChevronDown } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import CargoController from '@/actions/App/Http/Controllers/Configuracion/CargoController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import { Button } from '@/components/ui/button';
import StatusBadge from '@/components/shared/StatusBadge.vue';
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
import type { Cargo } from '@/types/models/configuracion';

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
            { title: 'Cargos', href: CargoController.index().url },
        ],
    },
});

const props = defineProps<{
    cargos: PaginatedResponse<Cargo>;
    filters: { search?: string };
}>();

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    nombre: '',
    abreviatura: '',
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

function openEditModal(cargo: Cargo) {
    isEditing.value = true;
    editingId.value = cargo.id;
    form.clearErrors();
    form.nombre = cargo.nombre ?? '';
    form.abreviatura = cargo.abreviatura ?? '';
    form.rolTrabajador_id = cargo.rolTrabajador_id;
    form.activo = cargo.activo;
    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(CargoController.update({ cargo: editingId.value }).url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    } else {
        form.post(CargoController.store().url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
}

const showDeleteModal = ref(false);
const cargoToDelete = ref<Cargo | null>(null);
const isDeleting = ref(false);

function confirmDelete(cargo: Cargo) {
    cargoToDelete.value = cargo;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!cargoToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(
        CargoController.destroy({ cargo: cargoToDelete.value.id }).url,
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                cargoToDelete.value = null;
            },
            onFinish: () => {
                isDeleting.value = false;
            },
        },
    );
}
</script>

<template>
    <Head title="Cargos" />
    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Cargos</h1>
            <Button @click="openCreateModal"
                ><Plus class="mr-2 h-4 w-4" />Nuevo Cargo</Button
            >
        </div>
        <div class="rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Código</TableHead>
                        <TableHead>Nombre</TableHead>
                        <TableHead>Abreviatura</TableHead>
                        <TableHead>Rol Trabajador</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="cargo in props.cargos.data"
                        :key="cargo.id"
                    >
                        <TableCell class="font-mono text-sm">{{
                            cargo.codigo || '-'
                        }}</TableCell>
                        <TableCell class="font-medium">{{
                            cargo.nombre
                        }}</TableCell>
                        <TableCell>{{ cargo.abreviatura || '-' }}</TableCell>
                        <TableCell class="text-sm text-muted-foreground">{{
                            cargo.rol_trabajador?.nombre || '-'
                        }}</TableCell>
                        <TableCell>
                            <StatusBadge :active="cargo.activo" />
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
                                        @click="openEditModal(cargo)"
                                    >
                                        <Pencil class="mr-2 h-4 w-4" />
                                        <span>Editar</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        @click="confirmDelete(cargo)"
                                        class="cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive"
                                    >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Eliminar</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.cargos.data.length === 0">
                        <TableCell colspan="6" class="h-24 text-center"
                            >No hay cargos registrados.</TableCell
                        >
                    </TableRow>
                </TableBody>
            </Table>
            <div
                class="flex items-center justify-between border-t px-4 py-3"
                v-if="props.cargos.total > 0"
            >
                <span class="text-sm text-muted-foreground"
                    >Página {{ props.cargos.current_page }} de
                    {{ props.cargos.last_page }} ({{
                        props.cargos.total
                    }}
                    registros)</span
                >
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.cargos.current_page <= 1"
                        @click="
                            router.get(CargoController.index().url, {
                                page: props.cargos.current_page - 1,
                            })
                        "
                        >Anterior</Button
                    >
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.cargos.current_page >= props.cargos.last_page
                        "
                        @click="
                            router.get(CargoController.index().url, {
                                page: props.cargos.current_page + 1,
                            })
                        "
                        >Siguiente</Button
                    >
                </div>
            </div>
        </div>

        <FormModal
            v-model:show="showModal"
            :title="isEditing ? 'Editar Cargo' : 'Nuevo Cargo'"
            :processing="form.processing"
            @submit="submitForm"
        >
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="nombre">Nombre *</Label>
                    <Input
                        id="nombre"
                        v-model="form.nombre"
                        placeholder="Nombre del cargo"
                    />
                    <p
                        v-if="form.errors.nombre"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.nombre }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label for="abreviatura">Abreviatura</Label>
                    <Input
                        id="abreviatura"
                        v-model="form.abreviatura"
                        placeholder="Ej: DIR"
                    />
                </div>
                <ParamSelect
                    v-model="form.rolTrabajador_id"
                    type="roles-trabajador"
                    label="Rol Trabajador"
                    :error="form.errors.rolTrabajador_id"
                />
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
                        >Cargo Activo</Label
                    >
                </div>
            </div>
        </FormModal>

        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Desactivar Cargo"
            :description="`¿Desactivar el cargo '${cargoToDelete?.nombre}'?`"
            confirm-text="Desactivar"
            cancel-text="Cancelar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="cargoToDelete = null"
        />
    </div>
</template>

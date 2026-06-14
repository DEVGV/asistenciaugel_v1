<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, ChevronDown, Upload } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import EntidadController from '@/actions/App/Http/Controllers/Entidad/EntidadController';
import EntidadForm from '@/components/entidad/EntidadForm.vue';
import EntidadMasivaGrid from '@/components/entidad/EntidadMasivaGrid.vue';
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
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

import type { Entidad } from '@/types/models/entidad';

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
            { title: 'Entidades', href: EntidadController.index().url },
        ],
    },
});

const props = defineProps<{
    entidades: PaginatedResponse<Entidad>;
    filters: { search?: string };
}>();

// Estado del Modal
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    tipoEntidad_id: null as number | null,
    ruc: '',
    razonSocial: '',
    razonComercial: '',
    personaRepLegal_id: null as number | null,
    activo: true,
});

// Limpiar el formulario al cerrar el modal
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

function openEditModal(entidad: Entidad) {
    isEditing.value = true;
    editingId.value = entidad.id;
    form.clearErrors();
    form.tipoEntidad_id = entidad.tipoEntidad_id;
    form.ruc = entidad.ruc;
    form.razonSocial = entidad.razonSocial;
    form.razonComercial = entidad.razonComercial || '';
    form.personaRepLegal_id = entidad.personaRepLegal_id;
    form.activo = entidad.activo;
    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(EntidadController.update({ entidade: editingId.value }).url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    } else {
        form.post(EntidadController.store().url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
}

const showDeleteModal = ref(false);
const entidadToDelete = ref<Entidad | null>(null);
const isDeleting = ref(false);

function confirmDeleteEntidad(entidad: Entidad) {
    entidadToDelete.value = entidad;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!entidadToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(
        EntidadController.destroy({ entidade: entidadToDelete.value.id }).url,
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                entidadToDelete.value = null;
            },
            onFinish: () => {
                isDeleting.value = false;
            },
        },
    );
}

// Carga Masiva
const showMasivoModal = ref(false);

function onMasivoSuccess(count: number) {
    showMasivoModal.value = false;
    router.reload({ only: ['entidades'] });
    console.log(`${count} entidades registradas en lote.`);
}
</script>

<template>
    <Head title="Entidades" />

    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Entidades</h1>
            <div class="flex items-center gap-2">
                <Button variant="outline" @click="showMasivoModal = true">
                    <Upload class="mr-2 h-4 w-4" />
                    Carga Masiva
                </Button>
                <Button @click="openCreateModal">
                    <Plus class="mr-2 h-4 w-4" />
                    Nueva Entidad
                </Button>
            </div>
        </div>

        <div class="rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>RUC</TableHead>
                        <TableHead>Razón Social</TableHead>
                        <TableHead>Tipo</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="entidad in props.entidades.data"
                        :key="entidad.id"
                    >
                        <TableCell class="font-medium">{{
                            entidad.ruc
                        }}</TableCell>
                        <TableCell>
                            <div>{{ entidad.razonSocial }}</div>
                            <div class="text-sm text-muted-foreground">
                                {{ entidad.razonComercial }}
                            </div>
                        </TableCell>
                        <TableCell>{{
                            entidad.tipo_entidad?.nombre || '-'
                        }}</TableCell>
                        <TableCell>
                            <StatusBadge :active="entidad.activo" />
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
                                        @click="openEditModal(entidad)"
                                    >
                                        <Pencil class="mr-2 h-4 w-4" />
                                        <span>Editar</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        @click="confirmDeleteEntidad(entidad)"
                                        class="cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive"
                                    >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Eliminar</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.entidades.data.length === 0">
                        <TableCell colspan="5" class="h-24 text-center">
                            No hay entidades registradas.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div
                class="flex items-center justify-between border-t px-4 py-3"
                v-if="props.entidades.total > 0"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.entidades.current_page }} de
                    {{ props.entidades.last_page }} ({{ props.entidades.total }}
                    registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.entidades.current_page <= 1"
                        @click="
                            router.get(EntidadController.index().url, {
                                page: props.entidades.current_page - 1,
                            })
                        "
                    >
                        Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.entidades.current_page >=
                            props.entidades.last_page
                        "
                        @click="
                            router.get(EntidadController.index().url, {
                                page: props.entidades.current_page + 1,
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
            :title="isEditing ? 'Editar Entidad' : 'Nueva Entidad'"
            :processing="form.processing"
            @submit="submitForm"
        >
            <EntidadForm
                :form="form"
                @ruc-data="
                    (data) => {
                        form.razonSocial = data.razonSocial;
                    }
                "
            />
        </FormModal>

        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Eliminar Entidad"
            :description="`¿Estás seguro de que deseas eliminar la entidad ${entidadToDelete?.razonSocial}? Esta acción no se puede deshacer.`"
            confirm-text="Eliminar"
            cancel-text="Cancelar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="entidadToDelete = null"
        />

        <!-- Modal Carga Masiva Grid -->
        <EntidadMasivaGrid
            v-if="showMasivoModal"
            @close="showMasivoModal = false"
            @success="onMasivoSuccess"
        />
    </div>
</template>

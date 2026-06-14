<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, ChevronDown } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import CondicionLaboralController from '@/actions/App/Http/Controllers/Configuracion/CondicionLaboralController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import { Badge } from '@/components/ui/badge';
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
import type { CondicionLaboral } from '@/types/models/configuracion';

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
            {
                title: 'Condiciones Laborales',
                href: CondicionLaboralController.index().url,
            },
        ],
    },
});

const props = defineProps<{
    condiciones: PaginatedResponse<CondicionLaboral>;
    filters: { search?: string };
}>();

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    nombre: '',
    abreviatura: '',
    descripcion: '',
    regimenLaboral_id: null as number | null,
    tipoTrabajador_id: null as number | null,
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

function openEditModal(condicion: CondicionLaboral) {
    isEditing.value = true;
    editingId.value = condicion.id;
    form.clearErrors();
    form.nombre = condicion.nombre ?? '';
    form.abreviatura = condicion.abreviatura ?? '';
    form.descripcion = condicion.descripcion ?? '';
    form.regimenLaboral_id = condicion.regimenLaboral_id;
    form.tipoTrabajador_id = condicion.tipoTrabajador_id;
    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(
            CondicionLaboralController.update({
                condicionLaboral: editingId.value,
            }).url,
            {
                onSuccess: () => {
                    showModal.value = false;
                    form.reset();
                },
            },
        );
    } else {
        form.post(CondicionLaboralController.store().url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
}

const showDeleteModal = ref(false);
const condicionToDelete = ref<CondicionLaboral | null>(null);
const isDeleting = ref(false);

function confirmDelete(condicion: CondicionLaboral) {
    condicionToDelete.value = condicion;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!condicionToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(
        CondicionLaboralController.destroy({
            condicionLaboral: condicionToDelete.value.id,
        }).url,
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                condicionToDelete.value = null;
            },
            onFinish: () => {
                isDeleting.value = false;
            },
        },
    );
}
</script>

<template>
    <Head title="Condiciones Laborales" />
    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">
                Condiciones Laborales
            </h1>
            <Button @click="openCreateModal"
                ><Plus class="mr-2 h-4 w-4" />Nueva Condición</Button
            >
        </div>
        <div class="rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Código</TableHead>
                        <TableHead>Nombre</TableHead>
                        <TableHead>Abreviatura</TableHead>
                        <TableHead>Régimen Laboral</TableHead>
                        <TableHead>Tipo Trabajador</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="condicion in props.condiciones.data"
                        :key="condicion.id"
                    >
                        <TableCell class="font-mono text-sm">{{
                            condicion.codigo || '-'
                        }}</TableCell>
                        <TableCell class="font-medium">{{
                            condicion.nombre
                        }}</TableCell>
                        <TableCell>
                            <Badge
                                v-if="condicion.abreviatura"
                                variant="outline"
                                class="border-sky-200 bg-sky-50 text-[10px] font-semibold tracking-wider text-sky-700 uppercase dark:border-sky-800 dark:bg-sky-950 dark:text-sky-300"
                            >
                                {{ condicion.abreviatura }}
                            </Badge>
                            <span v-else>-</span>
                        </TableCell>
                        <TableCell class="text-sm text-muted-foreground">{{
                            condicion.regimenLaboral?.nombre || '-'
                        }}</TableCell>
                        <TableCell class="text-sm text-muted-foreground">{{
                            condicion.tipoTrabajador?.nombre || '-'
                        }}</TableCell>
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
                                        @click="openEditModal(condicion)"
                                    >
                                        <Pencil class="mr-2 h-4 w-4" />
                                        <span>Editar</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        @click="confirmDelete(condicion)"
                                        class="cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive"
                                    >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Eliminar</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.condiciones.data.length === 0">
                        <TableCell colspan="6" class="h-24 text-center"
                            >No hay condiciones laborales
                            registradas.</TableCell
                        >
                    </TableRow>
                </TableBody>
            </Table>
            <div
                class="flex items-center justify-between border-t px-4 py-3"
                v-if="props.condiciones.total > 0"
            >
                <span class="text-sm text-muted-foreground"
                    >Página {{ props.condiciones.current_page }} de
                    {{ props.condiciones.last_page }} ({{
                        props.condiciones.total
                    }}
                    registros)</span
                >
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.condiciones.current_page <= 1"
                        @click="
                            router.get(CondicionLaboralController.index().url, {
                                page: props.condiciones.current_page - 1,
                            })
                        "
                        >Anterior</Button
                    >
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.condiciones.current_page >=
                            props.condiciones.last_page
                        "
                        @click="
                            router.get(CondicionLaboralController.index().url, {
                                page: props.condiciones.current_page + 1,
                            })
                        "
                        >Siguiente</Button
                    >
                </div>
            </div>
        </div>

        <FormModal
            v-model:show="showModal"
            :title="
                isEditing
                    ? 'Editar Condición Laboral'
                    : 'Nueva Condición Laboral'
            "
            :processing="form.processing"
            @submit="submitForm"
        >
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="nombre">Nombre *</Label>
                    <Input
                        id="nombre"
                        v-model="form.nombre"
                        placeholder="Nombre de la condición laboral"
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
                        placeholder="Ej: CAS"
                    />
                </div>
                <ParamSelect
                    v-model="form.regimenLaboral_id"
                    type="regimenes-laborales"
                    label="Régimen Laboral *"
                    :error="form.errors.regimenLaboral_id"
                />
                <ParamSelect
                    v-model="form.tipoTrabajador_id"
                    type="tipos-trabajador"
                    label="Tipo de Trabajador *"
                    :error="form.errors.tipoTrabajador_id"
                />
                <div class="grid gap-2">
                    <Label for="descripcion">Descripción</Label>
                    <Input
                        id="descripcion"
                        v-model="form.descripcion"
                        placeholder="Descripción opcional"
                    />
                </div>
            </div>
        </FormModal>

        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Eliminar Condición Laboral"
            :description="`¿Eliminar la condición '${condicionToDelete?.nombre}'? Esta acción no se puede deshacer.`"
            confirm-text="Eliminar"
            cancel-text="Cancelar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="condicionToDelete = null"
        />
    </div>
</template>

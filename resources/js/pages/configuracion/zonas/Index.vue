<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, ChevronDown } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import ZonaController from '@/actions/App/Http/Controllers/Configuracion/ZonaController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
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
import type { Zona } from '@/types/models/configuracion';

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
            { title: 'Zonas', href: ZonaController.index().url },
        ],
    },
});

const props = defineProps<{
    zonas: PaginatedResponse<Zona>;
    filters: { search?: string };
}>();

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const selectedDepartamento = ref<number | null>(null);
const selectedProvincia = ref<number | null>(null);

const form = useForm({
    nombre: '',
    abreviatura: '',
    tipoZona_id: null as number | null,
    distrito_id: null as number | null,
    activo: true,
});

watch(showModal, (val) => {
    if (!val) {
        form.reset();
        form.clearErrors();
        selectedDepartamento.value = null;
        selectedProvincia.value = null;
    }
});

function openCreateModal() {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    selectedDepartamento.value = null;
    selectedProvincia.value = null;
    showModal.value = true;
}

function openEditModal(zona: Zona) {
    isEditing.value = true;
    editingId.value = zona.id;
    form.clearErrors();
    form.nombre = zona.nombre ?? '';
    form.abreviatura = zona.abreviatura ?? '';
    form.tipoZona_id = zona.tipoZona_id;
    form.distrito_id = zona.distrito_id;
    form.activo = zona.activo;

    if (zona.distrito) {
        selectedProvincia.value = zona.distrito.provincia?.id ?? null;
        selectedDepartamento.value =
            zona.distrito.provincia?.departamento?.id ?? null;
    } else {
        selectedProvincia.value = null;
        selectedDepartamento.value = null;
    }

    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(ZonaController.update({ zona: editingId.value }).url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    } else {
        form.post(ZonaController.store().url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
}

const showDeleteModal = ref(false);
const zonaToDelete = ref<Zona | null>(null);
const isDeleting = ref(false);

function confirmDelete(zona: Zona) {
    zonaToDelete.value = zona;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!zonaToDelete.value) {
return;
}

    isDeleting.value = true;
    router.delete(ZonaController.destroy({ zona: zonaToDelete.value.id }).url, {
        onSuccess: () => {
            showDeleteModal.value = false;
            zonaToDelete.value = null;
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
}

function getUbigeoText(zona: Zona): string {
    if (!zona.distrito) {
return '-';
}

    const dist = zona.distrito.nombre || '';
    const prov = zona.distrito.provincia?.nombre || '';
    const depto = zona.distrito.provincia?.departamento?.nombre || '';

    return [depto, prov, dist].filter(Boolean).join(' / ');
}
</script>

<template>
    <Head title="Zonas" />

    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Zonas</h1>
            <Button @click="openCreateModal">
                <Plus class="mr-2 h-4 w-4" />
                Nueva Zona
            </Button>
        </div>

        <div class="rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Nombre</TableHead>
                        <TableHead>Abreviatura</TableHead>
                        <TableHead>Tipo de Zona</TableHead>
                        <TableHead>Ubigeo (Depto / Prov / Dist)</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="zona in props.zonas.data" :key="zona.id">
                        <TableCell class="font-medium">{{
                            zona.nombre || '-'
                        }}</TableCell>
                        <TableCell>{{ zona.abreviatura || '-' }}</TableCell>
                        <TableCell>{{
                            zona.tipoZona?.nombre || '-'
                        }}</TableCell>
                        <TableCell class="text-muted-foreground">{{
                            getUbigeoText(zona)
                        }}</TableCell>
                        <TableCell>
                            <StatusBadge :active="zona.activo" />
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
                                        @click="openEditModal(zona)"
                                    >
                                        <Pencil class="mr-2 h-4 w-4" />
                                        <span>Editar</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        @click="confirmDelete(zona)"
                                        class="cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive"
                                    >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Eliminar</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.zonas.data.length === 0">
                        <TableCell colspan="6" class="h-24 text-center">
                            No hay zonas registradas.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div
                class="flex items-center justify-between border-t px-4 py-3"
                v-if="props.zonas.total > 0"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.zonas.current_page }} de
                    {{ props.zonas.last_page }} ({{ props.zonas.total }}
                    registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.zonas.current_page <= 1"
                        @click="
                            router.get(ZonaController.index().url, {
                                page: props.zonas.current_page - 1,
                            })
                        "
                    >
                        Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.zonas.current_page >= props.zonas.last_page
                        "
                        @click="
                            router.get(ZonaController.index().url, {
                                page: props.zonas.current_page + 1,
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
            :title="isEditing ? 'Editar Zona' : 'Nueva Zona'"
            :processing="form.processing"
            @submit="submitForm"
        >
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="nombre">Nombre *</Label>
                    <Input
                        id="nombre"
                        v-model="form.nombre"
                        placeholder="Nombre de la zona"
                    />
                    <p
                        v-if="form.errors.nombre"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.nombre }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="abreviatura">Abreviatura</Label>
                        <Input
                            id="abreviatura"
                            v-model="form.abreviatura"
                            placeholder="Ej: ZN-01"
                        />
                        <p
                            v-if="form.errors.abreviatura"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.abreviatura }}
                        </p>
                    </div>

                    <div class="grid gap-2">
                        <ParamSelect
                            type="tipos-zona"
                            label="Tipo de Zona *"
                            v-model="form.tipoZona_id"
                            placeholder="Seleccionar tipo..."
                            :error="form.errors.tipoZona_id"
                        />
                    </div>
                </div>

                <div class="border-t pt-3 mt-1">
                    <Label class="text-sm font-semibold text-muted-foreground block mb-3"
                        >Ubicación Geográfica (Ubigeo)</Label
                    >
                    <div class="grid gap-3">
                        <ParamSelect
                            type="departamentos"
                            label="Departamento"
                            v-model="selectedDepartamento"
                            placeholder="Seleccionar departamento..."
                            @update:modelValue="
                                () => {
                                    selectedProvincia = null;
                                    form.distrito_id = null;
                                }
                            "
                        />

                        <ParamSelect
                            type="provincias"
                            label="Provincia"
                            :parent-id="selectedDepartamento"
                            v-model="selectedProvincia"
                            placeholder="Seleccionar provincia..."
                            :disabled="!selectedDepartamento"
                            @update:modelValue="
                                () => {
                                    form.distrito_id = null;
                                }
                            "
                        />

                        <ParamSelect
                            type="distritos"
                            label="Distrito"
                            :parent-id="selectedProvincia"
                            v-model="form.distrito_id"
                            placeholder="Seleccionar distrito..."
                            :disabled="!selectedProvincia"
                            :error="form.errors.distrito_id"
                        />
                    </div>
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
                        >Zona Activa</Label
                    >
                </div>
                <p v-if="form.errors.activo" class="text-sm text-destructive">
                    {{ form.errors.activo }}
                </p>
            </div>
        </FormModal>

        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Desactivar Zona"
            :description="`¿Estás seguro de que deseas desactivar la zona '${zonaToDelete?.nombre}'?`"
            confirm-text="Desactivar"
            cancel-text="Cancelar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="zonaToDelete = null"
        />
    </div>
</template>

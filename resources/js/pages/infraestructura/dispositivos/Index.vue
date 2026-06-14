<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import {
    Plus,
    Trash2,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    Smartphone,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import DispositivoMarcaController from '@/actions/App/Http/Controllers/Infraestructura/DispositivoMarcaController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
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
import type { DispositivoMarca } from '@/types/models/infraestructura';

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
            {
                title: 'Dispositivos de Marca',
                href: DispositivoMarcaController.index().url,
            },
        ],
    },
});

const props = defineProps<{
    dispositivos: PaginatedResponse<DispositivoMarca>;
    filters: { search?: string };
}>();

const searchQuery = ref(props.filters.search || '');
let searchTimeout: ReturnType<typeof setTimeout>;

watch(searchQuery, (val) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            DispositivoMarcaController.index().url,
            { search: val || undefined },
            { preserveState: true, replace: true },
        );
    }, 300);
});

// ─── Modal crear ───
const showModal = ref(false);
const form = useForm({
    telefonoMovil_id: '' as string | number,
    fechaInicio: '',
    fechaFin: '',
});

watch(showModal, (val) => {
    if (!val) {
        form.reset();
        form.clearErrors();
    }
});

function openCreate() {
    form.reset();
    form.clearErrors();
    showModal.value = true;
}

function submitForm() {
    form.post(DispositivoMarcaController.store().url, {
        onSuccess: () => {
            showModal.value = false;
        },
    });
}

// ─── Eliminar ───
const showDelete = ref(false);
const deleteId = ref<number | null>(null);
const isDeleting = ref(false);

function confirmDelete(dispositivo: DispositivoMarca) {
    deleteId.value = dispositivo.id;
    showDelete.value = true;
}

function executeDelete() {
    if (!deleteId.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(
        DispositivoMarcaController.destroy({
            dispositivosMarca: deleteId.value,
        }).url,
        {
            onSuccess: () => {
                showDelete.value = false;
                deleteId.value = null;
            },
            onFinish: () => {
                isDeleting.value = false;
            },
        },
    );
}
</script>

<template>
    <Head title="Dispositivos de Marca" />

    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    Dispositivos de Marca
                </h1>
                <p class="text-sm text-muted-foreground">
                    Teléfonos móviles registrados para marcación.
                </p>
            </div>
            <Button size="sm" @click="openCreate()"
                ><Plus class="mr-2 h-4 w-4" /> Nuevo Dispositivo</Button
            >
        </div>

        <div class="flex items-center gap-2">
            <Input
                v-model="searchQuery"
                placeholder="Buscar..."
                class="max-w-sm"
            />
        </div>

        <div class="overflow-hidden rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>ID</TableHead>
                        <TableHead>Teléfono</TableHead>
                        <TableHead>Fecha Inicio</TableHead>
                        <TableHead>Fecha Fin</TableHead>
                        <TableHead class="w-[100px] text-right"
                            >Acciones</TableHead
                        >
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="disp in props.dispositivos.data"
                        :key="disp.id"
                    >
                        <TableCell class="text-sm font-medium">{{
                            disp.id
                        }}</TableCell>
                        <TableCell class="text-sm">{{
                            disp.telefono_movil?.numero || '-'
                        }}</TableCell>
                        <TableCell class="text-sm">{{
                            disp.fechaInicio || '-'
                        }}</TableCell>
                        <TableCell class="text-sm">{{
                            disp.fechaFin || '-'
                        }}</TableCell>
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
                                    <DropdownMenuItem
                                        @click="confirmDelete(disp)"
                                        class="text-destructive"
                                        ><Trash2 class="mr-2 h-4 w-4" />
                                        Eliminar</DropdownMenuItem
                                    >
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="!props.dispositivos.data.length">
                        <TableCell
                            colspan="5"
                            class="h-20 text-center text-muted-foreground"
                            >No se encontraron dispositivos.</TableCell
                        >
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div
            v-if="props.dispositivos.last_page > 1"
            class="flex items-center justify-between"
        >
            <p class="text-sm text-muted-foreground">
                Página {{ props.dispositivos.current_page }} de
                {{ props.dispositivos.last_page }}
            </p>
            <div class="flex gap-2">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="props.dispositivos.current_page <= 1"
                    @click="
                        router.get(
                            DispositivoMarcaController.index().url,
                            { page: props.dispositivos.current_page - 1 },
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
                        props.dispositivos.current_page >=
                        props.dispositivos.last_page
                    "
                    @click="
                        router.get(
                            DispositivoMarcaController.index().url,
                            { page: props.dispositivos.current_page + 1 },
                            { preserveState: true },
                        )
                    "
                >
                    Siguiente <ChevronRight class="h-4 w-4" />
                </Button>
            </div>
        </div>

        <FormModal
            v-model:show="showModal"
            title="Nuevo Dispositivo de Marca"
            :processing="form.processing"
            @submit="submitForm"
        >
            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label>ID Teléfono Móvil *</Label>
                    <Input
                        v-model="form.telefonoMovil_id"
                        type="number"
                        placeholder="ID del teléfono"
                        :class="{
                            'border-destructive': form.errors.telefonoMovil_id,
                        }"
                    />
                    <p
                        v-if="form.errors.telefonoMovil_id"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.telefonoMovil_id }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>Fecha Inicio</Label>
                        <Input v-model="form.fechaInicio" type="date" />
                    </div>
                    <div class="grid gap-2">
                        <Label>Fecha Fin</Label>
                        <Input v-model="form.fechaFin" type="date" />
                    </div>
                </div>
            </div>
        </FormModal>

        <ConfirmModal
            v-model:show="showDelete"
            title="Eliminar Dispositivo"
            description="¿Eliminar este dispositivo de marca?"
            confirm-text="Eliminar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="deleteId = null"
        />
    </div>
</template>

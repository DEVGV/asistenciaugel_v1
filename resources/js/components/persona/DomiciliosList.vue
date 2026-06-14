<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, MapPin, ArrowDownCircle } from 'lucide-vue-next';
import { ref } from 'vue';
import DomicilioController from '@/actions/App/Http/Controllers/Persona/DomicilioController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import ZonaSelect from '@/components/shared/ZonaSelect.vue';
import { Button } from '@/components/ui/button';
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
import type { Domicilio } from '@/types/models/persona';

const props = defineProps<{
    domicilios: Domicilio[];
    personaId: number;
}>();

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);
const showDeleteModal = ref(false);
const itemToDelete = ref<Domicilio | null>(null);
const isDeleting = ref(false);
const showBajaModal = ref(false);
const itemToBaja = ref<Domicilio | null>(null);
const isBaja = ref(false);

const selectedDepartamento = ref<number | null>(null);
const selectedProvincia = ref<number | null>(null);
const selectedDistrito = ref<number | null>(null);

const form = useForm({
    domicilio: '',
    zona_id: null as number | null,
    ubigeo: '',
});

function resetForm() {
    form.domicilio = '';
    form.zona_id = null;
    form.ubigeo = '';
    form.clearErrors();
    selectedDepartamento.value = null;
    selectedProvincia.value = null;
    selectedDistrito.value = null;
}

function closeModal() {
    showModal.value = false;
    resetForm();
    isEditing.value = false;
    editingId.value = null;
}

function openCreate() {
    resetForm();
    isEditing.value = false;
    editingId.value = null;
    showModal.value = true;
}

async function openEdit(item: Domicilio) {
    resetForm();
    isEditing.value = true;
    editingId.value = item.id;
    form.domicilio = item.domicilio;
    form.zona_id = item.zona_id;
    form.ubigeo = item.ubigeo || '';

    if (item.ubigeo) {
        try {
            const res = await fetch(`/api/params/ubigeo/${item.ubigeo}`);

            if (res.ok) {
                const data = await res.json();
                selectedDepartamento.value = data.departamento_id;
                selectedProvincia.value = data.provincia_id;
                selectedDistrito.value = data.distrito_id;
            }
        } catch (e) {
            console.error('Error fetching ubigeo hierarchy', e);
        }
    }

    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(
            DomicilioController.update({ domicilio: editingId.value }).url,
            { onSuccess: () => closeModal() },
        );
    } else {
        form.post(DomicilioController.store({ persona: props.personaId }).url, {
            onSuccess: () => closeModal(),
        });
    }
}

function confirmDelete(item: Domicilio) {
    itemToDelete.value = item;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!itemToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(
        DomicilioController.destroy({ domicilio: itemToDelete.value.id }).url,
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                itemToDelete.value = null;
            },
            onFinish: () => {
                isDeleting.value = false;
            },
        },
    );
}

function confirmBaja(item: Domicilio) {
    itemToBaja.value = item;
    showBajaModal.value = true;
}

function executeBaja() {
    if (!itemToBaja.value) {
        return;
    }

    isBaja.value = true;
    router.patch(
        `/domicilios/${itemToBaja.value.id}/dar-de-baja`,
        {},
        {
            onSuccess: () => {
                showBajaModal.value = false;
                itemToBaja.value = null;
            },
            onFinish: () => {
                isBaja.value = false;
            },
        },
    );
}
</script>

<template>
    <div>
        <div class="mb-3 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-muted-foreground">
                Domicilios
            </h3>
            <Button size="sm" variant="outline" @click="openCreate">
                <Plus class="mr-1 h-3.5 w-3.5" /> Agregar
            </Button>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Dirección</TableHead>
                        <TableHead>Zona</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="item in domicilios" :key="item.id">
                        <TableCell class="font-medium">
                            <div class="flex items-center gap-1.5">
                                <MapPin
                                    class="h-4 w-4 shrink-0 text-muted-foreground"
                                />
                                <span class="line-clamp-1">{{
                                    item.domicilio
                                }}</span>
                            </div>
                        </TableCell>
                        <TableCell>{{ item.zona?.nombre || '-' }}</TableCell>
                        <TableCell>
                            <StatusBadge :active="!item.fechaFin" />
                        </TableCell>
                        <TableCell class="text-right">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="h-7 w-7"
                                @click="openEdit(item)"
                            >
                                <Pencil class="h-3.5 w-3.5" />
                            </Button>
                            <Button
                                v-if="!item.fechaFin"
                                variant="ghost"
                                size="icon"
                                class="h-7 w-7 text-amber-600"
                                title="Dar de baja"
                                @click="confirmBaja(item)"
                            >
                                <ArrowDownCircle class="h-3.5 w-3.5" />
                            </Button>
                            <Button
                                variant="ghost"
                                size="icon"
                                class="h-7 w-7 text-destructive"
                                @click="confirmDelete(item)"
                            >
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="domicilios.length === 0">
                        <TableCell
                            colspan="4"
                            class="h-16 text-center text-sm text-muted-foreground"
                        >
                            Sin domicilios registrados.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <FormModal
            v-model:show="showModal"
            :title="isEditing ? 'Editar Domicilio' : 'Nuevo Domicilio'"
            max-width="4xl"
            :processing="form.processing"
            @submit="submitForm"
            @close="closeModal"
        >
            <div class="grid gap-6">
                <!-- Fila 1: Dirección y Departamento -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="grid gap-2 md:col-span-2">
                        <Label>Dirección *</Label>
                        <Input
                            v-model="form.domicilio"
                            placeholder="Av. / Jr. / Calle..."
                            :class="{
                                'border-destructive': form.errors.domicilio,
                            }"
                        />
                        <p
                            v-if="form.errors.domicilio"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.domicilio }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <ParamSelect
                            type="departamentos"
                            label="Departamento"
                            v-model="selectedDepartamento"
                            placeholder="Seleccionar..."
                            @update:modelValue="
                                () => {
                                    selectedProvincia = null;
                                    selectedDistrito = null;
                                    form.ubigeo = '';
                                }
                            "
                        />
                    </div>
                </div>

                <!-- Fila 2: Provincia, Distrito y Zona -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="grid gap-2">
                        <ParamSelect
                            type="provincias"
                            label="Provincia"
                            :parent-id="selectedDepartamento"
                            v-model="selectedProvincia"
                            placeholder="Seleccionar..."
                            :disabled="!selectedDepartamento"
                            @update:modelValue="
                                () => {
                                    selectedDistrito = null;
                                    form.ubigeo = '';
                                }
                            "
                        />
                    </div>
                    <div class="grid gap-2">
                        <ParamSelect
                            type="distritos"
                            label="Distrito"
                            :parent-id="selectedProvincia"
                            v-model="selectedDistrito"
                            placeholder="Seleccionar..."
                            :disabled="!selectedProvincia"
                            @update:item="
                                (item) => {
                                    form.ubigeo = item ? item.codigo || '' : '';
                                }
                            "
                        />
                    </div>
                    <div class="grid gap-2">
                        <ZonaSelect
                            v-model="form.zona_id"
                            :distrito-id="selectedDistrito"
                            label="Zona (opcional)"
                            placeholder="Seleccionar zona..."
                            :disabled="!selectedDistrito"
                            :error="form.errors.zona_id"
                        />
                    </div>
                </div>

                <!-- Fila 3: Ubigeo -->
                <div
                    class="grid grid-cols-1 items-end gap-4 border-t pt-4 md:grid-cols-5"
                >
                    <div class="grid gap-2 md:col-span-1">
                        <Label
                            class="text-xs font-bold text-muted-foreground uppercase"
                            >Ubigeo</Label
                        >
                        <Input
                            v-model="form.ubigeo"
                            placeholder="000000"
                            maxlength="6"
                            class="h-9 bg-muted/30 font-mono font-bold"
                        />
                    </div>
                </div>
                <p v-if="form.errors.ubigeo" class="text-xs text-destructive">
                    {{ form.errors.ubigeo }}
                </p>
            </div>
        </FormModal>

        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Eliminar Domicilio"
            description="¿Eliminar este domicilio?"
            confirm-text="Eliminar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
        />

        <ConfirmModal
            v-model:show="showBajaModal"
            title="Dar de Baja Domicilio"
            description="¿Dar de baja este domicilio? Se registrará la fecha de hoy como fecha de baja."
            confirm-text="Dar de Baja"
            :processing="isBaja"
            @confirm="executeBaja"
        />
    </div>
</template>

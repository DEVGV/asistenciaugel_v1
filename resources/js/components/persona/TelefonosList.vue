<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import {
    Plus,
    Pencil,
    Trash2,
    Phone,
    Smartphone,
    ArrowDownCircle,
} from 'lucide-vue-next';
import { ref } from 'vue';
import TelefonoController from '@/actions/App/Http/Controllers/Persona/TelefonoController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
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
import type { Telefono } from '@/types/models/persona';

const props = defineProps<{
    telefonos: Telefono[];
    personaId: number;
}>();

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);
const showDeleteModal = ref(false);
const itemToDelete = ref<Telefono | null>(null);
const isDeleting = ref(false);
const showBajaModal = ref(false);
const itemToBaja = ref<Telefono | null>(null);
const isBaja = ref(false);

const form = useForm({
    operador_id: null as number | null,
    movilFijo: 'M' as 'M' | 'F',
    codigoPais: '+51',
    numero: '',
    imei: '',
});

function resetForm() {
    form.operador_id = null;
    form.movilFijo = 'M';
    form.codigoPais = '+51';
    form.numero = '';
    form.imei = '';
    form.clearErrors();
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

function openEdit(tel: Telefono) {
    resetForm();
    isEditing.value = true;
    editingId.value = tel.id;
    form.operador_id = tel.operador_id;
    form.movilFijo = tel.movilFijo;
    form.codigoPais = tel.codigoPais || '+51';
    form.numero = tel.numero;
    form.imei = tel.imei || '';
    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(TelefonoController.update({ telefono: editingId.value }).url, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(TelefonoController.store({ persona: props.personaId }).url, {
            onSuccess: () => closeModal(),
        });
    }
}

function confirmDelete(tel: Telefono) {
    itemToDelete.value = tel;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!itemToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(
        TelefonoController.destroy({ telefono: itemToDelete.value.id }).url,
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

function confirmBaja(tel: Telefono) {
    itemToBaja.value = tel;
    showBajaModal.value = true;
}

function executeBaja() {
    if (!itemToBaja.value) {
        return;
    }

    isBaja.value = true;
    router.patch(
        `/telefonos/${itemToBaja.value.id}/dar-de-baja`,
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
                Teléfonos
            </h3>
            <Button size="sm" variant="outline" @click="openCreate">
                <Plus class="mr-1 h-3.5 w-3.5" /> Agregar
            </Button>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Tipo</TableHead>
                        <TableHead>Número</TableHead>
                        <TableHead>Operador</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="tel in telefonos" :key="tel.id">
                        <TableCell>
                            <div class="flex items-center gap-1.5">
                                <Smartphone
                                    v-if="tel.movilFijo === 'M'"
                                    class="h-4 w-4 text-muted-foreground"
                                />
                                <Phone
                                    v-else
                                    class="h-4 w-4 text-muted-foreground"
                                />
                                {{ tel.movilFijo === 'M' ? 'Móvil' : 'Fijo' }}
                            </div>
                        </TableCell>
                        <TableCell class="font-medium"
                            >{{ tel.codigoPais }} {{ tel.numero }}</TableCell
                        >
                        <TableCell>{{ tel.operador?.nombre || '-' }}</TableCell>
                        <TableCell>
                            <StatusBadge :active="!tel.fechaFin" />
                        </TableCell>
                        <TableCell class="text-right">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="h-7 w-7"
                                @click="openEdit(tel)"
                            >
                                <Pencil class="h-3.5 w-3.5" />
                            </Button>
                            <Button
                                v-if="!tel.fechaFin"
                                variant="ghost"
                                size="icon"
                                class="h-7 w-7 text-amber-600"
                                title="Dar de baja"
                                @click="confirmBaja(tel)"
                            >
                                <ArrowDownCircle class="h-3.5 w-3.5" />
                            </Button>
                            <Button
                                variant="ghost"
                                size="icon"
                                class="h-7 w-7 text-destructive"
                                @click="confirmDelete(tel)"
                            >
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="telefonos.length === 0">
                        <TableCell
                            colspan="5"
                            class="h-16 text-center text-sm text-muted-foreground"
                        >
                            Sin teléfonos registrados.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <FormModal
            v-model:show="showModal"
            :title="isEditing ? 'Editar Teléfono' : 'Nuevo Teléfono'"
            :processing="form.processing"
            @submit="submitForm"
            @close="closeModal"
        >
            <div class="grid gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label>Tipo *</Label>
                        <select
                            v-model="form.movilFijo"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs"
                        >
                            <option value="M">Móvil</option>
                            <option value="F">Fijo</option>
                        </select>
                    </div>
                    <ParamSelect
                        v-model="form.operador_id"
                        type="operadores"
                        label="Operador"
                    />
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="grid gap-2">
                        <Label>Código</Label>
                        <Input
                            v-model="form.codigoPais"
                            placeholder="+51"
                            maxlength="5"
                        />
                    </div>
                    <div class="col-span-2 grid gap-2">
                        <Label>Número *</Label>
                        <Input
                            v-model="form.numero"
                            placeholder="999999999"
                            maxlength="20"
                        />
                        <p
                            v-if="form.errors.numero"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.numero }}
                        </p>
                    </div>
                </div>
            </div>
        </FormModal>

        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Eliminar Teléfono"
            :description="`¿Eliminar el número ${itemToDelete?.numero}?`"
            confirm-text="Eliminar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
        />

        <ConfirmModal
            v-model:show="showBajaModal"
            title="Dar de Baja Teléfono"
            :description="`¿Dar de baja el número ${itemToBaja?.numero}? Se registrará la fecha de hoy como fecha de baja.`"
            confirm-text="Dar de Baja"
            :processing="isBaja"
            @confirm="executeBaja"
        />
    </div>
</template>

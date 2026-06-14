<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, Mail, ArrowDownCircle } from 'lucide-vue-next';
import { ref } from 'vue';
import EmailController from '@/actions/App/Http/Controllers/Persona/EmailController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
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
import type { Email } from '@/types/models/persona';

const props = defineProps<{
    emails: Email[];
    personaId: number;
}>();

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);
const showDeleteModal = ref(false);
const itemToDelete = ref<Email | null>(null);
const isDeleting = ref(false);
const showBajaModal = ref(false);
const itemToBaja = ref<Email | null>(null);
const isBaja = ref(false);

const form = useForm({
    email: '',
    personalInst: 'P' as 'P' | 'I',
});

function resetForm() {
    form.email = '';
    form.personalInst = 'P';
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

function openEdit(item: Email) {
    resetForm();
    isEditing.value = true;
    editingId.value = item.id;
    form.email = item.email;
    form.personalInst = item.personalInst;
    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(EmailController.update({ email: editingId.value }).url, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(EmailController.store({ persona: props.personaId }).url, {
            onSuccess: () => closeModal(),
        });
    }
}

function confirmDelete(item: Email) {
    itemToDelete.value = item;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!itemToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(
        EmailController.destroy({ email: itemToDelete.value.id }).url,
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

function confirmBaja(item: Email) {
    itemToBaja.value = item;
    showBajaModal.value = true;
}

function executeBaja() {
    if (!itemToBaja.value) {
        return;
    }

    isBaja.value = true;
    router.patch(
        `/emails/${itemToBaja.value.id}/dar-de-baja`,
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
                Correos Electrónicos
            </h3>
            <Button size="sm" variant="outline" @click="openCreate">
                <Plus class="mr-1 h-3.5 w-3.5" /> Agregar
            </Button>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Email</TableHead>
                        <TableHead>Tipo</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="item in emails" :key="item.id">
                        <TableCell class="font-medium">
                            <div class="flex items-center gap-1.5">
                                <Mail class="h-4 w-4 text-muted-foreground" />
                                {{ item.email }}
                            </div>
                        </TableCell>
                        <TableCell>{{
                            item.personalInst === 'P'
                                ? 'Personal'
                                : 'Institucional'
                        }}</TableCell>
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
                    <TableRow v-if="emails.length === 0">
                        <TableCell
                            colspan="4"
                            class="h-16 text-center text-sm text-muted-foreground"
                        >
                            Sin correos registrados.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <FormModal
            v-model:show="showModal"
            :title="isEditing ? 'Editar Email' : 'Nuevo Email'"
            :processing="form.processing"
            @submit="submitForm"
            @close="closeModal"
        >
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label>Email *</Label>
                    <Input
                        v-model="form.email"
                        type="email"
                        placeholder="correo@ejemplo.com"
                    />
                    <p
                        v-if="form.errors.email"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label>Tipo *</Label>
                    <select
                        v-model="form.personalInst"
                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs"
                    >
                        <option value="P">Personal</option>
                        <option value="I">Institucional</option>
                    </select>
                </div>
            </div>
        </FormModal>

        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Eliminar Email"
            :description="`¿Eliminar el correo ${itemToDelete?.email}?`"
            confirm-text="Eliminar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
        />

        <ConfirmModal
            v-model:show="showBajaModal"
            title="Dar de Baja Email"
            :description="`¿Dar de baja el correo ${itemToBaja?.email}? Se registrará la fecha de hoy como fecha de baja.`"
            confirm-text="Dar de Baja"
            :processing="isBaja"
            @confirm="executeBaja"
        />
    </div>
</template>

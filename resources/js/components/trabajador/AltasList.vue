<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import {
    Building2,
    Calendar,
    ChevronDown,
    Pencil,
    Plus,
    UserX,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import AltaTrabajadorController from '@/actions/App/Http/Controllers/Trabajador/AltaTrabajadorController';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import AltaForm from '@/components/trabajador/AltaForm.vue';
import BajaForm from '@/components/trabajador/BajaForm.vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
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
import altasRoutes from '@/routes/altas';
import type { AltaTrabajador } from '@/types/models/trabajador';

const props = defineProps<{
    altas: AltaTrabajador[];
    trabajadorId: number;
}>();

// ─── Modal Alta (Crear / Editar) ───
const showAltaModal = ref(false);
const isEditingAlta = ref(false);
const editingAlta = ref<AltaTrabajador | null>(null);

function openAltaCreate() {
    isEditingAlta.value = false;
    editingAlta.value = null;
    showAltaModal.value = true;
}

function openAltaEdit(alta: AltaTrabajador) {
    isEditingAlta.value = true;
    editingAlta.value = { ...alta };
    showAltaModal.value = true;
}

watch(showAltaModal, (val) => {
    if (!val) {
        editingAlta.value = null;
    }
});

// ─── Modal Baja ───
const showBajaModal = ref(false);
const altaParaBaja = ref<AltaTrabajador | null>(null);

function openBaja(alta: AltaTrabajador) {
    altaParaBaja.value = alta;
    showBajaModal.value = true;
}

watch(showBajaModal, (val) => {
    if (!val) {
        altaParaBaja.value = null;
    }
});

// ─── Modal Confirmar Eliminación ───
const showDeleteModal = ref(false);
const altaToDelete = ref<AltaTrabajador | null>(null);
const isDeleting = ref(false);

function confirmDelete(alta: AltaTrabajador) {
    altaToDelete.value = alta;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!altaToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(altasRoutes.destroy({ alta: altaToDelete.value.id }).url, {
        onSuccess: () => {
            showDeleteModal.value = false;
            altaToDelete.value = null;
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
}

function estaActiva(alta: AltaTrabajador): boolean {
    return !alta.fechaBaja;
}
</script>

<template>
    <div class="space-y-4">
        <!-- Cabecera -->
        <div class="flex items-center justify-between">
            <p class="text-sm text-muted-foreground">
                Historial de vinculaciones laborales del trabajador con
                instituciones educativas.
            </p>
            <Button size="sm" @click="openAltaCreate">
                <Plus class="mr-2 h-4 w-4" /> Nueva Alta
            </Button>
        </div>

        <!-- Tabla -->
        <div class="overflow-hidden rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Institución</TableHead>
                        <TableHead>Condición / Rol</TableHead>
                        <TableHead>Área / Cargo</TableHead>
                        <TableHead class="w-[120px]">Inicio</TableHead>
                        <TableHead class="w-[120px]">Fin / Baja</TableHead>
                        <TableHead class="w-[80px]">Estado</TableHead>
                        <TableHead class="w-[80px] text-right"
                            >Acciones</TableHead
                        >
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="alta in props.altas" :key="alta.id">
                        <TableCell>
                            <div class="flex items-start gap-1.5">
                                <Building2
                                    class="mt-0.5 h-3.5 w-3.5 shrink-0 text-muted-foreground"
                                />
                                <div>
                                    <div class="text-sm font-medium">
                                        {{
                                            (
                                                alta.institucion_educativa ||
                                                alta.institucionEducativa
                                            )?.nombreLegal || '-'
                                        }}
                                    </div>
                                    <div
                                        v-if="alta.codigoAirsp"
                                        class="text-xs text-muted-foreground"
                                    >
                                        AIRSP: {{ alta.codigoAirsp }}
                                    </div>
                                </div>
                            </div>
                        </TableCell>
                        <TableCell>
                            <div class="text-xs font-medium">
                                {{
                                    (
                                        alta.condicion_laboral ||
                                        alta.condicionLaboral
                                    )?.abreviatura ||
                                    (
                                        alta.condicion_laboral ||
                                        alta.condicionLaboral
                                    )?.nombre ||
                                    '-'
                                }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{
                                    (alta.rol_trabajador || alta.rolTrabajador)
                                        ?.nombre || '-'
                                }}
                            </div>
                            <div v-if="alta.perfil_ie?.perfil" class="mt-1">
                                <span
                                    class="inline-flex items-center rounded bg-blue-50 px-1.5 py-0.5 text-[10px] font-semibold text-blue-700 ring-1 ring-blue-700/10 dark:bg-blue-950/30 dark:text-blue-400 dark:ring-blue-400/20"
                                >
                                    Perfil: {{ alta.perfil_ie.perfil.nombre }}
                                </span>
                            </div>
                        </TableCell>
                        <TableCell>
                            <div class="text-xs font-medium">
                                {{ alta.area?.nombre || '-' }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{ alta.cargo?.nombre || '-' }}
                            </div>
                        </TableCell>
                        <TableCell class="text-xs">
                            <div class="flex items-center gap-1">
                                <Calendar
                                    class="h-3 w-3 text-muted-foreground"
                                />
                                {{ alta.fechaInicio }}
                            </div>
                        </TableCell>
                        <TableCell class="text-xs">
                            <div v-if="alta.fechaBaja" class="text-destructive">
                                <div class="font-medium">
                                    {{ alta.fechaBaja }}
                                </div>
                                <div class="text-muted-foreground">
                                    {{
                                        (alta.motivo_baja || alta.motivoBaja)
                                            ?.nombre
                                    }}
                                </div>
                            </div>
                            <div
                                v-else-if="alta.fechaFin"
                                class="text-muted-foreground"
                            >
                                {{ alta.fechaFin }}
                            </div>
                            <span v-else class="text-muted-foreground">—</span>
                        </TableCell>
                        <TableCell>
                            <StatusBadge
                                :active="estaActiva(alta)"
                                :label-active="'Activo'"
                                :label-inactive="'Baja'"
                            />
                        </TableCell>
                        <TableCell class="text-right">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        class="h-7"
                                    >
                                        <ChevronDown class="h-4 w-4" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuItem
                                        @click="openAltaEdit(alta)"
                                    >
                                        <Pencil class="mr-2 h-4 w-4" /> Editar
                                    </DropdownMenuItem>
                                    <DropdownMenuItem
                                        v-if="estaActiva(alta)"
                                        @click="openBaja(alta)"
                                        class="text-destructive focus:text-destructive"
                                    >
                                        <UserX class="mr-2 h-4 w-4" /> Dar de
                                        Baja
                                    </DropdownMenuItem>
                                    <DropdownMenuItem
                                        v-if="estaActiva(alta)"
                                        @click="confirmDelete(alta)"
                                        class="text-destructive focus:text-destructive"
                                    >
                                        Eliminar
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="!props.altas.length">
                        <TableCell
                            colspan="7"
                            class="h-24 text-center text-muted-foreground"
                        >
                            No hay altas registradas para este trabajador.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Modal Alta -->
        <AltaForm
            v-model:show="showAltaModal"
            :is-editing="isEditingAlta"
            :alta="editingAlta"
            :trabajador-id="props.trabajadorId"
        />

        <!-- Modal Baja -->
        <BajaForm
            v-if="altaParaBaja"
            v-model:show="showBajaModal"
            :alta="altaParaBaja"
        />

        <!-- Confirmación Eliminar -->
        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Eliminar Alta"
            :description="`¿Eliminar el alta en ${(altaToDelete?.institucion_educativa || altaToDelete?.institucionEducativa)?.nombreLegal}? Esta acción no se puede deshacer.`"
            confirm-text="Eliminar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="altaToDelete = null"
        />
    </div>
</template>

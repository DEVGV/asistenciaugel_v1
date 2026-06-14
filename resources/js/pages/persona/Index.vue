<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import {
    Plus,
    Pencil,
    Trash2,
    Eye,
    User,
    Phone,
    Mail,
    MapPin,
    ChevronDown,
    UserCheck,
    Upload,
} from 'lucide-vue-next';
import { ref, watch, computed } from 'vue';
import PersonaController from '@/actions/App/Http/Controllers/Persona/PersonaController';
import DomiciliosList from '@/components/persona/DomiciliosList.vue';
import EmailsList from '@/components/persona/EmailsList.vue';
import PersonaForm from '@/components/persona/PersonaForm.vue';
import PersonaMasivaGrid from '@/components/persona/PersonaMasivaGrid.vue';
import TelefonosList from '@/components/persona/TelefonosList.vue';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';
import FormModal from '@/components/shared/FormModal.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
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
import type { Persona, PaginatedResponse } from '@/types/models/persona';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Personas', href: PersonaController.index().url },
        ],
    },
});

const props = defineProps<{
    personas: PaginatedResponse<Persona>;
    filters: { search?: string };
    selectedPersona?: Persona | null;
}>();

// ─── Búsqueda ───
const search = ref(props.filters.search || '');
let searchTimeout: any = null;

watch(search, (val) => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    searchTimeout = setTimeout(() => {
        router.get(
            PersonaController.index().url,
            { search: val || undefined },
            {
                preserveState: true,
                replace: true,
            },
        );
    }, 300);
});

// ─── Modal Create/Edit ───
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    tipoDocIdentidad_id: null as number | null,
    docIdentidad: '',
    paterno: '',
    materno: '',
    nombre: '',
    sexo_id: null as number | null,
    fechaNac: '',
    pais_id: null as number | null,
    ubigeo: '',
    foto: '',
    activo: true,
    es_trabajador: false,
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

function openEditModal(persona: Persona) {
    isEditing.value = true;
    editingId.value = persona.id;
    form.clearErrors();
    form.tipoDocIdentidad_id = persona.tipoDocIdentidad_id;
    form.docIdentidad = persona.docIdentidad;
    form.paterno = persona.paterno;
    form.materno = persona.materno;
    form.nombre = persona.nombre;
    form.sexo_id = persona.sexo_id;
    form.fechaNac = persona.fechaNac || '';
    form.pais_id = persona.pais_id;
    form.ubigeo = persona.ubigeo || '';
    form.foto = persona.foto || '';
    form.activo = persona.activo;
    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(PersonaController.update({ persona: editingId.value }).url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    } else {
        form.post(PersonaController.store().url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
}

// ─── Modal Delete ───
const showDeleteModal = ref(false);
const personaToDelete = ref<Persona | null>(null);
const isDeleting = ref(false);

function confirmDelete(persona: Persona) {
    personaToDelete.value = persona;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!personaToDelete.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(
        PersonaController.destroy({ persona: personaToDelete.value.id }).url,
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                personaToDelete.value = null;
            },
            onFinish: () => {
                isDeleting.value = false;
            },
        },
    );
}

// ─── Carga Masiva Grid ────────────────────────────────────────────────────────
const showMasivoModal = ref(false);

function onMasivoSuccess(count: number) {
    showMasivoModal.value = false;
    router.reload({ only: ['personas'] });
    console.log(`${count} personas registradas en lote.`);
}

// ─── Modal Marcar como Trabajador ───
const showWorkerModal = ref(false);
const personaToMarkWorker = ref<Persona | null>(null);
const isMarkingWorker = ref(false);

function confirmMarkAsWorker(persona: Persona) {
    personaToMarkWorker.value = persona;
    showWorkerModal.value = true;
}

function executeMarkAsWorker() {
    if (!personaToMarkWorker.value) {
        return;
    }

    isMarkingWorker.value = true;
    router.post(
        PersonaController.convertirTrabajador({
            persona: personaToMarkWorker.value.id,
        }).url,
        {},
        {
            onSuccess: () => {
                showWorkerModal.value = false;
                personaToMarkWorker.value = null;
            },
            onFinish: () => {
                isMarkingWorker.value = false;
            },
        },
    );
}

// ─── Modal Detalle (con tabs) ───
const showDetailModal = ref(!!props.selectedPersona);
const detailPersona = ref<Persona | null>(props.selectedPersona || null);
const activeTab = ref<'datos' | 'telefonos' | 'emails' | 'domicilios'>('datos');

watch(
    () => props.selectedPersona,
    (val) => {
        if (val) {
            detailPersona.value = val;
            showDetailModal.value = true;
        }
    },
);

function openDetailModal(persona: Persona) {
    // Abrir inmediatamente con los datos básicos
    detailPersona.value = persona;
    showDetailModal.value = true;
    activeTab.value = 'datos';

    router.get(
        PersonaController.show({ persona: persona.id }).url,
        {},
        {
            preserveState: true,
            preserveScroll: true,
            only: ['selectedPersona'],
        },
    );
}

function closeDetailModal() {
    showDetailModal.value = false;
    setTimeout(() => {
        detailPersona.value = null;
    }, 300); // Evitar salto visual al cerrar

    // Navegar de vuelta al index limpio
    router.get(
        PersonaController.index().url,
        { search: search.value || undefined },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['personas', 'filters'],
        },
    );
}

const tabs = [
    { key: 'datos', label: 'Datos', icon: User },
    { key: 'telefonos', label: 'Teléfonos', icon: Phone },
    { key: 'emails', label: 'Emails', icon: Mail },
    { key: 'domicilios', label: 'Domicilios', icon: MapPin },
] as const;
</script>

<template>
    <Head title="Personas" />

    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Personas</h1>
            <div class="flex items-center gap-2">
                <Button variant="outline" @click="showMasivoModal = true">
                    <Upload class="mr-2 h-4 w-4" />
                    Carga Masiva
                </Button>
                <Button @click="openCreateModal">
                    <Plus class="mr-2 h-4 w-4" />
                    Nueva Persona
                </Button>
            </div>
        </div>

        <!-- Buscador -->
        <div class="flex items-center gap-2">
            <input
                v-model="search"
                type="text"
                placeholder="Buscar por documento, apellidos o nombres..."
                class="flex h-9 w-full max-w-sm rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs placeholder:text-muted-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none"
            />
        </div>

        <!-- Tabla -->
        <div class="rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Documento</TableHead>
                        <TableHead>Apellidos y Nombres</TableHead>
                        <TableHead>Sexo</TableHead>
                        <TableHead>Trabajador</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="persona in props.personas.data"
                        :key="persona.id"
                    >
                        <TableCell class="font-medium">
                            <div class="text-xs text-muted-foreground">
                                {{
                                    persona.tipoDocIdentidad?.abreviatura ||
                                    persona.tipoDocIdentidad?.nombre
                                }}
                            </div>
                            <div>{{ persona.docIdentidad }}</div>
                        </TableCell>
                        <TableCell>
                            <div class="font-medium">
                                {{ persona.paterno }} {{ persona.materno }}
                            </div>
                            <div class="text-sm text-muted-foreground">
                                {{ persona.nombre }}
                            </div>
                        </TableCell>
                        <TableCell>{{ persona.sexo?.nombre || '-' }}</TableCell>
                        <TableCell>
                            <Badge
                                v-if="
                                    persona.trabajador &&
                                    persona.trabajador.activo
                                "
                                variant="outline"
                                class="rounded-md border-green-200 bg-green-50 px-2 py-0.5 text-[10px] font-semibold tracking-wider text-green-700 uppercase dark:border-green-800 dark:bg-green-950/30 dark:text-green-400"
                            >
                                Sí
                            </Badge>
                            <Badge
                                v-else
                                variant="outline"
                                class="rounded-md border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-semibold tracking-wider text-amber-700 uppercase dark:border-amber-800 dark:bg-amber-950/30 dark:text-amber-400"
                            >
                                No
                            </Badge>
                        </TableCell>
                        <TableCell>
                            <StatusBadge :active="persona.activo" />
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
                                        @click="openDetailModal(persona)"
                                    >
                                        <Eye class="mr-2 h-4 w-4" />
                                        <span>Ver Detalles</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem
                                        @click="openEditModal(persona)"
                                    >
                                        <Pencil class="mr-2 h-4 w-4" />
                                        <span>Editar</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem
                                        v-if="
                                            !persona.trabajador ||
                                            !persona.trabajador.activo
                                        "
                                        @click="confirmMarkAsWorker(persona)"
                                    >
                                        <UserCheck class="mr-2 h-4 w-4" />
                                        <span>Marcar como Trabajador</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        @click="confirmDelete(persona)"
                                        class="cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive"
                                    >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Eliminar</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.personas.data.length === 0">
                        <TableCell colspan="6" class="h-24 text-center">
                            No hay personas registradas.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div
                class="flex items-center justify-between border-t px-4 py-3"
                v-if="props.personas.total > 0"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.personas.current_page }} de
                    {{ props.personas.last_page }} ({{ props.personas.total }}
                    registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.personas.current_page <= 1"
                        @click="
                            router.get(PersonaController.index().url, {
                                page: props.personas.current_page - 1,
                                search: search || undefined,
                            })
                        "
                    >
                        Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.personas.current_page >=
                            props.personas.last_page
                        "
                        @click="
                            router.get(PersonaController.index().url, {
                                page: props.personas.current_page + 1,
                                search: search || undefined,
                            })
                        "
                    >
                        Siguiente
                    </Button>
                </div>
            </div>
        </div>

        <!-- Modal Create/Edit Persona -->
        <FormModal
            v-model:show="showModal"
            :title="isEditing ? 'Editar Persona' : 'Nueva Persona'"
            :processing="form.processing"
            max-width="lg"
            @submit="submitForm"
        >
            <PersonaForm :form="form" :is-editing="isEditing" />
        </FormModal>

        <!-- Modal Confirmar Eliminación -->
        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Eliminar Persona"
            :description="`¿Estás seguro de que deseas eliminar a ${personaToDelete?.paterno} ${personaToDelete?.materno}, ${personaToDelete?.nombre}? Esta acción no se puede deshacer.`"
            confirm-text="Eliminar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="personaToDelete = null"
        />

        <!-- Modal Confirmar Marcar como Trabajador -->
        <ConfirmModal
            v-model:show="showWorkerModal"
            title="Marcar como Trabajador"
            :description="`¿Estás seguro de que deseas registrar a ${personaToMarkWorker?.paterno} ${personaToMarkWorker?.materno}, ${personaToMarkWorker?.nombre} como trabajador? Se le creará un usuario con su documento de identidad.`"
            confirm-text="Confirmar"
            :processing="isMarkingWorker"
            @confirm="executeMarkAsWorker"
            @cancel="personaToMarkWorker = null"
        />

        <!-- Modal Detalle con Tabs -->
        <Dialog
            :open="showDetailModal"
            @update:open="
                (v: boolean) => {
                    if (!v) closeDetailModal();
                }
            "
        >
            <DialogContent
                class="flex max-h-[min(800px,_85vh)] flex-col gap-0 overflow-hidden p-0 font-sans duration-300 sm:max-w-3xl"
            >
                <DialogHeader
                    class="sticky top-0 z-10 shrink-0 border-b bg-background px-6 pt-6 pb-4"
                >
                    <DialogTitle
                        class="text-2xl font-semibold tracking-[-0.029375rem]"
                    >
                        {{ detailPersona?.paterno }}
                        {{ detailPersona?.materno }},
                        {{ detailPersona?.nombre }}
                    </DialogTitle>
                    <DialogDescription class="text-base text-muted-foreground">
                        {{ detailPersona?.tipoDocIdentidad?.nombre }}:
                        {{ detailPersona?.docIdentidad }}
                    </DialogDescription>

                    <!-- Tabs -->
                    <div class="mt-3 -mb-4 flex gap-1 border-b-0">
                        <button
                            v-for="tab in tabs"
                            :key="tab.key"
                            @click="activeTab = tab.key"
                            :class="[
                                'flex items-center gap-1.5 rounded-t-md px-3 py-2 text-sm font-medium transition-colors',
                                activeTab === tab.key
                                    ? 'border border-b-0 border-border bg-muted text-foreground'
                                    : 'text-muted-foreground hover:bg-muted/50 hover:text-foreground',
                            ]"
                        >
                            <component :is="tab.icon" class="h-4 w-4" />
                            {{ tab.label }}
                        </button>
                    </div>
                </DialogHeader>

                <div class="flex-1 overflow-y-auto p-6" v-if="detailPersona">
                    <!-- Tab: Datos -->
                    <div v-if="activeTab === 'datos'" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Tipo Doc.
                                </p>
                                <p class="text-sm font-medium">
                                    {{
                                        detailPersona.tipoDocIdentidad
                                            ?.nombre || '-'
                                    }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    N° Documento
                                </p>
                                <p class="text-sm font-medium">
                                    {{ detailPersona.docIdentidad }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Apellido Paterno
                                </p>
                                <p class="text-sm font-medium">
                                    {{ detailPersona.paterno }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Apellido Materno
                                </p>
                                <p class="text-sm font-medium">
                                    {{ detailPersona.materno }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Nombres
                                </p>
                                <p class="text-sm font-medium">
                                    {{ detailPersona.nombre }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Sexo
                                </p>
                                <p class="text-sm font-medium">
                                    {{ detailPersona.sexo?.nombre || '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Fecha Nacimiento
                                </p>
                                <p class="text-sm font-medium">
                                    {{ detailPersona.fechaNac || '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    Estado
                                </p>
                                <StatusBadge :active="detailPersona.activo" />
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Teléfonos -->
                    <TelefonosList
                        v-if="activeTab === 'telefonos'"
                        :telefonos="detailPersona.telefonos || []"
                        :persona-id="detailPersona.id"
                    />

                    <!-- Tab: Emails -->
                    <EmailsList
                        v-if="activeTab === 'emails'"
                        :emails="detailPersona.emails || []"
                        :persona-id="detailPersona.id"
                    />

                    <!-- Tab: Domicilios -->
                    <DomiciliosList
                        v-if="activeTab === 'domicilios'"
                        :domicilios="detailPersona.domicilios || []"
                        :persona-id="detailPersona.id"
                    />
                </div>
            </DialogContent>
        </Dialog>

        <!-- Modal Carga Masiva Grid -->
        <PersonaMasivaGrid
            v-if="showMasivoModal"
            @close="showMasivoModal = false"
            @success="onMasivoSuccess"
        />
    </div>
</template>

<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import {
    Plus,
    Pencil,
    Trash2,
    ChevronDown,
    ShieldCheck,
} from 'lucide-vue-next';
import { ref, watch, computed, onMounted } from 'vue';
import PerfilController from '@/actions/App/Http/Controllers/Configuracion/PerfilController';
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
import type { Perfil, Permiso } from '@/types/models/configuracion';

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
            { title: 'Perfiles', href: PerfilController.index().url },
        ],
    },
});

const props = defineProps<{
    perfiles: PaginatedResponse<Perfil>;
    permisosPorModulo: Record<string, Permiso[]>;
    filters: { search?: string };
}>();

const isMounted = ref(false);
onMounted(() => {
    isMounted.value = true;
});

// ── Crear / Editar perfil ─────────────────────────────────────────────────────
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    nombre: '',
    descripcion: '',
    activo: true,
});

watch(showModal, (val) => {
    if (!val) {
        form.reset();
        form.clearErrors();
        isEditing.value = false;
        editingId.value = null;
    }
});

function openCreate() {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
}

function openEdit(p: Perfil) {
    isEditing.value = true;
    editingId.value = p.id;
    form.nombre = p.nombre;
    form.descripcion = p.descripcion ?? '';
    form.activo = p.activo;
    form.clearErrors();
    showModal.value = true;
}

function submitForm() {
    if (isEditing.value && editingId.value) {
        form.put(
            PerfilController.update({ perfil: String(editingId.value) }).url,
            {
                onSuccess: () => {
                    showModal.value = false;
                },
            },
        );
    } else {
        form.post(PerfilController.store().url, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
}

// ── Eliminar ──────────────────────────────────────────────────────────────────
const showDeleteModal = ref(false);
const perfilToDelete = ref<Perfil | null>(null);
const isDeleting = ref(false);

function confirmDelete(p: Perfil) {
    perfilToDelete.value = p;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!perfilToDelete.value) return;
    isDeleting.value = true;
    router.delete(
        PerfilController.destroy({ perfil: String(perfilToDelete.value.id) })
            .url,
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                perfilToDelete.value = null;
            },
            onFinish: () => {
                isDeleting.value = false;
            },
        },
    );
}

// ── Panel de permisos ─────────────────────────────────────────────────────────
const showPermisosModal = ref(false);
const perfilEditandoPermisos = ref<Perfil | null>(null);
const selectedPermisos = ref<Set<number>>(new Set());
const isSavingPermisos = ref(false);

const modulos = computed(() => Object.keys(props.permisosPorModulo).sort());

function openPermisos(p: Perfil) {
    perfilEditandoPermisos.value = p;
    // Pre-seleccionar los permisos actuales del perfil (si vienen cargados)
    selectedPermisos.value = new Set(p.permisos?.map((x) => x.id) ?? []);
    // Si no vinieron con permisos, hacer fetch
    if (!p.permisos) {
        router.reload({ only: [] }); // recarga sutil; en realidad lo manejamos con el modal
    }
    showPermisosModal.value = true;
}

function togglePermiso(id: number) {
    if (selectedPermisos.value.has(id)) {
        selectedPermisos.value.delete(id);
    } else {
        selectedPermisos.value.add(id);
    }
}

function toggleModulo(modulo: string) {
    const ids = props.permisosPorModulo[modulo].map((p) => p.id);
    const allSelected = ids.every((id) => selectedPermisos.value.has(id));
    if (allSelected) {
        ids.forEach((id) => selectedPermisos.value.delete(id));
    } else {
        ids.forEach((id) => selectedPermisos.value.add(id));
    }
}

function moduloSeleccionado(modulo: string): boolean {
    return props.permisosPorModulo[modulo].every((p) =>
        selectedPermisos.value.has(p.id),
    );
}

function savePermisos() {
    if (!perfilEditandoPermisos.value) return;
    isSavingPermisos.value = true;
    router.post(
        PerfilController.syncPermisos({
            perfil: String(perfilEditandoPermisos.value.id),
        }).url,
        { permiso_ids: Array.from(selectedPermisos.value) },
        {
            onSuccess: () => {
                showPermisosModal.value = false;
            },
            onFinish: () => {
                isSavingPermisos.value = false;
            },
        },
    );
}

watch(showPermisosModal, (val) => {
    if (!val) {
        perfilEditandoPermisos.value = null;
        selectedPermisos.value = new Set();
    }
});
</script>

<template>
    <Head title="Perfiles" />

    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Perfiles</h1>
            <Button @click="openCreate">
                <Plus class="mr-2 h-4 w-4" />
                Nuevo Perfil
            </Button>
        </div>

        <div class="rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Nombre</TableHead>
                        <TableHead>Descripción</TableHead>
                        <TableHead>Permisos</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="p in props.perfiles.data" :key="p.id">
                        <TableCell class="font-medium">{{
                            p.nombre
                        }}</TableCell>
                        <TableCell
                            class="max-w-xs truncate text-muted-foreground"
                        >
                            {{ p.descripcion || '-' }}
                        </TableCell>
                        <TableCell class="text-sm">
                            {{ p.permisos_count ?? 0 }} permiso(s)
                        </TableCell>
                        <TableCell>
                            <StatusBadge :active="p.activo" />
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
                                    <DropdownMenuItem @click="openPermisos(p)">
                                        <ShieldCheck class="mr-2 h-4 w-4" />
                                        <span>Gestionar Permisos</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem @click="openEdit(p)">
                                        <Pencil class="mr-2 h-4 w-4" />
                                        <span>Editar</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem
                                        @click="confirmDelete(p)"
                                        class="cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive"
                                    >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Desactivar</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.perfiles.data.length === 0">
                        <TableCell
                            colspan="5"
                            class="h-24 text-center text-muted-foreground"
                        >
                            No hay perfiles registrados.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div
                v-if="props.perfiles.total > 0"
                class="flex items-center justify-between border-t px-4 py-3"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.perfiles.current_page }} de
                    {{ props.perfiles.last_page }} ({{ props.perfiles.total }}
                    registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.perfiles.current_page <= 1"
                        @click="
                            router.get(PerfilController.index().url, {
                                page: props.perfiles.current_page - 1,
                            })
                        "
                    >
                        Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.perfiles.current_page >=
                            props.perfiles.last_page
                        "
                        @click="
                            router.get(PerfilController.index().url, {
                                page: props.perfiles.current_page + 1,
                            })
                        "
                    >
                        Siguiente
                    </Button>
                </div>
            </div>
        </div>

        <!-- Modal crear/editar perfil -->
        <FormModal
            v-model:show="showModal"
            :title="isEditing ? 'Editar Perfil' : 'Nuevo Perfil'"
            :processing="form.processing"
            @submit="submitForm"
        >
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="nombre">Nombre *</Label>
                    <Input
                        id="nombre"
                        v-model="form.nombre"
                        placeholder="Ej: Director IE"
                    />
                    <p
                        v-if="form.errors.nombre"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.nombre }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label for="descripcion">Descripción</Label>
                    <Input
                        id="descripcion"
                        v-model="form.descripcion"
                        placeholder="Descripción opcional"
                    />
                </div>
                <div v-if="isEditing" class="mt-1 flex items-center gap-2">
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
                        >Perfil activo</Label
                    >
                </div>
            </div>
        </FormModal>

        <!-- Modal desactivar perfil -->
        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Desactivar Perfil"
            :description="`¿Desactivar el perfil '${perfilToDelete?.nombre}'?`"
            confirm-text="Desactivar"
            cancel-text="Cancelar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="perfilToDelete = null"
        />

        <!-- Panel de permisos (modal grande) -->
        <Teleport to="body" v-if="isMounted">
            <div
                v-if="showPermisosModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                @click.self="showPermisosModal = false"
            >
                <div
                    class="mx-4 flex max-h-[90vh] w-full max-w-2xl flex-col rounded-lg bg-background shadow-xl"
                >
                    <div
                        class="flex items-center justify-between border-b px-6 py-4"
                    >
                        <div>
                            <h2 class="text-lg font-semibold">
                                Gestionar Permisos
                            </h2>
                            <p class="text-sm text-muted-foreground">
                                {{ perfilEditandoPermisos?.nombre }}
                            </p>
                        </div>
                        <button
                            @click="showPermisosModal = false"
                            class="rounded-md p-1 text-muted-foreground hover:bg-muted"
                        >
                            ✕
                        </button>
                    </div>

                    <div class="flex-1 space-y-6 overflow-y-auto px-6 py-4">
                        <div
                            v-for="modulo in modulos"
                            :key="modulo"
                            class="rounded-md border"
                        >
                            <div
                                class="flex items-center gap-3 border-b bg-muted/40 px-4 py-2"
                            >
                                <input
                                    type="checkbox"
                                    :id="`mod-${modulo}`"
                                    :checked="moduloSeleccionado(modulo)"
                                    @change="toggleModulo(modulo)"
                                    class="size-4 cursor-pointer accent-primary"
                                />
                                <label
                                    :for="`mod-${modulo}`"
                                    class="cursor-pointer font-semibold capitalize select-none"
                                >
                                    {{ modulo }}
                                </label>
                            </div>
                            <div
                                class="grid grid-cols-1 gap-2 p-3 sm:grid-cols-2"
                            >
                                <label
                                    v-for="permiso in permisosPorModulo[modulo]"
                                    :key="permiso.id"
                                    class="flex cursor-pointer items-start gap-2 rounded p-1 hover:bg-muted/50"
                                >
                                    <input
                                        type="checkbox"
                                        :checked="
                                            selectedPermisos.has(permiso.id)
                                        "
                                        @change="togglePermiso(permiso.id)"
                                        class="mt-0.5 size-4 cursor-pointer accent-primary"
                                    />
                                    <div>
                                        <p
                                            class="text-sm leading-none font-medium"
                                        >
                                            {{ permiso.codigo }}
                                        </p>
                                        <p
                                            v-if="permiso.descripcion"
                                            class="mt-0.5 text-xs text-muted-foreground"
                                        >
                                            {{ permiso.descripcion }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <p
                            v-if="modulos.length === 0"
                            class="py-8 text-center text-muted-foreground"
                        >
                            No hay permisos configurados en el sistema.
                        </p>
                    </div>

                    <div class="flex justify-end gap-2 border-t px-6 py-4">
                        <Button
                            variant="outline"
                            @click="showPermisosModal = false"
                            >Cancelar</Button
                        >
                        <Button
                            @click="savePermisos"
                            :disabled="isSavingPermisos"
                        >
                            {{
                                isSavingPermisos
                                    ? 'Guardando...'
                                    : 'Guardar Permisos'
                            }}
                        </Button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

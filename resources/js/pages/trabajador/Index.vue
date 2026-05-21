<script setup lang="ts">
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import {
    Plus,
    Pencil,
    Trash2,
    Eye,
    ChevronDown,
    UserCheck,
    Search,
    Loader2,
    CheckCircle2,
} from 'lucide-vue-next';
import { ref, watch, computed } from 'vue';
import PersonaController from '@/actions/App/Http/Controllers/Persona/PersonaController';
import TrabajadorController from '@/actions/App/Http/Controllers/Trabajador/TrabajadorController';
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
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { Persona } from '@/types/models/persona';
import type { Trabajador, PaginatedResponse } from '@/types/models/trabajador';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Trabajadores', href: TrabajadorController.index().url },
        ],
    },
});

const props = defineProps<{
    trabajadores: PaginatedResponse<Trabajador>;
    filters: { search?: string };
}>();

// ─── Búsqueda en Vivo en el Index ───
const search = ref(props.filters.search || '');
let searchTimeout: any = null;

watch(search, (val) => {
    if (searchTimeout) {
clearTimeout(searchTimeout);
}

    searchTimeout = setTimeout(() => {
        router.get(
            TrabajadorController.index().url,
            { search: val || undefined },
            {
                preserveState: true,
                replace: true,
            },
        );
    }, 300);
});

// ─── Modal Confirmar Desactivación ───
const showDeleteModal = ref(false);
const trabajadorToDelete = ref<Trabajador | null>(null);
const isDeleting = ref(false);

function confirmDelete(trabajador: Trabajador) {
    trabajadorToDelete.value = trabajador;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (!trabajadorToDelete.value) {
return;
}

    isDeleting.value = true;
    router.delete(
        TrabajadorController.destroy({ trabajador: trabajadorToDelete.value.id }).url,
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                trabajadorToDelete.value = null;
            },
            onFinish: () => {
                isDeleting.value = false;
            },
        },
    );
}

// ─── Modal de Creación Masiva ───
const showCreateModal = ref(false);
const searchPersonaQuery = ref('');
const isSearchingPersonas = ref(false);
const searchedPersonas = ref<PaginatedResponse<Persona> | null>(null);
let searchPersonaTimeout: any = null;

// Formularios y Selecciones
const selectedPersonas = ref<Persona[]>([]);
const showOnlySelected = ref(false);

const displayedPersonas = computed(() => {
    return showOnlySelected.value 
        ? selectedPersonas.value 
        : (searchedPersonas.value?.data || []);
});

const form = useForm({
    trabajadores: [] as { persona_id: number; activo: boolean }[],
});

function openCreateModal() {
    showCreateModal.value = true;
    showOnlySelected.value = false;
    searchPersonaQuery.value = '';
    searchedPersonas.value = null;
    selectedPersonas.value = [];
    form.reset();
    form.clearErrors();
    searchPersonasAPI('');
}

async function searchPersonasAPI(query: string, page = 1) {
    isSearchingPersonas.value = true;

    try {
        const response = await fetch(
            PersonaController.search({ query: { search: query, page, exclude_trabajadores: true } }).url,
            {
                headers: {
                    Accept: 'application/json',
                },
            }
        );

        if (response.ok) {
            searchedPersonas.value = await response.json();
        }
    } catch (e) {
        console.error('Error buscando personas', e);
    } finally {
        isSearchingPersonas.value = false;
    }
}

watch(searchPersonaQuery, (val) => {
    if (searchPersonaTimeout) {
clearTimeout(searchPersonaTimeout);
}

    searchPersonaTimeout = setTimeout(() => {
        searchPersonasAPI(val);
    }, 300);
});

function togglePersonaSelection(persona: Persona) {
    const index = selectedPersonas.value.findIndex((p) => p.id === persona.id);

    if (index === -1) {
        selectedPersonas.value.push(persona);
    } else {
        selectedPersonas.value.splice(index, 1);
    }
}

function isPersonaSelected(id: number) {
    return selectedPersonas.value.some((p) => p.id === id);
}

function submitMasivo() {
    if (selectedPersonas.value.length === 0) {
return;
}
    
    form.trabajadores = selectedPersonas.value.map((persona) => ({
        persona_id: persona.id,
        activo: true,
    }));
    
    form.post(TrabajadorController.store().url, {
        onSuccess: () => {
            showCreateModal.value = false;
        },
    });
}
</script>

<template>
    <Head title="Trabajadores" />

    <div class="flex flex-col gap-4 p-4 ">
        <!-- Encabezado -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    Trabajadores
                </h1>
                <p class="text-sm text-muted-foreground">
                    Gestión del personal registrado y sus códigos de trabajador asignados.
                </p>
            </div>
            <Button @click="openCreateModal">
                <Plus class="mr-2 h-4 w-4" />
                Nuevo Trabajador
            </Button>
        </div>

        <!-- Barra de Búsqueda -->
        <div class="flex items-center gap-2">
            <input
                v-model="search"
                type="text"
                placeholder="Buscar por código de trabajador, DNI o nombres..."
                class="flex h-9 w-full max-w-md rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs placeholder:text-muted-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none"
            />
        </div>

        <!-- Tabla Principal -->
        <div class="rounded-md border bg-card shadow-xs overflow-hidden">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[180px]">Código Trabajador</TableHead>
                        <TableHead>Persona</TableHead>
                        <TableHead>Documento</TableHead>
                        <TableHead>Sexo</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="trabajador in props.trabajadores.data"
                        :key="trabajador.id"
                    >
                        <TableCell class="font-semibold text-primary">
                            <div class="flex items-center gap-1.5">
                                <UserCheck class="h-4 w-4 text-muted-foreground shrink-0" />
                                {{ trabajador.codigo }}
                            </div>
                        </TableCell>
                        <TableCell>
                            <div class="font-medium text-sm">
                                {{ trabajador.persona?.paterno }} {{ trabajador.persona?.materno }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{ trabajador.persona?.nombre }}
                            </div>
                        </TableCell>
                        <TableCell>
                            <div class="text-xs text-muted-foreground">
                                {{
                                    trabajador.persona?.tipoDocIdentidad?.abreviatura ||
                                    trabajador.persona?.tipoDocIdentidad?.nombre
                                }}
                            </div>
                            <div class="text-xs font-medium">
                                {{ trabajador.persona?.docIdentidad }}
                            </div>
                        </TableCell>
                        <TableCell class="text-xs">
                            {{ trabajador.persona?.sexo?.nombre || '-' }}
                        </TableCell>
                        <TableCell>
                            <StatusBadge :active="trabajador.activo" />
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
                                    <DropdownMenuLabel>Acciones</DropdownMenuLabel>
                                    <DropdownMenuItem as-child>
                                        <Link
                                            :href="TrabajadorController.show({ trabajador: trabajador.id }).url"
                                            class="flex items-center"
                                        >
                                            <Eye class="mr-2 h-4 w-4" />
                                            <span>Ver Detalles</span>
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem as-child>
                                        <Link
                                            :href="TrabajadorController.edit({ trabajador: trabajador.id }).url"
                                            class="flex items-center"
                                        >
                                            <Pencil class="mr-2 h-4 w-4" />
                                            <span>Editar</span>
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        @click="confirmDelete(trabajador)"
                                        class="cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive"
                                    >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Desactivar</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.trabajadores.data.length === 0">
                        <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                            No hay trabajadores registrados.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Paginación -->
            <div
                class="flex items-center justify-between border-t px-4 py-3"
                v-if="props.trabajadores.total > 0"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.trabajadores.current_page }} de
                    {{ props.trabajadores.last_page }} ({{ props.trabajadores.total }}
                    registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.trabajadores.current_page <= 1"
                        @click="
                            router.get(TrabajadorController.index().url, {
                                page: props.trabajadores.current_page - 1,
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
                            props.trabajadores.current_page >=
                            props.trabajadores.last_page
                        "
                        @click="
                            router.get(TrabajadorController.index().url, {
                                page: props.trabajadores.current_page + 1,
                                search: search || undefined,
                            })
                        "
                    >
                        Siguiente
                    </Button>
                </div>
            </div>
        </div>

        <!-- Modal Confirmación de Desactivación -->
        <ConfirmModal
            v-model:show="showDeleteModal"
            title="Desactivar Trabajador"
            :description="`¿Estás seguro de que deseas desactivar el trabajador con código ${trabajadorToDelete?.codigo}?`"
            confirm-text="Desactivar"
            destructive
            :processing="isDeleting"
            @confirm="executeDelete"
            @cancel="trabajadorToDelete = null"
        />

        <!-- Modal de Creación Masiva -->
        <FormModal
            v-model:show="showCreateModal"
            title="Registrar Trabajadores"
            max-width="4xl"
            @submit="submitMasivo"
            :processing="form.processing"
        >
            <template #default>
                <div class="space-y-4 min-h-[400px]">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">
                            Seleccione una o varias personas para asignarlas como trabajadores.
                        </p>
                        <div class="flex items-center gap-2">
                            <Button
                                v-if="selectedPersonas.length > 0"
                                type="button"
                                variant="outline"
                                size="sm"
                                class="h-7 text-xs"
                                @click="showOnlySelected = !showOnlySelected"
                            >
                                {{ showOnlySelected ? 'Ver Todos' : 'Ver Seleccionados' }}
                            </Button>
                            <span class="text-xs font-semibold bg-primary/10 text-primary px-2 py-1 rounded-full">
                                {{ selectedPersonas.length }} Seleccionados
                            </span>
                        </div>
                    </div>

                    <div v-if="!showOnlySelected" class="relative">
                        <Search class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                        <Input
                            v-model="searchPersonaQuery"
                            placeholder="Buscar por DNI, apellidos o nombres..."
                            class="pl-9"
                        />
                        <Loader2 v-if="isSearchingPersonas" class="absolute right-3 top-2.5 h-4 w-4 animate-spin text-muted-foreground" />
                    </div>

                    <div class="rounded-md border overflow-hidden max-h-[300px] overflow-y-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[50px] text-center"></TableHead>
                                    <TableHead class="w-[100px]">Doc.</TableHead>
                                    <TableHead>Apellidos y Nombres</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow
                                    v-for="persona in displayedPersonas"
                                    :key="persona.id"
                                    class="cursor-pointer hover:bg-muted/50"
                                    @click="togglePersonaSelection(persona)"
                                    :class="{ 'bg-primary/5': isPersonaSelected(persona.id) }"
                                >
                                    <TableCell class="text-center">
                                        <input
                                            type="checkbox"
                                            class="size-4 rounded border-input accent-primary"
                                            :checked="isPersonaSelected(persona.id)"
                                            @click.stop="togglePersonaSelection(persona)"
                                        />
                                    </TableCell>
                                    <TableCell class="font-medium text-xs">
                                        {{ persona.docIdentidad }}
                                    </TableCell>
                                    <TableCell>
                                        <div class="font-medium text-sm">
                                            {{ persona.paterno }} {{ persona.materno }}
                                        </div>
                                        <div class="text-xs text-muted-foreground">
                                            {{ persona.nombre }}
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="displayedPersonas.length === 0">
                                    <TableCell colspan="3" class="h-24 text-center text-muted-foreground text-sm">
                                        {{ showOnlySelected ? 'No hay personas seleccionadas.' : 'No se encontraron personas con ese criterio.' }}
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Paginación de Personas Modal -->
                    <div v-if="!showOnlySelected && searchedPersonas && searchedPersonas.total > 0" class="flex justify-between items-center text-xs text-muted-foreground">
                        <span>Página {{ searchedPersonas.current_page }} de {{ searchedPersonas.last_page }}</span>
                        <div class="flex gap-1">
                            <Button
                                type="button"
                                variant="ghost" size="sm" class="h-7 px-2"
                                :disabled="searchedPersonas.current_page <= 1"
                                @click="searchPersonasAPI(searchPersonaQuery, searchedPersonas.current_page - 1)"
                            >
                                Anterior
                            </Button>
                            <Button
                                type="button"
                                variant="ghost" size="sm" class="h-7 px-2"
                                :disabled="searchedPersonas.current_page >= searchedPersonas.last_page"
                                @click="searchPersonasAPI(searchPersonaQuery, searchedPersonas.current_page + 1)"
                            >
                                Siguiente
                            </Button>
                        </div>
                    </div>
                </div>
            </template>
            
            <template #footer>
                <div class="flex w-full justify-end gap-2">
                    <Button
                        type="button"
                        variant="outline"
                        @click="showCreateModal = false"
                    >
                        Cancelar
                    </Button>
                    
                    <Button
                        type="submit"
                        :disabled="form.processing || selectedPersonas.length === 0"
                    >
                        <CheckCircle2 class="mr-2 h-4 w-4" />
                        {{ form.processing ? 'Guardando...' : `Registrar (${selectedPersonas.length})` }}
                    </Button>
                </div>
            </template>
        </FormModal>
    </div>
</template>

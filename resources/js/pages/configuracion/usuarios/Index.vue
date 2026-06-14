<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Search, ShieldCheck } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import UsuarioController from '@/actions/App/Http/Controllers/Configuracion/UsuarioController';
import GestionUsuarioModal from '@/components/shared/GestionUsuarioModal.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { Usuario } from '@/types/models/configuracion';

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
            { title: 'Usuarios', href: UsuarioController.index().url },
        ],
    },
});

const props = defineProps<{
    usuarios: PaginatedResponse<Usuario>;
    filters: { search?: string };
}>();

const search = ref(props.filters.search ?? '');

let debounceTimer: ReturnType<typeof setTimeout>;
watch(search, (val) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        router.get(
            UsuarioController.index().url,
            { search: val },
            { preserveState: true, replace: true },
        );
    }, 350);
});

function nombreCompleto(u: Usuario): string {
    const p = u.trabajador?.persona;
    if (!p) return u.login;
    return [p.paterno, p.materno, p.nombre].filter(Boolean).join(' ');
}

// ── Modal gestión usuario ──────────────────────────────────────────────────────
const showModal = ref(false);
const modalUserId = ref<number | null>(null);
const modalNombre = ref('');

function openModal(u: Usuario) {
    modalUserId.value = u.id;
    modalNombre.value = nombreCompleto(u);
    showModal.value = true;
}

function onUpdated() {
    router.reload({ only: ['usuarios'] });
}
</script>

<template>
    <Head title="Usuarios" />

    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Usuarios</h1>
        </div>

        <div class="flex items-center gap-2">
            <div class="relative w-72">
                <Search
                    class="absolute top-2.5 left-2.5 h-4 w-4 text-muted-foreground"
                />
                <Input
                    v-model="search"
                    placeholder="Buscar por login o nombre..."
                    class="pl-8"
                />
            </div>
        </div>

        <div class="rounded-md border bg-card">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Login</TableHead>
                        <TableHead>Nombre</TableHead>
                        <TableHead>Perfiles asignados</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="u in props.usuarios.data" :key="u.id">
                        <TableCell class="font-mono text-sm">{{
                            u.login
                        }}</TableCell>
                        <TableCell class="font-medium">{{
                            nombreCompleto(u)
                        }}</TableCell>
                        <TableCell class="text-sm text-muted-foreground">
                            <span
                                v-if="u.perfiles_ie && u.perfiles_ie.length > 0"
                            >
                                {{
                                    u.perfiles_ie
                                        .map((p) => p.perfil?.nombre)
                                        .join(', ')
                                }}
                            </span>
                            <span v-else class="italic">Sin perfiles</span>
                        </TableCell>
                        <TableCell>
                            <StatusBadge :active="u.activo" />
                        </TableCell>
                        <TableCell class="text-right">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="openModal(u)"
                            >
                                <ShieldCheck class="mr-1 h-4 w-4" />
                                Gestionar
                            </Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.usuarios.data.length === 0">
                        <TableCell
                            colspan="5"
                            class="h-24 text-center text-muted-foreground"
                        >
                            No hay usuarios registrados.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div
                v-if="props.usuarios.total > 0"
                class="flex items-center justify-between border-t px-4 py-3"
            >
                <span class="text-sm text-muted-foreground">
                    Página {{ props.usuarios.current_page }} de
                    {{ props.usuarios.last_page }} ({{ props.usuarios.total }}
                    registros)
                </span>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="props.usuarios.current_page <= 1"
                        @click="
                            router.get(UsuarioController.index().url, {
                                page: props.usuarios.current_page - 1,
                                search,
                            })
                        "
                    >
                        Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="
                            props.usuarios.current_page >=
                            props.usuarios.last_page
                        "
                        @click="
                            router.get(UsuarioController.index().url, {
                                page: props.usuarios.current_page + 1,
                                search,
                            })
                        "
                    >
                        Siguiente
                    </Button>
                </div>
            </div>
        </div>
    </div>

    <GestionUsuarioModal
        v-if="modalUserId !== null"
        v-model:show="showModal"
        :user-id="modalUserId"
        :trabajador-nombre="modalNombre"
        @updated="onUpdated"
    />
</template>

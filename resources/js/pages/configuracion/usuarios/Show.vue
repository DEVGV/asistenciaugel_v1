<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import {
    KeyRound,
    Plus,
    Trash2,
    ShieldCheck,
    ShieldOff,
    Building2,
    School,
} from 'lucide-vue-next';
import { ref, watch, computed } from 'vue';
import UsuarioController from '@/actions/App/Http/Controllers/Configuracion/UsuarioController';
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
import type {
    Perfil,
    Usuario,
    UsuarioPerfilIe,
} from '@/types/models/configuracion';

interface InstitucionSimple {
    id: number;
    nombreLegal: string;
    codigoModular: string | null;
    entidadUgel_id: number | null;
}

interface UgelSimple {
    id: number;
    razonSocial: string;
}

const props = defineProps<{
    usuario: Usuario;
    perfilesIe: UsuarioPerfilIe[];
    perfiles: Perfil[];
    instituciones: InstitucionSimple[];
    ugeles: UgelSimple[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Configuración', href: '#' },
            { title: 'Usuarios', href: UsuarioController.index().url },
            { title: 'Gestionar Usuario', href: '#' },
        ],
    },
});

function nombreCompleto(): string {
    const p = props.usuario.trabajador?.persona;

    if (!p) {
        return props.usuario.login;
    }

    return [p.paterno, p.materno, p.nombre].filter(Boolean).join(' ');
}

// ── Clasificar asignaciones ──────────────────────────────────────────────────
const asignacionesIe = computed(() =>
    props.perfilesIe.filter(
        (upi) => upi.institucionEducativa_id !== null && upi.institucionEducativa_id !== undefined,
    ),
);

const asignacionesUgel = computed(() =>
    props.perfilesIe.filter(
        (upi) =>
            (upi.institucionEducativa_id === null || upi.institucionEducativa_id === undefined) &&
            upi.entidadUgel_id !== null && upi.entidadUgel_id !== undefined,
    ),
);

const asignacionesGlobal = computed(() =>
    props.perfilesIe.filter(
        (upi) =>
            (upi.institucionEducativa_id === null || upi.institucionEducativa_id === undefined) &&
            (upi.entidadUgel_id === null || upi.entidadUgel_id === undefined),
    ),
);

// ── Cambiar contraseña ────────────────────────────────────────────────────────
const showPasswordModal = ref(false);
const passwordForm = useForm({
    password: '',
    password_confirmation: '',
});

watch(showPasswordModal, (val) => {
    if (!val) {
        passwordForm.reset();
        passwordForm.clearErrors();
    }
});

function submitPassword() {
    passwordForm.post(UsuarioController.cambiarPassword(props.usuario.id).url, {
        onSuccess: () => {
            showPasswordModal.value = false;
            passwordForm.reset();
        },
    });
}

// ── Toggle activo ─────────────────────────────────────────────────────────────
const showToggleModal = ref(false);
const isToggling = ref(false);

function executeToggle() {
    isToggling.value = true;
    router.post(
        UsuarioController.toggleActivo(props.usuario.id).url,
        {},
        {
            onSuccess: () => {
                showToggleModal.value = false;
            },
            onFinish: () => {
                isToggling.value = false;
            },
        },
    );
}

// ── Asignar perfil a IE (flujo principal) ────────────────────────────────────
const showAsignarIeModal = ref(false);
const asignarIeForm = useForm({
    perfil_id: null as number | null,
    institucionEducativa_id: null as number | null,
});

watch(showAsignarIeModal, (val) => {
    if (!val) {
        asignarIeForm.reset();
        asignarIeForm.clearErrors();
    }
});

function submitAsignarIe() {
    if (!asignarIeForm.institucionEducativa_id) {
        return;
    }
    asignarIeForm.post(UsuarioController.asignarPerfil(props.usuario.id).url, {
        onSuccess: () => {
            showAsignarIeModal.value = false;
            asignarIeForm.reset();
        },
    });
}

// ── Dar acceso UGEL (flujo separado) ─────────────────────────────────────────
const showAsignarUgelModal = ref(false);
const asignarUgelForm = useForm({
    perfil_id: null as number | null,
    institucionEducativa_id: null as null,
    entidadUgel_id: null as number | null,
});

watch(showAsignarUgelModal, (val) => {
    if (!val) {
        asignarUgelForm.reset();
        asignarUgelForm.clearErrors();
    }
});

function submitAsignarUgel() {
    if (!asignarUgelForm.entidadUgel_id) {
        return;
    }
    asignarUgelForm.post(
        UsuarioController.asignarPerfil(props.usuario.id).url,
        {
            onSuccess: () => {
                showAsignarUgelModal.value = false;
                asignarUgelForm.reset();
            },
        },
    );
}

// ── Revocar perfil ────────────────────────────────────────────────────────────
const showRevocarModal = ref(false);
const perfilIeAEliminar = ref<UsuarioPerfilIe | null>(null);
const isRevoking = ref(false);

function confirmRevocar(upi: UsuarioPerfilIe) {
    perfilIeAEliminar.value = upi;
    showRevocarModal.value = true;
}

function executeRevocar() {
    if (!perfilIeAEliminar.value) {
        return;
    }

    isRevoking.value = true;
    router.delete(
        UsuarioController.revocarPerfil({
            usuario: props.usuario.id,
            perfilIe: perfilIeAEliminar.value.id,
        }).url,
        {
            onSuccess: () => {
                showRevocarModal.value = false;
                perfilIeAEliminar.value = null;
            },
            onFinish: () => {
                isRevoking.value = false;
            },
        },
    );
}
</script>

<template>
    <Head :title="`Usuario: ${usuario.login}`" />

    <div class="flex flex-col gap-6 p-4">
        <!-- Encabezado -->
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">
                    {{ nombreCompleto() }}
                </h1>
                <p class="mt-1 font-mono text-sm text-muted-foreground">
                    {{ usuario.login }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <StatusBadge :active="usuario.activo" />
                <Button
                    variant="outline"
                    size="sm"
                    @click="showToggleModal = true"
                >
                    <ShieldOff v-if="usuario.activo" class="mr-1 h-4 w-4" />
                    <ShieldCheck v-else class="mr-1 h-4 w-4" />
                    {{ usuario.activo ? 'Desactivar' : 'Activar' }}
                </Button>
                <Button
                    variant="outline"
                    size="sm"
                    @click="showPasswordModal = true"
                >
                    <KeyRound class="mr-1 h-4 w-4" />
                    Cambiar Contraseña
                </Button>
            </div>
        </div>

        <!-- ═══ Acceso a Instituciones Educativas ═══ -->
        <div class="rounded-md border bg-card">
            <div class="flex items-center justify-between border-b px-4 py-3">
                <div class="flex items-center gap-2">
                    <School class="h-4 w-4 text-muted-foreground" />
                    <h2 class="font-semibold">
                        Acceso a Instituciones Educativas
                    </h2>
                </div>
                <Button size="sm" @click="showAsignarIeModal = true">
                    <Plus class="mr-1 h-4 w-4" />
                    Asignar a IE
                </Button>
            </div>
            <p class="border-b px-4 py-2 text-xs text-muted-foreground">
                El usuario solo puede acceder a las IEs listadas aquí. Este es
                el acceso por defecto para todos los usuarios.
            </p>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Perfil</TableHead>
                        <TableHead>Institución Educativa</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="upi in asignacionesIe" :key="upi.id">
                        <TableCell class="font-medium">{{
                            upi.perfil?.nombre
                        }}</TableCell>
                        <TableCell>
                            {{ upi.institucionEducativa?.nombreLegal }}
                            <span
                                v-if="
                                    upi.institucionEducativa?.codigoModular
                                "
                                class="ml-1 font-mono text-xs text-muted-foreground"
                            >
                                ({{
                                    upi.institucionEducativa.codigoModular
                                }})
                            </span>
                        </TableCell>
                        <TableCell class="text-right">
                            <Button
                                variant="ghost"
                                size="sm"
                                class="text-destructive hover:bg-destructive/10 hover:text-destructive"
                                @click="confirmRevocar(upi)"
                            >
                                <Trash2 class="h-4 w-4" />
                            </Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="asignacionesIe.length === 0">
                        <TableCell
                            colspan="3"
                            class="h-16 text-center text-muted-foreground"
                        >
                            Sin acceso a ninguna IE. El usuario no podrá
                            ingresar al sistema.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- ═══ Acceso como Administrador de UGEL ═══ -->
        <div class="rounded-md border bg-card">
            <div class="flex items-center justify-between border-b px-4 py-3">
                <div class="flex items-center gap-2">
                    <Building2 class="h-4 w-4 text-muted-foreground" />
                    <h2 class="font-semibold">
                        Acceso como Administrador de UGEL
                    </h2>
                </div>
                <Button
                    size="sm"
                    variant="outline"
                    @click="showAsignarUgelModal = true"
                >
                    <Plus class="mr-1 h-4 w-4" />
                    Dar Acceso UGEL
                </Button>
            </div>
            <p class="border-b px-4 py-2 text-xs text-muted-foreground">
                Un administrador de UGEL tiene acceso a
                <strong>todas</strong> las IEs de esa UGEL. Solo asigne este
                nivel si es necesario.
            </p>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Perfil</TableHead>
                        <TableHead>UGEL</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="upi in asignacionesUgel" :key="upi.id">
                        <TableCell class="font-medium">{{
                            upi.perfil?.nombre
                        }}</TableCell>
                        <TableCell class="italic">
                            {{ upi.entidadUgel?.razonSocial }}
                        </TableCell>
                        <TableCell class="text-right">
                            <Button
                                variant="ghost"
                                size="sm"
                                class="text-destructive hover:bg-destructive/10 hover:text-destructive"
                                @click="confirmRevocar(upi)"
                            >
                                <Trash2 class="h-4 w-4" />
                            </Button>
                        </TableCell>
                    </TableRow>
                    <!-- Admin global (si existe) -->
                    <TableRow
                        v-for="upi in asignacionesGlobal"
                        :key="upi.id"
                    >
                        <TableCell class="font-medium">{{
                            upi.perfil?.nombre
                        }}</TableCell>
                        <TableCell class="italic text-muted-foreground">
                            Admin global (todas las UGELs)
                        </TableCell>
                        <TableCell class="text-right">
                            <Button
                                variant="ghost"
                                size="sm"
                                class="text-destructive hover:bg-destructive/10 hover:text-destructive"
                                @click="confirmRevocar(upi)"
                            >
                                <Trash2 class="h-4 w-4" />
                            </Button>
                        </TableCell>
                    </TableRow>
                    <TableRow
                        v-if="
                            asignacionesUgel.length === 0 &&
                            asignacionesGlobal.length === 0
                        "
                    >
                        <TableCell
                            colspan="3"
                            class="h-16 text-center text-muted-foreground"
                        >
                            Sin acceso de administrador de UGEL.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Modal cambiar contraseña -->
        <FormModal
            v-model:show="showPasswordModal"
            title="Cambiar Contraseña"
            :processing="passwordForm.processing"
            @submit="submitPassword"
        >
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="password">Nueva contraseña *</Label>
                    <Input
                        id="password"
                        type="password"
                        v-model="passwordForm.password"
                        placeholder="Mínimo 8 caracteres"
                        autocomplete="new-password"
                    />
                    <p
                        v-if="passwordForm.errors.password"
                        class="text-sm text-destructive"
                    >
                        {{ passwordForm.errors.password }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label for="password_confirmation"
                        >Confirmar contraseña *</Label
                    >
                    <Input
                        id="password_confirmation"
                        type="password"
                        v-model="passwordForm.password_confirmation"
                        placeholder="Repetir contraseña"
                        autocomplete="new-password"
                    />
                </div>
            </div>
        </FormModal>

        <!-- Modal asignar perfil a IE -->
        <FormModal
            v-model:show="showAsignarIeModal"
            title="Asignar Perfil a Institución Educativa"
            :processing="asignarIeForm.processing"
            @submit="submitAsignarIe"
        >
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="perfil_ie">Perfil *</Label>
                    <select
                        id="perfil_ie"
                        v-model="asignarIeForm.perfil_id"
                        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm focus:ring-1 focus:ring-ring focus:outline-none"
                    >
                        <option :value="null" disabled>
                            Seleccionar perfil...
                        </option>
                        <option v-for="p in perfiles" :key="p.id" :value="p.id">
                            {{ p.nombre }}
                        </option>
                    </select>
                    <p
                        v-if="asignarIeForm.errors.perfil_id"
                        class="text-sm text-destructive"
                    >
                        {{ asignarIeForm.errors.perfil_id }}
                    </p>
                </div>

                <div class="grid gap-2">
                    <Label for="ie_id">Institución Educativa *</Label>
                    <select
                        id="ie_id"
                        v-model="asignarIeForm.institucionEducativa_id"
                        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm focus:ring-1 focus:ring-ring focus:outline-none"
                    >
                        <option :value="null" disabled>
                            Seleccionar institución...
                        </option>
                        <option
                            v-for="ie in instituciones"
                            :key="ie.id"
                            :value="ie.id"
                        >
                            {{ ie.nombreLegal
                            }}{{
                                ie.codigoModular ? ` (${ie.codigoModular})` : ''
                            }}
                        </option>
                    </select>
                    <p
                        v-if="asignarIeForm.errors.institucionEducativa_id"
                        class="text-sm text-destructive"
                    >
                        {{ asignarIeForm.errors.institucionEducativa_id }}
                    </p>
                </div>
            </div>
        </FormModal>

        <!-- Modal dar acceso UGEL -->
        <FormModal
            v-model:show="showAsignarUgelModal"
            title="Dar Acceso de Administrador de UGEL"
            :processing="asignarUgelForm.processing"
            @submit="submitAsignarUgel"
        >
            <div class="grid gap-4">
                <div
                    class="rounded-md border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-950/30 dark:text-amber-300"
                >
                    Un administrador de UGEL tiene acceso a
                    <strong>todas</strong> las instituciones educativas de la
                    UGEL seleccionada. Solo asigne este nivel a personal
                    autorizado.
                </div>

                <div class="grid gap-2">
                    <Label for="perfil_ugel">Perfil *</Label>
                    <select
                        id="perfil_ugel"
                        v-model="asignarUgelForm.perfil_id"
                        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm focus:ring-1 focus:ring-ring focus:outline-none"
                    >
                        <option :value="null" disabled>
                            Seleccionar perfil...
                        </option>
                        <option v-for="p in perfiles" :key="p.id" :value="p.id">
                            {{ p.nombre }}
                        </option>
                    </select>
                    <p
                        v-if="asignarUgelForm.errors.perfil_id"
                        class="text-sm text-destructive"
                    >
                        {{ asignarUgelForm.errors.perfil_id }}
                    </p>
                </div>

                <div class="grid gap-2">
                    <Label for="ugel_id">UGEL *</Label>
                    <select
                        id="ugel_id"
                        v-model="asignarUgelForm.entidadUgel_id"
                        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm focus:ring-1 focus:ring-ring focus:outline-none"
                    >
                        <option :value="null" disabled>
                            Seleccionar UGEL...
                        </option>
                        <option
                            v-for="ugel in ugeles"
                            :key="ugel.id"
                            :value="ugel.id"
                        >
                            {{ ugel.razonSocial }}
                        </option>
                    </select>
                    <p
                        v-if="asignarUgelForm.errors.entidadUgel_id"
                        class="text-sm text-destructive"
                    >
                        {{ asignarUgelForm.errors.entidadUgel_id }}
                    </p>
                </div>
            </div>
        </FormModal>

        <!-- Modal confirmar toggle -->
        <ConfirmModal
            v-model:show="showToggleModal"
            :title="usuario.activo ? 'Desactivar Usuario' : 'Activar Usuario'"
            :description="`¿Confirmas ${usuario.activo ? 'desactivar' : 'activar'} al usuario '${usuario.login}'?`"
            :confirm-text="usuario.activo ? 'Desactivar' : 'Activar'"
            cancel-text="Cancelar"
            :destructive="usuario.activo"
            :processing="isToggling"
            @confirm="executeToggle"
        />

        <!-- Modal confirmar revocar -->
        <ConfirmModal
            v-model:show="showRevocarModal"
            title="Revocar Acceso"
            :description="`¿Revocar el perfil '${perfilIeAEliminar?.perfil?.nombre}' de este usuario?`"
            confirm-text="Revocar"
            cancel-text="Cancelar"
            destructive
            :processing="isRevoking"
            @confirm="executeRevocar"
            @cancel="perfilIeAEliminar = null"
        />
    </div>
</template>

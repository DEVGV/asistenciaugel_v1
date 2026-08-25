<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import {
    ArrowLeft,
    User,
    FileText,
    Calendar,
    CalendarCheck2,
    ClipboardCheck,
    Phone,
    Mail,
    MapPin,
    ShieldCheck,
    Pencil,
} from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { usePermisos } from '@/composables/usePermisos';
import TrabajadorController from '@/actions/App/Http/Controllers/Trabajador/TrabajadorController';
import DomiciliosList from '@/components/persona/DomiciliosList.vue';
import EmailsList from '@/components/persona/EmailsList.vue';
import PersonaForm from '@/components/persona/PersonaForm.vue';
import TelefonosList from '@/components/persona/TelefonosList.vue';
import FormModal from '@/components/shared/FormModal.vue';
import GestionUsuarioModal from '@/components/shared/GestionUsuarioModal.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import AltasList from '@/components/trabajador/AltasList.vue';
import AsistenciaTrabajadorTab from '@/components/trabajador/AsistenciaTrabajadorTab.vue';
import HorariosTrabajadorTab from '@/components/trabajador/HorariosTrabajadorTab.vue';
import PermisosTrabajadorTab from '@/components/trabajador/PermisosTrabajadorTab.vue';
import { Button } from '@/components/ui/button';
import type { Trabajador } from '@/types/models/trabajador';

const props = defineProps<{
    trabajador: Trabajador;
    activeTab?: string;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Trabajadores', href: TrabajadorController.index().url },
            { title: 'Detalle', href: '#' },
        ],
    },
});

const { can } = usePermisos();

type TabKey = 'datos' | 'laboral' | 'horarios' | 'asistencia' | 'permisos';

const activeTab = ref<TabKey>((props.activeTab as TabKey) ?? 'datos');

const tabs = [
    { key: 'datos', label: 'Datos Personales', icon: User },
    { key: 'laboral', label: 'Información Laboral', icon: FileText },
    { key: 'horarios', label: 'Horarios', icon: Calendar },
    { key: 'asistencia', label: 'Registro de Asistencia', icon: CalendarCheck2 },
    { key: 'permisos', label: 'Expedientes', icon: ClipboardCheck },
] as const;

// 'horarios' usa 'horario' (singular) en la URL para evitar conflicto con la ruta JSON
const tabSegments: Partial<Record<TabKey, string>> = {
    laboral: 'laboral',
    horarios: 'horario',
    asistencia: 'asistencia',
    permisos: 'permisos',
};

function switchTab(key: TabKey) {
    const base = TrabajadorController.show({ trabajador: props.trabajador.id }).url;
    const segment = tabSegments[key];
    router.visit(segment ? `${base}/${segment}` : base, { preserveScroll: false });
}

// ── Modal usuario ─────────────────────────────────────────────────────────────
const showUsuarioModal = ref(false);

const nombreTrabajador = computed(() => {
    const p = props.trabajador.persona;

    if (!p) {
        return `Trabajador #${props.trabajador.id}`;
    }

    return [p.paterno, p.materno, p.nombre].filter(Boolean).join(' ');
});

// ── Modal editar datos personales ─────────────────────────────────────────────
const showEditPersonaModal = ref(false);

const editForm = useForm({
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
});

watch(showEditPersonaModal, (open) => {
    if (open) {
        const p = props.trabajador.persona;
        editForm.tipoDocIdentidad_id = p?.tipoDocIdentidad_id ?? null;
        editForm.docIdentidad = p?.docIdentidad ?? '';
        editForm.paterno = p?.paterno ?? '';
        editForm.materno = p?.materno ?? '';
        editForm.nombre = p?.nombre ?? '';
        editForm.sexo_id = p?.sexo_id ?? null;
        editForm.fechaNac = p?.fechaNac ?? '';
        editForm.pais_id = p?.pais_id ?? null;
        editForm.ubigeo = p?.ubigeo ?? '';
        editForm.foto = p?.foto ?? '';
        editForm.activo = p?.activo ?? true;
        editForm.clearErrors();
    }
});

function submitEditPersona() {
    editForm.put(TrabajadorController.show({ trabajador: props.trabajador.id }).url, {
        onSuccess: () => {
            showEditPersonaModal.value = false;
        },
    });
}
</script>

<template>
    <Head
        :title="`Trabajador: ${props.trabajador.persona?.paterno} ${props.trabajador.persona?.nombre}`"
    />

    <div class="flex flex-col gap-4 p-4">
        <!-- Encabezado -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    {{ props.trabajador.persona?.paterno }}
                    {{ props.trabajador.persona?.materno }},
                    {{ props.trabajador.persona?.nombre }}
                </h1>
                <div
                    class="mt-1 flex flex-wrap items-center gap-2 text-sm text-muted-foreground"
                >
                    <span>
                        Código:
                        <span class="font-semibold text-primary">{{
                            props.trabajador.codigo
                        }}</span>
                    </span>
                    <span
                        v-if="props.trabajador.persona?.docIdentidad"
                        class="flex items-center gap-1"
                    >
                        <span class="text-muted-foreground/50">·</span>
                        {{
                            props.trabajador.persona?.tipoDocIdentidad?.nombre
                        }}:
                        <span class="font-semibold text-foreground">{{
                            props.trabajador.persona?.docIdentidad
                        }}</span>
                    </span>
                    <StatusBadge :active="props.trabajador.activo" />
                </div>
            </div>
            <div class="flex items-center gap-2">
                <Button
                    v-if="can('usuarios.gestionar')"
                    variant="outline"
                    size="sm"
                    @click="showUsuarioModal = true"
                >
                    <ShieldCheck class="mr-2 h-4 w-4" />
                    Usuario
                </Button>
                <Button variant="outline" as-child size="sm">
                    <Link :href="TrabajadorController.index().url">
                        <ArrowLeft class="mr-2 h-4 w-4" /> Volver
                    </Link>
                </Button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap gap-1 border-b">
            <button
                v-for="tab in tabs"
                :key="tab.key"
                @click="switchTab(tab.key as TabKey)"
                :class="[
                    '-mb-px flex items-center gap-2 border-b-2 px-4 py-2.5 text-sm font-medium transition-colors',
                    activeTab === tab.key
                        ? 'border-primary text-primary'
                        : 'border-transparent text-muted-foreground hover:border-muted-foreground/50 hover:text-foreground',
                ]"
            >
                <component :is="tab.icon" class="h-4 w-4" />
                {{ tab.label }}
            </button>
        </div>

        <!-- Contenido -->
        <div class="mt-2">
            <!-- TAB: Datos Personales (incluye contacto y domicilio) -->
            <div v-if="activeTab === 'datos'" class="space-y-6">
                <!-- Datos personales -->
                <div class="rounded-xl border bg-card p-6 shadow-xs">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">
                            Datos Personales
                        </h2>
                        <Button v-if="can('trabajadores.editar')" variant="outline" size="sm" @click="showEditPersonaModal = true">
                            <Pencil class="mr-2 h-3.5 w-3.5" />
                            Editar
                        </Button>
                    </div>
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 text-sm md:grid-cols-3">
                        <div>
                            <span class="text-muted-foreground">Tipo de Documento:</span>
                            <p class="mt-0.5 font-medium">
                                {{ props.trabajador.persona?.tipoDocIdentidad?.nombre || '-' }}
                            </p>
                        </div>
                        <div>
                            <span class="text-muted-foreground">N° de Documento:</span>
                            <p class="mt-0.5 font-medium">{{ props.trabajador.persona?.docIdentidad }}</p>
                        </div>
                        <div>
                            <span class="text-muted-foreground">Sexo:</span>
                            <p class="mt-0.5 font-medium">{{ props.trabajador.persona?.sexo?.nombre || '-' }}</p>
                        </div>
                        <div>
                            <span class="text-muted-foreground">País de Origen:</span>
                            <p class="mt-0.5 font-medium">{{ props.trabajador.persona?.pais?.nombre || '-' }}</p>
                        </div>
                        <div>
                            <span class="text-muted-foreground">Fecha de Nacimiento:</span>
                            <p class="mt-0.5 font-medium">{{ props.trabajador.persona?.fechaNac || '-' }}</p>
                        </div>
                        <div>
                            <span class="text-muted-foreground">Ubigeo:</span>
                            <p class="mt-0.5 font-medium">{{ props.trabajador.persona?.ubigeo || '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contacto y Domicilio -->
                <div v-if="props.trabajador.persona" class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-xl border bg-card p-4 shadow-xs">
                        <div class="mb-3 flex items-center gap-2 border-b pb-2">
                            <Phone class="h-4 w-4 text-primary" />
                            <h3 class="text-sm font-semibold">Teléfonos</h3>
                        </div>
                        <TelefonosList
                            :telefonos="props.trabajador.persona.telefonos || []"
                            :persona-id="props.trabajador.persona.id"
                        />
                    </div>
                    <div class="rounded-xl border bg-card p-4 shadow-xs">
                        <div class="mb-3 flex items-center gap-2 border-b pb-2">
                            <Mail class="h-4 w-4 text-primary" />
                            <h3 class="text-sm font-semibold">Correos Electrónicos</h3>
                        </div>
                        <EmailsList
                            :emails="props.trabajador.persona.emails || []"
                            :persona-id="props.trabajador.persona.id"
                        />
                    </div>
                    <div class="rounded-xl border bg-card p-4 shadow-xs">
                        <div class="mb-3 flex items-center gap-2 border-b pb-2">
                            <MapPin class="h-4 w-4 text-primary" />
                            <h3 class="text-sm font-semibold">Domicilios</h3>
                        </div>
                        <DomiciliosList
                            :domicilios="props.trabajador.persona.domicilios || []"
                            :persona-id="props.trabajador.persona.id"
                        />
                    </div>
                </div>
            </div>

            <!-- TAB: Información Laboral -->
            <div v-if="activeTab === 'laboral'">
                <AltasList
                    :altas="props.trabajador.altas || []"
                    :trabajador-id="props.trabajador.id"
                />
            </div>

            <!-- TAB: Horarios -->
            <div v-if="activeTab === 'horarios'">
                <HorariosTrabajadorTab :trabajador-id="props.trabajador.id" />
            </div>

            <!-- TAB: Registro de Asistencia -->
            <div v-if="activeTab === 'asistencia'">
                <AsistenciaTrabajadorTab :trabajador-id="props.trabajador.id" />
            </div>

            <!-- TAB: Permisos -->
            <div v-if="activeTab === 'permisos'">
                <PermisosTrabajadorTab :trabajador="props.trabajador" />
            </div>
        </div>
    </div>

    <!-- Modal Editar Datos Personales -->
    <FormModal
        v-model:show="showEditPersonaModal"
        title="Editar Datos Personales"
        :processing="editForm.processing"
        @submit="submitEditPersona"
    >
        <PersonaForm :form="editForm" :is-editing="true" />
    </FormModal>

    <GestionUsuarioModal
        v-model:show="showUsuarioModal"
        :trabajador-id="props.trabajador.id"
        :trabajador-nombre="nombreTrabajador"
    />
</template>

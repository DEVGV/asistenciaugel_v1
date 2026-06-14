<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    User,
    FileText,
    Calendar,
    ClipboardCheck,
    Phone,
    Mail,
    MapPin,
    ShieldCheck,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import TrabajadorController from '@/actions/App/Http/Controllers/Trabajador/TrabajadorController';
import DomiciliosList from '@/components/persona/DomiciliosList.vue';
import EmailsList from '@/components/persona/EmailsList.vue';
import TelefonosList from '@/components/persona/TelefonosList.vue';
import GestionUsuarioModal from '@/components/shared/GestionUsuarioModal.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import AltasList from '@/components/trabajador/AltasList.vue';
import HorariosTrabajadorTab from '@/components/trabajador/HorariosTrabajadorTab.vue';
import PermisosTrabajadorTab from '@/components/trabajador/PermisosTrabajadorTab.vue';
import { Button } from '@/components/ui/button';
import type { Trabajador } from '@/types/models/trabajador';

const props = defineProps<{
    trabajador: Trabajador;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Trabajadores', href: TrabajadorController.index().url },
            { title: 'Detalle', href: '#' },
        ],
    },
});

const activeTab = ref<
    'datos' | 'contacto' | 'laboral' | 'horarios' | 'permisos'
>('datos');

const tabs = [
    { key: 'datos', label: 'Datos Personales', icon: User },
    { key: 'contacto', label: 'Contacto y Domicilio', icon: MapPin },
    { key: 'laboral', label: 'Información Laboral', icon: FileText },
    { key: 'horarios', label: 'Horarios', icon: Calendar },
    { key: 'permisos', label: 'Permisos', icon: ClipboardCheck },
] as const;

// ── Modal usuario ─────────────────────────────────────────────────────────────
const showUsuarioModal = ref(false);

const nombreTrabajador = computed(() => {
    const p = props.trabajador.persona;

    if (!p) {
return `Trabajador #${props.trabajador.id}`;
}

    return [p.paterno, p.materno, p.nombre].filter(Boolean).join(' ');
});
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
        <div class="flex gap-1 border-b">
            <button
                v-for="tab in tabs"
                :key="tab.key"
                @click="activeTab = tab.key"
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
            <!-- TAB: Datos Personales -->
            <div
                v-if="activeTab === 'datos'"
                class="rounded-xl border bg-card p-6 shadow-xs"
            >
                <div
                    class="grid grid-cols-1 gap-x-8 gap-y-4 text-sm md:grid-cols-3"
                >
                    <div>
                        <span class="text-muted-foreground"
                            >Tipo de Documento:</span
                        >
                        <p class="mt-0.5 font-medium">
                            {{
                                props.trabajador.persona?.tipoDocIdentidad
                                    ?.nombre || '-'
                            }}
                        </p>
                    </div>
                    <div>
                        <span class="text-muted-foreground"
                            >N° de Documento:</span
                        >
                        <p class="mt-0.5 font-medium">
                            {{ props.trabajador.persona?.docIdentidad }}
                        </p>
                    </div>
                    <div>
                        <span class="text-muted-foreground">Sexo:</span>
                        <p class="mt-0.5 font-medium">
                            {{ props.trabajador.persona?.sexo?.nombre || '-' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-muted-foreground"
                            >País de Origen:</span
                        >
                        <p class="mt-0.5 font-medium">
                            {{ props.trabajador.persona?.pais?.nombre || '-' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-muted-foreground"
                            >Fecha de Nacimiento:</span
                        >
                        <p class="mt-0.5 font-medium">
                            {{ props.trabajador.persona?.fechaNac || '-' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-muted-foreground">Ubigeo:</span>
                        <p class="mt-0.5 font-medium">
                            {{ props.trabajador.persona?.ubigeo || '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- TAB: Contacto y Domicilio -->
            <div
                v-if="activeTab === 'contacto' && props.trabajador.persona"
                class="grid gap-6 md:grid-cols-3"
            >
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
                        <h3 class="text-sm font-semibold">
                            Correos Electrónicos
                        </h3>
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

            <!-- TAB: Permisos -->
            <div v-if="activeTab === 'permisos'">
                <PermisosTrabajadorTab :trabajador="props.trabajador" />
            </div>
        </div>
    </div>

    <GestionUsuarioModal
        v-model:show="showUsuarioModal"
        :trabajador-id="props.trabajador.id"
        :trabajador-nombre="nombreTrabajador"
    />
</template>

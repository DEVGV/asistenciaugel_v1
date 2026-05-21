<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    User,
    FileText,
    Calendar,
    Phone,
    Mail,
    MapPin,
    Pencil,
} from 'lucide-vue-next';
import { ref } from 'vue';
import TrabajadorController from '@/actions/App/Http/Controllers/Trabajador/TrabajadorController';
import DomiciliosList from '@/components/persona/DomiciliosList.vue';
import EmailsList from '@/components/persona/EmailsList.vue';
import TelefonosList from '@/components/persona/TelefonosList.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
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

const activeTab = ref<'datos' | 'contacto' | 'laboral' | 'horarios'>('datos');

const tabs = [
    { key: 'datos', label: 'Datos Personales', icon: User },
    { key: 'contacto', label: 'Contacto y Domicilio', icon: MapPin },
    { key: 'laboral', label: 'Información Laboral', icon: FileText },
    { key: 'horarios', label: 'Horarios', icon: Calendar },
] as const;
</script>

<template>
    <Head :title="`Trabajador: ${props.trabajador.persona?.paterno} ${props.trabajador.persona?.nombre}`" />

    <div class="flex flex-col gap-4 p-4">
        <!-- Encabezado -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    {{ props.trabajador.persona?.paterno }} {{ props.trabajador.persona?.materno }},
                    {{ props.trabajador.persona?.nombre }}
                </h1>
                <p class="text-sm text-muted-foreground">
                    Código: <span class="font-semibold text-primary">{{ props.trabajador.codigo }}</span>
                    <span v-if="props.trabajador.persona?.docIdentidad" class="ml-2">
                        · {{ props.trabajador.persona?.tipoDocIdentidad?.nombre }}:
                        <span class="font-semibold">{{ props.trabajador.persona?.docIdentidad }}</span>
                    </span>
                    <span class="ml-2">
                        · <StatusBadge :active="props.trabajador.activo" />
                    </span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Button variant="outline" as-child size="sm">
                    <Link :href="TrabajadorController.edit({ trabajador: props.trabajador.id }).url">
                        <Pencil class="mr-2 h-4 w-4" /> Editar Asignación
                    </Link>
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
                    'flex items-center gap-2 px-4 py-2.5 text-sm font-medium transition-colors border-b-2 -mb-px',
                    activeTab === tab.key
                        ? 'border-primary text-primary'
                        : 'border-transparent text-muted-foreground hover:text-foreground hover:border-muted-foreground/50',
                ]"
            >
                <component :is="tab.icon" class="h-4 w-4" />
                {{ tab.label }}
            </button>
        </div>

        <!-- Contenido -->
        <div class="mt-2">
            <!-- TAB: Datos Personales -->
            <div v-if="activeTab === 'datos'" class="rounded-xl border bg-card p-6 shadow-xs">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-4 text-sm">
                    <div>
                        <span class="text-muted-foreground">Tipo de Documento:</span>
                        <p class="font-medium mt-0.5">{{ props.trabajador.persona?.tipoDocIdentidad?.nombre || '-' }}</p>
                    </div>
                    <div>
                        <span class="text-muted-foreground">N° de Documento:</span>
                        <p class="font-medium mt-0.5">{{ props.trabajador.persona?.docIdentidad }}</p>
                    </div>
                    <div>
                        <span class="text-muted-foreground">Sexo:</span>
                        <p class="font-medium mt-0.5">{{ props.trabajador.persona?.sexo?.nombre || '-' }}</p>
                    </div>
                    <div>
                        <span class="text-muted-foreground">País de Origen:</span>
                        <p class="font-medium mt-0.5">{{ props.trabajador.persona?.pais?.nombre || '-' }}</p>
                    </div>
                    <div>
                        <span class="text-muted-foreground">Fecha de Nacimiento:</span>
                        <p class="font-medium mt-0.5">{{ props.trabajador.persona?.fechaNac || '-' }}</p>
                    </div>
                    <div>
                        <span class="text-muted-foreground">Ubigeo:</span>
                        <p class="font-medium mt-0.5">{{ props.trabajador.persona?.ubigeo || '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- TAB: Contacto y Domicilio -->
            <div v-if="activeTab === 'contacto' && props.trabajador.persona" class="grid gap-6 md:grid-cols-3">
                <div class="rounded-xl border bg-card p-4 shadow-xs">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b">
                        <Phone class="h-4 w-4 text-primary" />
                        <h3 class="font-semibold text-sm">Teléfonos</h3>
                    </div>
                    <TelefonosList
                        :telefonos="props.trabajador.persona.telefonos || []"
                        :persona-id="props.trabajador.persona.id"
                    />
                </div>

                <div class="rounded-xl border bg-card p-4 shadow-xs">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b">
                        <Mail class="h-4 w-4 text-primary" />
                        <h3 class="font-semibold text-sm">Correos Electrónicos</h3>
                    </div>
                    <EmailsList
                        :emails="props.trabajador.persona.emails || []"
                        :persona-id="props.trabajador.persona.id"
                    />
                </div>

                <div class="rounded-xl border bg-card p-4 shadow-xs">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b">
                        <MapPin class="h-4 w-4 text-primary" />
                        <h3 class="font-semibold text-sm">Domicilios</h3>
                    </div>
                    <DomiciliosList
                        :domicilios="props.trabajador.persona.domicilios || []"
                        :persona-id="props.trabajador.persona.id"
                    />
                </div>
            </div>

            <!-- TAB: Información Laboral (Próximamente) -->
            <div v-if="activeTab === 'laboral'" class="rounded-xl border bg-card p-12 text-center shadow-xs">
                <div class="mx-auto max-w-md">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <FileText class="h-6 w-6" />
                    </div>
                    <h3 class="mt-4 text-lg font-semibold">Módulo de Gestión Laboral</h3>
                    <p class="mt-2 text-sm text-muted-foreground">
                        La gestión de altas, bajas, cargos y áreas para este trabajador estará disponible próximamente.
                    </p>
                </div>
            </div>

            <!-- TAB: Horarios (Próximamente) -->
            <div v-if="activeTab === 'horarios'" class="rounded-xl border bg-card p-12 text-center shadow-xs">
                <div class="mx-auto max-w-md">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <Calendar class="h-6 w-6" />
                    </div>
                    <h3 class="mt-4 text-lg font-semibold">Módulo de Horarios</h3>
                    <p class="mt-2 text-sm text-muted-foreground">
                        La programación de turnos y horarios semanales se habilitará en las próximas versiones.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>


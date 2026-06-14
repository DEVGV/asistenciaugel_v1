<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import DeleteUser from '@/components/DeleteUser.vue';
import { edit } from '@/routes/profile';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Ajustes de perfil',
                href: edit(),
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const persona = computed(() => user.value?.trabajador?.persona);
</script>

<template>
    <Head title="Ajustes de perfil" />

    <h1 class="sr-only">Ajustes de perfil</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Información del perfil"
            description="Datos de tu cuenta en el sistema"
        />

        <div class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs text-muted-foreground">
                        Login (Documento)
                    </p>
                    <p class="mt-0.5 font-mono text-sm font-semibold">
                        {{ user.login }}
                    </p>
                </div>
                <div v-if="persona">
                    <p class="text-xs text-muted-foreground">Nombre Completo</p>
                    <p class="mt-0.5 text-sm font-medium">
                        {{ persona.paterno }} {{ persona.materno }},
                        {{ persona.nombre }}
                    </p>
                </div>
            </div>

            <div v-if="persona" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs text-muted-foreground">Tipo Documento</p>
                    <p class="mt-0.5 text-sm">
                        {{ persona.tipoDocIdentidad?.nombre || '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-muted-foreground">N° Documento</p>
                    <p class="mt-0.5 text-sm font-medium">
                        {{ persona.docIdentidad }}
                    </p>
                </div>
            </div>

            <p class="text-xs text-muted-foreground">
                Los datos personales se gestionan desde el módulo de Personas.
                Para cambiar tu contraseña, utiliza la sección de Seguridad.
            </p>
        </div>
    </div>

    <DeleteUser />
</template>

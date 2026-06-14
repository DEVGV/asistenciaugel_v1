<script setup lang="ts">
import { Search, Loader2 } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { ParamSimple } from '@/types/models/params';

const props = defineProps<{
    form: any;
    isEditing?: boolean;
}>();

const selectedDocTypeAbrev = ref<string | null>(null);
const isSearchingReniec = ref(false);
const searchError = ref<string | null>(null);

function onDocTypeChange(item: ParamSimple | null) {
    selectedDocTypeAbrev.value = item?.abreviatura || null;
}

async function buscarReniec() {
    if (!props.form.docIdentidad || props.form.docIdentidad.length !== 8) {
        return;
    }

    if (selectedDocTypeAbrev.value !== 'DNI') {
        return;
    }

    isSearchingReniec.value = true;
    searchError.value = null;

    try {
        const response = await fetch('/api/sunat/dni', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            body: JSON.stringify({ dni: props.form.docIdentidad }),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Error al buscar el DNI');
        }

        if (result.data) {
            props.form.nombre = result.data.nombres || '';
            props.form.paterno = result.data.apellido_paterno || '';
            props.form.materno = result.data.apellido_materno || '';
            props.form.clearErrors('nombre', 'paterno', 'materno');
        }
    } catch (e: any) {
        searchError.value = e.message;
    } finally {
        isSearchingReniec.value = false;
    }
}

watch(
    () => props.form.docIdentidad,
    (newVal) => {
        if (newVal?.length === 8 && selectedDocTypeAbrev.value === 'DNI') {
            buscarReniec();
        }
    },
);
</script>

<template>
    <div class="grid gap-4">
        <div class="grid grid-cols-2 gap-4">
            <ParamSelect
                v-model="form.tipoDocIdentidad_id"
                type="tipos-doc-identidad"
                label="Tipo Doc. *"
                :error="form.errors.tipoDocIdentidad_id"
                @update:item="onDocTypeChange"
            />

            <div class="grid gap-2">
                <Label for="docIdentidad">N° Documento *</Label>
                <div class="relative flex items-center">
                    <Input
                        id="docIdentidad"
                        v-model="form.docIdentidad"
                        placeholder="N° documento"
                        maxlength="20"
                        :class="{ 'pr-10': selectedDocTypeAbrev === 'DNI' }"
                    />
                    <Button
                        v-if="selectedDocTypeAbrev === 'DNI'"
                        type="button"
                        variant="ghost"
                        size="icon"
                        class="absolute right-0 h-full px-3 py-2 hover:bg-transparent"
                        @click="buscarReniec"
                        :disabled="
                            isSearchingReniec || form.docIdentidad?.length !== 8
                        "
                    >
                        <Loader2
                            v-if="isSearchingReniec"
                            class="h-4 w-4 animate-spin text-muted-foreground"
                        />
                        <Search v-else class="h-4 w-4 text-muted-foreground" />
                    </Button>
                </div>
                <p v-if="searchError" class="text-sm text-amber-500">
                    {{ searchError }}
                </p>
                <p
                    v-if="form.errors.docIdentidad"
                    class="text-sm text-destructive"
                >
                    {{ form.errors.docIdentidad }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="grid gap-2">
                <Label for="paterno">Ap. Paterno *</Label>
                <Input
                    id="paterno"
                    v-model="form.paterno"
                    placeholder="Apellido paterno"
                />
                <p v-if="form.errors.paterno" class="text-sm text-destructive">
                    {{ form.errors.paterno }}
                </p>
            </div>
            <div class="grid gap-2">
                <Label for="materno">Ap. Materno *</Label>
                <Input
                    id="materno"
                    v-model="form.materno"
                    placeholder="Apellido materno"
                />
                <p v-if="form.errors.materno" class="text-sm text-destructive">
                    {{ form.errors.materno }}
                </p>
            </div>
        </div>

        <div class="grid gap-2">
            <Label for="nombre">Nombres *</Label>
            <Input
                id="nombre"
                v-model="form.nombre"
                placeholder="Nombres completos"
            />
            <p v-if="form.errors.nombre" class="text-sm text-destructive">
                {{ form.errors.nombre }}
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <ParamSelect
                v-model="form.sexo_id"
                type="sexos"
                label="Sexo *"
                :error="form.errors.sexo_id"
            />

            <ParamSelect
                v-model="form.pais_id"
                type="paises"
                label="País *"
                :error="form.errors.pais_id"
            />
        </div>

        <div class="grid gap-2">
            <Label for="fechaNac">Fecha Nacimiento</Label>
            <Input id="fechaNac" v-model="form.fechaNac" type="date" />
            <p v-if="form.errors.fechaNac" class="text-sm text-destructive">
                {{ form.errors.fechaNac }}
            </p>
        </div>

        <div class="mt-2 flex items-center space-x-2">
            <input
                id="activo"
                type="checkbox"
                :checked="!!form.activo"
                @change="
                    (e: Event) =>
                        (form.activo = (e.target as HTMLInputElement).checked)
                "
                class="size-4 cursor-pointer rounded border-input accent-primary"
            />
            <Label for="activo" class="cursor-pointer">Persona Activa</Label>
        </div>
        <p v-if="form.errors.activo" class="text-sm text-destructive">
            {{ form.errors.activo }}
        </p>

        <!-- Registrar como Trabajador (solo en creación) -->
        <div v-if="!props.isEditing" class="mt-2 flex items-center space-x-2">
            <input
                id="es_trabajador"
                type="checkbox"
                :checked="!!form.es_trabajador"
                @change="
                    (e: Event) =>
                        (form.es_trabajador = (
                            e.target as HTMLInputElement
                        ).checked)
                "
                class="size-4 cursor-pointer rounded border-input accent-primary"
            />
            <Label for="es_trabajador" class="cursor-pointer"
                >Registrar como Trabajador</Label
            >
        </div>
        <p v-if="form.errors.es_trabajador" class="text-sm text-destructive">
            {{ form.errors.es_trabajador }}
        </p>
    </div>
</template>

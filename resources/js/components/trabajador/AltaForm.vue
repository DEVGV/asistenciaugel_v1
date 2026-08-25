<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AltaTrabajadorController from '@/actions/App/Http/Controllers/Trabajador/AltaTrabajadorController';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import altasRoutes from '@/routes/altas';
import type { AltaTrabajador } from '@/types/models/trabajador';

const props = defineProps<{
    show: boolean;
    isEditing: boolean;
    trabajadorId: number;
    alta: AltaTrabajador | null;
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
}>();

const form = useForm({
    trabajador_id: props.trabajadorId,
    institucionEducativa_id: null as number | null,
    condicionLaboral_id: null as number | null,
    tipoContrato_id: null as number | null,
    rolTrabajador_id: null as number | null,
    situacionLaboral_id: null as number | null,
    area_id: null as number | null,
    cargo_id: null as number | null,
    codigoAirsp: '',
    fechaInicio: '',
    fechaFin: '',
    fechaAlta: '',
    observacion: '',
    perfil_id: null as number | null,
});

watch(
    () => props.show,
    (visible) => {
        if (visible && props.isEditing && props.alta) {
            form.trabajador_id = props.trabajadorId;
            form.institucionEducativa_id = props.alta.institucionEducativa_id;
            form.condicionLaboral_id = props.alta.condicionLaboral_id;
            form.tipoContrato_id = props.alta.tipoContrato_id;
            form.rolTrabajador_id = props.alta.rolTrabajador_id;
            form.situacionLaboral_id = props.alta.situacionLaboral_id;
            form.area_id = props.alta.area_id;
            form.cargo_id = props.alta.cargo_id;
            form.codigoAirsp = props.alta.codigoAirsp ?? '';
            form.fechaInicio = props.alta.fechaInicio ?? '';
            form.fechaFin = props.alta.fechaFin ?? '';
            form.fechaAlta = props.alta.fechaAlta ?? '';
            form.observacion = props.alta.observacion ?? '';
            form.perfil_id = props.alta.perfil_ie
                ? (props.alta.perfil_ie.perfil_id ?? null)
                : null;

            // Mostrar el nombre de la IE en el input de búsqueda (sin disparar búsqueda)
            const ie = props.alta.institucionEducativa ?? props.alta.institucion_educativa;
            ieSkipNextSearch = true;
            ieQuery.value = ie?.nombreLegal ?? ie?.codigoInstitucion ?? '';
        } else if (visible && !props.isEditing) {
            form.reset();
            form.trabajador_id = props.trabajadorId;
            ieQuery.value = '';
        }

        if (!visible) {
            form.clearErrors();
        }
    },
);

// IE search with debounce — fetch direct from API
const ieQuery = ref('');
const ieOptions = ref<{ id: number; nombre: string }[]>([]);
const ieLoading = ref(false);
let ieTimeout: ReturnType<typeof setTimeout>;
let ieSkipNextSearch = false;

watch(ieQuery, (q) => {
    clearTimeout(ieTimeout);
    if (ieSkipNextSearch) {
        ieSkipNextSearch = false;
        return;
    }
    ieTimeout = setTimeout(async () => {
        if (!q && !props.isEditing) {
            return;
        }

        ieLoading.value = true;

        try {
            const res = await fetch(
                `/api/instituciones/search?search=${encodeURIComponent(q)}&per_page=30`,
            );
            const data = await res.json();
            ieOptions.value = (data.data || []).map((ie: any) => ({
                id: ie.id,
                nombre: ie.nombreLegal ?? ie.codigoInstitucion ?? String(ie.id),
            }));
        } catch {
            ieOptions.value = [];
        } finally {
            ieLoading.value = false;
        }
    }, 300);
});

function submit() {
    if (props.isEditing && props.alta) {
        form.put(altasRoutes.update({ alta: props.alta.id }).url, {
            onSuccess: () => emit('update:show', false),
        });
    } else {
        form.post(
            AltaTrabajadorController.store({ trabajador: props.trabajadorId })
                .url,
            {
                onSuccess: () => emit('update:show', false),
            },
        );
    }
}
</script>

<template>
    <FormModal
        :show="props.show"
        :title="props.isEditing ? 'Editar Alta' : 'Nueva Alta'"
        description="Registra la vinculación laboral del trabajador con una institución educativa."
        max-width="4xl"
        :processing="form.processing"
        @update:show="emit('update:show', $event)"
        @submit="submit"
    >
        <div class="grid gap-5">
            <!-- Fila 1: IE y Código AIRSP -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="grid gap-2 md:col-span-2">
                    <Label>Institución Educativa *</Label>
                    <!-- Select manual para IE con búsqueda -->
                    <div class="relative">
                        <input
                            v-model="ieQuery"
                            type="text"
                            placeholder="Buscar institución..."
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs placeholder:text-muted-foreground focus:ring-2 focus:ring-primary focus:outline-none"
                        />
                    </div>
                    <!-- Show selected IE name -->
                    <div
                        v-if="form.institucionEducativa_id"
                        class="text-xs text-muted-foreground"
                    >
                        IE seleccionada ID: {{ form.institucionEducativa_id }}
                    </div>
                    <!-- IE dropdown results -->
                    <div
                        v-if="ieOptions.length && ieQuery"
                        class="max-h-48 overflow-y-auto rounded-md border bg-background shadow-lg"
                    >
                        <button
                            v-for="ie in ieOptions"
                            :key="ie.id"
                            type="button"
                            class="w-full px-3 py-2 text-left text-sm hover:bg-accent hover:text-accent-foreground"
                            :class="{
                                'bg-primary/10 font-medium text-primary':
                                    form.institucionEducativa_id === ie.id,
                            }"
                            @click="
                                form.institucionEducativa_id = ie.id;
                                ieQuery = ie.nombre;
                                ieOptions = [];
                            "
                        >
                            {{ ie.nombre }}
                        </button>
                    </div>
                    <p
                        v-if="form.errors.institucionEducativa_id"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.institucionEducativa_id }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label>Código AIRSP</Label>
                    <Input
                        v-model="form.codigoAirsp"
                        placeholder="Ej: 28001234"
                    />
                </div>
            </div>

            <!-- Fila 2: Condición y Tipo Contrato -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <ParamSelect
                    type="condiciones-laborales"
                    label="Condición Laboral *"
                    v-model="form.condicionLaboral_id"
                    placeholder="Seleccionar..."
                    :error="form.errors.condicionLaboral_id"
                />
                <ParamSelect
                    type="tipos-contrato"
                    label="Tipo de Contrato *"
                    v-model="form.tipoContrato_id"
                    placeholder="Seleccionar..."
                    :error="form.errors.tipoContrato_id"
                />
            </div>

            <!-- Fila 3: Rol y Situación Laboral -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <ParamSelect
                    type="roles-trabajador"
                    label="Rol del Trabajador *"
                    v-model="form.rolTrabajador_id"
                    placeholder="Seleccionar..."
                    :error="form.errors.rolTrabajador_id"
                />
                <ParamSelect
                    type="situaciones-laborales"
                    label="Situación Laboral *"
                    v-model="form.situacionLaboral_id"
                    placeholder="Seleccionar..."
                    :error="form.errors.situacionLaboral_id"
                />
            </div>

            <!-- Fila 4: Área y Cargo -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <ParamSelect
                    type="areas"
                    label="Área *"
                    v-model="form.area_id"
                    placeholder="Seleccionar..."
                    :error="form.errors.area_id"
                />
                <ParamSelect
                    type="cargos"
                    label="Cargo *"
                    v-model="form.cargo_id"
                    placeholder="Seleccionar..."
                    :error="form.errors.cargo_id"
                />
            </div>

            <!-- Fila 5: Fechas -->
            <div class="grid grid-cols-2 gap-4 border-t pt-4 md:grid-cols-4">
                <div class="grid gap-2">
                    <Label>Fecha Inicio *</Label>
                    <Input
                        v-model="form.fechaInicio"
                        type="date"
                        :class="{
                            'border-destructive': form.errors.fechaInicio,
                        }"
                    />
                    <p
                        v-if="form.errors.fechaInicio"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.fechaInicio }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label>Fecha Fin</Label>
                    <Input v-model="form.fechaFin" type="date" />
                </div>
                <div class="grid gap-2">
                    <Label>Fecha Alta</Label>
                    <Input v-model="form.fechaAlta" type="date" />
                </div>
            </div>

            <!-- Observación -->
            <div class="grid gap-2">
                <Label>Observación</Label>
                <textarea
                    v-model="form.observacion"
                    rows="2"
                    placeholder="Observaciones adicionales..."
                    class="flex min-h-[60px] w-full resize-none rounded-md border border-input bg-background px-3 py-2 text-sm shadow-xs placeholder:text-muted-foreground focus:ring-2 focus:ring-primary focus:outline-none"
                />
            </div>

            <!-- Perfil de Usuario -->
            <div
                class="rounded-lg border border-dashed border-primary/30 bg-primary/5 p-4"
            >
                <h3 class="mb-2 text-sm font-semibold text-foreground">
                    Perfil de Usuario en esta IE
                </h3>
                <p class="mb-3 text-xs leading-relaxed text-muted-foreground">
                    Asigne el rol y permisos que tendrá el usuario en esta
                    institución educativa.
                </p>
                <div class="grid gap-2">
                    <Label>Perfil</Label>
                    <ParamSelect
                        type="perfiles"
                        v-model="form.perfil_id"
                        placeholder="Seleccionar perfil..."
                        :error="form.errors.perfil_id"
                    />
                </div>
            </div>
        </div>
    </FormModal>
</template>

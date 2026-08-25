<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AltaTrabajadorController from '@/actions/App/Http/Controllers/Trabajador/AltaTrabajadorController';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps<{
    show: boolean;
    institucionId: number;
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'success'): void;
}>();

// ─── Búsqueda de trabajador (typeahead) ──────────────────────────────────────
const trabajadorQuery = ref('');
const trabajadorOptions = ref<{ id: number; nombre: string; dni: string; codigo: string }[]>([]);
const trabajadorLoading = ref(false);
const selectedTrabajadorId = ref<number | null>(null);
const selectedTrabajadorNombre = ref('');
let trabajadorTimeout: ReturnType<typeof setTimeout>;

watch(trabajadorQuery, (q) => {
    if (selectedTrabajadorId.value) return; // ya seleccionado
    clearTimeout(trabajadorTimeout);
    trabajadorTimeout = setTimeout(async () => {
        if (!q || q.length < 2) {
            trabajadorOptions.value = [];
            return;
        }
        trabajadorLoading.value = true;
        try {
            const res = await fetch(`/api/trabajadores/search?search=${encodeURIComponent(q)}&per_page=20`);
            const data = await res.json();
            trabajadorOptions.value = (data || []).map((t: any) => ({
                id: t.id,
                nombre: [t.persona?.paterno, t.persona?.materno, t.persona?.nombre]
                    .filter(Boolean)
                    .join(' '),
                dni: t.persona?.docIdentidad ?? '',
                codigo: t.codigo ?? '',
            }));
        } catch {
            trabajadorOptions.value = [];
        } finally {
            trabajadorLoading.value = false;
        }
    }, 300);
});

function selectTrabajador(t: { id: number; nombre: string; dni: string; codigo: string }) {
    selectedTrabajadorId.value = t.id;
    selectedTrabajadorNombre.value = t.nombre;
    trabajadorQuery.value = t.nombre;
    trabajadorOptions.value = [];
    form.trabajador_id = t.id;
}

function clearTrabajador() {
    selectedTrabajadorId.value = null;
    selectedTrabajadorNombre.value = '';
    trabajadorQuery.value = '';
    trabajadorOptions.value = [];
    form.trabajador_id = null as any;
}

// ─── Formulario ───────────────────────────────────────────────────────────────
const form = useForm({
    trabajador_id: null as number | null,
    institucionEducativa_id: props.institucionId,
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
        if (!visible) {
            form.reset();
            form.clearErrors();
            form.institucionEducativa_id = props.institucionId;
            clearTrabajador();
        }
    },
);

function submit() {
    if (!selectedTrabajadorId.value) return;

    form.trabajador_id = selectedTrabajadorId.value;
    form.institucionEducativa_id = props.institucionId;

    form.post(
        AltaTrabajadorController.store({ trabajador: selectedTrabajadorId.value }).url,
        {
            onSuccess: () => {
                emit('update:show', false);
                emit('success');
            },
        },
    );
}
</script>

<template>
    <FormModal
        :show="props.show"
        title="Nueva Alta — Docente / Personal"
        description="Registra la vinculación laboral de un trabajador con esta institución educativa."
        max-width="4xl"
        :processing="form.processing"
        @update:show="emit('update:show', $event)"
        @submit="submit"
    >
        <div class="grid gap-5">
            <!-- Búsqueda de trabajador -->
            <div class="grid gap-2">
                <Label>Trabajador *</Label>
                <div class="relative">
                    <input
                        v-model="trabajadorQuery"
                        type="text"
                        placeholder="Buscar por nombre, DNI o código..."
                        :disabled="!!selectedTrabajadorId"
                        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs placeholder:text-muted-foreground focus:ring-2 focus:ring-primary focus:outline-none disabled:cursor-not-allowed disabled:opacity-60"
                        :class="{ 'border-destructive': form.errors.trabajador_id }"
                    />
                    <!-- Botón limpiar selección -->
                    <button
                        v-if="selectedTrabajadorId"
                        type="button"
                        class="absolute right-2 top-2 text-muted-foreground hover:text-foreground"
                        @click="clearTrabajador"
                        title="Cambiar trabajador"
                    >
                        ✕
                    </button>
                    <!-- Spinner de carga -->
                    <span
                        v-else-if="trabajadorLoading"
                        class="absolute right-2 top-2.5 text-xs text-muted-foreground"
                    >
                        …
                    </span>
                </div>

                <!-- Resultados del typeahead -->
                <div
                    v-if="trabajadorOptions.length && !selectedTrabajadorId"
                    class="max-h-48 overflow-y-auto rounded-md border bg-background shadow-lg"
                >
                    <button
                        v-for="t in trabajadorOptions"
                        :key="t.id"
                        type="button"
                        class="w-full px-3 py-2 text-left text-sm hover:bg-accent hover:text-accent-foreground"
                        @click="selectTrabajador(t)"
                    >
                        <span class="font-medium">{{ t.nombre }}</span>
                        <span class="ml-2 text-xs text-muted-foreground">
                            {{ t.dni }}<span v-if="t.codigo"> · {{ t.codigo }}</span>
                        </span>
                    </button>
                </div>

                <p v-if="form.errors.trabajador_id" class="text-sm text-destructive">
                    {{ form.errors.trabajador_id }}
                </p>
            </div>

            <!-- Código AIRSP -->
            <div class="grid gap-2">
                <Label>Código AIRSP</Label>
                <Input v-model="form.codigoAirsp" placeholder="Ej: 28001234" />
            </div>

            <!-- Condición y Tipo Contrato -->
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

            <!-- Rol y Situación Laboral -->
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

            <!-- Área y Cargo -->
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

            <!-- Fechas -->
            <div class="grid grid-cols-2 gap-4 border-t pt-4 md:grid-cols-4">
                <div class="grid gap-2">
                    <Label>Fecha Inicio *</Label>
                    <Input
                        v-model="form.fechaInicio"
                        type="date"
                        :class="{ 'border-destructive': form.errors.fechaInicio }"
                    />
                    <p v-if="form.errors.fechaInicio" class="text-sm text-destructive">
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

            <!-- Perfil de Usuario en esta IE -->
            <div class="rounded-lg border border-dashed border-primary/30 bg-primary/5 p-4">
                <h3 class="mb-2 text-sm font-semibold text-foreground">
                    Perfil de Usuario en esta IE
                </h3>
                <p class="mb-3 text-xs leading-relaxed text-muted-foreground">
                    Asigne el rol y permisos que tendrá el usuario en esta institución educativa.
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

<script setup lang="ts">
import { ref, watch } from 'vue';
import SunatController from '@/actions/App/Http/Controllers/Api/SunatController';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineProps<{
    form: any; // Inertia Form instance
}>();

const isLookingUp = ref(false);
const rucError = ref<string | null>(null);

async function buscarRuc(ruc: string) {
    if (ruc.length !== 11 || !/^\d{11}$/.test(ruc)) {
        rucError.value = null;

        return;
    }

    isLookingUp.value = true;
    rucError.value = null;

    try {
        const response = await fetch(SunatController.ruc().url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN':
                    (
                        document.querySelector(
                            'meta[name="csrf-token"]',
                        ) as HTMLMetaElement
                    )?.content ?? '',
            },
            body: JSON.stringify({ ruc }),
        });

        const json = await response.json();

        if (!response.ok) {
            rucError.value =
                json.message ?? 'No se encontraron datos para este RUC.';

            return;
        }

        const data = json.data;

        // Rellenar campos automáticamente
        if (data?.nombre_o_razon_social) {
            // Escribimos en los campos del formulario padre via prop
            // Usamos un emit para que el padre actualice el form
            emits('rucData', {
                razonSocial: data.nombre_o_razon_social,
            });
        }
    } catch {
        rucError.value = 'Error al conectar con el servicio de consulta RUC.';
    } finally {
        isLookingUp.value = false;
    }
}

const emits = defineEmits<{
    (e: 'rucData', data: { razonSocial: string }): void;
}>();
</script>

<template>
    <div class="grid gap-4">
        <ParamSelect
            v-model="form.tipoEntidad_id"
            type="tipo-entidad"
            label="Tipo de Entidad *"
            :error="form.errors.tipoEntidad_id"
        />

        <div class="grid gap-2">
            <Label for="ruc">RUC *</Label>
            <div class="relative">
                <Input
                    id="ruc"
                    v-model="form.ruc"
                    placeholder="11 dígitos"
                    maxlength="11"
                    :disabled="isLookingUp"
                    @input="buscarRuc(form.ruc)"
                />
                <span
                    v-if="isLookingUp"
                    class="absolute top-1/2 right-3 -translate-y-1/2 animate-pulse text-xs text-muted-foreground"
                >
                    Buscando…
                </span>
            </div>
            <p v-if="rucError" class="text-sm text-destructive">
                {{ rucError }}
            </p>
            <p v-else-if="form.errors.ruc" class="text-sm text-destructive">
                {{ form.errors.ruc }}
            </p>
        </div>

        <div class="grid gap-2">
            <Label for="razonSocial">Razón Social *</Label>
            <Input
                id="razonSocial"
                v-model="form.razonSocial"
                placeholder="Se llenará automáticamente al ingresar el RUC"
            />
            <p v-if="form.errors.razonSocial" class="text-sm text-destructive">
                {{ form.errors.razonSocial }}
            </p>
        </div>

        <div class="grid gap-2">
            <Label for="razonComercial">Razón Comercial</Label>
            <Input
                id="razonComercial"
                v-model="form.razonComercial"
                placeholder="Nombre comercial (Opcional)"
            />
            <p
                v-if="form.errors.razonComercial"
                class="text-sm text-destructive"
            >
                {{ form.errors.razonComercial }}
            </p>
        </div>

        <div class="mt-2 flex items-center space-x-2">
            <input
                id="activo"
                type="checkbox"
                :checked="!!form.activo"
                @change="
                    (e) =>
                        (form.activo = (e.target as HTMLInputElement).checked)
                "
                class="size-4 cursor-pointer rounded border-input accent-primary"
            />
            <Label for="activo" class="cursor-pointer">Entidad Activa</Label>
        </div>
        <p v-if="form.errors.activo" class="text-sm text-destructive">
            {{ form.errors.activo }}
        </p>
    </div>
</template>

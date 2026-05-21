<script setup lang="ts">
import { Label } from '@/components/ui/label';
import type { Persona } from '@/types/models/persona';

defineProps<{
    form: any;
    persona?: Persona | null;
}>();
</script>

<template>
    <div class="grid gap-4">
        <!-- Tarjeta de información de la Persona seleccionada -->
        <div
            v-if="persona"
            class="rounded-lg border bg-muted/40 p-3 text-sm"
        >
            <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                Persona Asignada
            </p>
            <div class="mt-1 flex items-center justify-between">
                <div>
                    <p class="font-medium">
                        {{ persona.paterno }} {{ persona.materno }}, {{ persona.nombre }}
                    </p>
                    <p class="text-xs text-muted-foreground">
                        {{ persona.docIdentidad }}
                    </p>
                </div>
            </div>
        </div>


        <!-- Estado Activo -->
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
            <Label for="activo" class="cursor-pointer">Trabajador Activo</Label>
        </div>
        <p v-if="form.errors?.activo" class="text-sm text-destructive">
            {{ form.errors.activo }}
        </p>
    </div>
</template>

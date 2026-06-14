<script setup lang="ts">
/**
 * LocalMarcacionSelect — Select de local de marcación dependiente de la IE.
 *
 * Carga, vía /api/instituciones/{ie}/locales, únicamente los locales que
 * pertenecen a la institución educativa donde se dará de alta al trabajador.
 * Si no hay IE seleccionada, el select queda deshabilitado con una pista.
 *
 * El valor emitido es el `localInstEduc_id` (no el local_id).
 */
import { onClickOutside } from '@vueuse/core';
import { Check, ChevronDown, MapPin, Search } from 'lucide-vue-next';
import { ref, computed, watch, nextTick } from 'vue';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';

interface LocalItem {
    id: number;
    nombre: string;
}

const props = defineProps<{
    institucionId?: number | null;
    modelValue?: number | null;
    label?: string;
    placeholder?: string;
    error?: string;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: number | null): void;
}>();

const data = ref<LocalItem[]>([]);
const loading = ref(false);
const isOpen = ref(false);
const searchQuery = ref('');
const target = ref(null);
const searchInput = ref<HTMLInputElement | null>(null);

let abortController: AbortController | null = null;

onClickOutside(target, () => {
    isOpen.value = false;
});

watch(isOpen, async (val) => {
    if (val) {
        await nextTick();
        searchInput.value?.focus();
    }
});

async function fetchData() {
    if (!props.institucionId) {
        data.value = [];
        loading.value = false;

        return;
    }

    if (abortController) {
        abortController.abort();
    }

    abortController = new AbortController();
    const signal = abortController.signal;

    loading.value = true;
    data.value = [];

    try {
        const response = await fetch(
            `/api/instituciones/${props.institucionId}/locales`,
            { signal },
        );

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const json = await response.json();
        data.value = json.data ?? [];
    } catch (e: any) {
        if (e?.name !== 'AbortError') {
            console.error('Error loading locales de marcación', e);
        }
    } finally {
        loading.value = false;
    }
}

// Recarga al cambiar la IE; si la IE cambia, el local previo deja de ser válido.
watch(
    () => props.institucionId,
    (newVal, oldVal) => {
        if (newVal !== oldVal) {
            if (oldVal != null && props.modelValue) {
                emit('update:modelValue', null);
            }

            fetchData();
        }
    },
    { immediate: true },
);

const filteredData = computed(() => {
    const query = searchQuery.value.toLowerCase().trim();
    let results = data.value;

    if (query) {
        results = results.filter((item) =>
            item.nombre.toLowerCase().includes(query),
        );
    }

    return results.slice(0, 100);
});

const selectedItemName = computed(() => {
    if (!props.modelValue) {
        return '';
    }

    return data.value.find((i) => i.id === props.modelValue)?.nombre ?? '';
});

const isDisabled = computed(
    () => props.disabled || !props.institucionId || loading.value,
);

function toggleDropdown() {
    if (isDisabled.value) {
        return;
    }

    isOpen.value = !isOpen.value;

    if (isOpen.value) {
        searchQuery.value = '';
    }
}

function selectItem(item: LocalItem) {
    emit('update:modelValue', item.id);
    isOpen.value = false;
    searchQuery.value = '';
}

function limpiar() {
    emit('update:modelValue', null);
    isOpen.value = false;
}
</script>

<template>
    <div class="grid gap-2" ref="target">
        <Label v-if="label">{{ label }}</Label>
        <div class="relative">
            <button
                type="button"
                @click="toggleDropdown"
                :disabled="isDisabled"
                :class="
                    cn(
                        'flex w-full items-center justify-between rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition-all outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
                        isOpen
                            ? 'border-primary ring-2 ring-primary ring-offset-2'
                            : 'border-input',
                        error ? 'border-destructive ring-destructive' : '',
                    )
                "
            >
                <span class="flex items-center gap-2 truncate">
                    <MapPin
                        class="h-4 w-4 shrink-0 text-muted-foreground opacity-60"
                    />
                    <span
                        :class="
                            !selectedItemName
                                ? 'text-muted-foreground'
                                : 'line-clamp-1 text-left text-foreground'
                        "
                    >
                        {{
                            !institucionId
                                ? 'Seleccione una IE primero'
                                : loading
                                  ? 'Cargando...'
                                  : selectedItemName ||
                                    placeholder ||
                                    'Sin local de marcación'
                        }}
                    </span>
                </span>
                <ChevronDown
                    class="ml-2 h-4 w-4 shrink-0 text-muted-foreground opacity-50"
                />
            </button>

            <div
                v-if="isOpen"
                class="absolute z-50 mt-1 w-full animate-in overflow-hidden rounded-md border bg-background text-sm shadow-lg fade-in-0 zoom-in-95"
            >
                <div class="flex items-center border-b px-3">
                    <Search
                        class="h-4 w-4 shrink-0 text-muted-foreground opacity-50"
                    />
                    <input
                        ref="searchInput"
                        type="text"
                        v-model="searchQuery"
                        placeholder="Buscar local..."
                        class="flex h-10 w-full rounded-md bg-transparent px-3 py-3 text-sm outline-none placeholder:text-muted-foreground"
                        @click.stop
                    />
                </div>

                <div class="max-h-60 overflow-y-auto p-1">
                    <div
                        v-if="filteredData.length === 0"
                        class="py-6 text-center text-sm text-muted-foreground"
                    >
                        Esta IE no tiene locales de marcación.
                    </div>

                    <!-- Opción para limpiar / sin local -->
                    <div
                        v-if="modelValue"
                        @click="limpiar"
                        class="relative flex w-full cursor-pointer items-center rounded-sm py-2 pr-2 pl-8 text-sm text-muted-foreground transition-colors outline-none select-none hover:bg-accent hover:text-accent-foreground"
                    >
                        Sin local de marcación
                    </div>

                    <div
                        v-for="item in filteredData"
                        :key="item.id"
                        @click="selectItem(item)"
                        :class="
                            cn(
                                'relative flex w-full cursor-pointer items-center rounded-sm py-2 pr-2 pl-8 text-sm transition-colors outline-none select-none hover:bg-accent hover:text-accent-foreground',
                                item.id === modelValue
                                    ? 'bg-primary/10 font-medium text-primary'
                                    : '',
                            )
                        "
                    >
                        <span
                            v-if="item.id === modelValue"
                            class="absolute left-2 flex h-3.5 w-3.5 items-center justify-center"
                        >
                            <Check class="h-4 w-4" />
                        </span>
                        <span class="line-clamp-1">{{ item.nombre }}</span>
                    </div>
                </div>
            </div>
        </div>
        <p v-if="error" class="text-sm text-destructive">{{ error }}</p>
    </div>
</template>

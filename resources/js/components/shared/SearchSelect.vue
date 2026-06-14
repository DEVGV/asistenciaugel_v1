<script
    setup
    lang="ts"
    generic="
        T extends { id: number | string; label: string; sublabel?: string }
    "
>
import { onClickOutside } from '@vueuse/core';
import { Check, ChevronDown, Search, X } from 'lucide-vue-next';
import { ref, computed, watch, nextTick } from 'vue';
import { cn } from '@/lib/utils';

/**
 * SearchSelect — select con buscador reutilizable.
 * Sigue el mismo patrón visual que ParamSelect y EntidadSelect del proyecto.
 *
 * Props:
 *   items       — array de opciones { id, label, sublabel? }
 *   modelValue  — id del item seleccionado
 *   placeholder — texto cuando no hay selección
 *   disabled    — deshabilitar
 *   clearable   — mostrar botón para limpiar la selección
 */

const props = withDefaults(
    defineProps<{
        items: T[];
        modelValue?: T['id'] | null;
        placeholder?: string;
        disabled?: boolean;
        clearable?: boolean;
        loading?: boolean;
    }>(),
    {
        placeholder: 'Seleccionar...',
        disabled: false,
        clearable: false,
        loading: false,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: T['id'] | null): void;
    (e: 'update:item', item: T | null): void;
    (e: 'search', query: string): void;
}>();

const isOpen = ref(false);
const searchQuery = ref('');
const target = ref<HTMLElement | null>(null);
const searchInput = ref<HTMLInputElement | null>(null);

onClickOutside(target, () => {
    isOpen.value = false;
    searchQuery.value = '';
});

watch(isOpen, async (val) => {
    if (val) {
        await nextTick();
        searchInput.value?.focus();
    }
});

const selectedItem = computed(() =>
    props.modelValue != null
        ? (props.items.find((i) => String(i.id) === String(props.modelValue)) ??
          null)
        : null,
);

const filteredItems = computed(() => {
    const q = searchQuery.value.toLowerCase().trim();

    if (!q) {
        return props.items.slice(0, 150);
    }

    return props.items
        .filter(
            (i) =>
                i.label.toLowerCase().includes(q) ||
                (i.sublabel ?? '').toLowerCase().includes(q),
        )
        .slice(0, 150);
});

function toggleDropdown() {
    if (props.disabled || props.loading) {
        return;
    }

    isOpen.value = !isOpen.value;

    if (isOpen.value) {
        searchQuery.value = '';
    }
}

function selectItem(item: T) {
    emit('update:modelValue', item.id);
    emit('update:item', item);
    isOpen.value = false;
    searchQuery.value = '';
}

function clearSelection(e: MouseEvent) {
    e.stopPropagation();
    emit('update:modelValue', null);
    emit('update:item', null);
}

watch(
    () => props.modelValue,
    (val) => {
        if (!val) {
            emit('update:item', null);
        } else {
            const found = props.items.find((i) => String(i.id) === String(val));

            if (found) {
                emit('update:item', found);
            }
        }
    },
);

watch(searchQuery, (val) => {
    emit('search', val);
});
</script>

<template>
    <div ref="target" class="relative">
        <!-- Trigger -->
        <button
            type="button"
            :disabled="disabled || (loading && !isOpen)"
            :class="
                cn(
                    'flex w-full items-center justify-between rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition-all outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
                    isOpen
                        ? 'border-primary ring-2 ring-primary ring-offset-2'
                        : 'border-input',
                )
            "
            @click="toggleDropdown"
        >
            <span
                :class="
                    selectedItem
                        ? 'line-clamp-1 text-left text-foreground'
                        : 'text-muted-foreground'
                "
            >
                {{
                    loading
                        ? 'Cargando...'
                        : (selectedItem?.label ?? placeholder)
                }}
            </span>

            <span class="ml-2 flex shrink-0 items-center gap-1">
                <X
                    v-if="clearable && selectedItem"
                    class="h-3.5 w-3.5 text-muted-foreground hover:text-foreground"
                    @click="clearSelection"
                />
                <ChevronDown class="h-4 w-4 text-muted-foreground opacity-50" />
            </span>
        </button>

        <!-- Dropdown -->
        <div
            v-if="isOpen"
            class="absolute z-50 mt-1 w-full animate-in overflow-hidden rounded-md border bg-background text-sm shadow-lg fade-in-0 zoom-in-95"
        >
            <!-- Buscador -->
            <div class="flex items-center border-b px-3">
                <Search
                    class="h-4 w-4 shrink-0 text-muted-foreground opacity-50"
                />
                <input
                    ref="searchInput"
                    type="text"
                    v-model="searchQuery"
                    placeholder="Buscar..."
                    class="flex h-10 w-full rounded-md bg-transparent px-3 py-3 text-sm outline-none placeholder:text-muted-foreground"
                    @click.stop
                />
            </div>

            <!-- Lista -->
            <div class="max-h-60 overflow-y-auto p-1">
                <div
                    v-if="loading"
                    class="flex items-center justify-center gap-2 py-6 text-center text-sm text-muted-foreground"
                >
                    <span
                        class="h-4 w-4 animate-spin rounded-full border-2 border-primary border-t-transparent"
                    ></span>
                    <span>Buscando...</span>
                </div>
                <template v-else>
                    <div
                        v-if="filteredItems.length === 0"
                        class="py-6 text-center text-sm text-muted-foreground"
                    >
                        No se encontraron resultados.
                    </div>

                    <div
                        v-for="item in filteredItems"
                        :key="item.id"
                        :class="
                            cn(
                                'relative flex w-full cursor-pointer items-center rounded-sm py-2 pr-2 pl-8 text-sm transition-colors outline-none select-none hover:bg-accent hover:text-accent-foreground',
                                String(item.id) === String(modelValue)
                                    ? 'bg-primary/10 font-medium text-primary'
                                    : '',
                            )
                        "
                        @click="selectItem(item)"
                    >
                        <span
                            v-if="String(item.id) === String(modelValue)"
                            class="absolute left-2 flex h-3.5 w-3.5 items-center justify-center"
                        >
                            <Check class="h-4 w-4" />
                        </span>
                        <div class="flex flex-col gap-0.5">
                            <span class="line-clamp-1">{{ item.label }}</span>
                            <span
                                v-if="item.sublabel"
                                class="text-[11px] text-muted-foreground"
                            >
                                {{ item.sublabel }}
                            </span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onClickOutside } from '@vueuse/core';
import { Check, ChevronDown, Search } from 'lucide-vue-next';
import { ref, onMounted, watch } from 'vue';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';

interface ZonaSimple {
    id: number;
    nombre: string;
    abreviatura?: string;
}

const props = defineProps<{
    modelValue?: number | string | null;
    distritoId?: number | null;
    label?: string;
    placeholder?: string;
    error?: string;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: number | null): void;
    (e: 'update:item', item: ZonaSimple | null): void;
}>();

const data = ref<ZonaSimple[]>([]);
const loading = ref(false);
const isOpen = ref(false);
const searchQuery = ref('');
const target = ref(null);
const selectedItemName = ref('');

onClickOutside(target, () => {
    isOpen.value = false;
});

let debounceTimer: any = null;

async function fetchData(query = '') {
    loading.value = true;
    try {
        const url = new URL('/api/zonas/search', window.location.origin);
        if (query) url.searchParams.append('q', query);
        if (props.distritoId) url.searchParams.append('distrito_id', String(props.distritoId));

        const response = await fetch(url.toString());
        if (!response.ok) throw new Error('Network response was not ok');

        const json = await response.json();
        data.value = json.data;

        if (props.modelValue && !selectedItemName.value) {
            const initialItem = data.value.find((i) => String(i.id) === String(props.modelValue));
            if (initialItem) {
                selectedItemName.value = initialItem.nombre;
                emit('update:item', initialItem);
            }
        }
    } catch (e) {
        console.error(`Error loading zonas`, e);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchData();
});

watch(
    () => props.distritoId,
    () => {
        fetchData();
    },
);

watch(isOpen, (val) => {
    if (val && data.value.length === 0) {
        fetchData();
    }
});

watch(searchQuery, (newVal) => {
    if (debounceTimer) clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        fetchData(newVal);
    }, 300);
});

watch(
    () => props.modelValue,
    (newVal) => {
        if (!newVal) {
            selectedItemName.value = '';
            emit('update:item', null);
        } else {
            const item = data.value.find((i) => String(i.id) === String(newVal));
            if (item) {
                selectedItemName.value = item.nombre;
                emit('update:item', item);
            }
        }
    },
);

function toggleDropdown() {
    if (props.disabled) return;
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        searchQuery.value = ''; 
        fetchData(); 
    }
}

function selectItem(item: ZonaSimple) {
    selectedItemName.value = item.nombre;
    emit('update:modelValue', item.id);
    emit('update:item', item);
    isOpen.value = false;
    searchQuery.value = '';
}
</script>

<template>
    <div class="grid gap-2" ref="target">
        <Label v-if="label">{{ label }}</Label>
        <div class="relative">
            <!-- Trigger -->
            <button
                type="button"
                @click="toggleDropdown"
                :disabled="disabled"
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
                <span :class="!selectedItemName ? 'text-muted-foreground' : 'line-clamp-1 text-left text-foreground'">
                    {{ selectedItemName || placeholder || 'Seleccionar...' }}
                </span>
                <ChevronDown class="ml-2 h-4 w-4 shrink-0 text-muted-foreground opacity-50" />
            </button>

            <!-- Dropdown Menu -->
            <div v-if="isOpen" class="absolute z-50 mt-1 w-full animate-in overflow-hidden rounded-md border bg-background text-sm shadow-lg fade-in-0 zoom-in-95">
                <!-- Search Input -->
                <div class="flex items-center border-b px-3">
                    <Search class="h-4 w-4 shrink-0 text-muted-foreground opacity-50" />
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Buscar zona..."
                        class="flex h-10 w-full rounded-md bg-transparent px-3 py-3 text-sm outline-none placeholder:text-muted-foreground"
                        @click.stop
                    />
                </div>

                <!-- Viewport -->
                <div class="max-h-60 overflow-y-auto p-1">
                    <div v-if="loading" class="py-6 text-center text-sm text-muted-foreground">
                        Buscando...
                    </div>
                    <div v-else-if="data.length === 0" class="py-6 text-center text-sm text-muted-foreground">
                        No se encontraron zonas.
                    </div>
                    <div
                        v-for="item in data"
                        :key="item.id"
                        @click="selectItem(item)"
                        :class="
                            cn(
                                'relative flex w-full cursor-pointer items-center rounded-sm py-2 pr-2 pl-8 text-sm transition-colors outline-none select-none hover:bg-accent hover:text-accent-foreground',
                                String(item.id) === String(modelValue) ? 'bg-primary/10 font-medium text-primary' : '',
                            )
                        "
                    >
                        <span v-if="String(item.id) === String(modelValue)" class="absolute left-2 flex h-3.5 w-3.5 items-center justify-center">
                            <Check class="h-4 w-4" />
                        </span>
                        <div class="flex flex-col gap-0.5">
                            <span class="line-clamp-1 font-medium">{{ item.nombre }}</span>
                            <span v-if="item.abreviatura" class="text-[11px] text-muted-foreground">{{ item.abreviatura }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p v-if="error" class="text-sm text-destructive">{{ error }}</p>
    </div>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

defineProps<{
    show: boolean;
    title?: string;
    description?: string;
    processing?: boolean;
    confirmText?: string;
    cancelText?: string;
    destructive?: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'confirm'): void;
    (e: 'cancel'): void;
}>();

function onUpdateOpen(value: boolean) {
    emit('update:show', value);

    if (!value) {
        emit('cancel');
    }
}
</script>

<template>
    <Dialog :open="show" @update:open="onUpdateOpen">
        <DialogContent
            class="flex max-h-[min(800px,_80vh)] flex-col gap-0 overflow-hidden p-0 font-sans duration-300 sm:max-w-md"
        >
            <DialogHeader
                class="sticky top-0 z-10 shrink-0 border-b bg-background px-6 pt-6 pb-5"
            >
                <DialogTitle
                    class="text-2xl font-semibold tracking-[-0.029375rem]"
                    >{{ title || 'Confirmar acción' }}</DialogTitle
                >
                <DialogDescription class="mt-2 text-base text-muted-foreground">
                    {{
                        description ||
                        '¿Estás seguro de que deseas continuar con esta acción? Esta acción no se puede deshacer.'
                    }}
                </DialogDescription>
            </DialogHeader>

            <DialogFooter
                class="sticky bottom-0 z-10 flex shrink-0 flex-row justify-between rounded-b-xl border-t bg-muted/30 p-4 sm:justify-between"
            >
                <Button
                    type="button"
                    variant="outline"
                    @click="onUpdateOpen(false)"
                    :disabled="processing"
                >
                    {{ cancelText || 'Cancelar' }}
                </Button>
                <Button
                    type="button"
                    :variant="destructive ? 'destructive' : 'default'"
                    :disabled="processing"
                    @click="emit('confirm')"
                >
                    {{ confirmText || 'Confirmar' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

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
    title: string;
    description?: string;
    processing?: boolean;
    submitText?: string;
    cancelText?: string;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl' | '5xl';
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'submit'): void;
    (e: 'close'): void;
}>();

function onUpdateOpen(value: boolean) {
    emit('update:show', value);

    if (!value) {
        emit('close');
    }
}
</script>

<template>
    <Dialog :open="show" @update:open="onUpdateOpen">
        <DialogContent
            :class="[
                'flex max-h-[min(800px,_80vh)] flex-col gap-0 overflow-hidden p-0 font-sans duration-300 sm:max-w-md',
                {
                    'sm:max-w-sm': maxWidth === 'sm',
                    'sm:max-w-lg': maxWidth === 'lg',
                    'sm:max-w-xl': maxWidth === 'xl',
                    'sm:max-w-2xl': maxWidth === '2xl',
                    'sm:max-w-3xl': maxWidth === '3xl',
                    'sm:max-w-4xl': maxWidth === '4xl',
                    'sm:max-w-5xl': maxWidth === '5xl',
                },
            ]"
        >
            <DialogHeader
                class="sticky top-0 z-10 shrink-0 border-b bg-background px-6 pt-6 pb-5"
            >
                <DialogTitle
                    class="text-2xl font-semibold tracking-[-0.029375rem]"
                    >{{ title }}</DialogTitle
                >
                <DialogDescription
                    :class="[
                        'text-base text-muted-foreground',
                        { 'sr-only': !description },
                    ]"
                >
                    {{
                        description || 'Complete el formulario a continuación.'
                    }}
                </DialogDescription>
            </DialogHeader>

            <form
                @submit.prevent="emit('submit')"
                class="flex flex-1 flex-col overflow-hidden"
            >
                <div class="flex-1 overflow-y-auto px-6 py-6 text-sm">
                    <slot />
                </div>

                <DialogFooter
                    class="sticky bottom-0 z-10 flex shrink-0 flex-row justify-between rounded-b-xl border-t bg-muted/30 p-4 sm:justify-between"
                >
                    <slot name="footer">
                        <Button
                            type="button"
                            variant="outline"
                            @click="onUpdateOpen(false)"
                            :disabled="processing"
                        >
                            {{ cancelText || 'Cancelar' }}
                        </Button>
                        <Button type="submit" :disabled="processing">
                            {{ submitText || 'Guardar' }}
                        </Button>
                    </slot>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

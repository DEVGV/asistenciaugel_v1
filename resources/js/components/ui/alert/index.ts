import type { VariantProps } from 'class-variance-authority';
import { cva } from 'class-variance-authority';

export { default as Alert } from './Alert.vue';
export { default as AlertDescription } from './AlertDescription.vue';
export { default as AlertTitle } from './AlertTitle.vue';
export { default as AlertIcon } from './AlertIcon.vue';
export { default as AlertToolbar } from './AlertToolbar.vue';
export { default as AlertContent } from './AlertContent.vue';

export const alertVariants = cva('flex items-stretch w-full gap-2 group-[.toaster]:w-[var(--width)]', {
    variants: {
        variant: {
            secondary: '',
            primary: '',
            destructive: '',
            success: '',
            info: '',
            mono: '',
            warning: '',
        },
        icon: {
            primary: '',
            destructive: '',
            success: '',
            info: '',
            warning: '',
        },
        appearance: {
            solid: '',
            outline: '',
            light: '',
            stroke: 'text-foreground',
        },
        size: {
            lg: 'rounded-lg p-4 gap-3 text-base [&>[data-slot=alert-icon]>svg]:size-6 *:data-[slot=alert-icon]:mt-0.5 [&_[data-slot=alert-close]]:mt-1',
            md: 'rounded-lg p-3.5 gap-2.5 text-sm [&>[data-slot=alert-icon]>svg]:size-5 *:data-[slot=alert-icon]:mt-0 [&_[data-slot=alert-close]]:mt-0.5',
            sm: 'rounded-md px-3 py-2.5 gap-2 text-xs [&>[data-slot=alert-icon]>svg]:size-4 *:data-[slot=alert-icon]:mt-0.5 [&_[data-slot=alert-close]]:mt-0.25 [&_[data-slot=alert-close]_svg]:size-3.5',
        },
    },
    compoundVariants: [
        /* Solid */
        { variant: 'secondary', appearance: 'solid', className: 'bg-muted text-foreground' },
        { variant: 'primary', appearance: 'solid', className: 'bg-primary text-primary-foreground' },
        { variant: 'destructive', appearance: 'solid', className: 'bg-destructive text-destructive-foreground' },
        { variant: 'success', appearance: 'solid', className: 'bg-[var(--color-success,var(--color-green-500))] text-[var(--color-success-foreground,var(--color-white))]' },
        { variant: 'info', appearance: 'solid', className: 'bg-[var(--color-info,var(--color-violet-600))] text-[var(--color-info-foreground,var(--color-white))]' },
        { variant: 'warning', appearance: 'solid', className: 'bg-[var(--color-warning,var(--color-yellow-500))] text-[var(--color-warning-foreground,var(--color-white))]' },
        { variant: 'mono', appearance: 'solid', className: 'bg-zinc-950 text-white dark:bg-zinc-300 dark:text-black *:data-[slot=alert-close]:text-white' },

        /* Outline */
        { variant: 'secondary', appearance: 'outline', className: 'border border-border bg-background text-foreground [&_[data-slot=alert-close]]:text-foreground' },
        { variant: 'primary', appearance: 'outline', className: 'border border-border bg-background text-primary [&_[data-slot=alert-close]]:text-foreground' },
        { variant: 'destructive', appearance: 'outline', className: 'border border-border bg-background text-destructive [&_[data-slot=alert-close]]:text-foreground' },
        { variant: 'success', appearance: 'outline', className: 'border border-border bg-background text-[var(--color-success,var(--color-green-500))] [&_[data-slot=alert-close]]:text-foreground' },
        { variant: 'info', appearance: 'outline', className: 'border border-border bg-background text-[var(--color-info,var(--color-violet-600))] [&_[data-slot=alert-close]]:text-foreground' },
        { variant: 'warning', appearance: 'outline', className: 'border border-border bg-background text-[var(--color-warning,var(--color-yellow-500))] [&_[data-slot=alert-close]]:text-foreground' },
        { variant: 'mono', appearance: 'outline', className: 'border border-border bg-background text-foreground [&_[data-slot=alert-close]]:text-foreground' },

        /* Light */
        { variant: 'secondary', appearance: 'light', className: 'bg-muted border border-border text-foreground' },
        { variant: 'primary', appearance: 'light', className: 'text-foreground bg-[var(--color-primary-soft,var(--color-blue-50))] border border-[var(--color-primary-alpha,var(--color-blue-100))] [&_[data-slot=alert-icon]]:text-primary dark:bg-[var(--color-primary-soft,var(--color-blue-950))] dark:border-[var(--color-primary-alpha,var(--color-blue-900))]' },
        { variant: 'destructive', appearance: 'light', className: 'bg-[var(--color-destructive-soft,var(--color-red-50))] border border-[var(--color-destructive-alpha,var(--color-red-100))] text-foreground [&_[data-slot=alert-icon]]:text-destructive dark:bg-[var(--color-destructive-soft,var(--color-red-950))] dark:border-[var(--color-destructive-alpha,var(--color-red-900))]' },
        { variant: 'success', appearance: 'light', className: 'bg-[var(--color-success-soft,var(--color-green-50))] border border-[var(--color-success-alpha,var(--color-green-200))] text-foreground [&_[data-slot=alert-icon]]:text-[var(--color-success-foreground,var(--color-green-600))] dark:bg-[var(--color-success-soft,var(--color-green-950))] dark:border-[var(--color-success-alpha,var(--color-green-900))]' },
        { variant: 'info', appearance: 'light', className: 'bg-[var(--color-info-soft,var(--color-violet-50))] border border-[var(--color-info-alpha,var(--color-violet-100))] text-foreground [&_[data-slot=alert-icon]]:text-[var(--color-info-foreground,var(--color-violet-600))] dark:bg-[var(--color-info-soft,var(--color-violet-950))] dark:border-[var(--color-info-alpha,var(--color-violet-900))]' },
        { variant: 'warning', appearance: 'light', className: 'bg-[var(--color-warning-soft,var(--color-yellow-50))] border border-[var(--color-warning-alpha,var(--color-yellow-200))] text-foreground [&_[data-slot=alert-icon]]:text-[var(--color-warning-foreground,var(--color-yellow-600))] dark:bg-[var(--color-warning-soft,var(--color-yellow-950))] dark:border-[var(--color-warning-alpha,var(--color-yellow-900))]' },

        /* Mono */
        { variant: 'mono', icon: 'primary', className: '[&_[data-slot=alert-icon]]:text-primary' },
        { variant: 'mono', icon: 'warning', className: '[&_[data-slot=alert-icon]]:text-[var(--color-warning-foreground,var(--color-yellow-600))]' },
        { variant: 'mono', icon: 'success', className: '[&_[data-slot=alert-icon]]:text-[var(--color-success-foreground,var(--color-green-600))]' },
        { variant: 'mono', icon: 'destructive', className: '[&_[data-slot=alert-icon]]:text-destructive' },
        { variant: 'mono', icon: 'info', className: '[&_[data-slot=alert-icon]]:text-[var(--color-info-foreground,var(--color-violet-600))]' },
    ],
    defaultVariants: {
        variant: 'secondary',
        appearance: 'solid',
        size: 'md',
    },
});

export type AlertVariants = VariantProps<typeof alertVariants>;

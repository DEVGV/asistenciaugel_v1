<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
}>();

const { isCurrentUrl } = useCurrentUrl();

const isMainActive = (item: NavItem) => {
    if (isCurrentUrl(item.href)) {
        return true;
    }

    if (item.items && item.items.length > 0) {
        return item.items.some((subItem) => isCurrentUrl(subItem.href));
    }

    return false;
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel
            class="text-[10px] font-medium tracking-wider text-sidebar-foreground/55 uppercase"
            >Administración</SidebarGroupLabel
        >
        <SidebarMenu>
            <template v-for="item in items" :key="item.title">
                <Collapsible
                    v-if="item.items && item.items.length > 0"
                    as-child
                    :default-open="
                        item.isActive ||
                        item.items.some((subItem) => isCurrentUrl(subItem.href))
                    "
                    class="group/collapsible"
                >
                    <SidebarMenuItem>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton
                                :tooltip="item.title"
                                :is-active="isMainActive(item)"
                            >
                                <component
                                    :is="item.icon"
                                    v-if="item.icon"
                                    :class="
                                        isMainActive(item)
                                            ? 'scale-105 text-primary'
                                            : 'text-sidebar-foreground/60'
                                    "
                                    class="shrink-0 transition-all duration-200"
                                />
                                <span
                                    :class="
                                        isMainActive(item)
                                            ? 'font-semibold text-foreground'
                                            : 'text-sidebar-foreground/80'
                                    "
                                    >{{ item.title }}</span
                                >
                                <ChevronRight
                                    class="ml-auto shrink-0 transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                    :class="
                                        isMainActive(item)
                                            ? 'text-primary'
                                            : 'text-sidebar-foreground/40'
                                    "
                                />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem
                                    v-for="subItem in item.items"
                                    :key="subItem.title"
                                >
                                    <SidebarMenuSubButton
                                        as-child
                                        :is-active="isCurrentUrl(subItem.href)"
                                        class="transition-all duration-200"
                                    >
                                        <Link
                                            :href="subItem.href"
                                            class="flex items-center gap-2"
                                        >
                                            <span
                                                class="h-1 w-1 shrink-0 rounded-full transition-all duration-200"
                                                :class="
                                                    isCurrentUrl(subItem.href)
                                                        ? 'scale-125 bg-primary shadow-xs shadow-primary/50'
                                                        : 'bg-sidebar-foreground/35 group-hover:bg-sidebar-foreground/60'
                                                "
                                            />
                                            <span
                                                :class="
                                                    isCurrentUrl(subItem.href)
                                                        ? 'font-medium text-foreground'
                                                        : 'text-sidebar-foreground/80'
                                                "
                                                >{{ subItem.title }}</span
                                            >
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </SidebarMenuItem>
                </Collapsible>

                <SidebarMenuItem v-else>
                    <SidebarMenuButton
                        as-child
                        :is-active="isCurrentUrl(item.href)"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component
                                :is="item.icon"
                                v-if="item.icon"
                                :class="
                                    isCurrentUrl(item.href)
                                        ? 'scale-105 text-primary'
                                        : 'text-sidebar-foreground/60'
                                "
                                class="shrink-0 transition-all duration-200"
                            />
                            <span
                                :class="
                                    isCurrentUrl(item.href)
                                        ? 'font-semibold text-foreground'
                                        : 'text-sidebar-foreground/80'
                                "
                                >{{ item.title }}</span
                            >
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>

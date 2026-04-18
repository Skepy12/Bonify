<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Building2 } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import {
    create as businessesCreate,
    edit,
    index as businessesIndex,
} from '@/routes/businesses';

type Business = {
    id: number;
    name: string;
    description: string | null;
};

type Props = {
    businesses: {
        data: Business[];
    };
};

const { businesses } = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'My business',
                href: businessesIndex(),
            },
        ],
    },
});
</script>

<template>
    <Head title="My business" />

    <div class="flex flex-col gap-6 p-4">
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
        >
            <Heading
                variant="small"
                title="My business"
                description="Create a business profile or manage existing ones."
            />
            <Button as-child>
                <Link :href="businessesCreate()">Create business</Link>
            </Button>
        </div>

        <div
            v-if="businesses.data.length === 0"
            class="flex flex-col items-center justify-center gap-4 rounded-xl border border-dashed border-sidebar-border/70 p-12 text-center dark:border-sidebar-border"
        >
            <Building2 class="size-10 text-muted-foreground" />
            <p class="text-sm text-muted-foreground">
                You have not created a business yet.
            </p>
            <Button as-child>
                <Link :href="businessesCreate()"
                    >Create your first business</Link
                >
            </Button>
        </div>

        <ul v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <li
                v-for="business in businesses.data"
                :key="business.id"
                class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
            >
                <h2 class="font-medium">{{ business.name }}</h2>
                <p
                    v-if="business.description"
                    class="mt-2 line-clamp-3 text-sm text-muted-foreground"
                >
                    {{ business.description }}
                </p>
                <Button
                    as-child
                    variant="outline"
                    class="mt-4 w-full sm:w-auto"
                >
                    <Link :href="edit(business)">Edit</Link>
                </Button>
            </li>
        </ul>
    </div>
</template>

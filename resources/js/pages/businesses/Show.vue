<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { edit, index as businessesIndex } from '@/routes/businesses';

type Business = {
    id: number;
    name: string;
    description: string | null;
};

const { business } = defineProps<{
    business: Business;
}>();

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
    <Head :title="business.name" />

    <div class="mx-auto flex max-w-lg flex-col gap-6 p-4">
        <Heading
            variant="small"
            :title="business.name"
            description="Business profile overview."
        />

        <p
            v-if="business.description"
            class="text-sm whitespace-pre-wrap text-muted-foreground"
        >
            {{ business.description }}
        </p>
        <p v-else class="text-sm text-muted-foreground">No description yet.</p>

        <div class="flex flex-wrap gap-3">
            <Button as-child>
                <Link :href="edit(business)">Edit</Link>
            </Button>
            <Button variant="outline" as-child>
                <Link :href="businessesIndex()">All businesses</Link>
            </Button>
        </div>
    </div>
</template>

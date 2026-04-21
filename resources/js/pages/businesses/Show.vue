<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { DAY_LABELS_BY_VALUE } from '@/composables/dayOfWeek';
import { edit, index as businessesIndex } from '@/routes/businesses';
import type { BusinessDetails } from '@/types/business';

const { business } = defineProps<{
    business: BusinessDetails;
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

        <div
            class="grid gap-2 rounded-lg border border-sidebar-border/70 p-4 text-sm dark:border-sidebar-border"
        >
            <p>
                <span class="text-muted-foreground">Mode:</span>
                {{ business.service_mode }}
            </p>
            <p>
                <span class="text-muted-foreground">Country:</span>
                {{ business.country?.name ?? 'Not set' }}
            </p>
            <p>
                <span class="text-muted-foreground">Language:</span>
                {{ business.language?.name ?? 'Not set' }}
            </p>
            <p>
                <span class="text-muted-foreground">Type:</span>
                {{ business.business_type?.name ?? 'Not set' }}
            </p>
            <p>
                <span class="text-muted-foreground">Category:</span>
                {{ business.category?.name ?? 'Not set' }}
            </p>
        </div>

        <div class="rounded-lg border border-sidebar-border/70 p-4 dark:border-sidebar-border">
            <h3 class="mb-3 text-sm font-medium">Business hours</h3>
            <ul
                v-if="business.business_hours && business.business_hours.length"
                class="space-y-2 text-sm"
            >
                <li
                    v-for="hour in business.business_hours"
                    :key="hour.id"
                    class="flex items-center justify-between gap-2"
                >
                    <span>{{
                        DAY_LABELS_BY_VALUE[hour.day_of_week] ??
                        `Day ${hour.day_of_week}`
                    }}</span>
                    <span class="text-muted-foreground">
                        <template v-if="hour.is_closed">Closed</template>
                        <template v-else-if="hour.is_24_hours">24 hours</template>
                        <template v-else
                            >{{ hour.opens_at ?? '--:--' }} -
                            {{ hour.closes_at ?? '--:--' }}</template
                        >
                    </span>
                </li>
            </ul>
            <p v-else class="text-sm text-muted-foreground">
                Hours not configured yet.
            </p>
        </div>

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

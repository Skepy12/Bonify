<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import BusinessController from '@/actions/App/Http/Controllers/Business/BusinessController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { DAYS_OF_WEEK } from '@/composables/dayOfWeek';
import { useBusinessCategoryFilter } from '@/composables/useBusinessCategoryFilter';
import { cn } from '@/lib/utils';
import {
    create as businessesCreate,
    index as businessesIndex,
} from '@/routes/businesses';
import type { Option, ServiceModeOption } from '@/types/business';

const props = defineProps<{
    countries: Option[];
    languages: Option[];
    businessTypes: Option[];
    categories: Option[];
    serviceModes: ServiceModeOption[];
}>();

const {
    selectedBusinessTypeId,
    selectedCategoryId,
    filteredCategories,
    handleBusinessTypeChange,
} = useBusinessCategoryFilter(props.categories);

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'My business',
                href: businessesIndex(),
            },
            {
                title: 'Create',
                href: businessesCreate(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Create business" />

    <div class="mx-auto flex max-w-lg flex-col gap-6 p-4">
        <Heading
            variant="small"
            title="Create business"
            description="Add a name and optional description. You can change these later."
        />

        <Form
            v-bind="BusinessController.store.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="name">Business name</Label>
                <Input
                    id="name"
                    name="name"
                    required
                    autocomplete="organization"
                    placeholder="e.g. Northside Coffee"
                />
                <InputError class="mt-2" :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="description">Description (optional)</Label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    :class="
                        cn(
                            'w-full min-w-0 rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-xs transition-[color,box-shadow] outline-none selection:bg-primary selection:text-primary-foreground placeholder:text-muted-foreground md:text-sm dark:bg-input/30',
                            'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
                            'aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40',
                            'min-h-24 resize-y',
                        )
                    "
                    placeholder="Short description for your customers"
                />
                <InputError class="mt-2" :message="errors.description" />
            </div>

            <div class="grid gap-2">
                <Label for="service_mode">Service mode</Label>
                <select
                    id="service_mode"
                    name="service_mode"
                    required
                    :class="
                        cn(
                            'w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none',
                            'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
                        )
                    "
                >
                    <option
                        v-for="mode in props.serviceModes"
                        :key="mode.value"
                        :value="mode.value"
                        :selected="mode.value === 'offline'"
                    >
                        {{ mode.label }}
                    </option>
                </select>
                <InputError class="mt-2" :message="errors.service_mode" />
            </div>

            <div class="grid gap-2">
                <Label for="country_id">Country</Label>
                <select
                    id="country_id"
                    name="country_id"
                    :class="
                        cn(
                            'w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none',
                            'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
                        )
                    "
                >
                    <option value="">Not set</option>
                    <option
                        v-for="country in props.countries"
                        :key="country.id"
                        :value="country.id"
                    >
                        {{ country.name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="errors.country_id" />
            </div>

            <div class="grid gap-2">
                <Label for="language_id">Language</Label>
                <select
                    id="language_id"
                    name="language_id"
                    :class="
                        cn(
                            'w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none',
                            'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
                        )
                    "
                >
                    <option value="">Not set</option>
                    <option
                        v-for="language in props.languages"
                        :key="language.id"
                        :value="language.id"
                    >
                        {{ language.name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="errors.language_id" />
            </div>

            <div class="grid gap-2">
                <Label for="business_type_id">Business type</Label>
                <select
                    id="business_type_id"
                    name="business_type_id"
                    v-model="selectedBusinessTypeId"
                    @change="handleBusinessTypeChange"
                    :class="
                        cn(
                            'w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none',
                            'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
                        )
                    "
                >
                    <option value="">Not set</option>
                    <option
                        v-for="type in props.businessTypes"
                        :key="type.id"
                        :value="type.id"
                    >
                        {{ type.name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="errors.business_type_id" />
            </div>

            <div class="grid gap-2">
                <Label for="category_id">Category</Label>
                <select
                    id="category_id"
                    name="category_id"
                    v-model="selectedCategoryId"
                    :disabled="!selectedBusinessTypeId"
                    :class="
                        cn(
                            'w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none',
                            'disabled:cursor-not-allowed disabled:opacity-60',
                            'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
                        )
                    "
                >
                    <option value="">
                        {{
                            selectedBusinessTypeId
                                ? 'Not set'
                                : 'Select business type first'
                        }}
                    </option>
                    <option
                        v-for="category in filteredCategories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="errors.category_id" />
            </div>

            <div class="grid gap-3">
                <Label>Business hours</Label>
                <div
                    v-for="day in DAYS_OF_WEEK"
                    :key="day.value"
                    class="rounded-md border border-sidebar-border/70 p-3 dark:border-sidebar-border"
                >
                    <input
                        type="hidden"
                        :name="`business_hours[${day.value}][day_of_week]`"
                        :value="day.value"
                    >
                    <p class="mb-2 text-sm font-medium">{{ day.label }}</p>
                    <div class="mb-3 flex flex-wrap gap-4 text-sm">
                        <label class="inline-flex items-center gap-2">
                            <input
                                type="hidden"
                                :name="`business_hours[${day.value}][is_closed]`"
                                value="0"
                            >
                            <input
                                type="checkbox"
                                :name="`business_hours[${day.value}][is_closed]`"
                                value="1"
                                class="size-4"
                            >
                            Closed
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input
                                type="hidden"
                                :name="`business_hours[${day.value}][is_24_hours]`"
                                value="0"
                            >
                            <input
                                type="checkbox"
                                :name="`business_hours[${day.value}][is_24_hours]`"
                                value="1"
                                class="size-4"
                            >
                            24 hours
                        </label>
                    </div>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label :for="`opens_at_${day.value}`">Opens at</Label>
                            <Input
                                :id="`opens_at_${day.value}`"
                                type="time"
                                :name="`business_hours[${day.value}][opens_at]`"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label :for="`closes_at_${day.value}`">Closes at</Label>
                            <Input
                                :id="`closes_at_${day.value}`"
                                type="time"
                                :name="`business_hours[${day.value}][closes_at]`"
                            />
                        </div>
                    </div>
                </div>
                <InputError class="mt-2" :message="errors.business_hours" />
            </div>

            <div class="flex gap-3">
                <Button type="submit" :disabled="processing">Save</Button>
                <Button variant="outline" type="button" as-child>
                    <Link :href="businessesIndex()">Cancel</Link>
                </Button>
            </div>
        </Form>
    </div>
</template>

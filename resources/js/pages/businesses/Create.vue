<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import BusinessController from '@/actions/App/Http/Controllers/Business/BusinessController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import {
    create as businessesCreate,
    index as businessesIndex,
} from '@/routes/businesses';

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

            <div class="flex gap-3">
                <Button type="submit" :disabled="processing">Save</Button>
                <Button variant="outline" type="button" as-child>
                    <Link :href="businessesIndex()">Cancel</Link>
                </Button>
            </div>
        </Form>
    </div>
</template>

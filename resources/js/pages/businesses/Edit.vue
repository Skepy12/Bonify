<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import BusinessController from '@/actions/App/Http/Controllers/Business/BusinessController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { index as businessesIndex } from '@/routes/businesses';

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
    <Head :title="`Edit · ${business.name}`" />

    <div class="mx-auto flex max-w-lg flex-col gap-8 p-4">
        <Heading
            variant="small"
            title="Edit business"
            description="Update how your business appears in your loyalty program."
        />

        <Form
            v-bind="BusinessController.update.form(business)"
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
                    :default-value="business.name"
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
                    >{{ business.description ?? '' }}</textarea
                >
                <InputError class="mt-2" :message="errors.description" />
            </div>

            <div class="flex flex-wrap gap-3">
                <Button type="submit" :disabled="processing"
                    >Save changes</Button
                >
                <Button variant="outline" type="button" as-child>
                    <Link :href="businessesIndex()">Back to list</Link>
                </Button>
            </div>
        </Form>

        <div
            class="border-t border-sidebar-border/70 pt-8 dark:border-sidebar-border"
        >
            <Heading
                variant="small"
                title="Delete business"
                description="This will remove the business from your account. You can recreate it later."
            />
            <Form
                v-bind="BusinessController.destroy.form(business)"
                class="mt-4"
                v-slot="{ processing: deleting }"
            >
                <Button
                    type="submit"
                    variant="destructive"
                    :disabled="deleting"
                >
                    Delete business
                </Button>
            </Form>
        </div>
    </div>
</template>

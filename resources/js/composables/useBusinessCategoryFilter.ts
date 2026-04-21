import { computed, ref } from 'vue';
import type { Option } from '@/types/business';

type FilterOptions = {
    initialBusinessTypeId?: string | number;
    initialCategoryId?: string | number;
};

export function useBusinessCategoryFilter(
    categories: Option[],
    options: FilterOptions = {},
) {
    const selectedBusinessTypeId = ref<string | number>(
        options.initialBusinessTypeId ?? '',
    );
    const selectedCategoryId = ref<string | number>(
        options.initialCategoryId ?? '',
    );

    const filteredCategories = computed(() => {
        if (!selectedBusinessTypeId.value) {
            return [];
        }

        const selectedTypeId = String(selectedBusinessTypeId.value);

        return categories.filter(
            (category) =>
                String(category.business_type_id ?? '') === selectedTypeId,
        );
    });

    const handleBusinessTypeChange = (): void => {
        const isSelectedCategoryVisible = filteredCategories.value.some(
            (category) => String(category.id) === String(selectedCategoryId.value),
        );

        if (!isSelectedCategoryVisible) {
            selectedCategoryId.value = '';
        }
    };

    return {
        selectedBusinessTypeId,
        selectedCategoryId,
        filteredCategories,
        handleBusinessTypeChange,
    };
}

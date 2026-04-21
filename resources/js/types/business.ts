export type Option = {
    id: number;
    name: string;
    business_type_id?: number;
};

export type ServiceModeValue = 'offline' | 'online' | 'hybrid';

export type ServiceModeOption = {
    value: ServiceModeValue;
    label: string;
};

export type BusinessHour = {
    id?: number;
    day_of_week: number;
    opens_at: string | null;
    closes_at: string | null;
    is_closed: boolean;
    is_24_hours: boolean;
};

export type BusinessSummary = {
    id: number;
    name: string;
    description: string | null;
    service_mode: ServiceModeValue;
    country?: {
        name: string;
    } | null;
};

export type BusinessDetails = {
    id: number;
    name: string;
    description: string | null;
    service_mode: ServiceModeValue;
    country_id: number | null;
    language_id: number | null;
    business_type_id: number | null;
    category_id: number | null;
    country?: { name: string } | null;
    language?: { name: string } | null;
    business_type?: { name: string } | null;
    category?: { name: string } | null;
    business_hours?: BusinessHour[];
};

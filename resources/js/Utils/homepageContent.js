export const contentText = (value, fallback) => (
    typeof value === 'string' && value.trim() ? value.trim() : fallback
);

export const contentUrl = (value, fallback) => {
    if (typeof value !== 'string') return fallback;

    const url = value.trim();

    return /^(\/(?!\/)|#|https?:\/\/|mailto:|tel:)/i.test(url) ? url : fallback;
};

export const contentItems = (value, fallback, maximum = 12) => (
    Array.isArray(value) && value.length ? value.slice(0, maximum) : fallback
);

<template>
    <textarea
        :id="id"
        :name="name"
        :value="modelValue"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        :readonly="readonly"
        :rows="rows"
        :cols="cols"
        :maxlength="maxlength"
        :class="classes"
        @input="$emit('update:modelValue', $event.target.value)"
        @blur="$emit('blur', $event)"
        @focus="$emit('focus', $event)"
        @change="$emit('change', $event)"
        ref="textarea"
    />
</template>

<script setup>
import { computed, ref, nextTick } from 'vue';

const props = defineProps({
    id: {
        type: String,
        default: null,
    },
    name: {
        type: String,
        default: null,
    },
    modelValue: {
        type: [String, Number],
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    readonly: {
        type: Boolean,
        default: false,
    },
    rows: {
        type: [String, Number],
        default: 3,
    },
    cols: {
        type: [String, Number],
        default: null,
    },
    maxlength: {
        type: [String, Number],
        default: null,
    },
    class: {
        type: [String, Array, Object],
        default: '',
    },
});

const emit = defineEmits(['update:modelValue', 'blur', 'focus', 'change']);

const textarea = ref(null);

const classes = computed(() => {
    const baseClasses = [
        'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
        'dark:bg-gray-900 dark:text-white dark:ring-gray-700 dark:placeholder:text-gray-400 dark:focus:ring-indigo-500',
        'disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 disabled:cursor-not-allowed',
        'dark:disabled:bg-gray-800 dark:disabled:text-gray-400 dark:disabled:ring-gray-600'
    ];

    if (props.class) {
        if (typeof props.class === 'string') {
            baseClasses.push(props.class);
        } else if (Array.isArray(props.class)) {
            baseClasses.push(...props.class);
        } else {
            baseClasses.push(props.class);
        }
    }

    return baseClasses.join(' ');
});

const focus = () => {
    nextTick(() => {
        textarea.value?.focus();
    });
};

const blur = () => {
    textarea.value?.blur();
};

const select = () => {
    textarea.value?.select();
};

defineExpose({
    focus,
    blur,
    select,
});
</script>
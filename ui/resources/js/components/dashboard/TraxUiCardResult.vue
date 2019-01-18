<template>

    <trax-ui-card align="center" :background-color="backgroundColor">
        <p class="mb-1 mt-2">{{ title }}</p>
        <h3 class="mb-3" v-if="resultSize == 'sm'">{{ result }}</h3>
        <h2 class="mb-3" v-if="resultSize == 'md'">{{ result }}</h2>
        <h1 class="mb-3" v-if="resultSize == 'lg'">{{ result }}</h1>
    </trax-ui-card>
    
</template>

<script>
    export default {

        props: {
            backgroundColor: null,
            title: null,
            result: null,
            resultSize: {default: 'lg'},
        }
    }
</script>

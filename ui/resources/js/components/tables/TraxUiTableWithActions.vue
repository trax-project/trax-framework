<template>
    <div>

        <!-- The table -->
        <trax-ui-data-table :id="id" :titles="titles" :header="header">
        </trax-ui-data-table>

        <!-- Action buttons template -->
        
        <div :id="id+'-table-actions'" class="d-none">
            <button type="button" v-for="action in actions" :key="action.name" 
                :class="action.class+' btn btn-link btn-just-icon '+action.name"
                :data-event="action.event" :data-url="action.url" :data-wrapper="action.wrapper" 
                >
                    <i class="material-icons">{{ action.icon }}</i>
            </button>
        </div>
    
    </div>
</template>

<script>
    export default {
        props: {
            header: { default: 1 },
            id: null,
            titles: null,
            actions: null
        }
    }
</script>

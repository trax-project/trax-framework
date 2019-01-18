<template>

    <trax-ui-simple-table 
        :id="id" :header="header" :header-color="headerColor" :titles="titles" :rows="rows" :cols="cols">
    </trax-ui-simple-table>

</template>

<script>
    export default {
    
        props: {
            id: null,
            header: { default: 1 },
            headerColor: { default: 'primary' },
            titles: null,
            rows: null,
            cols: null,
            endpoint: null,
            endpointParams: null,
            bus: null,
        },

        computed: {

            params: function() {
                return this.endpointParams ? this.endpointParams : {};
            }
        },
        
        created: function() {
            this.getData();
        },
        
        methods: {
        
            getData() {
                var that = this;
                axios.get(this.endpoint, {params: that.params})
                    .then(function (response) {
                        if (that.bus) that.bus.$emit(that.id+'-data', response.data);
                    })
                    .catch(function (error) {
                    });
            }
        }
    }
</script>

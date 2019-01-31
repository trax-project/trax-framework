<template>
    <canvas :id="id"></canvas>
</template>

<script>
    export default {

        props: {
            legend: null,
            data: null,
            colors: null,
        },
    
        data: function() {
            return {
                id: 'trax-dashboard-chart-'+this.uuid(), 
                chart: null,
            }
        },

        mounted: function() {
            var ctx = document.getElementById(this.id).getContext('2d');
            var params = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: this.data,
                        backgroundColor: this.colors
                    }]
                },
                options: {
                    legend: {},
                    tooltips: {},
                }
            };
            if (this.labels) {
                params.data.labels = this.labels;
            } else {
                params.options.tooltips.enabled = false;
            }
            if (this.legend) {
                params.options.legend.position = this.legend;
            }
            this.chart = new Chart(ctx, params);
        },

        methods: {

            uuid() {
                var res = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
                    return v.toString(16);
                });
                return res;
            }
        }

    }
</script>

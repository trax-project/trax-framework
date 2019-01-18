<template>

    <div class="trax-responsive-table mb-3">
        <div class="container">
            <div class="row" v-for="(row, index2) in rows" :key="index2">
                <div v-for="(cell, index3) in row" :key="index3" :class="colCellClass(index3)" v-html="cell"></div>
            </div>
        </div>
    </div>
        
</template>

<script>
    export default {    

        props: {
            rows: null,
            cols: null
        },

        methods: {

            colCellClass: function(index) {
                var cellClass = 'trax-cell col';
                if (!this.cols || !this.cols[index]) return cellClass;
                if (this.cols[index].width) cellClass += '-'+this.cols[index].width;
                if (this.cols[index].class) cellClass += ' '+this.cols[index].class;
                return cellClass;
            }
        }
    }
</script>

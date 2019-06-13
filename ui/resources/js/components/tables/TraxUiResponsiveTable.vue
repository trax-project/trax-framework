<template>

    <div class="trax-responsive-table mb-3">
        <div class="container">
            <div class="row" v-for="(row, rowNum) in rows" :key="rowNum">
                <div v-for="(cell, colNum) in row" :key="colNum" :class="cellClass(colNum, cell)" v-html="cellContent(cell)"></div>
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

            cellClass: function(colNum, cell) {
                var cellClass = 'trax-cell col';

                // Add column class and width.
                if (this.cols && this.cols[colNum]) {
                    if (this.cols[colNum].width) cellClass += '-'+this.cols[colNum].width;
                    if (this.cols[colNum].class) cellClass += ' '+this.cols[colNum].class;
                }

                // Add column class.
                if (cell.class) {
                    cellClass += ' ' + cell.class;
                }

                return cellClass;
            },

            cellContent: function(cell) {
                return cell.content ? cell.content : cell;
            }

        }
    }
</script>

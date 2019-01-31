<template>

    <div class="material-datatables">
        <table :id="id+'-table'" class="table table-striped trax-table" width="100%" style="width:100%">
            <thead v-if="header">
                <tr>
                    <th v-for="(title, index) in titles" :key="index"> {{ title }} </th>
                </tr>
            </thead>
        </table>
    </div> 
    
</template>

<script>
    export default {
        props: {
            id: null,
            header: { default: 1 },
            titles: null,
        }
    }
</script>
